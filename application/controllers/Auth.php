<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Auth
 * 
 * Mengelola proses autentikasi pengguna sistem absensi MTs Al-Muttaqin.
 * Controller ini bertanggung jawab untuk login, logout, dan validasi session.
 * 
 * Fitur Utama:
 * - Login dengan username dan password
 * - Validasi kredensial terhadap database
 * - Manajemen session pengguna
 * - Logout dan pembersihan session
 * - Redirect berdasarkan role pengguna
 * 
 * Alur Proses:
 * 1. User mengakses halaman login
 * 2. Input username dan password
 * 3. Validasi form di sisi client dan server
 * 4. Verifikasi kredensial dengan database
 * 5. Buat session jika valid
 * 6. Redirect ke dashboard sesuai role
 * 
 * @author  Tim Pengembang MTs Al-Muttaqin
 * @version 1.0
 * @since   2024
 */
class Auth extends CI_Controller {

    /**
     * Constructor
     * Inisialisasi model dan helper yang diperlukan
     */
    public function __construct() {
        parent::__construct();
        
        // Load model untuk autentikasi
        $this->load->model('auth_model');
        
        // Load helper tambahan jika diperlukan
        $this->load->helper('security');
    }

    /**
     * Halaman Login (Default)
     * 
     * Menampilkan form login atau redirect jika sudah login.
     * Method ini dipanggil saat user mengakses root aplikasi.
     * 
     * Flow:
     * 1. Cek apakah user sudah login
     * 2. Jika sudah, redirect ke dashboard
     * 3. Jika belum, tampilkan form login
     * 4. Proses validasi jika form disubmit
     * 
     * @return void
     */
    public function index() {
        // Cek apakah user sudah login
        if ($this->_check_login()) {
            // Redirect ke dashboard sesuai role
            $this->_redirect_dashboard();
            return;
        }

        // Set rules validasi form
        $this->form_validation->set_rules(
            'username', 
            'Username', 
            'required|trim|min_length[3]|max_length[50]',
            array(
                'required' => '%s wajib diisi',
                'min_length' => '%s minimal 3 karakter',
                'max_length' => '%s maksimal 50 karakter'
            )
        );
        
        $this->form_validation->set_rules(
            'password', 
            'Password', 
            'required|min_length[6]',
            array(
                'required' => '%s wajib diisi',
                'min_length' => '%s minimal 6 karakter'
            )
        );

        // Cek apakah form valid
        if ($this->form_validation->run() == FALSE) {
            // Form tidak valid atau belum disubmit
            // Tampilkan halaman login
            $data['title'] = 'Login - Sistem Absensi MTs Al-Muttaqin';
            
            // Load view login
            $this->load->view('auth/login', $data);
        } else {
            // Form valid, proses login
            $this->_process_login();
        }
    }

    /**
     * Proses Login (Private Method)
     * 
     * Memproses kredensial yang disubmit dan membuat session.
     * Method private untuk keamanan, hanya bisa dipanggil internal.
     * 
     * Langkah Validasi:
     * 1. Ambil input username dan password
     * 2. Bersihkan input dari karakter berbahaya
     * 3. Cek ke database menggunakan model
     * 4. Verifikasi password dengan hash
     * 5. Buat session jika valid
     * 6. Catat log aktivitas login
     * 
     * @access private
     * @return void
     */
    private function _process_login() {
        // Ambil input dari form (sudah tervalidasi)
        $username = $this->input->post('username', TRUE); // TRUE untuk XSS filtering
        $password = $this->input->post('password', TRUE);
        
        // Validasi kredensial menggunakan model
        $user = $this->auth_model->validate_login($username, $password);
        
        if ($user !== FALSE) {
            // Login berhasil
            // Session sudah dibuat di model
            
            // Catat aktivitas login
            $this->_log_activity('login', $user->id);
            
            // Set flash message sukses
            $this->session->set_flashdata('success', 
                'Selamat datang kembali, ' . $user->nama_lengkap . '!'
            );
            
            // Redirect ke dashboard sesuai role
            $this->_redirect_dashboard();
            
        } else {
            // Login gagal
            // Set flash message error
            $this->session->set_flashdata('error', 
                'Username atau password salah. Silakan coba lagi.'
            );
            
            // Catat percobaan login gagal
            $this->_log_failed_attempt($username);
            
            // Redirect kembali ke halaman login
            redirect('auth');
        }
    }

