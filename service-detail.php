<?php
include 'koneksi.php';

$service_id = intval($_GET['id'] ?? 0);

if ($service_id > 0) {
    $service = $conn->query("SELECT * FROM layanan WHERE id = $service_id")->fetch_assoc();
    $prices = $conn->query("SELECT * FROM harga WHERE jenis_layanan = '{$service['nama_layanan']}' ORDER BY harga");
    
    if ($service) {
        echo '
        <div class="service-detail">
            <div class="service-detail-header">
                <div class="detail-image-container">
                    <img src="uploads/'.$service['foto'].'" alt="'.$service['nama_layanan'].'" class="detail-image">
                    <div class="image-badge">
                        <i class="fas fa-star"></i>
                        <span>Layanan Terpopuler</span>
                    </div>
                </div>
                <div class="detail-info">
                    <div class="service-meta">
                        <span class="service-category">Laundry Service</span>
                    </div>
                    <h1>'.$service['nama_layanan'].'</h1>
                    <p class="detail-description">'.$service['deskripsi'].'</p>
                    
                    <div class="service-highlights">
                        <div class="highlight-item">
                            <i class="fas fa-clock"></i>
                            <span>2-3 Hari Pengerjaan</span>
                        </div>
                        <div class="highlight-item">
                            <i class="fas fa-shield-alt"></i>
                            <span>Kualitas Terjamin</span>
                        </div>
                        <div class="highlight-item">
                            <i class="fas fa-truck"></i>
                            <span>Gratis Antar Jemput</span>
                        </div>
                    </div>
                    
                    <div class="starting-price">
                        <span class="price-label">Mulai dari</span>
                        <div class="price-amount">Rp '.number_format($service['harga_mulai'], 0, ',', '.').'</div>
                        <small>Harga tergantung berat dan jenis pakaian</small>
                    </div>
                    
                    <button class="btn-primary book-now-btn large" data-service-name="'.$service['nama_layanan'].'">
                        <i class="fas fa-calendar-plus"></i> Pesan Layanan Ini
                    </button>
                </div>
            </div>
            
            <div class="service-detail-content">
                <div class="content-grid">
                    <div class="content-column">
                        <div class="detail-section">
                            <div class="section-header">
                                <i class="fas fa-receipt"></i>
                                <h3>Daftar Harga Lengkap</h3>
                            </div>
                            <div class="price-list">';
        
        if ($prices->num_rows > 0) {
            while ($price = $prices->fetch_assoc()) {
                echo '<div class="price-item">
                        <div class="price-info">
                            <span class="price-category">'.$price['kategori'].'</span>
                            <span class="price-desc">Per item/kg</span>
                        </div>
                        <span class="price-value">Rp '.number_format($price['harga'], 0, ',', '.').'</span>
                      </div>';
            }
        } else {
            echo '<div class="empty-price">
                    <i class="fas fa-info-circle"></i>
                    <p>Belum ada harga tersedia untuk layanan ini</p>
                  </div>';
        }
        
        echo '          </div>
                        </div>
                        
                        <div class="detail-section">
                            <div class="section-header">
                                <i class="fas fa-info-circle"></i>
                                <h3>Tentang Layanan</h3>
                            </div>
                            <div class="service-info">
                                <p>Layanan '.$service['nama_layanan'].' kami memberikan hasil terbaik untuk pakaian Anda. Proses yang kami gunakan meliputi:</p>
                                <ul class="service-features">
                                    <li><i class="fas fa-check"></i> Pencucian dengan detergen premium</li>
                                    <li><i class="fas fa-check"></i> Pengeringan dengan suhu optimal</li>
                                    <li><i class="fas fa-check"></i> Penyeterakan yang rapi dan profesional</li>
                                    <li><i class="fas fa-check"></i> Pengemasan yang hygienis</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="content-column">
                        <div class="detail-section">
                            <div class="section-header">
                                <i class="fas fa-truck"></i>
                                <h3>Biaya Antar Jemput</h3>
                            </div>
                            <div class="delivery-info">
                                <div class="delivery-card free">
                                    <div class="delivery-header">
                                        <i class="fas fa-gift"></i>
                                        <div>
                                            <h4>Gratis Ongkir</h4>
                                            <span class="delivery-range">0 - 2 km</span>
                                        </div>
                                    </div>
                                    <div class="delivery-conditions">
                                        <p><strong>Syarat:</strong> Minimal pembelian Rp 30.000</p>
                                        <p class="note">Untuk pesanan di bawah minimal, dikenakan biaya tambahan Rp 2.000</p>
                                    </div>
                                </div>
                                
                                <div class="delivery-card standard">
                                    <div class="delivery-header">
                                        <i class="fas fa-shipping-fast"></i>
                                        <div>
                                            <h4>Reguler</h4>
                                            <span class="delivery-range">3 - 6 km</span>
                                        </div>
                                    </div>
                                    <div class="delivery-price">
                                        <span class="price">Rp 5.000</span>
                                        <span class="condition">Min. Rp 25.000</span>
                                    </div>
                                </div>
                                
                                <div class="delivery-note">
                                    <i class="fas fa-info-circle"></i>
                                    <p>Area layanan meliputi seluruh Kota Kendari dan sekitarnya</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="detail-section">
                            <div class="section-header">
                                <i class="fas fa-clock"></i>
                                <h3>Jadwal Operasional</h3>
                            </div>
                            <div class="schedule-info">
                                <div class="schedule-card">
                                    <div class="schedule-day">
                                        <strong>Senin - Jumat</strong>
                                        <span>Weekdays</span>
                                    </div>
                                    <div class="schedule-time">
                                        <i class="fas fa-sun"></i>
                                        <span>07:00 - 20:00 WITA</span>
                                    </div>
                                </div>
                                
                                <div class="schedule-card">
                                    <div class="schedule-day">
                                        <strong>Sabtu - Minggu</strong>
                                        <span>Weekend</span>
                                    </div>
                                    <div class="schedule-time">
                                        <i class="fas fa-sun"></i>
                                        <span>08:00 - 18:00 WITA</span>
                                    </div>
                                </div>
                                
                                <div class="schedule-note">
                                    <p><i class="fas fa-clock"></i> <strong>Penjemputan terakhir:</strong> 2 jam sebelum tutup</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="detail-cta">
                    <div class="cta-content">
                        <h3>Siap Mencoba Layanan Ini?</h3>
                        <p>Pesan sekarang dan dapatkan pakaian bersih, rapi, dan wangi dengan mudah!</p>
                        <div class="cta-actions">
                            <button class="btn-primary large book-now-btn" data-service-name="'.$service['nama_layanan'].'">
                                <i class="fas fa-calendar-plus"></i> Pesan Sekarang
                            </button>
                            <a href="https://wa.me/6281818710655" class="btn-secondary" target="_blank">
                                <i class="fab fa-whatsapp"></i> Tanya via WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
    } else {
        echo '
        <div class="error-state">
            <i class="fas fa-exclamation-triangle"></i>
            <h3>Layanan Tidak Ditemukan</h3>
            <p>Layanan yang Anda cari tidak tersedia atau telah dihapus.</p>
            <button class="btn-primary" onclick="closeServiceDetail()">
                <i class="fas fa-arrow-left"></i> Kembali
            </button>
        </div>';
    }
} else {
    echo '
    <div class="error-state">
        <i class="fas fa-exclamation-circle"></i>
        <h3>ID Layanan Tidak Valid</h3>
        <p>Terjadi kesalahan dalam memuat detail layanan.</p>
        <button class="btn-primary" onclick="closeServiceDetail()">
            <i class="fas fa-arrow-left"></i> Kembali
        </button>
    </div>';
}

