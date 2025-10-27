-- Buat database
CREATE DATABASE IF NOT EXISTS delondree_admin;
USE delondree_admin;

-- ====================================
-- TABEL LAYANAN
-- ====================================
CREATE TABLE IF NOT EXISTS `layanan` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nama_layanan` VARCHAR(100) NOT NULL,
    `deskripsi` TEXT NOT NULL,
    `harga_mulai` INT NOT NULL,
    `foto` VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ====================================
-- TABEL HARGA
-- ====================================
CREATE TABLE IF NOT EXISTS `harga` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `jenis_layanan` VARCHAR(100) NOT NULL,
    `kategori` VARCHAR(100) NOT NULL,
    `harga` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ====================================
-- TABEL GALERI
-- ====================================
CREATE TABLE IF NOT EXISTS `galeri` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `judul` VARCHAR(100) NOT NULL,
    `foto` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ====================================
-- TABEL PROFIL LAUNDRY
-- ====================================
CREATE TABLE IF NOT EXISTS `profil` (
    `id` INT PRIMARY KEY DEFAULT 1,
    `nama_laundry` VARCHAR(255) DEFAULT NULL,
    `alamat` TEXT,
    `whatsapp` VARCHAR(20) DEFAULT NULL,
    `email` VARCHAR(255) DEFAULT NULL,
    `jam_senin` VARCHAR(100) DEFAULT NULL,
    `jam_minggu` VARCHAR(100) DEFAULT NULL,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ====================================
-- TABEL ADMIN (untuk login & password)
-- ====================================
CREATE TABLE IF NOT EXISTS `admin` (
    `id` INT PRIMARY KEY DEFAULT 1,
    `username` VARCHAR(50) NOT NULL DEFAULT 'admin',
    `password` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ====================================
-- INSERT DATA DEFAULT
-- ====================================

-- Insert admin default
-- Username: admin
-- Password: admin123
-- MD5 Hash: 0192023a7bbd73250516f069df18b500
INSERT INTO `admin` (`id`, `username`, `password`) VALUES 
(1, 'admin', '0192023a7bbd73250516f069df18b500')
ON DUPLICATE KEY UPDATE username='admin';

-- Insert profil default
INSERT INTO `profil` (`id`, `nama_laundry`, `alamat`, `whatsapp`, `email`, `jam_senin`, `jam_minggu`) VALUES
(1, 'deLondree', 'Kota Kendari, Sulawesi Tenggara', '+62 8181 871 0655', 'info@delondree.com', '08.00 - 20.00', '09.00 - 18.00')
ON DUPLICATE KEY UPDATE id=1;

-- ====================================
-- DATA SAMPLE (OPTIONAL)
-- ====================================

-- Sample data untuk layanan
INSERT INTO `layanan` (`nama_layanan`, `deskripsi`, `harga_mulai`, `foto`) VALUES
('Cuci Kering Setrika', 'Layanan lengkap mencuci, mengeringkan, dan menyetrika pakaian Anda dengan rapi', 5000, NULL),
('Cuci Kering', 'Layanan mencuci dan mengeringkan pakaian tanpa setrika', 4000, NULL),
('Setrika Saja', 'Khusus layanan setrika untuk pakaian yang sudah bersih', 3000, NULL);

-- Sample data untuk harga
INSERT INTO `harga` (`jenis_layanan`, `kategori`, `harga`) VALUES
('Cuci Kering Setrika', 'Pakaian Reguler (per kg)', 5000),
('Cuci Kering Setrika', 'Bed Cover Single', 15000),
('Cuci Kering Setrika', 'Bed Cover Double', 25000),
('Cuci Kering', 'Pakaian Reguler (per kg)', 4000),
('Cuci Kering', 'Selimut', 20000),
('Setrika Saja', 'Per kg', 3000);


-- SELECT * FROM admin WHERE username='admin' AND password=MD5('admin123');