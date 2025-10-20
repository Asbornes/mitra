CREATE DATABASE delondree_admin;
USE delondree_admin;

-- Tabel untuk layanan
CREATE TABLE layanan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_layanan VARCHAR(100) NOT NULL,
    deskripsi TEXT NOT NULL,
    harga_mulai INT NOT NULL,
    foto VARCHAR(255)
);

-- Tabel untuk harga
CREATE TABLE harga (
    id INT AUTO_INCREMENT PRIMARY KEY,
    jenis_layanan VARCHAR(100) NOT NULL,
    kategori VARCHAR(100) NOT NULL,
    harga INT NOT NULL
);

-- Tabel untuk galeri
CREATE TABLE galeri (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(100) NOT NULL,
    foto VARCHAR(255) NOT NULL
);

-- Tabel untuk profil laundry
CREATE TABLE profil (
    id INT PRIMARY KEY DEFAULT 1,
    nama_laundry VARCHAR(100),
    alamat TEXT,
    whatsapp VARCHAR(20),
    email VARCHAR(100),
    jam_senin VARCHAR(50),
    jam_minggu VARCHAR(50)
);

-- Tabel admin (untuk ganti password)
CREATE TABLE admin (
    id INT PRIMARY KEY DEFAULT 1,
    username VARCHAR(50) DEFAULT 'admin',
    password VARCHAR(255) NOT NULL
);

-- Tambahkan akun admin default (password: admin123)
INSERT INTO admin (password) VALUES (MD5('admin123'));
