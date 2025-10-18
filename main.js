// Initialize default data if not exists
function initializeDefaultData() {
    if (!localStorage.getItem('layananData')) {
        localStorage.setItem('layananData', JSON.stringify([
            {
                id: 1,
                nama: 'Cuci Kering Setrika',
                deskripsi: 'Layanan lengkap mencuci, mengeringkan, dan menyetrika pakaian Anda hingga rapi dan siap pakai.',
                harga: 7000,
                foto: ''
            },
            {
                id: 2,
                nama: 'Cuci Kering',
                deskripsi: 'Pakaian dicuci bersih dan dikeringkan dengan sempurna, siap untuk Anda setrika sendiri.',
                harga: 5000,
                foto: ''
            },
            {
                id: 3,
                nama: 'Bed Cover',
                deskripsi: 'Layanan khusus untuk bed cover, sprei, dan perlengkapan tidur lainnya.',
                harga: 15000,
                foto: ''
            },
            {
                id: 4,
                nama: 'Antar Jemput',
                deskripsi: 'Kami siap menjemput dan mengantarkan cucian Anda ke lokasi dalam area jangkauan.',
                harga: 0,
                foto: ''
            }
        ]));
    }

    if (!localStorage.getItem('hargaData')) {
        localStorage.setItem('hargaData', JSON.stringify([
            { id: 1, jenis: 'Cuci Kering Setrika', kategori: 'Per Kg', harga: 7000 },
            { id: 2, jenis: 'Cuci Kering Setrika', kategori: 'Per Load', harga: 35000 },
            { id: 3, jenis: 'Cuci Kering Setrika', kategori: 'Per Pcs', harga: 5000 },
            { id: 4, jenis: 'Cuci Kering', kategori: 'Per Kg', harga: 5000 },
            { id: 5, jenis: 'Cuci Kering', kategori: 'Per Load', harga: 25000 },
            { id: 6, jenis: 'Cuci Kering', kategori: 'Per Pcs', harga: 3000 },
            { id: 7, jenis: 'Bed Cover', kategori: 'Single', harga: 15000 },
            { id: 8, jenis: 'Bed Cover', kategori: 'Double', harga: 20000 },
            { id: 9, jenis: 'Bed Cover', kategori: 'King', harga: 25000 }
        ]));
    }

    if (!localStorage.getItem('galeriData')) {
        localStorage.setItem('galeriData', JSON.stringify([]));
    }

    if (!localStorage.getItem('profilData')) {
        localStorage.setItem('profilData', JSON.stringify({
            nama: 'deLondree',
            alamat: '2G93+4QF, Kadia, Kec. Kadia, Kota Kendari, Sulawesi Tenggara 93115',
            whatsapp: '6281818710655',
            email: 'info@delondree.com',
            jamBukaSenin: '08.00 - 20.00',
            jamBukaMinggu: '09.00 - 17.00'
        }));
    }
}

initializeDefaultData();

// Load Services
function loadServices() {
    const data = JSON.parse(localStorage.getItem('layananData') || '[]');
    const grid = document.getElementById('servicesGrid');
    
    if (data.length === 0) {
        grid.innerHTML = '<p style="text-align: center; padding: 2rem;">Belum ada layanan tersedia</p>';
        return;
    }

    grid.innerHTML = data.map(service => `
        <div class="service-card">
            <div class="service-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
            </div>
            <h3 class="service-title">${service.nama}</h3>
            <p class="service-description">${service.deskripsi}</p>
            <p class="service-options">Mulai Rp ${service.harga.toLocaleString('id-ID')}</p>
        </div>
    `).join('');
}

// Load Pricing
function loadPricing() {
    const data = JSON.parse(localStorage.getItem('hargaData') || '[]');
    const table = document.getElementById('pricingTable');
    
    if (data.length === 0) {
        table.innerHTML = '<p style="text-align: center; padding: 2rem;">Belum ada daftar harga</p>';
        return;
    }

    // Group by jenis
    const grouped = {};
    data.forEach(item => {
        if (!grouped[item.jenis]) {
            grouped[item.jenis] = [];
        }
        grouped[item.jenis].push(item);
    });

    let html = '';
    let index = 0;
    for (const [jenis, items] of Object.entries(grouped)) {
        const isFeatured = index === 1 ? 'featured' : '';
        const badge = index === 1 ? `<div class="badge">Populer</div>` : ''; // PERBAIKAN: Ditambah backtick

        html += ` 
            <div class="pricing-card ${isFeatured}">
                ${badge}
                <h3 class="pricing-title">${jenis}</h3>
                <div class="pricing-details">
                    ${items.map(item => `
                        <div class="price-item">
                            <span class="price-label">${item.kategori}</span>
                            <span class="price-value">${item.harga > 0 ? 'Rp ' + item.harga.toLocaleString('id-ID') : 'Gratis'}</span>
                        </div>
                    `).join('')}
                </div>
            </div>
        `; // PERBAIKAN: Ditambah backtick
        index++;
    }

    table.innerHTML = html;
}

// Load Gallery
let currentGalleryIndex = 0;
let galleryData = [];

