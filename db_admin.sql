
-- DATABASE: delondree_admin (FINAL VERSION)
CREATE DATABASE IF NOT EXISTS delondree_admin;
USE delondree_admin;

-- ==========================================================
-- TABLE: layanan
-- ==========================================================
CREATE TABLE IF NOT EXISTS layanan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_layanan VARCHAR(100) NOT NULL,
    deskripsi TEXT NOT NULL,
    harga_mulai INT NOT NULL,
    foto VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================================================
-- TABLE: harga
-- ==========================================================
CREATE TABLE IF NOT EXISTS harga (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_id INT,
    jenis_layanan VARCHAR(100) NOT NULL,
    kategori VARCHAR(100) NOT NULL,
    description TEXT,
    harga INT NOT NULL,
    unit VARCHAR(20) DEFAULT 'kg',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_service (service_id),
    INDEX idx_jenis (jenis_layanan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================================================
-- TABLE: galeri
-- ==========================================================
CREATE TABLE IF NOT EXISTS galeri (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(100) NOT NULL,
    foto VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================================================
-- TABLE: profil (UPDATED)
-- ==========================================================
CREATE TABLE IF NOT EXISTS profil (
    id INT PRIMARY KEY DEFAULT 1,
    nama_laundry VARCHAR(255) DEFAULT NULL,
    alamat TEXT,
    whatsapp VARCHAR(20) DEFAULT NULL,
    email VARCHAR(255) DEFAULT NULL,
    jam_senin VARCHAR(100) DEFAULT NULL,
    jam_minggu VARCHAR(100) DEFAULT NULL,

    -- ABOUT SECTION
    about_title VARCHAR(255) DEFAULT 'deLondree - Laundry & Dry Cleaning Specialist',
    about_paragraph1 TEXT NULL,
    about_paragraph2 TEXT NULL,

    -- HERO SECTION
    hero_title VARCHAR(255) DEFAULT 'Layanan Laundry Profesional',
    hero_subtitle TEXT NULL,

    -- FEATURES
    feature1_title VARCHAR(100) DEFAULT 'Peralatan Modern',
    feature1_desc TEXT NULL,
    feature1_icon VARCHAR(50) DEFAULT 'fa-tools',

    feature2_title VARCHAR(100) DEFAULT 'Ramah Lingkungan',
    feature2_desc TEXT NULL,
    feature2_icon VARCHAR(50) DEFAULT 'fa-leaf',

    feature3_title VARCHAR(100) DEFAULT 'Tim Profesional',
    feature3_desc TEXT NULL,
    feature3_icon VARCHAR(50) DEFAULT 'fa-user-tie',

    feature4_title VARCHAR(100) DEFAULT 'Layanan Cepat',
    feature4_desc TEXT NULL,
    feature4_icon VARCHAR(50) DEFAULT 'fa-truck-fast',

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================================================
-- TABLE: admin
-- ==========================================================
CREATE TABLE IF NOT EXISTS admin (
    id INT PRIMARY KEY DEFAULT 1,
    username VARCHAR(50) NOT NULL DEFAULT 'admin',
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================================================
-- TABLE: orders (UPDATED)
-- ==========================================================
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id VARCHAR(20) NOT NULL UNIQUE,
    customer_name VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    customer_address TEXT NOT NULL,
    address_notes TEXT,
    service_name VARCHAR(255) NOT NULL,
    quantity DECIMAL(10,2) NOT NULL DEFAULT 1.00,
    unit_price INT NOT NULL DEFAULT 0,
    total_amount INT NOT NULL DEFAULT 0,
    delivery_cost INT NOT NULL DEFAULT 0,
    payment_method ENUM('cash', 'transfer') DEFAULT 'cash',
    pickup_date DATE NOT NULL,
    pickup_time VARCHAR(50) NOT NULL,
    status ENUM('pending', 'process', 'completed', 'cancelled') DEFAULT 'pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_order_date (created_at),
    INDEX idx_status (status),
    INDEX idx_payment (payment_method)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================================================
-- TABLE: order_items
-- ==========================================================
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id VARCHAR(20) NOT NULL,
    service_name VARCHAR(255) NOT NULL,
    quantity DECIMAL(10,2) NOT NULL,
    unit_price INT NOT NULL,
    total_price INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_order (order_id),
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================================================
-- TABLE: delivery_rates
-- ==========================================================
CREATE TABLE IF NOT EXISTS delivery_rates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    range_min INT NOT NULL,
    range_max INT NOT NULL,
    rate INT NOT NULL,
    description VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_range (range_min, range_max)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================================================
-- INSERT DEFAULT DATA
-- ==========================================================

INSERT INTO admin (id, username, password) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500')
ON DUPLICATE KEY UPDATE username='admin';

INSERT INTO profil (id, nama_laundry, alamat, whatsapp, email, jam_senin, jam_minggu)
VALUES (1, 'deLondree', 'Kota Kendari, Sulawesi Tenggara', '+62 8181 871 0655', 'info@delondree.com', '08.00 - 20.00', '09.00 - 18.00')
ON DUPLICATE KEY UPDATE id=1;

INSERT INTO layanan (nama_layanan, deskripsi, harga_mulai, foto) VALUES
('Cuci Kering Setrika', 'Layanan lengkap mencuci, mengeringkan, dan menyetrika pakaian Anda dengan rapi', 5000, NULL),
('Cuci Kering', 'Layanan mencuci dan mengeringkan pakaian tanpa setrika', 4000, NULL),
('Setrika Saja', 'Khusus layanan setrika untuk pakaian yang sudah bersih', 3000, NULL)
ON DUPLICATE KEY UPDATE updated_at=CURRENT_TIMESTAMP;

INSERT INTO harga (service_id, jenis_layanan, kategori, description, harga, unit) VALUES
(1, 'Cuci Kering Setrika', 'Pakaian Reguler', 'Layanan lengkap cuci, kering, dan setrika', 5000, 'kg'),
(1, 'Cuci Kering Setrika', 'Bed Cover Single', 'Bed cover single', 15000, 'buah'),
(1, 'Cuci Kering Setrika', 'Bed Cover Double', 'Bed cover double', 25000, 'buah'),
(1, 'Cuci Kering Setrika', 'Selimut', 'Selimut', 20000, 'buah'),
(2, 'Cuci Kering', 'Pakaian Reguler', 'Pakaian sehari-hari', 4000, 'kg'),
(2, 'Cuci Kering', 'Jeans & Jaket', 'Jeans dan jaket', 6000, 'kg'),
(3, 'Setrika Saja', 'Pakaian Reguler', 'Setrika pakaian', 3000, 'kg'),
(3, 'Setrika Saja', 'Kemeja', 'Setrika kemeja', 5000, 'buah')
ON DUPLICATE KEY UPDATE updated_at=CURRENT_TIMESTAMP;

INSERT INTO delivery_rates (range_min, range_max, rate, description) VALUES
(0, 2, 0, 'Gratis Ongkir'),
(3, 6, 5000, 'Area Dalam Kota'),
(7, 10, 8000, 'Area Luar Kota')
ON DUPLICATE KEY UPDATE updated_at=CURRENT_TIMESTAMP;

INSERT INTO orders (order_id, customer_name, customer_phone, customer_address, service_name, quantity, unit_price, total_amount, payment_method, pickup_date, pickup_time, status)
VALUES
('ORD001','Budi Santoso','081234567890','Jl. Merdeka No. 123','Cuci Kering Setrika (3.5kg)',3.5,5000,17500,'cash','2024-01-15','10:00-12:00','completed'),
('ORD002','Siti Rahayu','081298765432','Jl. Sudirman No. 45','Setrika Saja (2kg)',2.0,3000,6000,'transfer','2024-01-15','14:00-16:00','completed'),
('ORD003','Ahmad Wijaya','081311223344','Jl. Gatot Subroto No. 67','Cuci Kering (4kg), Setrika Saja (1kg)',5.0,3500,17500,'cash','2024-01-16','16:00-18:00','process')
ON DUPLICATE KEY UPDATE updated_at=CURRENT_TIMESTAMP;

INSERT INTO order_items (order_id, service_name, quantity, unit_price, total_price) VALUES
('ORD001','Cuci Kering Setrika Pakaian Reguler',3.5,5000,17500),
('ORD002','Setrika Saja Pakaian Reguler',2.0,3000,6000),
('ORD003','Cuci Kering Pakaian Reguler',4.0,4000,16000),
('ORD003','Setrika Saja Pakaian Reguler',1.0,3000,3000)
ON DUPLICATE KEY UPDATE total_price=VALUES(total_price);

-- ==========================================================
-- VIEW: monthly_reports
-- ==========================================================
CREATE OR REPLACE VIEW monthly_reports AS
SELECT 
    YEAR(created_at) AS year,
    MONTH(created_at) AS month,
    COUNT(*) AS total_orders,
    SUM(total_amount) AS total_revenue,
    AVG(total_amount) AS average_order
FROM orders
WHERE status = 'completed'
GROUP BY YEAR(created_at), MONTH(created_at)
ORDER BY year DESC, month DESC;

-- ==========================================================
-- VIEW: service_performance
-- ==========================================================
CREATE OR REPLACE VIEW service_performance AS
SELECT 
    service_name,
    COUNT(*) AS order_count,
    SUM(total_amount) AS total_revenue,
    AVG(total_amount) AS average_revenue
FROM orders
WHERE status = 'completed'
GROUP BY service_name
ORDER BY total_revenue DESC;

-- ==========================================================
-- VIEW: financial_reports
-- ==========================================================
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
    SUM(oi.total_price) AS items_total
FROM orders o
LEFT JOIN order_items oi ON o.order_id = oi.order_id
GROUP BY o.order_id
ORDER BY o.created_at DESC;

-- ==========================================================
-- TABLE: profil (UPDATED)
-- ==========================================================
CREATE TABLE IF NOT EXISTS profil (
    id INT PRIMARY KEY DEFAULT 1,
    nama_laundry VARCHAR(255) DEFAULT NULL,
    alamat TEXT,
    whatsapp VARCHAR(20) DEFAULT NULL,
    email VARCHAR(255) DEFAULT NULL,
    jam_senin VARCHAR(100) DEFAULT NULL,
    jam_minggu VARCHAR(100) DEFAULT NULL,

    -- ABOUT SECTION
    about_title VARCHAR(255) DEFAULT 'deLondree - Laundry & Dry Cleaning Specialist',
    about_paragraph1 TEXT NULL,
    about_paragraph2 TEXT NULL,

    -- HERO SECTION
    hero_title VARCHAR(255) DEFAULT 'Layanan Laundry Profesional',
    hero_subtitle TEXT NULL,

    -- FEATURES
    feature1_title VARCHAR(100) DEFAULT 'Peralatan Modern',
    feature1_desc TEXT NULL,
    feature1_icon VARCHAR(50) DEFAULT 'fa-tools',

    feature2_title VARCHAR(100) DEFAULT 'Ramah Lingkungan',
    feature2_desc TEXT NULL,
    feature2_icon VARCHAR(50) DEFAULT 'fa-leaf',

    feature3_title VARCHAR(100) DEFAULT 'Tim Profesional',
    feature3_desc TEXT NULL,
    feature3_icon VARCHAR(50) DEFAULT 'fa-user-tie',

    feature4_title VARCHAR(100) DEFAULT 'Layanan Cepat',
    feature4_desc TEXT NULL,
    feature4_icon VARCHAR(50) DEFAULT 'fa-truck-fast',

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default data
INSERT INTO profil (id, nama_laundry, alamat, whatsapp, email, jam_senin, jam_minggu)
VALUES (1, 'deLondree', 'Kota Kendari, Sulawesi Tenggara', '+62 8181 871 0655', 'info@delondree.com', '08.00 - 20.00', '09.00 - 18.00')
ON DUPLICATE KEY UPDATE id=1;
SHOW TABLES LIKE 'profil';
DESCRIBE profil;
SELECT * FROM profil WHERE id = 1;

ALTER TABLE orders MODIFY service_name TEXT;
