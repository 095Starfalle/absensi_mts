<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Konfigurasi Utama Aplikasi Absensi MTs Al-Muttaqin
 * 
 * File: application/config/config.php
 * 
 * Dokumen ini berisi konfigurasi fundamental untuk aplikasi web absensi.
 * Setiap bagian telah didokumentasikan dengan jelas untuk memudahkan
 * pemeliharaan dan pengembangan lebih lanjut.
 * 
 * Struktur Konfigurasi:
 * 1. Pengaturan URL dan Path
 * 2. Konfigurasi Keamanan
 * 3. Pengaturan Session
 * 4. Konfigurasi Cookie
 * 5. Pengaturan Lainnya
 * 
 * @author  Tim Pengembang MTs Al-Muttaqin
 * @version 1.0
 * @since   2024
 */

/*
|--------------------------------------------------------------------------
| BAGIAN 1: PENGATURAN URL DAN PATH
|--------------------------------------------------------------------------
| Konfigurasi dasar untuk URL aplikasi dan struktur direktori.
*/

/**
 * Base Site URL
 * URL lengkap ke root aplikasi CodeIgniter termasuk protokol.
 * Harus diakhiri dengan slash (/).
 * 
 * Contoh Development : http://localhost/absensi_mts/
 * Contoh Production  : https://absensi.mtsalmuttaqin.sch.id/
 */
$config['base_url'] = 'http://localhost/absensi_mts/';

/**
 * Index File
 * Nama file index utama (biasanya 'index.php').
 * Kosongkan jika menggunakan URL rewriting dengan .htaccess
 */
/* $config['index_page'] = '';

/**
 * URI Protocol
 * Metode deteksi URI yang akan digunakan.
 * AUTO = Deteksi otomatis (recommended)
 */
$config['uri_protocol'] = 'REQUEST_URI';

/**
 * URL Suffix
 * Suffix opsional untuk URL (misal: .html)
 * Biarkan kosong untuk URL standar
 */
$config['url_suffix'] = '';

/**
 * Default Language
 * Bahasa default aplikasi (folder di application/language/)
 */
$config['language'] = 'indonesian';

/**
 * Character Set Default
 * Character encoding default untuk aplikasi
 */
$config['charset'] = 'UTF-8';

/*
|--------------------------------------------------------------------------
| BAGIAN 2: KONFIGURASI KEAMANAN
|--------------------------------------------------------------------------
| Pengaturan keamanan untuk melindungi aplikasi dari serangan umum.
*/

/**
 * Enable Hooks
 * Aktifkan sistem hooks untuk preprocessing/postprocessing
 */
$config['enable_hooks'] = FALSE;

/**
 * Class Extension Prefix
 * Prefix untuk extended class (MY_ adalah standar)
 */
$config['subclass_prefix'] = 'MY_';

/**
 * Composer Auto-loading
 * Path ke vendor autoload jika menggunakan Composer
 */
$config['composer_autoload'] = FALSE;

/**
 * Allowed URL Characters
 * Karakter yang diizinkan dalam URI
 * Tambahkan karakter Indonesia jika diperlukan
 */
$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';

/*
|--------------------------------------------------------------------------
| BAGIAN 3: PENGATURAN SESSION
|--------------------------------------------------------------------------
| Konfigurasi untuk manajemen session pengguna.
*/

/**
 * Session Variables
 * Konfigurasi lengkap untuk session handling
 */
$config['sess_driver'] = 'files';
$config['sess_cookie_name'] = 'ci_session_absensi';
$config['sess_expiration'] = 7200; // 2 jam
$config['sess_save_path'] = sys_get_temp_dir();
$config['sess_match_ip'] = FALSE;
$config['sess_time_to_update'] = 300;
$config['sess_regenerate_destroy'] = FALSE;

/**
 * Cookie Related Variables
 * Pengaturan cookie untuk keamanan dan kompatibilitas
 */
$config['cookie_prefix']    = '';
$config['cookie_domain']    = '';
$config['cookie_path']      = '/';
$config['cookie_secure']    = FALSE;
$config['cookie_httponly']  = TRUE;
$config['cookie_samesite']  = 'Lax';

