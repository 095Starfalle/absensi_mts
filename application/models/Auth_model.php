<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Auth_model
 * 
 * Mengelola logika bisnis untuk autentikasi pengguna sistem absensi.
 * Model ini berinteraksi dengan database untuk validasi kredensial,
 * manajemen session, dan operasi terkait autentikasi lainnya.
 * 
 * Tabel Database yang Digunakan:
 * - users: Menyimpan data kredensial dan informasi dasar pengguna
 * - log_aktivitas: Mencatat aktivitas login/logout (opsional)
 * 
 * Fungsi Utama:
 * - Validasi kredensial login
 * - Pembuatan dan penghapusan session
 * - Pengecekan status login
 * - Manajemen role pengguna
 * 
 * Keamanan:
 * - Password di-hash menggunakan bcrypt (password_hash)
 * - Prepared statements untuk mencegah SQL injection
 * - Session encryption menggunakan CI encryption key
 * 
 * @author  Tim Pengembang MTs Al-Muttaqin
 * @version 1.0
 * @since   2024
 */
class Auth_model extends CI_Model {

    /**
     * Nama tabel database
     * @var string
     */
    private $table = 'users';

    /**
     * Constructor
     * Inisialisasi library dan konfigurasi
     */
    public function __construct() {
        parent::__construct();
        
        // Load library encryption jika diperlukan
        $this->load->library('encryption');
    }

    /**
     * Validasi Login
     * 
     * Memvalidasi kredensial pengguna dan membuat session jika valid.
     * Menggunakan password_verify untuk keamanan maksimal.
     * 
     * Proses Validasi:
     * 1. Query user berdasarkan username
     * 2. Cek apakah user ditemukan
     * 3. Verifikasi password dengan hash
     * 4. Update last login timestamp
     * 5. Buat session data
     * 
     * @param  string $username Username yang diinputkan
     * @param  string $password Password plain text yang diinputkan
     * @return mixed  Object user jika valid, FALSE jika tidak valid
     * 
     * @example
     * $user = $this->auth_model->validate_login('admin', 'password123');
     * if ($user !== FALSE) {
     *     // Login berhasil
     * }
     */
    public function validate_login($username, $password) {
        // Query untuk mendapatkan data user berdasarkan username
        $this->db->select('id, username, password, nama_lengkap, role');
        $this->db->from($this->table);
        $this->db->where('username', $username);
        $this->db->limit(1);
        
        $query = $this->db->get();
        
        // Cek apakah user ditemukan
        if ($query->num_rows() == 1) {
            $user = $query->row();
            
            // Verifikasi password menggunakan bcrypt
            if (password_verify($password, $user->password)) {
                // Password valid, update last login
                $this->_update_last_login($user->id);
                
                // Buat session data
                $this->_create_session($user);
                
                // Return user object tanpa password
                unset($user->password);
                return $user;
            }
        }
        
        // Login gagal
        return FALSE;
    }

    /**
     * Buat Session Data (Private Method)
     * 
     * Membuat session dengan data user yang diperlukan.
     * Session di-encrypt otomatis oleh CodeIgniter.
     * 
     * @access private
     * @param  object $user Data user dari database
     * @return void
     */
    private function _create_session($user) {
        // Data session yang akan disimpan
        $session_data = array(
            'user_id'      => $user->id,
            'username'     => $user->username,
            'nama_lengkap' => $user->nama_lengkap,
            'role'         => $user->role,
            'logged_in'    => TRUE,
            'login_time'   => time(),
            'last_activity'=> time()
        );
        
        // Set session data
        $this->session->set_userdata($session_data);
        
        // Regenerate session ID untuk keamanan
        $this->session->sess_regenerate(TRUE);
    }

    /**
     * Update Last Login Timestamp (Private Method)
     * 
     * Mencatat waktu terakhir user login untuk audit trail.
     * 
     * @access private
     * @param  int $user_id ID user yang login
     * @return void
     */
    private function _update_last_login($user_id) {
        $data = array(
            'last_login' => date('Y-m-d H:i:s')
        );
        
        $this->db->where('id', $user_id);
        $this->db->update($this->table, $data);
    }

    /**
     * Logout dan Hapus Session
     * 
     * Menghapus semua data session dan destroy session.
     * 
     * @return void
     */
    public function logout() {
        // Array key session yang akan dihapus
        $session_items = array(
            'user_id',
            'username', 
            'nama_lengkap',
            'role',
            'logged_in',
            'login_time',
            'last_activity'
        );
        
        // Hapus item session satu per satu
        $this->session->unset_userdata($session_items);
        
        // Destroy seluruh session
        $this->session->sess_destroy();
    }

