<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>deLondree - Layanan Laundry Profesional</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
</head>
<body>
    <div class="laundry-website">
        <!-- Header -->
        <header class="header">
            <div class="header-container">
                <div class="logo-section">
                    <img src="hero.jpg" alt="deLondree Logo" class="logo">
                    <span class="logo-text">deLondree</span>
                </div>
                
                <nav class="desktop-nav">
                    <a href="#home" class="nav-link">Beranda</a>
                    <a href="#about" class="nav-link">Tentang Kami</a>
                    <a href="#services" class="nav-link">Layanan</a>
                    <a href="#process" class="nav-link">Cara Kerja</a>
                    <a href="#gallery" class="nav-link">Galeri</a>
                    <a href="#contact" class="nav-link">Kontak</a>
                    <a href="booking.php" class="nav-link booking-cta">
                        <i class="fas fa-shopping-cart"></i> 
                        <span>Laundry Sekarang</span>
                    </a>
                </nav>

                <div class="header-actions">
                    <a href="booking.php" class="booking-btn-mobile">
                        <i class="fas fa-broom"></i>
                    </a>
                    <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div class="mobile-menu" id="mobileMenu">
                <a href="#home" class="mobile-nav-link" onclick="closeMobileMenu()">
                    <i class="fas fa-home"></i> Beranda
                </a>
                <a href="#about" class="mobile-nav-link" onclick="closeMobileMenu()">
                    <i class="fas fa-info-circle"></i> Tentang Kami
                </a>
                <a href="#services" class="mobile-nav-link" onclick="closeMobileMenu()">
                    <i class="fas fa-concierge-bell"></i> Layanan
                </a>
                <a href="#process" class="mobile-nav-link" onclick="closeMobileMenu()">
                    <i class="fas fa-play-circle"></i> Cara Kerja
                </a>
                <a href="#gallery" class="mobile-nav-link" onclick="closeMobileMenu()">
                    <i class="fas fa-images"></i> Galeri
                </a>
                <a href="#contact" class="mobile-nav-link" onclick="closeMobileMenu()">
                    <i class="fas fa-phone"></i> Kontak
                </a>
                <a href="booking.php" class="mobile-nav-link booking-link" onclick="closeMobileMenu()">
                    <i class="fas fa-shopping-cart"></i> Laundry Sekarang
                </a>
            </div>
        </header>

        <!-- Hero Section -->
        <section id="home" class="hero-section">
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <h1 class="hero-title">Layanan Laundry Profesional</h1>
                <p class="hero-subtitle">Bersih, Rapi, dan Wangi - Kepercayaan Anda adalah Prioritas Kami</p>
                <div class="hero-actions">
                    <a href="booking.php" class="cta-button primary">
                        <i class="fas fa-shopping-cart"></i> Pesan Sekarang
                    </a>
                    <a href="#services" class="cta-button secondary">
                        <i class="fas fa-concierge-bell"></i> Lihat Layanan
                    </a>
                </div>
                <div class="hero-features">
                    <div class="feature">
                        <i class="fas fa-shield-alt"></i>
                        <span>Kualitas Terjamin</span>
                    </div>
                    <div class="feature">
                        <i class="fas fa-bolt"></i>
                        <span>Layanan Cepat</span>
                    </div>
                    <div class="feature">
                        <i class="fas fa-truck"></i>
                        <span>Antar Jemput Gratis</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section id="about" class="about-section">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Tentang <span class="highlight">deLondree</span></h2>
                    <p class="section-subtitle">Solusi laundry profesional untuk kebutuhan harian Anda</p>
                </div>
                
                <div class="about-content-wrapper">
                    <div class="about-main-content">
                        <div class="about-image">
                            <div class="image-frame">
                                <img src="heroine.png" alt="Tentang deLondree Laundry" class="about-img">
                            </div>
                        </div>
                        
                        <div class="about-text">
                            <h3>deLondree - Laundry & Dry Cleaning Specialist</h3>
                            <p class="about-paragraph">
                                deLondree hadir sebagai penyedia jasa laundry profesional yang mengutamakan kualitas, 
                                kecepatan, dan kepuasan pelanggan. Dengan pengalaman bertahun-tahun dalam industri laundry, 
                                kami memahami betul kebutuhan akan pakaian bersih, rapi, dan wangi.
                            </p>
                            <p class="about-paragraph">
                                Kami menggunakan peralatan modern dan detergen berkualitas tinggi yang ramah lingkungan 
                                untuk memastikan pakaian Anda mendapatkan perawatan terbaik. Setiap pakaian ditangani 
                                dengan penuh perhatian oleh tim profesional yang terlatih.
                            </p>
                        </div>
                    </div>
                    
                    <!-- Fitur Horizontal FULL WIDTH -->
                    <div class="features-full-width">
                        <div class="feature-full-item">
                            <div class="feature-full-icon">
                                <i class="fas fa-tools"></i>
                            </div>
                            <h4>Peralatan Modern</h4>
                            <p>Menggunakan mesin laundry terbaru dan teknologi canggih</p>
                        </div>
                        
                        <div class="feature-full-item">
                            <div class="feature-full-icon">
                                <i class="fas fa-leaf"></i>
                            </div>
                            <h4>Ramah Lingkungan</h4>
                            <p>Detergen biodegradable yang aman untuk kulit dan lingkungan</p>
                        </div>
                        
                        <div class="feature-full-item">
                            <div class="feature-full-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <h4>Tim Profesional</h4>
                            <p>Staff berpengalaman dan terlatih dalam menangani berbagai jenis pakaian</p>
                        </div>
                        
                        <div class="feature-full-item">
                            <div class="feature-full-icon">
                                <i class="fas fa-truck-fast"></i>
                            </div>
                            <h4>Layanan Cepat</h4>
                            <p>Proses cepat dengan hasil maksimal dan pengantaran tepat waktu</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section id="services" class="services-section">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Layanan <span class="highlight">Kami</span></h2>
                    <p class="section-subtitle">Pilih layanan yang sesuai dengan kebutuhan Anda</p>
                </div>
                <div class="services-grid" id="servicesGrid">
                    <?php
                    $result = $conn->query("SELECT * FROM layanan");
                    while ($row = $result->fetch_assoc()) {
                        $fotoPath = !empty($row['foto']) ? 'uploads/' . htmlspecialchars($row['foto']) : 'assets/placeholder.jpg';
                        $namaLayanan = htmlspecialchars($row['nama_layanan']);
                        $deskripsi = htmlspecialchars($row['deskripsi']);
                        $harga = number_format($row['harga_mulai'], 0, ',', '.');
                        
                        echo "
                        <div class='service-card' onclick='openServiceDetail({$row['id']})'>
                            <div class='service-image-container'>
                                <img src='{$fotoPath}' 
                                    alt='{$namaLayanan}' 
                                    class='service-image'
                                    loading='lazy'>
                                <div class='service-overlay'>
                                    <div class='service-action'>
                                        <i class='fas fa-eye'></i>
                                        <span>Lihat Detail</span>
                                    </div>
                                </div>
                            </div>
                            <div class='service-content'>
                                <h3 class='service-title'>{$namaLayanan}</h3>
                                <p class='service-description'>{$deskripsi}</p>
                                <div class='service-footer'>
                                    <div class='service-price-wrapper'>
                                        <span class='price-label'>Mulai dari</span>
                                        <span class='service-price-align'>Rp {$harga}</span>
                                    </div>
                                    <button class='service-btn' onclick='event.stopPropagation(); openServiceDetail({$row['id']})'>
                                        <i class='fas fa-arrow-right'></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        ";
                    }
                    ?>
                </div>
                
                <?php if ($result->num_rows === 0): ?>
                <div class="empty-state">
                    <i class="fas fa-concierge-bell"></i>
                    <h3>Belum Ada Layanan Tersedia</h3>
                    <p>Admin dapat menambahkan layanan melalui panel admin</p>
                </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Service Detail Modal -->
        <div id="serviceDetailModal" class="modal">
            <div class="modal-content large">
                <span class="modal-close" onclick="closeServiceDetail()">
                    <i class="fas fa-times"></i>
                </span>
                <div id="serviceDetailContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>

        <!-- How It Works Section -->
        <section id="process" class="process-section">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Cara <span class="highlight">Memesan</span></h2>
                    <p class="section-subtitle">Hanya 4 langkah mudah untuk mendapatkan pakaian bersih dan rapi</p>
                </div>
                
                <!-- Horizontal Process Flow -->
                <div class="process-flow-horizontal">
                    <div class="flow-step">
                        <div class="flow-step-number">1</div>
                        <div class="flow-step-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <div class="flow-step-content">
                            <h3>Pesan Online</h3>
                            <p>Isi form pemesanan online dengan detail alamat dan jadwal penjemputan</p>
                        </div>
                        <div class="flow-connector">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>
                    
                    <div class="flow-step">
                        <div class="flow-step-number">2</div>
                        <div class="flow-step-icon">
                            <i class="fas fa-truck-pickup"></i>
                        </div>
                        <div class="flow-step-content">
                            <h3>Penjemputan</h3>
                            <p>Kurir kami akan menjemput pakaian di alamat yang telah ditentukan</p>
                        </div>
                        <div class="flow-connector">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>
                    
                    <div class="flow-step">
                        <div class="flow-step-number">3</div>
                        <div class="flow-step-icon">
                            <i class="fas fa-soap"></i>
                        </div>
                        <div class="flow-step-content">
                            <h3>Proses Laundry</h3>
                            <p>Pakaian diproses dengan standar kebersihan tinggi menggunakan peralatan modern</p>
                        </div>
                        <div class="flow-connector">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>
                    
                    <div class="flow-step">
                        <div class="flow-step-number">4</div>
                        <div class="flow-step-icon">
                            <i class="fas fa-truck"></i>
                        </div>
                        <div class="flow-step-content">
                            <h3>Pengantaran</h3>
                            <p>Pakaian bersih dan rapi diantar kembali dengan pembayaran fleksibel</p>
                        </div>
                    </div>
                </div>
                
                <!-- Features Grid -->
                <div class="process-features-grid">
                    <div class="process-feature">
                        <i class="fas fa-check-circle"></i>
                        <h4>Isi Form Mudah</h4>
                        <p>Form sederhana yang dapat diisi dalam 2 menit</p>
                    </div>
                    <div class="process-feature">
                        <i class="fas fa-check-circle"></i>
                        <h4>Antar Jemput Gratis</h4>
                        <p>Area sekitar Kendari dengan layanan gratis</p>
                    </div>
                    <div class="process-feature">
                        <i class="fas fa-check-circle"></i>
                        <h4>Proses Hygienis</h4>
                        <p>Detergen premium dan quality control ketat</p>
                    </div>
                    <div class="process-feature">
                        <i class="fas fa-check-circle"></i>
                        <h4>Pembayaran Fleksibel</h4>
                        <p>COD atau transfer bank sesuai kemudahan Anda</p>
                    </div>
                </div>
                
                <div class="process-cta">
                    <div class="cta-content">
                        <h3>Siap Mencoba Layanan Kami?</h3>
                        <p>Bergabung dengan ratusan pelanggan yang telah mempercayakan pakaian mereka pada deLondree</p>
                        <div class="cta-actions">
                            <a href="booking.php" class="cta-button primary large">
                                <i class="fas fa-shopping-cart"></i> Pesan Laundry Sekarang
                            </a>
                            <a href="https://wa.me/6281818710655" class="cta-button secondary" target="_blank">
                                <i class="fab fa-whatsapp"></i> Tanya via WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Gallery Section -->
        <section id="gallery" class="gallery-section">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Galeri <span class="highlight">Kami</span></h2>
                    <p class="section-subtitle">Lihat hasil kerja profesional dan fasilitas modern kami</p>
                </div>
                <div class="gallery-grid" id="galleryGrid">
                    <?php
                    $result = $conn->query("SELECT * FROM galeri ORDER BY created_at DESC LIMIT 8");
                    $gallery_items = [];
                    while ($row = $result->fetch_assoc()) {
                        $gallery_items[] = $row;
                        echo "
                        <div class='gallery-item' onclick='openGalleryModal(\"{$row['foto']}\", \"{$row['judul']}\")'>
                            <div class='gallery-image-container'>
                                <img src='uploads/{$row['foto']}' alt='{$row['judul']}' class='gallery-image'>
                                <div class='gallery-overlay'>
                                    <div class='gallery-content'>
                                        <i class='fas fa-search-plus'></i>
                                        <p class='gallery-title'>{$row['judul']}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        ";
                    }
                    ?>
                </div>
                
                <?php if ($result->num_rows === 0): ?>
                <div class="empty-state">
                    <i class="fas fa-images"></i>
                    <h3>Belum Ada Foto di Galeri</h3>
                    <p>Admin dapat menambahkan foto melalui panel admin</p>
                </div>
                <?php endif; ?>
                
                <?php if ($result->num_rows > 0): ?>
                <div class="gallery-cta">
                    <a href="gallery.php" class="btn-secondary">
                        <i class="fas fa-images"></i> Lihat Semua Foto
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Gallery Modal -->
        <div id="galleryModal" class="modal gallery-modal">
            <span class="gallery-modal-close" onclick="closeGalleryModal()">
                <i class="fas fa-times"></i>
            </span>
            <img class="gallery-modal-content" id="modalImage">
            <div id="caption" class="gallery-modal-caption"></div>
            <button class="gallery-nav-btn gallery-prev" onclick="navigateGallery(-1)">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="gallery-nav-btn gallery-next" onclick="navigateGallery(1)">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        <!-- Contact Section -->
        <section id="contact" class="contact-section">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Hubungi <span class="highlight">Kami</span></h2>
                    <p class="section-subtitle">Butuh bantuan? Jangan ragu untuk menghubungi tim kami</p>
                </div>
                <div class="contact-content">
                    <div class="contact-info-grid">
                        <div class="contact-card-compact">
                            <div class="contact-card-header">
                                <div class="contact-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>
                                    <h4>Alamat Laundry</h4>
                                    <p class="contact-text-compact"><?= $data['alamat'] ?? 'Jl. Mekar No.54, Kec.Kadia, Kota Kendari, Sulawesi Tenggara' ?></p>
                                </div>
                            </div>
                            <small>Kami melayani area sekitar Kendari dengan layanan antar jemput</small>
                        </div>
                        
                        <div class="contact-card-compact">
                            <div class="contact-card-header">
                                <div class="contact-icon">
                                    <i class="fab fa-whatsapp"></i>
                                </div>
                                <div>
                                    <h4>WhatsApp</h4>
                                    <p class="contact-text-compact"><?= $data['whatsapp'] ?? '+62 8181 871 0655' ?></p>
                                </div>
                            </div>
                            <small>Hubungi kami via WhatsApp untuk respon cepat</small>
                        </div>
                        
                        <div class="contact-card-compact">
                            <div class="contact-card-header">
                                <div class="contact-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div>
                                    <h4>Jam Operasional</h4>
                                    <div class="schedule-compact">
                                        <div class="schedule-item-compact">
                                            <strong>Senin - Sabtu  : </strong>
                                            <span class="contact-text-compact"><?= $data['jam_senin'] ?? '08.00 - 20.00 WITA' ?></span>
                                        </div>
                                        <div class="schedule-item-compact">
                                            <strong>Minggu:</strong>
                                            <span class="contact-text-compact"><?= $data['jam_minggu'] ?? '09.00 - 18.00 WITA' ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <small>Penjemputan terakhir 2 jam sebelum tutup</small>
                        </div>
                        
                        <div class="contact-actions-compact">
                            <a href="https://wa.me/6281818710655" class="whatsapp-btn-compact" target="_blank">
                                <i class="fab fa-whatsapp"></i>
                                <span>Chat via WhatsApp</span>
                            </a>
                        </div>
                    </div>
                    
                    <div class="contact-map-balanced">
                        <div class="map-container-balanced">
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d730.7368494865218!2d122.5033663!3d-3.9825938!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2d98f36fd6209aa5%3A0x6acec4dd33a3cf51!2sdeLondree!5e1!3m2!1sid!2sid!4v1762674053340!5m2!1sid!2sid" 
                                width="100%" 
                                height="100%" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="footer">
            <div class="container">
                <div class="footer-content">
                    <div class="footer-section">
                        <div class="footer-logo">
                            <img src="hero.jpg" alt="deLondree Logo">
                            <div>
                                <span class="footer-logo-text">deLondree</span>
                                <p class="footer-tagline">Laundry Profesional & Terpercaya</p>
                            </div>
                        </div>
                        <p class="footer-text">Layanan laundry profesional untuk pakaian bersih dan rapi dengan kualitas terbaik dan harga terjangkau.</p>
                        <div class="social-links">
                            <a href="#" class="social-link">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="social-link">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="https://wa.me/6281818710655" class="social-link">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <a href="#" class="social-link">
                                <i class="fab fa-tiktok"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="footer-section">
                        <h4 class="footer-title">Layanan Kami</h4>
                        <ul class="footer-links">
                            <?php
                            $services = $conn->query("SELECT nama_layanan FROM layanan LIMIT 5");
                            while ($service = $services->fetch_assoc()) {
                                echo '<li><a href="#services">' . $service['nama_layanan'] . '</a></li>';
                            }
                            ?>
                        </ul>
                    </div>

                    <div class="footer-section">
                        <h4 class="footer-title">Tautan Cepat</h4>
                        <ul class="footer-links">
                            <li><a href="#home">Beranda</a></li>
                            <li><a href="#about">Tentang Kami</a></li>
                            <li><a href="#services">Layanan</a></li>
                            <li><a href="#process">Cara Kerja</a></li>
                            <li><a href="#gallery">Galeri</a></li>
                            <li><a href="#contact">Kontak</a></li>
                        </ul>
                    </div>

                    <div class="footer-section">
                        <h4 class="footer-title">Kontak</h4>
                        <ul class="footer-links contact-links">
                            <li>
                                <i class="fas fa-map-marker-alt"></i>
                                <span><?= $data['alamat'] ?? 'Kota Kendari, Sulawesi Tenggara' ?></span>
                            </li>
                            <li>
                                <i class="fas fa-phone"></i>
                                <span><?= $data['whatsapp'] ?? '+62 8181 871 0655' ?></span>
                            </li>
                            <li>
                                <i class="fas fa-envelope"></i>
                                <span><?= $data['email'] ?? 'info@delondree.com' ?></span>
                            </li>
                            <li>
                                <i class="fas fa-clock"></i>
                                <span><?= $data['jam_senin'] ?? 'Senin - Sabtu: 08.00-20.00' ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <div class="footer-bottom">
                    <div class="footer-copyright">
                        <p>&copy; 2025 deLondree. All rights reserved.</p>
                    </div>
                    <!-- <div class="footer-admin">
                        <a href="login.php" class="admin-link">
                            <i class="fas fa-cog"></i> Admin Panel
                        </a>
                    </div> -->
                </div>
            </div>
        </footer>
    </div>

    <!-- WhatsApp Floating Button -->
    <a href="https://wa.me/6281818710655" class="whatsapp-float" target="_blank">
        <i class="fab fa-whatsapp"></i>
        <span class="float-text">Chat WhatsApp</span>
    </a>

    <!-- Scroll to Top Button -->
    <button class="scroll-to-top" onclick="scrollToTop()">
        <i class="fas fa-chevron-up"></i>
    </button>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="main.js"></script>
    <script>
        // Initialize Map
        function initMap() {
            const map = L.map('map').setView([-3.9984, 122.5129], 13); // Kendari coordinates
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
            
            L.marker([-3.9984, 122.5129]).addTo(map)
                .bindPopup('<b>deLondree Laundry</b><br>Kota Kendari, Sulawesi Tenggara')
                .openPopup();
        }
        
        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initMap();
        });
    </script>
</body>
</html>
<?php $conn->close(); ?>