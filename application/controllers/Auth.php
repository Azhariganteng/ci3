<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        //utk mgil contrak dici ini
        parent::__construct();
        $this->load->library('form_validation');
    }
    public function index()
    {
        if ($this->session->userdata('email')) {
            redirect('user');
        }
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        //validasi dl  ,,,,,,,     kalo gagal
        if ($this->form_validation->run() == FALSE) {

            $data['title'] = 'Login | AIN';
            $this->load->view('templates2/auth_header', $data);
            $this->load->view('auth/login');
            $this->load->view('templates2/auth_footer');
        } else {
            //ketika sukses
            $this->_login();
        }
    }


    private function _login()
    {
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        //cr didb yang ada data di user     select*frm usr whre emil
        $user = $this->db->get_where('user', ['email' => $email])->row_array();

        //dicek jika usernya ada
        if ($user) {
            //jika aktif
            if ($user['is_active'] == 1) {
                //cek pw   menyamakan pw dilogin
                if (password_verify($password, $user['password'])) {
                    //admin / member
                    $data = [
                        'email' => $user['email'],
                        'role_id' => $user['role_id'],
                        // 'abc' => 'role_id',
                    ];
                    //llu simpn k ssiion
                    $this->session->set_userdata($data);
                    //cek dlu rolenya
                    if ($user['role_id'] == 1) {
                        redirect('admin');
                    } else {
                        //kita arahakan admin ke adm, user ke usr
                        redirect('user');
                    }
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert" align="center">Password salah!
            </div>');
                    redirect('auth');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert" align="center">Email tidak aktif
            </div>');
                redirect('auth');
            }
        } else {
            //ggl
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert" align="center">Email belum terdaftar
            </div>');
            redirect('auth');
        }
    }

    public function registration()
    {
        if ($this->session->userdata('email')) {
            redirect('user');
        }

        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]', [
            'is_unique' => 'Email sudah terdaftar'
        ]);
        $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[5]|matches[password2]', [
            'matches' => 'Password tidak sama!',
            'min_length' => 'Password terlalu pendek!',
        ]);
        $this->form_validation->set_rules('password2', 'Password', 'required|trim|matches[password1]');
        if ($this->form_validation->run() == FALSE) {

            $data['title'] = 'Registrasi | AIN';
            $this->load->view('templates2/auth_header', $data);
            $this->load->view('auth/registration');
            $this->load->view('templates2/auth_footer');
        } else {
            $email = $this->input->post('email', true);
            $data = [
                'nama' => htmlspecialchars($this->input->post('name', true)),
                'email' => htmlspecialchars($email),
                'image' => 'default.jpg',
                //diingkripsi dlu
                'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
                'role_id' => 2,
                'is_active' => 0,
                'date_created' => time()
            ];

            //siapkan token
            $token = base64_encode(random_bytes(32));
            $user_token = [
                'email' => $email,
                'token' => $token,
                //klo tidak veri sejam jadi expired
                'date_created' => time()
            ];

            //inset ke db
            $this->db->insert('user', $data);
            $this->db->insert('user_token', $user_token);

            //kirim email
            $this->_sendEmail($token, 'verify');

            //ksih pesan
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert" align="center">Selamat Akun telah terdaftar! Silakan aktivasi dahulu
          </div>');
            redirect('auth');
        }
    }

    private function _sendEmail($token, $type)
    {
        $config = [
            //smtp simple mail tranfer protokol
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_user' => 'codeigniterframework3@gmail.com',
            'smtp_pass' => 'bxvvlbonevstobhz',
            'smtp_port' => 465,
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'newline' => "\r\n"
        ];

        $this->load->library('email', $config);
        $this->email->initialize($config);

        //dr siapa
        $this->email->from('codeigniterframework3@gmail.com', 'CI3');
        //mau krim ke sapa
        // $this->email->to('azhariramadan59@gmail.com');
        $this->email->to($this->input->post('email'));
        if ($type == 'verify') {
            $this->email->subject('Verifikasi Akun');
            //urlencode %++@#+
            $this->email->message('Tap link : <a href="' . base_url() . 'auth/verify?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '"> Aktif </a>');
        } else if ($type == 'forgot') {
            $this->email->subject('Reset Password');
            $this->email->message('Tap link untuk reset password : <a href="' . base_url() . 'auth/resetpassword?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '"> Reset Password </a>');
        }


        //klo brshl dkrm
        if ($this->email->send()) {
            return true;
        } else {
            echo $this->email->print_debugger();
            die;
        }
    }

    public function verify()
    {
        $email = $this->input->get('email');
        $token = $this->input->get('token');

        //spy valid dl
        $user = $this->db->get_where('user', ['email' => $email])->row_array();

        if ($user) {
            //cek tkutnya ngasal
            $user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();

            if ($user_token) {
                //klo brhasil ,,,,, waktu paidasi
                //waktu sstt ini dikurangi ....   stu hari blh dftr
                if (time() - $user_token['date_created'] < (60 * 60 * 24)) {
                    //klo bnr update tbl usrna
                    $this->db->set('is_active', 1);
                    $this->db->where('email', $email);
                    $this->db->update('user');

                    //hps tokennya
                    $this->db->delete('user_token', ['email' => $email]);

                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert" align="center">' . $email . ' Akun telah aktif! Silakan login.
                    </div>');
                    redirect('auth');
                } else {
                    //hps dl usernya jgn di db lg
                    $this->db->delete('user', ['email' => $email]);
                    $this->db->delete('user_token', ['email' => $email]);

                    //lbih dr 1 hr
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert" align="center">Akun aktif gagal! Token kedaluarsa.
                    </div>');
                    redirect('auth');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert" align="center">Akun aktif gagal! Token salah.
            </div>');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert" align="center">Akun aktif gagal! Gmail salah.
            </div>');
            redirect('auth');
        }
    }

    //bersihin session sm balikin ke login
    public function logout()
    {
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('role_id');

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert" align="center">Anda telah Logout
          </div>');
        redirect('auth');
    }

    public function blocked()
    {
        $this->load->view('auth/blocked');
    }

    public function forgotpassword()
    {
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        //blk lg ke form vldt ,,, klo ggl
        if ($this->form_validation->run() == FALSE) {

            $data['title'] = 'Lupa Password';
            $this->load->view('templates2/auth_header', $data);
            $this->load->view('auth/forgot-password');
            $this->load->view('templates2/auth_footer');
        } else {
            //ambil email
            $email = $this->input->post('email');
            //  ,,   kloada isiny
            $user = $this->db->get_where('user', ['email' => $email, 'is_active' => 1])->row_array();

            if ($user) {
                //klo ada isi token
                $token = base64_encode(random_bytes(32));
                $user_token = [
                    //arry asosisatif
                    'email' => $email,
                    'token' => $token,
                    'date_created' => time()
                ];

                //isnert
                $this->db->insert('user_token', $user_token);
                $this->_sendEmail($token, 'forgot');

                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert" align="center">Silakan check email untuk reset password!
                </div>');
                redirect('auth/forgotpassword');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert" align="center">Email belum terdaftar / Belum aktif
                </div>');
                redirect('auth/forgotpassword');
            }
        }
    }

    public function resetpassword()
    {
        //buat cek link valid g
        $email = $this->input->get('email');
        $token = $this->input->get('token');

        //dicek di db ada ga user ini
        $user = $this->db->get_where('user', ['email' => $email])->row_array();

        //klo ada ambil token dr tble ut
        if ($user) {
            //cek tokenny
            $user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();

            if ($user_token) {
                //sesion cm srvr yg tau ,,,,, sesion ini ada ktika usr pnct link
                $this->session->set_userdata('reset_email', $email);
                $this->changePassword();
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert" align="center">Reset password gagal! Token salah
                </div>');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert" align="center">Reset password gagal! Email salah
            </div>');
            redirect('auth');
        }
    }

    public function changePassword()
    {
        //simthod ini g bsa akses tnp lewt email
        if (!$this->session->userdata('reset_email')) {
            redirect('auth');
        }

        $this->form_validation->set_rules('password1', 'Password', 'trim|required|min_length[5]|matches[password2]');
        $this->form_validation->set_rules('password2', 'Password', 'trim|required|min_length[5]|matches[password1]');
        //validsi
        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Ubah Password';
            $this->load->view('templates2/auth_header', $data);
            $this->load->view('auth/change-password');
            $this->load->view('templates2/auth_footer');
        } else {
            //sbl update engkripsi dl
            $password = password_hash($this->input->post('password1'), PASSWORD_DEFAULT);
            //ambl email yg ad di sesion
            $email = $this->session->userdata('reset_email');

            //edit tbl user
            $this->db->set('password', $password);
            $this->db->where('email', $email);
            $this->db->update('user');

            //hps sesion dl
            $this->session->unset_userdata('reset_email');

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert" align="center">Password telah di ubah! Silakan Login
            </div>');
            redirect('auth');
        }
    }
}