/*
|--------------------------------------------------------------------------
| BAGIAN 4: PENGATURAN ENKRIPSI DAN KEAMANAN
|--------------------------------------------------------------------------
| Konfigurasi untuk enkripsi data dan perlindungan CSRF.
*/

/**
 * Encryption Key
 * Kunci enkripsi 32 karakter untuk keamanan session dan data sensitif
 * PENTING: Ganti dengan kunci acak yang kuat untuk production!
 */
$config['encryption_key'] = 'AbsensiMTsAlMuttaqin2024SecretKey';

/**
 * Cross Site Request Forgery (CSRF)
 * Perlindungan terhadap serangan CSRF
 */
$config['csrf_protection'] = TRUE;
$config['csrf_token_name'] = 'csrf_token_absensi';
$config['csrf_cookie_name'] = 'csrf_cookie_absensi';
$config['csrf_expire'] = 7200;
$config['csrf_regenerate'] = TRUE;
$config['csrf_exclude_uris'] = array();

/*
|--------------------------------------------------------------------------
| BAGIAN 5: PENGATURAN OUTPUT DAN LOGGING
|--------------------------------------------------------------------------
| Konfigurasi untuk output handling dan sistem logging.
*/

/**
 * Output Compression
 * Aktifkan gzip compression untuk performa lebih baik
 */
$config['compress_output'] = FALSE;

/**
 * Master Time Reference
 * Zona waktu default aplikasi
 */
$config['time_reference'] = 'local';
date_default_timezone_set('Asia/Jakarta');

/**
 * Rewrite PHP Short Tags
 * Konversi otomatis short tag PHP
 */
$config['rewrite_short_tags'] = FALSE;

/**
 * Error Logging
 * Level logging error (0-4)
 * 0 = Disable, 1 = Errors, 2 = Debug, 3 = Info, 4 = All
 */
$config['log_threshold'] = 1;
$config['log_path'] = '';
$config['log_file_extension'] = '';
$config['log_file_permissions'] = 0644;
$config['log_date_format'] = 'Y-m-d H:i:s';

/**
 * Error Views Directory Path
 * Path ke direktori error pages
 */
$config['error_views_path'] = '';

/**
 * Cache Directory Path
 * Path untuk menyimpan cache files
 */
$config['cache_path'] = '';
$config['cache_query_string'] = FALSE;

/*
|--------------------------------------------------------------------------
| BAGIAN 6: PENGATURAN PROXY DAN REVERSE PROXY
|--------------------------------------------------------------------------
| Konfigurasi untuk deployment di belakang proxy server.
*/

/**
 * Reverse Proxy IPs
 * Daftar IP proxy yang dipercaya (kosongkan jika tidak ada)
 */
$config['proxy_ips'] = '';

/*
|--------------------------------------------------------------------------
| BAGIAN 7: KONFIGURASI TAMBAHAN
|--------------------------------------------------------------------------
| Pengaturan tambahan untuk optimasi dan fitur khusus.
*/

/**
 * Standardize Newlines
 * Standarisasi karakter newline
 */
$config['standardize_newlines'] = TRUE;

/**
 * Global XSS Filtering
 * DEPRECATED: Gunakan Security library untuk XSS protection
 */
$config['global_xss_filtering'] = FALSE;

/**
 * Enable Query Strings
 * Aktifkan query string untuk controller dan function
 */
$config['enable_query_strings'] = FALSE;
$config['controller_trigger'] = 'c';
$config['function_trigger'] = 'm';
$config['directory_trigger'] = 'd';

/**
 * Allow $_GET array
 * Izinkan penggunaan $_GET (hati-hati dengan keamanan)
 */
$config['allow_get_array'] = TRUE;

/**
 * Error Logging Directory Permissions
 * Permission untuk direktori log
 */
$config['log_file_permissions'] = 0644;

/**
 * Aplikasi Metadata
 * Informasi tambahan tentang aplikasi
 */
$config['app_name'] = 'Sistem Absensi MTs Al-Muttaqin';
$config['app_version'] = '1.0.0';
$config['app_author'] = 'Tim Pengembang MTs Al-Muttaqin';
$config['app_year'] = '2024';

/* End of file config.php */
/* Location: ./application/config/config.php */