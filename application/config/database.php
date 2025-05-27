<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Konfigurasi Database untuk Sistem Absensi MTs Al-Muttaqin
 * 
 * File: application/config/database.php
 * 
 * Panduan Konfigurasi:
 * 1. Pastikan service MySQL di XAMPP sudah berjalan
 * 2. Sesuaikan username dan password dengan pengaturan lokal
 * 3. Nama database harus sesuai dengan yang telah dibuat
 * 4. Gunakan driver mysqli untuk kompatibilitas PHP 7.4
 * 
 * @author  Tim Pengembang MTs Al-Muttaqin
 * @version 1.0
 * @since   2024
 */

/*
| -------------------------------------------------------------------
| PENJELASAN KONFIGURASI DATABASE
| -------------------------------------------------------------------
| Parameter konfigurasi ini mengatur koneksi antara aplikasi dan database MySQL.
| Setiap parameter memiliki fungsi spesifik yang harus dikonfigurasi dengan benar.
*/

$active_group = 'default';
$query_builder = TRUE;

/**
 * Konfigurasi Koneksi Database Utama
 * 
 * @var array $db['default'] Array konfigurasi database
 */
$db['default'] = array(
    // Data Source Name - Biarkan kosong untuk MySQL
    'dsn'      => '',
    
    // Server database (localhost untuk pengembangan lokal)
    'hostname' => 'localhost',
    
    // Username MySQL (default XAMPP: root)
    'username' => 'root',
    
    // Password MySQL (default XAMPP: kosong)
    'password' => '',
    
    // Nama database yang telah dibuat
    'database' => 'absensi_mts_almuttaqin',
    
    // Driver database - gunakan mysqli untuk PHP 7+
    'dbdriver' => 'mysqli',
    
    // Prefix tabel (opsional) - biarkan kosong
    'dbprefix' => '',
    
    // Koneksi persisten - FALSE untuk keamanan
    'pconnect' => FALSE,
    
    // Debug database - TRUE untuk development, FALSE untuk production
    'db_debug' => (ENVIRONMENT !== 'production'),
    
    // Cache query - FALSE untuk aplikasi dinamis
    'cache_on' => FALSE,
    
    // Direktori cache (tidak digunakan jika cache_on = FALSE)
    'cachedir' => '',
    
    // Character set - UTF8 untuk mendukung bahasa Indonesia
    'char_set' => 'utf8',
    
    // Database collation - utf8_general_ci untuk umum
    'dbcollat' => 'utf8_general_ci',
    
    // Swap prefix (untuk active record) - biarkan kosong
    'swap_pre' => '',
    
    // Enkripsi koneksi - FALSE untuk lokal
    'encrypt'  => FALSE,
    
    // Kompresi koneksi - FALSE untuk lokal
    'compress' => FALSE,
    
    // Mode strict SQL - FALSE untuk kompatibilitas
    'stricton' => FALSE,
    
    // Failover servers - array kosong untuk single server
    'failover' => array(),
    
    // Simpan query untuk profiling - TRUE saat development
    'save_queries' => TRUE
);

/**
 * Konfigurasi Database Testing (Opsional)
 * Duplikasi konfigurasi di atas dengan database berbeda untuk testing
 */
$db['testing'] = array(
    'dsn'      => '',
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'absensi_mts_testing', // Database khusus testing
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => FALSE,
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt'  => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => FALSE
);

/* End of file database.php */
/* Location: ./application/config/database.php */