$conn->close();
?>

<style>
.service-detail {
    padding: 0;
}

.service-detail-header {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    padding: 2rem;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
}

.detail-image-container {
    position: relative;
    border-radius: var(--radius-2xl);
    overflow: hidden;
    box-shadow: var(--shadow-xl);
}

.detail-image {
    width: 100%;
    height: 400px;
    object-fit: cover;
}

.image-badge {
    position: absolute;
    top: 1rem;
    left: 1rem;
    background: var(--gradient-primary);
    color: var(--white);
    padding: 0.5rem 1rem;
    border-radius: var(--radius-lg);
    font-size: 0.9rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.detail-info {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.service-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.service-category {
    background: var(--light-2);
    color: var(--dark-3);
    padding: 0.25rem 0.75rem;
    border-radius: var(--radius-md);
    font-size: 0.9rem;
    font-weight: 500;
}

.service-rating {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--warning);
    font-weight: 600;
}

.detail-info h1 {
    font-size: 2.5rem;
    margin: 0;
    color: var(--dark);
    line-height: 1.2;
}

.detail-description {
    font-size: 1.1rem;
    color: var(--dark-3);
    line-height: 1.7;
    margin: 0;
}

.service-highlights {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
}

.highlight-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background: var(--white);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
}

.highlight-item i {
    color: var(--primary);
    font-size: 1.25rem;
}

.starting-price {
    background: var(--white);
    padding: 1.5rem;
    border-radius: var(--radius-xl);
    text-align: center;
    box-shadow: var(--shadow-md);
    border: 2px solid var(--primary-light);
}

.price-label {
    display: block;
    color: var(--dark-3);
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.price-amount {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--primary);
    margin-bottom: 0.5rem;
}

.starting-price small {
    color: var(--dark-3);
    font-size: 0.85rem;
}

.book-now-btn.large {
    padding: 1.25rem 2rem;
    font-size: 1.1rem;
}

