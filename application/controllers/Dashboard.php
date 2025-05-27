<?php
class Dashboard extends CI_Controller {
    public function index() {
        // Memuat model absensi
        $this->load->model('Absensi_model');
        
        // Ambil data statistik
        $data['jumlah_admin'] = $this->Absensi_model->getJumlahAdmin();
        $data['jumlah_wali_kelas'] = $this->Absensi_model->getJumlahWaliKelas();
        $data['jumlah_siswa'] = $this->Absensi_model->getJumlahSiswa();
        $data['jumlah_siswa_hadir'] = $this->Absensi_model->getJumlahSiswaHadir();
        $data['jumlah_siswa_terlambat'] = $this->Absensi_model->getJumlahSiswaTerlambat();
        $data['jumlah_siswa_belum_absen'] = $this->Absensi_model->getJumlahSiswaBelumAbsen();

        // Data dummy untuk grafik (bisa diganti dengan data dari database)
        $data['grafik_tanggal'] = ['17/05', '18/05', '19/05', '20/05', '21/05', '22/05', '23/05', '24/05', '25/05', '26/05', 'Hari Ini'];
        $data['grafik_hadir'] = [0,0,0,0,0,0,0,0,0,0,0];

        // Memuat tampilan dashboard dengan data
        $this->load->view('dashboard', $data);
    }
}
