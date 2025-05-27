<?php
class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
    }

    public function index() {
        // Tampilkan halaman login
        $this->load->view('login');
    }

    public function cek_login() {
        // Ambil data dari form
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        // Verifikasi data login
        $user = $this->User_model->cek_login($email, $password);

        if ($user) {
            // Jika login berhasil, redirect ke dashboard
            redirect('dashboard');
        } else {
            // Jika gagal, tampilkan pesan error
            $this->session->set_flashdata('error', 'Email atau Password salah');
            redirect('login');
        }
    }
}
