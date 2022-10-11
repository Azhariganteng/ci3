<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        //klo blm login dicek
        //function helper dia tu siapa role nya
        is_logged_in();
    }
    public function index()
    {
        $data['title'] = 'Dashboard';
        //ngambil data berdasar sessiob
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        //manggil view
        $this->load->view('templates2/header', $data);
        $this->load->view('templates2/sidebar', $data);
        $this->load->view('templates2/topbar', $data);
        $this->load->view('admin/index', $data);
        $this->load->view('templates2/footer');
    }

    public function role()
    {
        $data['title'] = 'Role';
        //ngambil data berdasar sessiob
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $data['role'] = $this->db->get('user_role')->result_array();
        //manggil view
        $this->load->view('templates2/header', $data);
        $this->load->view('templates2/sidebar', $data);
        $this->load->view('templates2/topbar', $data);
        $this->load->view('admin/role', $data);
        $this->load->view('templates2/footer');
    }

    public function roleAccess($role_id)
    {
        $data['title'] = 'Role Akses';
        //ngambil data berdasar sessiob
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $data['role'] = $this->db->get_where('user_role', ['id' => $role_id])->row_array();

        //jgn ddpt smua
        $this->db->where('id !=', 1);
        //dptin smua mnu
        $data['menu'] = $this->db->get('user_menu')->result_array();
        //manggil view
        $this->load->view('templates2/header', $data);
        $this->load->view('templates2/sidebar', $data);
        $this->load->view('templates2/topbar', $data);
        $this->load->view('admin/role-access', $data);
        $this->load->view('templates2/footer');
    }

    public function changeAccess()
    {
        $menu_id = $this->input->post('menuId');
        $role_id = $this->input->post('roleId');

        //pny dt yg isinya aray
        $data = [
            'role_id' => $role_id,
            'menu_id' => $menu_id
        ];

        $result = $this->db->get_where('user_access_menu', $data);

        //klo g ada isinya,,, insert
        if ($result->num_rows() < 1) {
            $this->db->insert('user_access_menu', $data);
        } else {
            //klo ada hps
            $this->db->delete('user_access_menu', $data);
        }

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert" >Access Changed!
        </div>');
    }
}