.service-detail-content {
    padding: 3rem 2rem;
}

.content-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    margin-bottom: 3rem;
}

.detail-section {
    background: var(--white);
    padding: 2rem;
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-md);
    border: 1px solid var(--light-3);
}

.section-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--light-2);
}

.section-header i {
    width: 40px;
    height: 40px;
    background: var(--gradient-primary);
    color: var(--white);
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.section-header h3 {
    margin: 0;
    color: var(--dark);
}

.price-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.price-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background: var(--light);
    border-radius: var(--radius-lg);
    transition: var(--transition);
}

.price-item:hover {
    background: var(--light-2);
    transform: translateX(5px);
}

.price-info {
    display: flex;
    flex-direction: column;
}

.price-category {
    font-weight: 600;
    color: var(--dark);
}

.price-desc {
    font-size: 0.85rem;
    color: var(--dark-3);
}

.price-value {
    font-weight: 700;
    color: var(--primary);
    font-size: 1.1rem;
}

.empty-price {
    text-align: center;
    padding: 2rem;
    color: var(--dark-3);
}

.empty-price i {
    font-size: 2rem;
    margin-bottom: 1rem;
    color: var(--light-3);
}

.service-info p {
    color: var(--dark-3);
    line-height: 1.7;
    margin-bottom: 1.5rem;
}

.service-features {
    list-style: none;
    display: grid;
    gap: 0.75rem;
}

.service-features li {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: var(--dark-3);
}

.service-features i {
    color: var(--success);
    font-size: 0.9rem;
}

.delivery-info {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.delivery-card {
    padding: 1.5rem;
    border-radius: var(--radius-lg);
    border: 2px solid transparent;
}

.delivery-card.free {
    background: linear-gradient(135deg, #dbeafe 0%, #e0f2fe 100%);
    border-color: var(--primary-light);
}

.delivery-card.standard {
    background: var(--light);
    border: 2px solid var(--light-3);
}

.delivery-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.delivery-header i {
    width: 50px;
    height: 50px;
    background: var(--gradient-primary);
    color: var(--white);
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.delivery-header h4 {
    margin: 0;
    color: var(--dark);
}

.delivery-range {
    color: var(--dark-3);
    font-size: 0.9rem;
}

.delivery-conditions {
    color: var(--dark-3);
}

.delivery-conditions p {
    margin: 0.25rem 0;
}

.delivery-conditions .note {
    font-size: 0.85rem;
    font-style: italic;
}

.delivery-price {
    text-align: center;
}

.delivery-price .price {
    display: block;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary);
}

.delivery-price .condition {
    font-size: 0.9rem;
    color: var(--dark-3);
}

.delivery-note {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    border-radius: var(--radius-lg);
    color: #856404;
}

.delivery-note i {
    font-size: 1.25rem;
}

.schedule-info {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.schedule-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    background: var(--light);
    border-radius: var(--radius-lg);
}

.schedule-day {
    display: flex;
    flex-direction: column;
}

.schedule-day strong {
    color: var(--dark);
    margin-bottom: 0.25rem;
}

.schedule-day span {
    color: var(--dark-3);
    font-size: 0.9rem;
}

.schedule-time {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: var(--primary);
    font-weight: 600;
}

.schedule-note {
    padding: 1rem;
    background: var(--light-2);
    border-radius: var(--radius-lg);
    color: var(--dark-3);
    font-size: 0.9rem;
}

.schedule-note i {
    color: var(--primary);
    margin-right: 0.5rem;
}

.detail-cta {
    background: var(--gradient-primary);
    color: var(--white);
    padding: 4rem 2rem;
    border-radius: var(--radius-2xl);
    text-align: center;
}

.cta-content h3 {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    color: var(--white);
}

.cta-content p {
    font-size: 1.2rem;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.cta-actions {
    display: flex;
    gap: 1.5rem;
    justify-content: center;
    flex-wrap: wrap;
}

@media (max-width: 768px) {
    .service-detail-header {
        grid-template-columns: 1fr;
        gap: 2rem;
        padding: 1rem;
    }
    
    .content-grid {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .service-detail-content {
        padding: 2rem 1rem;
    }
    
    .detail-info h1 {
        font-size: 2rem;
    }
    
    .service-highlights {
        grid-template-columns: 1fr;
    }
    
    .cta-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .schedule-card {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
}
</style>

<script>
// Add interactivity to service detail
document.addEventListener('DOMContentLoaded', function() {
    // Setup book now buttons
    document.querySelectorAll('.book-now-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const serviceName = this.getAttribute('data-service-name');
            if (serviceName) {
                // Store in localStorage for pre-selection in booking form
                localStorage.setItem('preSelectedService', serviceName);
            }
            window.location.href = 'booking.php';
        });
    });
});
</script>