<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Konfigurasi Routing untuk Sistem Absensi MTs Al-Muttaqin
 * 
 * File: application/config/routes.php
 * 
 * Dokumen ini mengatur pemetaan URL ke controller dan method yang sesuai.
 * Routing yang baik membuat URL aplikasi lebih bersih, SEO-friendly,
 * dan mudah dipahami oleh pengguna.
 * 
 * Struktur URL:
 * - http://domain.com/controller/method/parameter1/parameter2
 * - http://domain.com/custom-route => controller/method
 * 
 * Panduan Penambahan Route:
 * 1. Gunakan nama route yang deskriptif
 * 2. Kelompokkan route berdasarkan fitur
 * 3. Dokumentasikan setiap custom route
 * 4. Prioritaskan keamanan dengan membatasi akses
 * 
 * @author  Tim Pengembang MTs Al-Muttaqin
 * @version 1.0
 * @since   2024
 */

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| Dokumentasi lengkap routing tersedia di:
| https://codeigniter.com/user_guide/general/routing.html
*/

/**
 * ROUTE DEFAULT
 * Controller dan method yang dipanggil saat mengakses root aplikasi
 * Default: auth/index (halaman login)
 */
$route['default_controller'] = 'auth';
$route['404_override'] = 'errors/page_missing';
$route['translate_uri_dashes'] = FALSE;

/*
| -------------------------------------------------------------------------
| BAGIAN 1: AUTHENTICATION ROUTES
| -------------------------------------------------------------------------
| Route untuk proses autentikasi pengguna
*/

// Halaman login
$route['login'] = 'auth/index';
$route['login'] = 'login/index';  // Halaman login mengarah ke controller Login dan method index
$route['login/cek_login'] = 'login/cek_login';  // Untuk proses verifikasi login

$route['dashboard'] = 'dashboard/index';  // Halaman dashboard mengarah ke controller Dashboard dan method index


// Proses logout
$route['logout'] = 'auth/logout';

// Lupa password (future feature)
$route['lupa-password'] = 'auth/lupa_password';
$route['reset-password/(:any)'] = 'auth/reset_password/$1';

/*
| -------------------------------------------------------------------------
| BAGIAN 2: DASHBOARD ROUTES
| -------------------------------------------------------------------------
| Route untuk halaman dashboard berbagai role
*/

// Dashboard umum (redirect berdasarkan role)
$route['dashboard'] = 'dashboard/index';

// Dashboard spesifik per role
$route['admin/dashboard'] = 'dashboard/admin';
$route['guru/dashboard'] = 'dashboard/guru';

/*
| -------------------------------------------------------------------------
| BAGIAN 3: MASTER DATA ROUTES
| -------------------------------------------------------------------------
| Route untuk pengelolaan data master
*/

/**
 * Route Data Siswa
 * CRUD operations untuk data siswa
 */
$route['siswa'] = 'siswa/index';                    // List semua siswa
$route['siswa/tambah'] = 'siswa/tambah';           // Form tambah siswa
$route['siswa/simpan'] = 'siswa/simpan';           // Proses simpan siswa
$route['siswa/edit/(:num)'] = 'siswa/edit/$1';     // Form edit siswa
$route['siswa/update/(:num)'] = 'siswa/update/$1'; // Proses update siswa
$route['siswa/hapus/(:num)'] = 'siswa/hapus/$1';   // Hapus siswa
$route['siswa/detail/(:num)'] = 'siswa/detail/$1'; // Detail siswa
$route['siswa/import'] = 'siswa/import';            // Import data siswa

/**
 * Route Data Guru
 * CRUD operations untuk data guru
 */
$route['guru'] = 'guru/index';                      // List semua guru
$route['guru/tambah'] = 'guru/tambah';             // Form tambah guru
$route['guru/simpan'] = 'guru/simpan';             // Proses simpan guru
$route['guru/edit/(:num)'] = 'guru/edit/$1';       // Form edit guru
$route['guru/update/(:num)'] = 'guru/update/$1';   // Proses update guru
$route['guru/hapus/(:num)'] = 'guru/hapus/$1';     // Hapus guru
$route['guru/detail/(:num)'] = 'guru/detail/$1';   // Detail guru