    /**
     * Proses Logout
     * 
     * Menghapus session dan redirect ke halaman login.
     * Dapat diakses langsung via URL /logout.
     * 
     * @return void
     */
    public function logout() {
        // Cek apakah user memang sudah login
        if ($this->_check_login()) {
            // Ambil user_id sebelum session dihapus
            $user_id = $this->session->userdata('user_id');
            
            // Catat aktivitas logout
            $this->_log_activity('logout', $user_id);
            
            // Hapus session menggunakan model
            $this->auth_model->logout();
            
            // Set flash message
            $this->session->set_flashdata('success', 
                'Anda telah berhasil logout dari sistem.'
            );
        }
        
        // Redirect ke halaman login
        redirect('auth');
    }

    /**
     * Cek Status Login (Private Method)
     * 
     * Memeriksa apakah user sudah login atau belum.
     * 
     * @access private
     * @return boolean TRUE jika sudah login, FALSE jika belum
     */
    private function _check_login() {
        return $this->auth_model->is_logged_in();
    }

    /**
     * Redirect Dashboard Berdasarkan Role (Private Method)
     * 
     * Mengarahkan user ke dashboard yang sesuai dengan role mereka.
     * 
     * @access private
     * @return void
     */
    private function _redirect_dashboard() {
        $role = $this->session->userdata('role');
        
        switch ($role) {
            case 'admin':
                redirect('admin/dashboard');
                break;
            case 'guru':
                redirect('guru/dashboard');
                break;
            default:
                // Jika role tidak dikenali, redirect ke dashboard umum
                redirect('dashboard');
                break;
        }
    }

    /**
     * Catat Aktivitas Login/Logout (Private Method)
     * 
     * Mencatat aktivitas user untuk keperluan audit dan keamanan.
     * 
     * @access private
     * @param  string $action Jenis aktivitas (login/logout)
     * @param  int    $user_id ID user yang melakukan aktivitas
     * @return void
     */
    private function _log_activity($action, $user_id) {
        $log_data = array(
            'user_id' => $user_id,
            'action' => $action,
            'ip_address' => $this->input->ip_address(),
            'user_agent' => $this->input->user_agent(),
            'timestamp' => date('Y-m-d H:i:s')
        );
        
        // Simpan ke tabel log_aktivitas (opsional)
        // $this->db->insert('log_aktivitas', $log_data);
        
        // Untuk sementara, log ke file
        log_message('info', 'User ' . $user_id . ' melakukan ' . $action);
    }

    /**
     * Catat Percobaan Login Gagal (Private Method)
     * 
     * Mencatat percobaan login yang gagal untuk deteksi serangan.
     * 
     * @access private
     * @param  string $username Username yang dicoba
     * @return void
     */
    private function _log_failed_attempt($username) {
        $log_data = array(
            'username' => $username,
            'ip_address' => $this->input->ip_address(),
            'user_agent' => $this->input->user_agent(),
            'timestamp' => date('Y-m-d H:i:s')
        );
        
        // Log ke file untuk monitoring
        log_message('warning', 'Login gagal untuk username: ' . $username . 
                             ' dari IP: ' . $this->input->ip_address());
        
        // TODO: Implementasi pembatasan percobaan login
        // Contoh: Block IP setelah 5 kali percobaan gagal dalam 15 menit
    }

    /**
     * Halaman Lupa Password (Future Feature)
     * 
     * Menampilkan form untuk reset password.
     * 
     * @return void
     */
    public function lupa_password() {
        // TODO: Implementasi fitur lupa password
        // 1. Form input email
        // 2. Generate token reset
        // 3. Kirim email dengan link reset
        // 4. Validasi token dan update password
        
        $this->session->set_flashdata('info', 
            'Fitur lupa password sedang dalam pengembangan.'
        );
        redirect('auth');
    }

    /**
     * Reset Password dengan Token (Future Feature)
     * 
     * Memproses reset password menggunakan token dari email.
     * 
     * @param  string $token Token reset password
     * @return void
     */
    public function reset_password($token = '') {
        // TODO: Implementasi reset password
        // 1. Validasi token
        // 2. Cek expired date
        // 3. Form password baru
        // 4. Update password di database
        
        $this->session->set_flashdata('info', 
            'Fitur reset password sedang dalam pengembangan.'
        );
        redirect('auth');
    }
}

/* End of file Auth.php */
/* Location: ./application/controllers/Auth.php */