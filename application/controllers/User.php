<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
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
        $data['title'] = 'Profil Saya';
        //ngambil data berdasar sessiob
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        //manggil view
        $this->load->view('templates2/header', $data);
        $this->load->view('templates2/sidebar', $data);
        $this->load->view('templates2/topbar', $data);
        $this->load->view('user/index', $data);
        $this->load->view('templates2/footer');
    }
    public function edit()
    {
        $data['title'] = 'Edit Profil';
        //ngambil data berdasar sessiob
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $this->form_validation->set_rules('nama', 'Nama Lengkap', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            //manggil view
            $this->load->view('templates2/header', $data);
            $this->load->view('templates2/sidebar', $data);
            $this->load->view('templates2/topbar', $data);
            $this->load->view('user/edit', $data);
            $this->load->view('templates2/footer');
        } else {
            //klo berhsil

            //cek jika da gmbr yg uplod
            $upload_image = $_FILES['image']['name'];



            // cek jika ada uplod gambar / bukn
            if ($upload_image) {
                //tipenya
                $config['allowed_types'] = 'gif|jpg|png';
                //ukuran mb
                $config['max_size']     = '2048';
                //tempat nyimpn ft
                $config['upload_path'] = './assets/img/profile/';

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('image')) {
                    //cek gmbr lama
                    $old_image = $data['user']['image'];

                    if ($old_image != 'default.jpg') {
                        //cri tahu link ny dmn ,,,, fron cntrler
                        unlink(FCPATH . 'assets/img/profile/' . $old_image);
                    }
                    $new_image = $this->upload->data('file_name');
                    $this->db->set('image', $new_image);
                } else {
                    echo $this->upload->display_errors();
                }
            }

            $name = $this->input->post('nama');
            $email = $this->input->post('email');


            //update table
            $this->db->set('nama', $name);
            $this->db->where('email', $email);
            $this->db->update('user');

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Selamat Akun anda telah diedit
            </div>');
            redirect('user');
        }
    }

    public function ubahPassword()
    {
        $data['title'] = 'Ubah Password';
        //ngambil data berdasar sessiob
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $this->form_validation->set_rules('current_password', 'Password Lama', 'required|trim');
        $this->form_validation->set_rules('new_password1', 'Password Baru', 'required|trim|min_length[5]|matches[new_password2]');
        $this->form_validation->set_rules('new_password2', 'Konfirmasi Password', 'required|trim|min_length[5]|matches[new_password1]');


        if ($this->form_validation->run() == FALSE) {
            //manggil view
            $this->load->view('templates2/header', $data);
            $this->load->view('templates2/sidebar', $data);
            $this->load->view('templates2/topbar', $data);
            $this->load->view('user/ubahpassword', $data);
            $this->load->view('templates2/footer');
        } else {
            //cek curen pw sm td dgn db
            $current_password = $this->input->post('current_password');
            $new_password = $this->input->post('new_password1');
            // ,,,,,,,,,, yg ditulis diinput dan ,,,,, di db sama ga
            if (!password_verify($current_password, $data['user']['password'])) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                Kata sandi saat ini salah
                </div>');
                redirect('user/ubahpassword');
            } else {
                //cek pw new gblh sama dgn dlu
                if ($current_password == $new_password) {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                    Password tidak boleh sama
                    </div>');
                    redirect('user/ubahpassword');
                } else {
                    //pw sudah ok
                    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                    //ubah pw
                    $this->db->set('password', $password_hash);
                    $this->db->where('email', $this->session->userdata('email'));
                    $this->db->update('user');

                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
                    Password telah diubah
                    </div>');
                    redirect('user/ubahpassword');
                }
            }
        }
    }
}