/**
 * Route Data Kelas
 * Pengelolaan kelas dan pembagian siswa
 */
$route['kelas'] = 'kelas/index';                    // List semua kelas
$route['kelas/tambah'] = 'kelas/tambah';           // Form tambah kelas
$route['kelas/simpan'] = 'kelas/simpan';           // Proses simpan kelas
$route['kelas/edit/(:num)'] = 'kelas/edit/$1';     // Form edit kelas
$route['kelas/update/(:num)'] = 'kelas/update/$1'; // Proses update kelas
$route['kelas/hapus/(:num)'] = 'kelas/hapus/$1';   // Hapus kelas
$route['kelas/siswa/(:num)'] = 'kelas/daftar_siswa/$1'; // Daftar siswa per kelas

/**
 * Route Mata Pelajaran
 * Pengelolaan mata pelajaran
 */
$route['mapel'] = 'mata_pelajaran/index';
$route['mapel/tambah'] = 'mata_pelajaran/tambah';
$route['mapel/simpan'] = 'mata_pelajaran/simpan';
$route['mapel/edit/(:num)'] = 'mata_pelajaran/edit/$1';
$route['mapel/update/(:num)'] = 'mata_pelajaran/update/$1';
$route['mapel/hapus/(:num)'] = 'mata_pelajaran/hapus/$1';

/*
| -------------------------------------------------------------------------
| BAGIAN 4: JADWAL ROUTES
| -------------------------------------------------------------------------
| Route untuk pengelolaan jadwal pelajaran
*/

$route['jadwal'] = 'jadwal/index';                          // List jadwal
$route['jadwal/kelas/(:num)'] = 'jadwal/per_kelas/$1';     // Jadwal per kelas
$route['jadwal/guru/(:num)'] = 'jadwal/per_guru/$1';       // Jadwal per guru
$route['jadwal/tambah'] = 'jadwal/tambah';                 // Form tambah jadwal
$route['jadwal/simpan'] = 'jadwal/simpan';                 // Proses simpan
$route['jadwal/edit/(:num)'] = 'jadwal/edit/$1';           // Form edit
$route['jadwal/update/(:num)'] = 'jadwal/update/$1';       // Proses update
$route['jadwal/hapus/(:num)'] = 'jadwal/hapus/$1';         // Hapus jadwal

/*
| -------------------------------------------------------------------------
| BAGIAN 5: ABSENSI ROUTES
| -------------------------------------------------------------------------
| Route untuk proses absensi siswa
*/

/**
 * Input Absensi
 * Route untuk input absensi harian
 */
$route['absensi'] = 'absensi/index';                        // Pilih kelas
$route['absensi/input/(:num)'] = 'absensi/input_kelas/$1'; // Form input per kelas
$route['absensi/simpan'] = 'absensi/simpan';               // Proses simpan absensi
$route['absensi/edit/(:any)'] = 'absensi/edit/$1';         // Edit absensi (tanggal)
$route['absensi/update'] = 'absensi/update';               // Update absensi

/**
 * Rekap Absensi
 * Route untuk melihat rekap absensi
 */
$route['absensi/rekap'] = 'absensi/rekap';                 // Form filter rekap
$route['absensi/rekap/siswa/(:num)'] = 'absensi/rekap_siswa/$1';    // Rekap per siswa
$route['absensi/rekap/kelas/(:num)'] = 'absensi/rekap_kelas/$1';    // Rekap per kelas
$route['absensi/rekap/mapel/(:num)'] = 'absensi/rekap_mapel/$1';    // Rekap per mapel

/*
| -------------------------------------------------------------------------
| BAGIAN 6: LAPORAN ROUTES
| -------------------------------------------------------------------------
| Route untuk generate berbagai laporan
*/