function loadGallery() {
    galleryData = JSON.parse(localStorage.getItem('galeriData') || '[]');
    const grid = document.getElementById('galleryGrid');
    
    if (galleryData.length === 0) {
        grid.innerHTML = ` 
            <div class="gallery-item">
                <div class="gallery-placeholder">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    <p>Galeri akan segera hadir</p>
                </div>
            </div>
        `; // PERBAIKAN: Ditambah backtick
        return;
    }

    grid.innerHTML = galleryData.map((item, index) => `
        <div class="gallery-item" onclick="openGalleryModal(${index})">
            <img src="${item.foto}" alt="${item.judul}" style="width: 100%; height: 250px; object-fit: cover; border-radius: 12px; cursor: pointer; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
            <div style="padding: 1rem; text-align: center;">
                <h4 style="font-size: 1.1rem; margin: 0;">${item.judul}</h4>
            </div>
        </div>
    `).join(''); // PERBAIKAN: Ditambah backtick
}

// Gallery Modal Functions
function openGalleryModal(index) {
    currentGalleryIndex = index;
    const modal = document.getElementById('galleryModal');
    const modalImg = document.getElementById('modalImage');
    const caption = document.getElementById('caption');
    
    modal.style.display = 'flex';
    modalImg.src = galleryData[index].foto;
    caption.textContent = galleryData[index].judul;
    
    // Prevent body scroll when modal is open
    document.body.style.overflow = 'hidden';
}

function closeGalleryModal() {
    const modal = document.getElementById('galleryModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

function navigateGallery(direction) {
    currentGalleryIndex += direction;
    
    // Loop around
    if (currentGalleryIndex >= galleryData.length) {
        currentGalleryIndex = 0;
    } else if (currentGalleryIndex < 0) {
        currentGalleryIndex = galleryData.length - 1;
    }
    
    const modalImg = document.getElementById('modalImage');
    const caption = document.getElementById('caption');
    
    modalImg.src = galleryData[currentGalleryIndex].foto;
    caption.textContent = galleryData[currentGalleryIndex].judul;
}

// Keyboard navigation for gallery
document.addEventListener('keydown', function(e) {
    const modal = document.getElementById('galleryModal');
    if (modal.style.display === 'flex') {
        if (e.key === 'ArrowLeft') {
            navigateGallery(-1);
        } else if (e.key === 'ArrowRight') {
            navigateGallery(1);
        } else if (e.key === 'Escape') {
            closeGalleryModal();
        }
    }
});

// Close modal when clicking outside image
document.getElementById('galleryModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeGalleryModal();
    }
});

// Load Contact Info
function loadContactInfo() {
    const profil = JSON.parse(localStorage.getItem('profilData'));
    const contactInfo = document.getElementById('contactInfo');
    const contactButtons = document.getElementById('contactButtons');
    
    contactInfo.innerHTML = `
        <div class="contact-item">
            <svg class="contact-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/>
                <circle cx="12" cy="10" r="3"/>
            </svg>
            <div>
                <h4>Alamat</h4>
                <p>${profil.alamat}</p>
            </div>
        </div>
        <div class="contact-item">
            <svg class="contact-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/>
            </svg>
            <div>
                <h4>Telepon / WhatsApp</h4>
                <p>+${profil.whatsapp}</p>
            </div>
        </div>
        <div class="contact-item">
            <svg class="contact-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <polyline points="12 6 12 12 16 14"/>
            </svg>
            <div>
                <h4>Jam Operasional</h4>
                <p>Senin - Sabtu: ${profil.jamBukaSenin}</p>
                <p>Minggu: ${profil.jamBukaMinggu}</p>
            </div>
        </div>
    `; // PERBAIKAN: Ditambah backtick
    
    contactButtons.innerHTML = `
        <a href="https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(profil.alamat)}" 
            target="_blank" 
            rel="noopener noreferrer"
            class="map-button">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/>
                <circle cx="12" cy="10" r="3"/>
            </svg>
            Lihat di Google Maps
        </a>
        <a href="https://wa.me/${profil.whatsapp}?text=Halo%20${profil.nama},%20saya%20ingin%20bertanya%20tentang%20layanan%20laundry" 
            target="_blank" 
            rel="noopener noreferrer"
            class="whatsapp-button">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/>
            </svg>
            Chat via WhatsApp
        </a>
    `; // PERBAIKAN: Ditambah backtick
}

// Mobile Menu Functions
function toggleMobileMenu() {
    const mobileMenu = document.getElementById('mobileMenu');
    const menuIcon = document.querySelector('.menu-icon');
    const closeIcon = document.querySelector('.close-icon');
    
    if (mobileMenu.style.display === 'flex') {
        mobileMenu.style.display = 'none';
        menuIcon.style.display = 'block';
        closeIcon.style.display = 'none';
    } else {
        mobileMenu.style.display = 'flex';
        menuIcon.style.display = 'none';
        closeIcon.style.display = 'block';
    }
}

function closeMobileMenu() {
    const mobileMenu = document.getElementById('mobileMenu');
    const menuIcon = document.querySelector('.menu-icon');
    const closeIcon = document.querySelector('.close-icon');
    
    mobileMenu.style.display = 'none';
    menuIcon.style.display = 'block';
    closeIcon.style.display = 'none';
}

// Smooth scroll
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth'
            });
        }
    });
});

// Load all dynamic content when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadServices();
    loadPricing();
    loadGallery();
    loadContactInfo();
})