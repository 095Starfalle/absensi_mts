<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Library Template
 * 
 * Mengelola template dan layout untuk aplikasi absensi MTs Al-Muttaqin.
 * Library ini memudahkan penggunaan template yang konsisten di seluruh aplikasi
 * dengan memisahkan header, sidebar, content, dan footer.
 * 
 * Fitur Utama:
 * - Load view dengan template lengkap
 * - Manajemen asset CSS dan JavaScript
 * - Breadcrumb otomatis
 * - Title page dinamis
 * - Flash message handling
 * - Permission checking
 * 
 * Struktur Template:
 * 1. Header   - Meta tags, CSS, navigasi atas
 * 2. Sidebar  - Menu navigasi samping
 * 3. Content  - Konten utama halaman
 * 4. Footer   - JavaScript, copyright
 * 
 * @author  Tim Pengembang MTs Al-Muttaqin
 * @version 1.0
 * @since   2024
 */
class Template {
    
    /**
     * CodeIgniter instance
     * @var object
     */
    protected $CI;
    
    /**
     * Data template default
     * @var array
     */
    protected $template_data = array();
    
    /**
     * Constructor
     * Inisialisasi library dan load dependencies
     */
    public function __construct() {
        // Get CodeIgniter instance
        $this->CI =& get_instance();
        
        // Load model auth untuk checking
        $this->CI->load->model('auth_model');
        
        // Set default template data
        $this->template_data = array(
            'title' => 'Sistem Absensi MTs Al-Muttaqin',
            'page_title' => 'Dashboard',
            'breadcrumb' => array(),
            'css_files' => array(),
            'js_files' => array(),
            'meta_tags' => array()
        );
    }
    
    /**
     * Load View dengan Template
     * 
     * Method utama untuk menampilkan halaman dengan template lengkap.
     * Otomatis mengecek login status dan permission.
     * 
     * @param  string $content_view Path ke view content
     * @param  array  $data         Data yang akan dikirim ke view
     * @param  string $template     Template yang digunakan (default/login/print)
     * @return void
     * 
     * @example
     * // Di controller
     * $data['siswa'] = $this->siswa_model->get_all();
     * $this->template->load('siswa/index', $data);
     */
    public function load($content_view, $data = array(), $template = 'default') {
        // Cek login status
        if (!$this->CI->auth_model->is_logged_in()) {
            redirect('auth');
            return;
        }
        
        // Merge data dengan template data
        $data = array_merge($this->template_data, $data);
        
        // Set user data untuk header/sidebar
        $data['current_user'] = $this->CI->auth_model->get_current_user();
        
        // Generate breadcrumb otomatis
        if (empty($data['breadcrumb'])) {
            $data['breadcrumb'] = $this->_generate_breadcrumb();
        }
        
        // Load views berdasarkan template
        switch ($template) {
            case 'default':
                $this->CI->load->view('templates/header', $data);
                $this->CI->load->view('templates/sidebar', $data);
                $this->CI->load->view('templates/content_open', $data);
                $this->CI->load->view($content_view, $data);
                $this->CI->load->view('templates/content_close', $data);
                $this->CI->load->view('templates/footer', $data);
                break;
                
            case 'blank':
                // Template tanpa sidebar
                $this->CI->load->view('templates/header', $data);
                $this->CI->load->view($content_view, $data);
                $this->CI->load->view('templates/footer', $data);
                break;
                
            case 'print':
                // Template untuk print/export
                $this->CI->load->view('templates/print_header', $data);
                $this->CI->load->view($content_view, $data);
                $this->CI->load->view('templates/print_footer', $data);
                break;
                
            default:
                // Default template
                $this->_load_default($content_view, $data);
                break;
        }
    }
    
    /**
     * Load Single View
     * 
     * Load view tanpa template, untuk halaman khusus seperti login.
     * 
     * @param  string $view View yang akan di-load
     * @param  array  $data Data untuk view
     * @return void
     */
    public function load_single($view, $data = array()) {
        $data = array_merge($this->template_data, $data);
        $this->CI->load->view($view, $data);
    }
    
    /**
     * Set Page Title
     * 
     * Mengatur judul halaman yang tampil di browser tab dan page header.
     * 
     * @param  string $title Judul halaman
     * @return object $this untuk method chaining
     */
    public function set_title($title) {
        $this->template_data['title'] = $title . ' - Sistem Absensi MTs Al-Muttaqin';
        $this->template_data['page_title'] = $title;
        return $this;
    }
    
    /**
     * Add CSS File
     * 
     * Menambahkan file CSS eksternal ke halaman.
     * 
     * @param  string $css_file Path ke file CSS
     * @return object $this untuk method chaining
     * 
     * @example
     * $this->template->add_css('assets/plugins/datatables/datatables.min.css')
     *                ->add_css('assets/css/custom.css');
     */
    public function add_css($css_file) {
        $this->template_data['css_files'][] = $css_file;
        return $this;
    }
    
