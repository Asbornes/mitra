<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>deLondree - Layanan Laundry Profesional</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="laundry-website">
        <!-- Header -->
        <header class="header">
            <div class="header-container">
                <div class="logo-section">
                    <img src="hero.jpg" 
                         alt="deLondree Logo" 
                         class="logo">
                </div>
                
                <nav class="desktop-nav">
                    <a href="#home" class="nav-link">Beranda</a>
                    <a href="#about" class="nav-link">Tentang Kami</a>
                    <a href="#services" class="nav-link">Layanan</a>
                    <a href="#pricing" class="nav-link">Harga</a>
                    <a href="#gallery" class="nav-link">Galeri</a>
                    <a href="#contact" class="nav-link">Kontak</a>
                </nav>

                <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
                    <svg class="menu-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                    <svg class="close-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: none;">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div class="mobile-menu" id="mobileMenu">
                <a href="#home" class="mobile-nav-link" onclick="closeMobileMenu()">Beranda</a>
                <a href="#about" class="mobile-nav-link" onclick="closeMobileMenu()">Tentang Kami</a>
                <a href="#services" class="mobile-nav-link" onclick="closeMobileMenu()">Layanan</a>
                <a href="#pricing" class="mobile-nav-link" onclick="closeMobileMenu()">Harga</a>
                <a href="#gallery" class="mobile-nav-link" onclick="closeMobileMenu()">Galeri</a>
                <a href="#contact" class="mobile-nav-link" onclick="closeMobileMenu()">Kontak</a>
            </div>
        </header>

        <!-- Hero Section -->
        <section id="home" class="hero-section">
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <h1 class="hero-title">Layanan Laundry Profesional</h1>
                <p class="hero-subtitle">Bersih, Rapi, dan Wangi - Kepercayaan Anda adalah Prioritas Kami</p>
                <a href="#contact" class="cta-button">Hubungi Kami Sekarang</a>
            </div>
        </section>

        <!-- About Section -->
        <section id="about" class="about-section">
            <div class="container">
                <h2 class="section-title">Tentang deLondree</h2>
                <div class="about-content">
                    <div class="about-text">
                        <p class="about-paragraph">
                            deLondree adalah layanan laundry profesional yang berkomitmen memberikan hasil terbaik untuk pakaian Anda. 
                            Dengan peralatan modern dan tenaga ahli berpengalaman, kami memastikan setiap pakaian ditangani dengan hati-hati.
                        </p>
                        <p class="about-paragraph">
                            Kami memahami bahwa pakaian bersih dan rapi adalah kebutuhan penting dalam kehidupan sehari-hari. 
                            Oleh karena itu, kami hadir untuk memberikan solusi laundry yang cepat, berkualitas, dan terpercaya.
                        </p>
                        <div class="features-grid">
                            <div class="feature-item">
                                <svg class="feature-icon" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                                <h3>Kualitas Terjamin</h3>
                                <p>Menggunakan detergen berkualitas tinggi</p>
                            </div>
                            <div class="feature-item">
                                <svg class="feature-icon" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <polyline points="12 6 12 12 16 14"/>
                                </svg>
                                <h3>Layanan Cepat</h3>
                                <p>Proses laundry yang efisien</p>
                            </div>
                            <div class="feature-item">
                                <svg class="feature-icon" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="1" y="3" width="15" height="13"/>
                                    <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/>
                                    <circle cx="5.5" cy="18.5" r="2.5"/>
                                    <circle cx="18.5" cy="18.5" r="2.5"/>
                                </svg>
                                <h3>Antar Jemput</h3>
                                <p>Gratis dalam jangkauan area</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Section (Dynamic from Database) -->
        <section id="services" class="services-section">
            <div class="container">
                <h2 class="section-title">Layanan Kami</h2>
                <p class="section-subtitle">Berbagai pilihan layanan untuk memenuhi kebutuhan Anda</p>
                <div class="services-grid" id="servicesGrid">
                    <?php
                    $result = $conn->query("SELECT * FROM layanan");
                    while ($row = $result->fetch_assoc()) {
                        echo "
                        <div class='service-card'>
                            <img src='uploads/{$row['foto']}' alt='{$row['nama_layanan']}' class='service-image'>
                            <h3 class='service-title'>{$row['nama_layanan']}</h3>
                            <p class='service-description'>{$row['deskripsi']}</p>
                            <p class='service-price'>Mulai dari Rp " . number_format($row['harga_mulai'], 0, ',', '.') . "</p>
                        </div>
                        ";
                    }
                    ?>
                </div>
            </div>
        </section>

        <!-- Pricing Section (Dynamic from Database) -->
        <section id="pricing" class="pricing-section">
            <div class="container">
                <h2 class="section-title">Daftar Harga</h2>
                <p class="section-subtitle">Harga terjangkau dengan kualitas terbaik</p>
                <div class="pricing-table" id="pricingTable">
                    <table class="price-table">
                        <thead>
                            <tr>
                                <th>Jenis Layanan</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result = $conn->query("SELECT * FROM harga ORDER BY jenis_layanan, kategori");
                            while ($row = $result->fetch_assoc()) {
                                echo "
                                <tr>
                                    <td>{$row['jenis_layanan']}</td>
                                    <td>{$row['kategori']}</td>
                                    <td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>
                                </tr>
                                ";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <p class="pricing-note">*Harga dapat berubah sewaktu-waktu. Hubungi kami untuk informasi lebih lanjut.</p>
            </div>
        </section>

        <!-- Gallery Section (Dynamic from Database) -->
        <section id="gallery" class="gallery-section">
            <div class="container">
                <h2 class="section-title">Galeri</h2>
                <p class="section-subtitle">Lihat hasil kerja profesional kami</p>
                <div class="gallery-grid" id="galleryGrid">
                    <?php
                    $result = $conn->query("SELECT * FROM galeri");
                    $gallery_items = [];
                    while ($row = $result->fetch_assoc()) {
                        $gallery_items[] = $row;
                        echo "
                        <div class='gallery-item' onclick='openGalleryModal(\"{$row['foto']}\", \"{$row['judul']}\")'>
                            <img src='uploads/{$row['foto']}' alt='{$row['judul']}' class='gallery-image'>
                            <div class='gallery-overlay'>
                                <p class='gallery-title'>{$row['judul']}</p>
                            </div>
                        </div>
                        ";
                    }
                    ?>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section class="testimonials-section">
            <div class="container">
                <h2 class="section-title">Testimoni Pelanggan</h2>
                <p class="section-subtitle">Apa kata pelanggan kami</p>
                <div class="testimonials-grid">
                    <div class="testimonial-card">
                        <div class="stars">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="#FCD34D" stroke="#FCD34D" stroke-width="2">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="#FCD34D" stroke="#FCD34D" stroke-width="2">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="#FCD34D" stroke="#FCD34D" stroke-width="2">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="#FCD34D" stroke="#FCD34D" stroke-width="2">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="#FCD34D" stroke="#FCD34D" stroke-width="2">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        </div>
                        <p class="testimonial-text">
                            "Laundry terbaik di Kendari! Hasil cucian selalu bersih dan wangi. Pelayanan ramah dan cepat."
                        </p>
                        <p class="testimonial-author">- Ibu Siti</p>
                    </div>

                    <div class="testimonial-card">
                        <div class="stars">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="#FCD34D" stroke="#FCD34D" stroke-width="2">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="#FCD34D" stroke="#FCD34D" stroke-width="2">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="#FCD34D" stroke="#FCD34D" stroke-width="2">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="#FCD34D" stroke="#FCD34D" stroke-width="2">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="#FCD34D" stroke="#FCD34D" stroke-width="2">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        </div>
                        <p class="testimonial-text">
                            "Sangat puas dengan layanan antar jemputnya. Hemat waktu dan hasilnya memuaskan!"
                        </p>
                        <p class="testimonial-author">- Bapak Ahmad</p>
                    </div>

                    <div class="testimonial-card">
                        <div class="stars">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="#FCD34D" stroke="#FCD34D" stroke-width="2">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="#FCD34D" stroke="#FCD34D" stroke-width="2">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="#FCD34D" stroke="#FCD34D" stroke-width="2">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="#FCD34D" stroke="#FCD34D" stroke-width="2">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="#FCD34D" stroke="#FCD34D" stroke-width="2">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        </div>
                        <p class="testimonial-text">
                            "Harga terjangkau, kualitas premium. Saya jadi pelanggan setia deLondree."
                        </p>
                        <p class="testimonial-author">- Ibu Rina</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Section (Dynamic from Database) -->
        <section id="contact" class="contact-section">
            <div class="container">
                <h2 class="section-title">Hubungi Kami</h2>
                <p class="section-subtitle">Kami siap melayani Anda</p>
                <div class="contact-content">
                    <div class="contact-info" id="contactInfo">
                        <?php
                        // Ambil data profil dari database (jika ada tabel profil)
                        // Jika belum ada, tampilkan data default
                        $profil = $conn->query("SELECT * FROM profil LIMIT 1");
                        if ($profil && $profil->num_rows > 0) {
                            $data = $profil->fetch_assoc();
                            echo "
                            <div class='contact-item'>
                                <svg class='contact-icon' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2'>
                                    <path d='M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z'/>
                                    <circle cx='12' cy='10' r='3'/>
                                </svg>
                                <div>
                                    <h4>Alamat</h4>
                                    <p>{$data['alamat']}</p>
                                </div>
                            </div>
                            <div class='contact-item'>
                                <svg class='contact-icon' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2'>
                                    <path d='M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z'/>
                                </svg>
                                <div>
                                    <h4>WhatsApp</h4>
                                    <p>{$data['whatsapp']}</p>
                                </div>
                            </div>
                            <div class='contact-item'>
                                <svg class='contact-icon' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2'>
                                    <circle cx='12' cy='12' r='10'/>
                                    <polyline points='12 6 12 12 16 14'/>
                                </svg>
                                <div>
                                    <h4>Jam Operasional</h4>
                                    <p>Senin - Sabtu: {$data['jam_senin']}</p>
                                    <p>Minggu: {$data['jam_minggu']}</p>
                                </div>
                            </div>
                            ";
                        } else {
                            echo "
                            <div class='contact-item'>
                                <svg class='contact-icon' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2'>
                                    <path d='M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z'/>
                                    <circle cx='12' cy='10' r='3'/>
                                </svg>
                                <div>
                                    <h4>Alamat</h4>
                                    <p>Kota Kendari, Sulawesi Tenggara</p>
                                </div>
                            </div>
                            <div class='contact-item'>
                                <svg class='contact-icon' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2'>
                                    <path d='M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z'/>
                                </svg>
                                <div>
                                    <h4>WhatsApp</h4>
                                    <p>+62 8181 871 0655</p>
                                </div>
                            </div>
                            <div class='contact-item'>
                                <svg class='contact-icon' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2'>
                                    <circle cx='12' cy='12' r='10'/>
                                    <polyline points='12 6 12 12 16 14'/>
                                </svg>
                                <div>
                                    <h4>Jam Operasional</h4>
                                    <p>Senin - Sabtu: 08.00 - 20.00</p>
                                    <p>Minggu: 09.00 - 18.00</p>
                                </div>
                            </div>
                            ";
                        }
                        ?>
                    </div>

                    <div class="contact-map" id="contactButtons">
                        <a href="https://wa.me/6281818710655" target="_blank" class="contact-button whatsapp-btn">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                            Chat WhatsApp
                        </a>
                        <a href="tel:+6281818710655" class="contact-button phone-btn">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                            </svg>
                            Telepon Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="footer">
            <div class="container">
                <div class="footer-content">
                    <div class="footer-section">
                        <img src="hero.jpg" 
                             alt="deLondree Logo" 
                             class="footer-logo">
                        <p class="footer-text">Layanan laundry profesional untuk pakaian bersih dan rapi.</p>
                    </div>
                    
                    <div class="footer-section">
                        <h4 class="footer-title">Layanan</h4>
                        <ul class="footer-links">
                            <li>Cuci Kering Setrika</li>
                            <li>Cuci Kering</li>
                            <li>Bed Cover</li>
                            <li>Antar Jemput</li>
                        </ul>
                    </div>

                    <div class="footer-section">
                        <h4 class="footer-title">Kontak</h4>
                        <ul class="footer-links">
                            <li>+62 8181 871 0655</li>
                            <li>Kota Kendari</li>
                            <li>Sulawesi Tenggara</li>
                        </ul>
                    </div>
                </div>
                <div class="footer-bottom">
                    <p>&copy; 2025 deLondree. All rights reserved.</p>
                    <!-- Admin Login Link (Hidden) -->
                    <a href="login.php" class="admin-link" style="opacity: 0.3; font-size: 0.75rem; margin-top: 0.5rem; display: inline-block; color: inherit; text-decoration: none;">Admin</a>
                </div>
            </div>
        </footer>
    </div>

    <!-- Gallery Popup Modal -->
    <div id="galleryModal" class="gallery-modal">
        <span class="gallery-modal-close" onclick="closeGalleryModal()">&times;</span>
        <img class="gallery-modal-content" id="modalImage">
        <div id="caption" class="gallery-modal-caption"></div>
        <button class="gallery-nav-btn gallery-prev" onclick="navigateGallery(-1)">&#10094;</button>
        <button class="gallery-nav-btn gallery-next" onclick="navigateGallery(1)">&#10095;</button>
    </div>

    <script src="main.js"></script>
</body>
</html>