<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Laundry - deLondree</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="booking.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="booking-container">
        <!-- Header dengan gradient modern -->
        <header class="booking-header">
            <div class="container">
                <div class="logo-section">
                    <div class="logo-wrapper">
                        <img src="hero.jpg" alt="deLondree Logo" class="logo">
                        <span class="logo-text">deLondree</span>
                    </div>
                </div>
                <nav class="booking-nav">
                    <a href="index.php" class="nav-link">
                        <i class="fas fa-home"></i> Beranda
                    </a>
                </nav>
            </div>
        </header>

        <div class="booking-progress">
            <div class="container">
                <div class="progress-background"></div>
                <div class="progress-steps">
                    <div class="step active" data-step="1">
                        <div class="step-indicator">
                            <div class="step-number">1</div>
                            <div class="step-check"><i class="fas fa-check"></i></div>
                        </div>
                        <div class="step-label">Informasi & Alamat</div>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step" data-step="2">
                        <div class="step-indicator">
                            <div class="step-number">2</div>
                            <div class="step-check"><i class="fas fa-check"></i></div>
                        </div>
                        <div class="step-label">Pilih Layanan</div>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step" data-step="3">
                        <div class="step-indicator">
                            <div class="step-number">3</div>
                            <div class="step-check"><i class="fas fa-check"></i></div>
                        </div>
                        <div class="step-label">Tinjau Pesanan</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Form Container -->
        <div class="booking-form-container">
            <div class="container">
                <!-- Step 1: Informasi & Alamat -->
                <div id="step1" class="form-step active">
                    <div class="form-header">
                        <h2>Informasi & Alamat Penjemputan</h2>
                        <p>Lengkapi data diri dan alamat penjemputan laundry Anda</p>
                    </div>
                    
                    <form id="bookingInfoForm" class="modern-form">
                        <div class="form-section glass-card">
                            <div class="section-header">
                                <div class="section-icon">
                                    <i class="fas fa-user"></i>
                                </div>
                                <h3>Informasi Pelanggan</h3>
                            </div>
                            <div class="form-row">
                                <div class="form-group floating-label">
                                    <input type="text" name="first_name" id="first_name" required>
                                    <label for="first_name">Nama Depan *</label>
                                    <div class="form-icon">
                                        <i class="fas fa-user"></i>
                                    </div>
                                </div>
                                <div class="form-group floating-label">
                                    <input type="text" name="last_name" id="last_name" required>
                                    <label for="last_name">Nama Belakang *</label>
                                    <div class="form-icon">
                                        <i class="fas fa-user"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group floating-label">
                                    <select name="gender" id="gender" required>
                                        <option value=""></option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                    <label for="gender">Jenis Kelamin *</label>
                                    <div class="form-icon">
                                        <i class="fas fa-venus-mars"></i>
                                    </div>
                                </div>
                                <div class="form-group floating-label">
                                    <input type="tel" name="phone" id="phone" required>
                                    <label for="phone">Nomor WhatsApp *</label>
                                    <div class="form-icon">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-section glass-card">
                            <div class="section-header">
                                <div class="section-icon">
                                    <i class="fas fa-home"></i>
                                </div>
                                <h3>Alamat Penjemputan</h3>
                            </div>
                            <div class="form-group floating-label">
                                <textarea name="full_address" id="full_address" rows="3" required></textarea>
                                <label for="full_address">Alamat Lengkap *</label>
                                <div class="form-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                            </div>
                            
                            <div class="form-group floating-label">
                                <input type="text" name="address_notes" id="address_notes">
                                <label for="address_notes">Keterangan Alamat</label>
                                <div class="form-icon">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                            </div>
                            
                            <div class="map-section">
                                <label>Pin Lokasi di Google Maps</label>
                                <div class="map-container">
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

                        <div class="form-section glass-card">
                            <div class="section-header">
                                <div class="section-icon">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <h3>Jadwal Penjemputan</h3>
                            </div>
                            <div class="form-row">
                                <div class="form-group floating-label">
                                    <input type="date" name="pickup_date" id="pickupDate" required>
                                    <label for="pickupDate">Tanggal Penjemputan *</label>
                                    <div class="form-icon">
                                        <i class="fas fa-calendar"></i>
                                    </div>
                                </div>
                                
                                <div class="form-group floating-label">
                                    <select name="pickup_time" id="pickupTime" required>
                                        <option value=""></option>
                                    </select>
                                    <label for="pickupTime">Waktu Penjemputan *</label>
                                    <div class="form-icon">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn btn-outline" onclick="window.location.href='index.php'">
                                <i class="fas fa-arrow-left"></i> Kembali ke Beranda
                            </button>
                            <button type="button" class="btn btn-primary" onclick="nextToStep2()">
                                Lanjut ke Layanan <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Step 2: Pilih Layanan -->
                <div id="step2" class="form-step">
                    <div class="form-header">
                        <h2>Pilih Layanan Laundry</h2>
                        <p>Pilih layanan laundry yang Anda butuhkan</p>
                    </div>
                    
                    <div class="services-categories">
                        <?php
                        $categories = $conn->query("
                            SELECT DISTINCT jenis_layanan 
                            FROM harga 
                            WHERE service_id IS NOT NULL 
                            ORDER BY jenis_layanan
                        ");
                        
                        while ($category = $categories->fetch_assoc()) {
                            $jenisLayanan = $category['jenis_layanan'];
                            
                            echo '
                            <div class="category-section glass-card">
                                <div class="category-header">
                                    <h3 class="category-title">'.$jenisLayanan.'</h3>
                                    <div class="category-badge">
                                        <i class="fas fa-tag"></i> Promo
                                    </div>
                                </div>
                                
                                <div class="services-grid">';
                            
                            $services = $conn->query("
                                SELECT * FROM harga 
                                WHERE jenis_layanan = '".$jenisLayanan."' 
                                ORDER BY harga
                            ");
                            
                            while ($service = $services->fetch_assoc()) {
                                echo '
                                <div class="service-card" data-service-id="'.$service['id'].'">
                                    <div class="service-content">
                                        <div class="service-header">
                                            <h4>'.$service['kategori'].'</h4>
                                            <div class="service-badge">
                                                <i class="fas fa-star"></i> Terlaris
                                            </div>
                                        </div>
                                        <p class="service-description">'.($service['description'] ?? 'Layanan berkualitas dengan hasil terbaik').'</p>
                                        <div class="service-meta">
                                            <span class="service-unit">
                                                <i class="fas fa-weight-hanging"></i> Per '.$service['unit'].'
                                            </span>
                                            <span class="service-price">Rp '.number_format($service['harga']).'</span>
                                        </div>
                                    </div>
                                    <div class="service-controls">
                                        <button type="button" class="btn-quantity minus" onclick="updateQuantity('.$service['id'].', -1)">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <input type="number" id="qty_'.$service['id'].'" value="0" min="0" readonly>
                                        <button type="button" class="btn-quantity plus" onclick="updateQuantity('.$service['id'].', 1)">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>';
                            }
                            
                            echo '</div></div>';
                        }
                        ?>
                    </div>

                    <div class="selected-services-preview glass-card" id="selectedServicesPreview">
                        <div class="preview-header">
                            <h4><i class="fas fa-shopping-cart"></i> Layanan yang Dipilih</h4>
                            <span class="preview-count" id="selectedCount">0 item</span>
                        </div>
                        <div id="selectedServicesList" class="empty-state">
                            <i class="fas fa-cart-plus"></i>
                            <p>Belum ada layanan dipilih</p>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-outline" onclick="backToStep1()">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </button>
                        <button type="button" class="btn btn-primary" id="nextToStep3Btn" onclick="nextToStep3()" disabled>
                            Tinjau Pesanan <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 3: Tinjau Pesanan -->
                <div id="step3" class="form-step">
                    <div class="form-header">
                        <h2>Tinjau Pesanan Anda</h2>
                        <p>Periksa kembali detail pesanan Anda sebelum konfirmasi</p>
                    </div>
                    
                    <div class="order-summary-grid">
                        <div class="order-details">
                            <div class="summary-section glass-card">
                                <div class="section-header">
                                    <h3><i class="fas fa-concierge-bell"></i> Layanan Dipilih</h3>
                                </div>
                                <div id="orderServicesList" class="empty-state">
                                    <i class="fas fa-list"></i>
                                    <p>Belum ada layanan dipilih</p>
                                </div>
                                <div class="section-actions">
                                    <button type="button" class="btn btn-outline" onclick="backToStep2()">
                                        <i class="fas fa-plus"></i> Tambah/Ubah Layanan
                                    </button>
                                </div>
                            </div>
                            
                            <div class="summary-section glass-card">
                                <div class="section-header">
                                    <h3><i class="fas fa-receipt"></i> Rincian Biaya</h3>
                                </div>
                                <div id="priceBreakdown" class="empty-state">
                                    <i class="fas fa-calculator"></i>
                                    <p>Menunggu data pesanan...</p>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="button" class="btn btn-outline" onclick="backToStep2()">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </button>
                            </div>
                        </div>
                        
                        <div class="order-sidebar">
                            <div class="customer-info-card glass-card">
                                <div class="card-header">
                                    <h4><i class="fas fa-user-circle"></i> Informasi Pelanggan</h4>
                                </div>
                                <div id="customerInfo" class="empty-state">
                                    <i class="fas fa-user"></i>
                                    <p>Data pelanggan akan muncul di sini</p>
                                </div>
                            </div>
                            
                            <div class="payment-section glass-card">
                                <div class="section-header">
                                    <h3><i class="fas fa-credit-card"></i> Metode Pembayaran</h3>
                                </div>
                                <div class="payment-methods">
                                    <label class="payment-option">
                                        <input type="radio" name="payment_method" value="cash" checked>
                                        <span class="payment-label">
                                            <div class="payment-icon">
                                                <i class="fas fa-money-bill-wave"></i>
                                            </div>
                                            <div class="payment-info">
                                                <span class="payment-name">Cash on Delivery</span>
                                                <span class="payment-desc">Bayar saat barang diterima</span>
                                            </div>
                                        </span>
                                    </label>
                                    <label class="payment-option">
                                        <input type="radio" name="payment_method" value="transfer">
                                        <span class="payment-label">
                                            <div class="payment-icon">
                                                <i class="fas fa-university"></i>
                                            </div>
                                            <div class="payment-info">
                                                <span class="payment-name">Transfer Bank</span>
                                                <span class="payment-desc">Transfer sebelum penjemputan</span>
                                            </div>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="order-total-card glass-card">
                                <div class="card-header">
                                    <h4>Total Pembayaran</h4>
                                </div>
                                <div class="total-amount" id="totalAmount">Rp 0</div>
                                <button type="button" class="btn btn-primary large" id="submitOrderBtn" onclick="submitOrder()">
                                    <i class="fas fa-check"></i> Konfirmasi Pesanan
                                </button>
                                <div class="delivery-note">
                                    <i class="fas fa-info-circle"></i>
                                    <span>Biaya antar jemput akan dihitung oleh admin</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Spinner -->
    <div id="loadingSpinner" class="loading-spinner">
        <div class="spinner-content">
            <div class="spinner"></div>
            <p>Memproses pesanan...</p>
        </div>
    </div>

    <script src="booking.js"></script>
</body>
</html>