// Laporan Absensi
$route['laporan'] = 'laporan/index';                       // Menu laporan
$route['laporan/harian'] = 'laporan/absensi_harian';      // Laporan harian
$route['laporan/bulanan'] = 'laporan/absensi_bulanan';    // Laporan bulanan
$route['laporan/semester'] = 'laporan/absensi_semester';   // Laporan semester

// Export Laporan
$route['laporan/export/excel/(:any)'] = 'laporan/export_excel/$1';
$route['laporan/export/pdf/(:any)'] = 'laporan/export_pdf/$1';
$route['laporan/cetak/(:any)'] = 'laporan/cetak/$1';

// Statistik
$route['statistik'] = 'laporan/statistik';
$route['statistik/kehadiran'] = 'laporan/statistik_kehadiran';

/*
| -------------------------------------------------------------------------
| BAGIAN 7: PENGATURAN ROUTES
| -------------------------------------------------------------------------
| Route untuk konfigurasi aplikasi
*/

$route['pengaturan'] = 'pengaturan/index';
$route['pengaturan/sekolah'] = 'pengaturan/sekolah';
$route['pengaturan/tahun-ajaran'] = 'pengaturan/tahun_ajaran';
$route['pengaturan/update'] = 'pengaturan/update';

// User Management
$route['pengguna'] = 'pengguna/index';
$route['pengguna/tambah'] = 'pengguna/tambah';
$route['pengguna/simpan'] = 'pengguna/simpan';
$route['pengguna/edit/(:num)'] = 'pengguna/edit/$1';
$route['pengguna/update/(:num)'] = 'pengguna/update/$1';
$route['pengguna/hapus/(:num)'] = 'pengguna/hapus/$1';
$route['pengguna/reset-password/(:num)'] = 'pengguna/reset_password/$1';

// Profile
$route['profile'] = 'profile/index';
$route['profile/update'] = 'profile/update';
$route['profile/ganti-password'] = 'profile/ganti_password';

/*
| -------------------------------------------------------------------------
| BAGIAN 8: API ROUTES (Optional)
| -------------------------------------------------------------------------
| Route untuk API endpoints jika diperlukan integrasi
*/

// API untuk aplikasi mobile atau integrasi eksternal
$route['api/auth/login'] = 'api/auth/login';
$route['api/absensi/(:num)'] = 'api/absensi/get/$1';
$route['api/siswa/(:num)'] = 'api/siswa/get/$1';

/*
| -------------------------------------------------------------------------
| BAGIAN 9: UTILITY ROUTES
| -------------------------------------------------------------------------
| Route untuk fungsi-fungsi utilitas
*/

// AJAX endpoints
$route['ajax/get-siswa-kelas/(:num)'] = 'ajax/get_siswa_by_kelas/$1';
$route['ajax/check-nis'] = 'ajax/check_nis';
$route['ajax/get-jadwal-hari'] = 'ajax/get_jadwal_hari';

// Backup & Restore
$route['backup'] = 'utility/backup';
$route['backup/download'] = 'utility/download_backup';
$route['restore'] = 'utility/restore';

/*
| -------------------------------------------------------------------------
| CATATAN PENGEMBANGAN
| -------------------------------------------------------------------------
| 
| 1. Naming Convention:
|    - Gunakan huruf kecil dan dash (-) untuk URL
|    - Gunakan underscore (_) untuk nama method
| 
| 2. RESTful Routes:
|    - GET    /resource         -> index
|    - GET    /resource/create  -> create (form)
|    - POST   /resource         -> store
|    - GET    /resource/(:id)   -> show
|    - GET    /resource/(:id)/edit -> edit (form)
|    - PUT    /resource/(:id)   -> update
|    - DELETE /resource/(:id)   -> destroy
| 
| 3. Security:
|    - Selalu validasi parameter numerik dengan (:num)
|    - Gunakan (:any) hanya jika benar-benar diperlukan
|    - Implementasi middleware untuk cek autentikasi
| 
| 4. Performance:
|    - Route yang spesifik harus di atas route yang general
|    - Hindari terlalu banyak route regex yang kompleks
*/

/* End of file routes.php */
/* Location: ./application/config/routes.php */