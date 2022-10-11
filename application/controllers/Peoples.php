<?php

class Peoples extends CI_Controller
{




    public function index()
    {
        $data['judul'] = 'list of peoples';

        $this->load->model('Peoples_model', 'peoples');


        //pagiation load libray
        $this->load->library('pagination');

        //ambil daata keyword     jika ada yg inpt sbmt
        if ($this->input->post('submit')) {
            $data['keyword'] = $this->input->post('keyword');
            //simpn kdlm ssion
            $this->session->set_userdata('keyword', $data['keyword']);
        } else {
            $data['keyword'] = $this->session->userdata('keyword');
        }


        //config
        $this->db->like('name', $data['keyword']);
        // $this->db->or_like('email', $data['keyword']);
        $this->db->from('peoples');
        //data brdasarkan keywrd             
        $config['total_rows'] = $this->db->count_all_results();
        //spy bsa dkrm ke view
        $data['total_rows'] = $config['total_rows'];
        //tampil per hal
        $config['per_page'] = 6;



        //inisialisa ber konfig
        $this->pagination->initialize($config);

        $data['start'] = $this->uri->segment(3);
        $data['peoples'] = $this->peoples->getPeoples($config['per_page'], $data['start'], $data['keyword']);

        $this->load->view('templates/header', $data);
        $this->load->view('peoples/index', $data);
        $this->load->view('templates/footer');
    }
}