    /**
     * Cek Status Login
     * 
     * Memeriksa apakah user sudah login dengan validasi session.
     * 
     * Validasi meliputi:
     * - Keberadaan session logged_in
     * - Validitas user_id
     * - Timeout session (opsional)
     * 
     * @return boolean TRUE jika sudah login, FALSE jika belum
     */
    public function is_logged_in() {
        // Cek basic login flag
        if ($this->session->userdata('logged_in') !== TRUE) {
            return FALSE;
        }
        
        // Cek keberadaan user_id
        if (!$this->session->userdata('user_id')) {
            return FALSE;
        }
        
        // Cek session timeout (opsional)
        if ($this->_is_session_expired()) {
            $this->logout();
            return FALSE;
        }
        
        // Update last activity
        $this->session->set_userdata('last_activity', time());
        
        return TRUE;
    }

    /**
     * Cek Session Timeout (Private Method)
     * 
     * Memeriksa apakah session sudah expired berdasarkan inactivity.
     * Default: 2 jam tanpa aktivitas.
     * 
     * @access private
     * @return boolean TRUE jika expired, FALSE jika masih valid
     */
    private function _is_session_expired() {
        $last_activity = $this->session->userdata('last_activity');
        $timeout = 7200; // 2 jam dalam detik
        
        if ($last_activity && (time() - $last_activity > $timeout)) {
            return TRUE;
        }
        
        return FALSE;
    }

    /**
     * Cek Role Pengguna
     * 
     * Memeriksa apakah user memiliki role tertentu.
     * Digunakan untuk authorization di controller.
     * 
     * @param  string|array $role Role yang dicek (bisa string atau array)
     * @return boolean TRUE jika role sesuai, FALSE jika tidak
     * 
     * @example
     * // Cek single role
     * if ($this->auth_model->check_role('admin')) {
     *     // User adalah admin
     * }
     * 
     * // Cek multiple roles
     * if ($this->auth_model->check_role(['admin', 'guru'])) {
     *     // User adalah admin ATAU guru
     * }
     */
    public function check_role($role) {
        $user_role = $this->session->userdata('role');
        
        if (!$user_role) {
            return FALSE;
        }
        
        // Jika parameter adalah array, cek apakah user role ada di dalamnya
        if (is_array($role)) {
            return in_array($user_role, $role);
        }
        
        // Jika parameter adalah string, cek kesamaan
        return $user_role === $role;
    }

    /**
     * Get Current User Data
     * 
     * Mengambil data user yang sedang login dari database.
     * Data fresh dari database, bukan dari session.
     * 
     * @return mixed Object user jika login, NULL jika tidak
     */
    public function get_current_user() {
        $user_id = $this->session->userdata('user_id');
        
        if (!$user_id) {
            return NULL;
        }
        
        // Query data user terbaru
        $this->db->select('id, username, nama_lengkap, role, created_at, last_login');
        $this->db->from($this->table);
        $this->db->where('id', $user_id);
        $this->db->limit(1);
        
        $query = $this->db->get();
        
        if ($query->num_rows() == 1) {
            return $query->row();
        }
        
        return NULL;
    }

    /**
     * Update Password
     * 
     * Mengupdate password user dengan hash baru.
     * Digunakan untuk fitur ganti password.
     * 
     * @param  int    $user_id ID user
     * @param  string $new_password Password baru (plain text)
     * @return boolean TRUE jika berhasil, FALSE jika gagal
     */
    public function update_password($user_id, $new_password) {
        // Hash password baru
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        
        // Update di database
        $data = array(
            'password' => $hashed_password,
            'password_changed_at' => date('Y-m-d H:i:s')
        );
        
        $this->db->where('id', $user_id);
        $updated = $this->db->update($this->table, $data);
        
        return $updated;
    }

    /**
     * Verify Current Password
     * 
     * Memverifikasi password lama sebelum ganti password.
     * 
     * @param  int    $user_id ID user
     * @param  string $current_password Password lama untuk verifikasi
     * @return boolean TRUE jika password benar, FALSE jika salah
     */
    public function verify_current_password($user_id, $current_password) {
        // Ambil password hash dari database
        $this->db->select('password');
        $this->db->from($this->table);
        $this->db->where('id', $user_id);
        $this->db->limit(1);
        
        $query = $this->db->get();
        
        if ($query->num_rows() == 1) {
            $user = $query->row();
            return password_verify($current_password, $user->password);
        }
        
        return FALSE;
    }

    /**
     * Get User By Username
     * 
     * Mengambil data user berdasarkan username.
     * Digunakan untuk berbagai keperluan validasi.
     * 
     * @param  string $username Username yang dicari
     * @return mixed  Object user jika ditemukan, NULL jika tidak
     */
    public function get_user_by_username($username) {
        $this->db->select('id, username, nama_lengkap, role');
        $this->db->from($this->table);
        $this->db->where('username', $username);
        $this->db->limit(1);
        
        $query = $this->db->get();
        
        if ($query->num_rows() == 1) {
            return $query->row();
        }
        
        return NULL;
    }
}

/* End of file Auth_model.php */
/* Location: ./application/models/Auth_model.php */
