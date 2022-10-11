<?php

function is_logged_in()
{
    //instan ci baru    ,,,utk mmgil librari di ci
    $ci =  get_instance();
    //dicek udh login apa blm , klo udh log cek rol ny apa
    if (!$ci->session->userdata('email')) {
        $ci->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible fade show" role="alert" align="center" class="row no-gutters fixed-top">
        Anda harus login terlebih dahulu!
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
        //   $ci->session->set_flashdata('message', ' $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert" align="center">Anda harus login terlebih dahulu!
        //   </div>')
        redirect('auth');
    } else {
        //klo dh login dicek rol id ny apa
        $role_id = $ci->session->userdata('role_id');
        //brusha akses mnu mna
        $menu = $ci->uri->segment(1);

        //query tble menu berdasrakn menu_id ,,,,, dpt semua
        $queryMenu = $ci->db->get_where('user_menu', ['menu' => $menu])->row_array();
        $menu_id = $queryMenu['id'];

        //user gbs ke admn
        $userAccess = $ci->db->get_where('user_access_menu', ['role_id' => $role_id, 'menu_id' => $menu_id]);

        if ($userAccess->num_rows() < 1) {
            redirect('auth/blocked');
        }
    }
}

function check_access($role_id, $menu_id)
{
    $ci = get_instance();

    //cari ke tbl akses mnu yg role id ny brp menu b rp
    $ci->db->where('role_id', $role_id);
    $ci->db->where('menu_id', $menu_id);
    $result = $ci->db->get('user_access_menu');

    //jika besar dr 0 cklis
    if ($result->num_rows() > 0) {
        return "checked='checked'";
    }
}
