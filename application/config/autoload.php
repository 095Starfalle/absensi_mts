<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Konfigurasi Autoload untuk Sistem Absensi MTs Al-Muttaqin
 * 
 * File: application/config/autoload.php
 * 
 * Dokumen ini mengatur komponen-komponen yang akan dimuat otomatis
 * saat aplikasi dijalankan. Autoload meningkatkan performa dengan
 * memuat komponen yang sering digunakan sekali saja di awal.
 * 
 * Panduan Penggunaan:
 * 1. Hanya muat komponen yang benar-benar digunakan di seluruh aplikasi
 * 2. Pertimbangkan dampak performa dari setiap komponen yang dimuat
 * 3. Gunakan lazy loading untuk komponen yang jarang digunakan
 * 
 * @author  Tim Pengembang MTs Al-Muttaqin
 * @version 1.0
 * @since   2024
 */

/*
| -------------------------------------------------------------------
| BAGIAN 1: AUTO-LOADER PACKAGES
| -------------------------------------------------------------------
| Prototype:
|   $autoload['packages'] = array(APPPATH.'third_party', '/usr/local/shared');
| 
| Memuat package pihak ketiga secara otomatis.
| Untuk aplikasi ini, tidak ada package eksternal yang diperlukan.
*/
$autoload['packages'] = array();

/*
| -------------------------------------------------------------------
| BAGIAN 2: AUTO-LOADER LIBRARIES
| -------------------------------------------------------------------
| Libraries adalah class-class yang menyediakan fungsi spesifik.
| Memuat libraries yang esensial untuk operasi aplikasi.
| 
| Libraries yang dimuat:
| - database    : Koneksi dan operasi database
| - session     : Manajemen session pengguna
| - form_validation : Validasi input form
| - email       : Pengiriman email (untuk notifikasi)
| - template    : Custom library untuk manajemen template
*/
$autoload['libraries'] = array(
    'database',        // Wajib: Koneksi database MySQL
    'session',         // Wajib: Manajemen login dan data session
    'form_validation', // Wajib: Validasi form input absensi
    'email'           // Opsional: Notifikasi email
);

/*
| -------------------------------------------------------------------
| BAGIAN 3: AUTO-LOADER DRIVERS
| -------------------------------------------------------------------
| Prototype:
|   $autoload['drivers'] = array('cache');
| 
| Driver adalah libraries khusus dengan sub-libraries.
| Saat ini tidak ada driver yang perlu dimuat otomatis.
*/
$autoload['drivers'] = array();

/*
| -------------------------------------------------------------------
| BAGIAN 4: AUTO-LOADER HELPER FILES
| -------------------------------------------------------------------
| Helper adalah kumpulan fungsi yang membantu tugas-tugas umum.
| 
| Helper yang dimuat:
| - url    : Fungsi URL (base_url, site_url, redirect)
| - form   : Pembuatan elemen form HTML
| - html   : Tag HTML umum
| - date   : Manipulasi tanggal (penting untuk absensi)
| - security : Fungsi keamanan tambahan
| - cookie : Manajemen cookie
*/
$autoload['helper'] = array(
    'url',      // Wajib: Navigasi dan URL generation
    'form',     // Wajib: Pembuatan form absensi
    'html',     // Wajib: Elemen HTML
    'date',     // Wajib: Format tanggal absensi
    'security', // Recommended: XSS protection
    'cookie'    // Opsional: Remember me feature
);

/*
| -------------------------------------------------------------------
| BAGIAN 5: AUTO-LOADER CONFIG FILES
| -------------------------------------------------------------------
| Prototype:
|   $autoload['config'] = array('config1', 'config2');
| 
| Memuat file konfigurasi tambahan secara otomatis.
| File konfigurasi khusus aplikasi akan dimuat di sini.
*/
$autoload['config'] = array(
    // 'app_config' // Konfigurasi khusus aplikasi (jika ada)
);

/*
| -------------------------------------------------------------------
| BAGIAN 6: AUTO-LOADER LANGUAGE FILES
| -------------------------------------------------------------------
| Prototype:
|   $autoload['language'] = array('lang1', 'lang2');
| 
| Memuat file bahasa untuk mendukung multi-bahasa.
| Aplikasi ini menggunakan Bahasa Indonesia sebagai default.
*/
$autoload['language'] = array(
    // 'app_lang' // File bahasa khusus aplikasi
);

/*
| -------------------------------------------------------------------
| BAGIAN 7: AUTO-LOADER MODELS
| -------------------------------------------------------------------
| Prototype:
|   $autoload['model'] = array('first_model', 'second_model');
| 
| Model yang sering digunakan dapat dimuat otomatis.
| Namun, best practice adalah memuat model sesuai kebutuhan
| di masing-masing controller untuk efisiensi memori.
*/
$autoload['model'] = array(
    // Models akan dimuat per-controller untuk efisiensi
    // Contoh yang mungkin dimuat global:
    // 'pengaturan_model' // Untuk konfigurasi aplikasi global
);

/*
| -------------------------------------------------------------------
| CATATAN PERFORMA DAN OPTIMASI
| -------------------------------------------------------------------
| 
| 1. Memory Usage
|    Setiap komponen yang di-autoload akan menggunakan memori
|    sepanjang eksekusi aplikasi. Pertimbangkan dengan bijak.
| 
| 2. Loading Time
|    Terlalu banyak autoload dapat memperlambat startup aplikasi.
|    Target: < 100ms untuk halaman sederhana.
| 
| 3. Best Practices
|    - Autoload hanya komponen yang digunakan > 80% halaman
|    - Gunakan lazy loading untuk komponen spesifik
|    - Monitor performa dengan profiler CodeIgniter
| 
| 4. Rekomendasi Khusus untuk Aplikasi Absensi
|    - Database dan Session wajib di-autoload
|    - Form validation untuk keamanan input
|    - URL helper untuk navigasi konsisten
|    - Model dimuat per-kebutuhan di controller
*/

/* End of file autoload.php */
/* Location: ./application/config/autoload.php */