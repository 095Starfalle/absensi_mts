<?php
class Absensi_model extends CI_Model {

    public function getJumlahAdmin() {
        // Gantilah query ini sesuai dengan tabel dan struktur database Anda
        $this->db->from('users');
        $this->db->where('role', 'admin');
        return $this->db->count_all_results();
    }

    public function getJumlahSiswa() {
        // Gantilah query ini sesuai dengan tabel dan struktur database Anda
        $this->db->from('siswa');
        return $this->db->count_all_results();
    }

    public function getJumlahSiswaHadir() {
        // Gantilah query ini sesuai dengan tabel dan struktur database Anda
        $this->db->from('absensi');
        $this->db->where('status', 'hadir');
        return $this->db->count_all_results();
    }

    public function getJumlahWaliKelas() {
        // Ganti query sesuai struktur tabel Anda
        $this->db->from('users');
        $this->db->where('role', 'wali_kelas');
        return $this->db->count_all_results();
    }

    public function getJumlahSiswaTerlambat() {
        // Ganti query sesuai struktur tabel Anda
        $this->db->from('absensi');
        $this->db->where('status', 'terlambat');
        return $this->db->count_all_results();
    }

    public function getJumlahSiswaBelumAbsen() {
        // Ganti query sesuai struktur tabel Anda
        $this->db->from('siswa');
        // Asumsi siswa yang belum absen adalah siswa yang tidak ada di tabel absensi hari ini
        $today = date('Y-m-d');
        $this->db->where_not_in('id', function($db) use ($today) {
            $db->select('id_siswa')->from('absensi')->where('tanggal', $today);
        });
        return $this->db->count_all_results();
    }
}
