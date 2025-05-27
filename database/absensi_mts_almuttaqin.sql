-- Membuat database baru
CREATE DATABASE IF NOT EXISTS absensi_mts_almuttaqin
CHARACTER SET utf8 COLLATE utf8_general_ci;

-- Gunakan database
USE absensi_mts_almuttaqin;

-- Tabel 1: users (untuk login admin dan guru)
CREATE TABLE users (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    role ENUM('admin', 'guru') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabel 2: guru
CREATE TABLE guru (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    nip VARCHAR(20) UNIQUE,
    nama_lengkap VARCHAR(100) NOT NULL,
    jenis_kelamin ENUM('L', 'P') NOT NULL,
    tempat_lahir VARCHAR(50),
    tanggal_lahir DATE,
    alamat TEXT,
    no_hp VARCHAR(15),
    email VARCHAR(100),
    user_id INT(11),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabel 3: kelas
CREATE TABLE kelas (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    nama_kelas VARCHAR(20) NOT NULL,
    tingkat ENUM('7', '8', '9') NOT NULL,
    wali_kelas_id INT(11),
    tahun_ajaran VARCHAR(9) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (wali_kelas_id) REFERENCES guru(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabel 4: siswa
CREATE TABLE siswa (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    nis VARCHAR(20) UNIQUE NOT NULL,
    nisn VARCHAR(20) UNIQUE,
    nama_lengkap VARCHAR(100) NOT NULL,
    jenis_kelamin ENUM('L', 'P') NOT NULL,
    tempat_lahir VARCHAR(50),
    tanggal_lahir DATE,
    alamat TEXT,
    nama_ayah VARCHAR(100),
    nama_ibu VARCHAR(100),
    no_hp_ortu VARCHAR(15),
    kelas_id INT(11),
    foto VARCHAR(255),
    status ENUM('aktif', 'tidak_aktif') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (kelas_id) REFERENCES kelas(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabel 5: mata_pelajaran
CREATE TABLE mata_pelajaran (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    kode_mapel VARCHAR(10) UNIQUE NOT NULL,
    nama_mapel VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabel 6: jadwal_pelajaran
CREATE TABLE jadwal_pelajaran (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    kelas_id INT(11) NOT NULL,
    mapel_id INT(11) NOT NULL,
    guru_id INT(11) NOT NULL,
    hari ENUM('Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu') NOT NULL,
    jam_mulai TIME NOT NULL,
    jam_selesai TIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (kelas_id) REFERENCES kelas(id) ON DELETE CASCADE,
    FOREIGN KEY (mapel_id) REFERENCES mata_pelajaran(id) ON DELETE CASCADE,
    FOREIGN KEY (guru_id) REFERENCES guru(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabel 7: absensi
CREATE TABLE absensi (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    siswa_id INT(11) NOT NULL,
    jadwal_id INT(11) NOT NULL,
    tanggal DATE NOT NULL,
    status ENUM('H', 'S', 'I', 'A') NOT NULL COMMENT 'H=Hadir, S=Sakit, I=Izin, A=Alpa',
    keterangan TEXT,
    created_by INT(11),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE,
    FOREIGN KEY (jadwal_id) REFERENCES jadwal_pelajaran(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    UNIQUE KEY unique_absensi (siswa_id, jadwal_id, tanggal)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabel 8: pengaturan
CREATE TABLE pengaturan (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    nama_sekolah VARCHAR(100) NOT NULL DEFAULT 'MTs Al-Muttaqin',
    alamat_sekolah TEXT,
    no_telp VARCHAR(15),
    email VARCHAR(100),
    kepala_sekolah VARCHAR(100),
    logo VARCHAR(255),
    tahun_ajaran_aktif VARCHAR(9) NOT NULL DEFAULT '2024/2025',
    semester_aktif ENUM('1', '2') NOT NULL DEFAULT '1',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Insert data awal untuk admin
INSERT INTO users (username, password, nama_lengkap, role) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin');
-- Password default: password

-- Insert data pengaturan awal
INSERT INTO pengaturan (nama_sekolah, alamat_sekolah, tahun_ajaran_aktif, semester_aktif) VALUES
('MTs Al-Muttaqin', 'Jl. Pendidikan No. 1, Jember, Jawa Timur', '2024/2025', '1');

-- Insert data mata pelajaran dasar
INSERT INTO mata_pelajaran (kode_mapel, nama_mapel) VALUES
('PAI', 'Pendidikan Agama Islam'),
('PPKN', 'Pendidikan Pancasila dan Kewarganegaraan'),
('BIND', 'Bahasa Indonesia'),
('BING', 'Bahasa Inggris'),
('MTK', 'Matematika'),
('IPA', 'Ilmu Pengetahuan Alam'),
('IPS', 'Ilmu Pengetahuan Sosial'),
('PJOK', 'Pendidikan Jasmani, Olahraga dan Kesehatan'),
('SBD', 'Seni Budaya'),
('ARAB', 'Bahasa Arab'),
('QH', 'Al-Quran Hadits'),
('AA', 'Aqidah Akhlak'),
('FQ', 'Fiqih'),
('SKI', 'Sejarah Kebudayaan Islam');