    /**
     * Add JavaScript File
     * 
     * Menambahkan file JavaScript eksternal ke halaman.
     * 
     * @param  string $js_file Path ke file JavaScript
     * @return object $this untuk method chaining
     */
    public function add_js($js_file) {
        $this->template_data['js_files'][] = $js_file;
        return $this;
    }
    
    /**
     * Set Breadcrumb
     * 
     * Mengatur breadcrumb navigation secara manual.
     * 
     * @param  array $breadcrumb Array breadcrumb items
     * @return object $this untuk method chaining
     * 
     * @example
     * $this->template->set_breadcrumb([
     *     ['title' => 'Dashboard', 'url' => 'dashboard'],
     *     ['title' => 'Siswa', 'url' => 'siswa'],
     *     ['title' => 'Tambah', 'url' => '']  // Current page
     * ]);
     */
    public function set_breadcrumb($breadcrumb) {
        $this->template_data['breadcrumb'] = $breadcrumb;
        return $this;
    }
    
    /**
     * Add Meta Tag
     * 
     * Menambahkan meta tag ke halaman untuk SEO.
     * 
     * @param  string $name    Nama meta tag
     * @param  string $content Content meta tag
     * @return object $this untuk method chaining
     */
    public function add_meta($name, $content) {
        $this->template_data['meta_tags'][] = array(
            'name' => $name,
            'content' => $content
        );
        return $this;
    }
    
    /**
     * Check Permission
     * 
     * Mengecek apakah user memiliki permission untuk akses.
     * 
     * @param  string|array $roles Role yang diizinkan
     * @return boolean TRUE jika diizinkan
     */
    public function check_permission($roles) {
        if (!$this->CI->auth_model->check_role($roles)) {
            // Set error message
            $this->CI->session->set_flashdata('error', 
                'Anda tidak memiliki akses ke halaman tersebut.'
            );
            
            // Redirect ke dashboard
            redirect('dashboard');
            return FALSE;
        }
        
        return TRUE;
    }
    
    /**
     * Generate Breadcrumb Otomatis
     * 
     * Membuat breadcrumb berdasarkan URI segments.
     * 
     * @access private
     * @return array Breadcrumb items
     */
    private function _generate_breadcrumb() {
        $breadcrumb = array();
        
        // Home/Dashboard selalu ada
        $breadcrumb[] = array(
            'title' => 'Dashboard',
            'url' => base_url('dashboard')
        );
        
        // Get URI segments
        $segments = $this->CI->uri->segment_array();
        
        if (!empty($segments)) {
            $url = '';
            $total = count($segments);
            
            foreach ($segments as $key => $segment) {
                $url .= $segment;
                
                // Skip numeric segments (usually IDs)
                if (is_numeric($segment)) {
                    continue;
                }
                
                // Beautify segment name
                $title = ucwords(str_replace(array('-', '_'), ' ', $segment));
                
                // Last segment doesn't have URL
                if ($key == $total) {
                    $breadcrumb[] = array(
                        'title' => $title,
                        'url' => ''
                    );
                } else {
                    $breadcrumb[] = array(
                        'title' => $title,
                        'url' => base_url($url)
                    );
                    $url .= '/';
                }
            }
        }
        
        return $breadcrumb;
    }
    
    /**
     * Load Default Template
     * 
     * Helper method untuk load template default.
     * 
     * @access private
     * @param  string $content_view View content
     * @param  array  $data         View data
     * @return void
     */
    private function _load_default($content_view, $data) {
        $this->CI->load->view('templates/header', $data);
        $this->CI->load->view('templates/sidebar', $data);
        $this->CI->load->view('templates/content_open', $data);
        $this->CI->load->view($content_view, $data);
        $this->CI->load->view('templates/content_close', $data);
        $this->CI->load->view('templates/footer', $data);
    }
    
    /**
     * Show 404 Error Page
     * 
     * Menampilkan halaman error 404 dengan template.
     * 
     * @param  string $message Pesan error opsional
     * @return void
     */
    public function show_404($message = '') {
        $data['message'] = $message ?: 'Halaman yang Anda cari tidak ditemukan.';
        
        $this->set_title('404 - Halaman Tidak Ditemukan');
        $this->load('errors/error_404', $data);
    }
    
    /**
     * Show 403 Forbidden Page
     * 
     * Menampilkan halaman error 403 (akses ditolak).
     * 
     * @param  string $message Pesan error opsional
     * @return void
     */
    public function show_403($message = '') {
        $data['message'] = $message ?: 'Anda tidak memiliki akses ke halaman ini.';
        
        $this->set_title('403 - Akses Ditolak');
        $this->load('errors/error_403', $data);
    }
}

/* End of file Template.php */
/* Location: ./application/libraries/Template.php */