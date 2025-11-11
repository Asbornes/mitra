-- db_admin.sql 
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
    `foto` VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ====================================
-- TABEL HARGA (DETAIL)
-- ====================================
CREATE TABLE IF NOT EXISTS `harga` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `service_id` INT,
    `jenis_layanan` VARCHAR(100) NOT NULL,
    `kategori` VARCHAR(100) NOT NULL,
    `description` TEXT,
    `harga` INT NOT NULL,
    `unit` VARCHAR(20) DEFAULT 'kg',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_service` (`service_id`),
    INDEX `idx_jenis` (`jenis_layanan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ====================================
-- TABEL GALERI
-- ====================================
CREATE TABLE IF NOT EXISTS `galeri` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `judul` VARCHAR(100) NOT NULL,
    `foto` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
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
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ====================================
-- TABEL PESANAN (untuk laporan keuangan)
-- ====================================
CREATE TABLE IF NOT EXISTS `orders` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_id` VARCHAR(20) NOT NULL UNIQUE,
    `customer_name` VARCHAR(100) NOT NULL,
    `customer_phone` VARCHAR(20) NOT NULL,
    `customer_address` TEXT NOT NULL,
    `address_notes` TEXT,
    `service_name` VARCHAR(255) NOT NULL,
    `quantity` DECIMAL(8,2) NOT NULL,
    `unit_price` INT NOT NULL,
    `total_amount` INT NOT NULL,
    `delivery_cost` INT DEFAULT 0,
    `payment_method` ENUM('cash', 'transfer', 'ewallet') DEFAULT 'cash',
    `pickup_date` DATE NOT NULL,
    `pickup_time` VARCHAR(50) NOT NULL,
    `status` ENUM('pending', 'process', 'completed', 'cancelled') DEFAULT 'pending',
    `notes` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_order_date` (`created_at`),
    INDEX `idx_status` (`status`),
    INDEX `idx_payment` (`payment_method`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ====================================
-- TABEL ORDER ITEMS (Detail pesanan)
-- ====================================
CREATE TABLE IF NOT EXISTS `order_items` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_id` VARCHAR(20) NOT NULL,
    `service_name` VARCHAR(100) NOT NULL,
    `quantity` DECIMAL(8,2) NOT NULL,
    `unit_price` INT NOT NULL,
    `total_price` INT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_order` (`order_id`),
    FOREIGN KEY (`order_id`) REFERENCES `orders`(`order_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ====================================
-- TABEL DELIVERY RATES
-- ====================================
CREATE TABLE IF NOT EXISTS `delivery_rates` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `range_min` INT NOT NULL,
    `range_max` INT NOT NULL,
    `rate` INT NOT NULL,
    `description` VARCHAR(100),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_range` (`range_min`, `range_max`)
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
ON DUPLICATE KEY UPDATE username='admin', updated_at=CURRENT_TIMESTAMP;

-- Insert profil default
INSERT INTO `profil` (`id`, `nama_laundry`, `alamat`, `whatsapp`, `email`, `jam_senin`, `jam_minggu`) VALUES
(1, 'deLondree', 'Kota Kendari, Sulawesi Tenggara', '+62 8181 871 0655', 'info@delondree.com', '08.00 - 20.00', '09.00 - 18.00')
ON DUPLICATE KEY UPDATE id=1;

-- Insert sample layanan
INSERT INTO `layanan` (`nama_layanan`, `deskripsi`, `harga_mulai`, `foto`) VALUES
('Cuci Kering Setrika', 'Layanan lengkap mencuci, mengeringkan, dan menyetrika pakaian Anda dengan rapi', 5000, NULL),
('Cuci Kering', 'Layanan mencuci dan mengeringkan pakaian tanpa setrika', 4000, NULL),
('Setrika Saja', 'Khusus layanan setrika untuk pakaian yang sudah bersih', 3000, NULL)
ON DUPLICATE KEY UPDATE updated_at=CURRENT_TIMESTAMP;

-- Insert sample harga detail
INSERT INTO `harga` (`service_id`, `jenis_layanan`, `kategori`, `description`, `harga`, `unit`) VALUES
(1, 'Cuci Kering Setrika', 'Pakaian Reguler', 'Layanan lengkap cuci, kering, dan setrika untuk pakaian sehari-hari', 5000, 'kg'),
(1, 'Cuci Kering Setrika', 'Bed Cover Single', 'Cuci kering setrika untuk bed cover ukuran single', 15000, 'buah'),
(1, 'Cuci Kering Setrika', 'Bed Cover Double', 'Cuci kering setrika untuk bed cover ukuran double', 25000, 'buah'),
(1, 'Cuci Kering Setrika', 'Selimut', 'Cuci kering setrika untuk selimut', 20000, 'buah'),
(2, 'Cuci Kering', 'Pakaian Reguler', 'Cuci dan kering tanpa setrika untuk pakaian sehari-hari', 4000, 'kg'),
(2, 'Cuci Kering', 'Jeans & Jaket', 'Cuci kering khusus untuk jeans dan jaket', 6000, 'kg'),
(3, 'Setrika Saja', 'Pakaian Reguler', 'Layanan setrika saja untuk pakaian yang sudah bersih', 3000, 'kg'),
(3, 'Setrika Saja', 'Kemeja', 'Setrika khusus untuk kemeja dengan hasil rapi', 5000, 'buah')
ON DUPLICATE KEY UPDATE updated_at=CURRENT_TIMESTAMP;

-- Insert delivery rates
INSERT INTO `delivery_rates` (`range_min`, `range_max`, `rate`, `description`) VALUES
(0, 2, 0, 'Gratis Ongkir'),
(3, 6, 5000, 'Area Dalam Kota'),
(7, 10, 8000, 'Area Luar Kota')
ON DUPLICATE KEY UPDATE updated_at=CURRENT_TIMESTAMP;

-- Insert sample orders
INSERT INTO `orders` (`order_id`, `customer_name`, `customer_phone`, `customer_address`, `service_name`, `quantity`, `unit_price`, `total_amount`, `payment_method`, `pickup_date`, `pickup_time`, `status`) VALUES
('ORD001', 'Budi Santoso', '081234567890', 'Jl. Merdeka No. 123, Kendari', 'Cuci Kering Setrika (3.5kg)', 3.5, 5000, 17500, 'cash', '2024-01-15', '10:00-12:00', 'completed'),
('ORD002', 'Siti Rahayu', '081298765432', 'Jl. Sudirman No. 45, Kendari', 'Setrika Saja (2kg)', 2.0, 3000, 6000, 'transfer', '2024-01-15', '14:00-16:00', 'completed'),
('ORD003', 'Ahmad Wijaya', '081311223344', 'Jl. Gatot Subroto No. 67, Kendari', 'Cuci Kering (4kg), Setrika Saja (1kg)', 5.0, 3500, 17500, 'cash', '2024-01-16', '16:00-18:00', 'process')
ON DUPLICATE KEY UPDATE updated_at=CURRENT_TIMESTAMP;

-- Insert sample order items
INSERT INTO `order_items` (`order_id`, `service_name`, `quantity`, `unit_price`, `total_price`) VALUES
('ORD001', 'Cuci Kering Setrika Pakaian Reguler', 3.5, 5000, 17500),
('ORD002', 'Setrika Saja Pakaian Reguler', 2.0, 3000, 6000),
('ORD003', 'Cuci Kering Pakaian Reguler', 4.0, 4000, 16000),
('ORD003', 'Setrika Saja Pakaian Reguler', 1.0, 3000, 3000)
ON DUPLICATE KEY UPDATE updated_at=CURRENT_TIMESTAMP;

-- Buat view untuk laporan bulanan
CREATE OR REPLACE VIEW monthly_reports AS
SELECT 
    YEAR(created_at) as year,
    MONTH(created_at) as month,
    COUNT(*) as total_orders,
    SUM(total_amount) as total_revenue,
    AVG(total_amount) as average_order
FROM orders 
WHERE status = 'completed'
GROUP BY YEAR(created_at), MONTH(created_at)
ORDER BY year DESC, month DESC;

-- Buat view untuk performa layanan
CREATE OR REPLACE VIEW service_performance AS
SELECT 
    service_name,
    COUNT(*) as order_count,
    SUM(total_amount) as total_revenue,
    AVG(total_amount) as average_revenue
FROM orders 
WHERE status = 'completed'
GROUP BY service_name
ORDER BY total_revenue DESC;

-- Buat view untuk detail laporan keuangan
CREATE OR REPLACE VIEW financial_reports AS
SELECT 
    o.order_id,
    o.customer_name,
    o.service_name,
    o.quantity,
    o.unit_price,
    o.total_amount,
    o.delivery_cost,
    o.payment_method,
    o.status,
    o.created_at,
    SUM(oi.total_price) as items_total
FROM orders o
LEFT JOIN order_items oi ON o.order_id = oi.order_id
GROUP BY o.order_id
ORDER BY o.created_at DESC;

ALTER TABLE orders 
MODIFY COLUMN quantity DECIMAL(10,2) NOT NULL DEFAULT 1.00,
MODIFY COLUMN unit_price INT NOT NULL DEFAULT 0,
MODIFY COLUMN total_amount INT NOT NULL DEFAULT 0,
MODIFY COLUMN delivery_cost INT NOT NULL DEFAULT 0;

ALTER TABLE orders 
MODIFY COLUMN order_id VARCHAR(20) NOT NULL UNIQUE,
MODIFY COLUMN customer_name VARCHAR(100) NOT NULL,
MODIFY COLUMN customer_phone VARCHAR(20) NOT NULL,
MODIFY COLUMN customer_address TEXT NOT NULL,
MODIFY COLUMN service_name VARCHAR(255) NOT NULL,
MODIFY COLUMN quantity DECIMAL(10,2) NOT NULL DEFAULT 1.00,
MODIFY COLUMN unit_price INT NOT NULL DEFAULT 0,
MODIFY COLUMN total_amount INT NOT NULL DEFAULT 0,
MODIFY COLUMN delivery_cost INT NOT NULL DEFAULT 0,
MODIFY COLUMN payment_method ENUM('cash', 'transfer', 'ewallet') DEFAULT 'cash',
MODIFY COLUMN pickup_date DATE NOT NULL,
MODIFY COLUMN pickup_time VARCHAR(50) NOT NULL,
MODIFY COLUMN status ENUM('pending', 'process', 'completed', 'cancelled') DEFAULT 'pending';

CREATE TABLE IF NOT EXISTS `order_items` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_id` VARCHAR(20) NOT NULL,
    `service_name` VARCHAR(255) NOT NULL,
    `quantity` DECIMAL(10,2) NOT NULL,
    `unit_price` INT NOT NULL,
    `total_price` INT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_order` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `orders` (
    order_id, customer_name, customer_phone, customer_address, service_name,
    quantity, unit_price, total_amount, payment_method, pickup_date, pickup_time, status
) VALUES 
('ORD20241201001', 'Budi Santoso', '081234567890', 'Jl. Merdeka No. 123', 'Cuci Kering Setrika', 3.5, 5000, 17500, 'cash', '2024-12-02', '10:00-12:00', 'completed'),
('ORD20241201002', 'Siti Rahayu', '081298765432', 'Jl. Sudirman No. 45', 'Setrika Saja', 2.0, 3000, 6000, 'transfer', '2024-12-02', '14:00-16:00', 'completed'),
('ORD20241201003', 'Ahmad Wijaya', '081311223344', 'Jl. Gatot Subroto No. 67', 'Cuci Kering', 4.0, 4000, 16000, 'cash', '2024-12-03', '16:00-18:00', 'process');

INSERT IGNORE INTO `order_items` (order_id, service_name, quantity, unit_price, total_price) VALUES
('ORD20241201001', 'Cuci Kering Setrika Pakaian Reguler', 3.5, 5000, 17500),
('ORD20241201002', 'Setrika Saja Pakaian Reguler', 2.0, 3000, 6000),
('ORD20241201003', 'Cuci Kering Pakaian Reguler', 4.0, 4000, 16000);

ALTER TABLE orders 
MODIFY COLUMN payment_method ENUM('cash', 'transfer') DEFAULT 'cash';

SHOW COLUMNS FROM orders LIKE 'payment_method';