// Global Variables
let currentGalleryIndex = 0;
let galleryItems = [];
let isMobileMenuOpen = false;

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeWebsite();
    setupEventListeners();
    setupScrollEffects();
    setupIntersectionObserver();
});

// Initialize website functions
function initializeWebsite() {
    updateActiveNavLink();
    setupGallery();
    setupServiceDetails();
    checkScrollToTop();
    
    // Add loading animation
    document.body.classList.add('loaded');
}

// Setup event listeners
function setupEventListeners() {
    // Window scroll event
    window.addEventListener('scroll', function() {
        updateHeaderOnScroll();
        updateActiveNavLink();
        checkScrollToTop();
    });
    
    // Window resize event
    window.addEventListener('resize', function() {
        handleWindowResize();
    });
    
    // Keyboard events
    document.addEventListener('keydown', function(e) {
        handleKeyboardEvents(e);
    });
}

// Mobile Menu Functions
function toggleMobileMenu() {
    const mobileMenu = document.getElementById('mobileMenu');
    const menuIcon = document.querySelector('.mobile-menu-btn i');
    
    if (isMobileMenuOpen) {
        closeMobileMenu();
    } else {
        openMobileMenu();
    }
}

function openMobileMenu() {
    const mobileMenu = document.getElementById('mobileMenu');
    const menuIcon = document.querySelector('.mobile-menu-btn i');
    
    mobileMenu.classList.add('active');
    menuIcon.className = 'fas fa-times';
    isMobileMenuOpen = true;
    
    // Prevent body scroll
    document.body.style.overflow = 'hidden';
}

function closeMobileMenu() {
    const mobileMenu = document.getElementById('mobileMenu');
    const menuIcon = document.querySelector('.mobile-menu-btn i');
    
    mobileMenu.classList.remove('active');
    menuIcon.className = 'fas fa-bars';
    isMobileMenuOpen = false;
    
    // Restore body scroll
    document.body.style.overflow = 'auto';
}

// Scroll Effects
function setupScrollEffects() {
    updateHeaderOnScroll();
}

function updateHeaderOnScroll() {
    const header = document.querySelector('.header');
    const scrollY = window.scrollY;
    
    if (scrollY > 100) {
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }
}

function updateActiveNavLink() {
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.nav-link, .mobile-nav-link');
    const scrollY = window.scrollY + 100;
    
    sections.forEach(section => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.offsetHeight;
        const sectionId = section.getAttribute('id');
        
        if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === `#${sectionId}`) {
                    link.classList.add('active');
                }
            });
        }
    });
}

function checkScrollToTop() {
    const scrollToTopBtn = document.querySelector('.scroll-to-top');
    if (window.scrollY > 500) {
        scrollToTopBtn.classList.add('show');
    } else {
        scrollToTopBtn.classList.remove('show');
    }
}

function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

// Intersection Observer for animations
function setupIntersectionObserver() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, observerOptions);

    // Observe elements for animation
    const animateElements = document.querySelectorAll(
        '.service-card, .feature-card, .gallery-item, .process-step, .contact-card'
    );
    
    animateElements.forEach(element => {
        observer.observe(element);
    });
}

// Gallery Functions
function setupGallery() {
    galleryItems = Array.from(document.querySelectorAll('.gallery-item'));
}

function openGalleryModal(imageSrc, title) {
    const modal = document.getElementById('galleryModal');
    const modalImg = document.getElementById('modalImage');
    const caption = document.getElementById('caption');
    
    // Find current index
    currentGalleryIndex = galleryItems.findIndex(item => 
        item.querySelector('img').src.includes(imageSrc)
    );
    
    // Set modal content
    modalImg.src = 'uploads/' + imageSrc;
    caption.textContent = title;
    
    // Show modal
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
    
    // Add loading animation
    modalImg.style.opacity = '0';
    modalImg.onload = function() {
        modalImg.style.opacity = '1';
    };
}

function closeGalleryModal() {
    const modal = document.getElementById('galleryModal');
    modal.classList.remove('active');
    document.body.style.overflow = 'auto';
}

function navigateGallery(direction) {
    if (galleryItems.length === 0) return;
    
    currentGalleryIndex += direction;
    
    // Wrap around
    if (currentGalleryIndex < 0) {
        currentGalleryIndex = galleryItems.length - 1;
    } else if (currentGalleryIndex >= galleryItems.length) {
        currentGalleryIndex = 0;
    }
    
    const currentItem = galleryItems[currentGalleryIndex];
    const img = currentItem.querySelector('img');
    const title = currentItem.querySelector('.gallery-title').textContent;
    
    const modalImg = document.getElementById('modalImage');
    const caption = document.getElementById('caption');
    
    // Add loading state
    modalImg.style.opacity = '0';
    
    setTimeout(() => {
        modalImg.src = img.src;
        caption.textContent = title;
        
        modalImg.onload = function() {
            modalImg.style.opacity = '1';
        };
    }, 300);
}

// Service Detail Functions
function setupServiceDetails() {
    // Add click handlers to service cards
    const serviceCards = document.querySelectorAll('.service-card');
    serviceCards.forEach(card => {
        card.addEventListener('click', function() {
            const serviceId = this.getAttribute('onclick').match(/\d+/)[0];
            openServiceDetail(parseInt(serviceId));
        });
    });
}

function openServiceDetail(serviceId) {
    // Show loading state
    const modalContent = document.getElementById('serviceDetailContent');
    modalContent.innerHTML = `
        <div class="loading-state">
            <div class="loading-spinner"></div>
            <p>Memuat detail layanan...</p>
        </div>
    `;
    
    // Show modal
    document.getElementById('serviceDetailModal').classList.add('active');
    document.body.style.overflow = 'hidden';
    
    // Fetch service details
    fetch(`service-detail.php?id=${serviceId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(html => {
            modalContent.innerHTML = html;
            setupServiceDetailInteractions();
        })
        .catch(error => {
            console.error('Error loading service detail:', error);
            modalContent.innerHTML = `
                <div class="error-state">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h3>Terjadi Kesalahan</h3>
                    <p>Gagal memuat detail layanan. Silakan coba lagi.</p>
                    <button class="btn-primary" onclick="openServiceDetail(${serviceId})">
                        <i class="fas fa-redo"></i> Coba Lagi
                    </button>
                </div>
            `;
        });
}

function closeServiceDetail() {
    document.getElementById('serviceDetailModal').classList.remove('active');
    document.body.style.overflow = 'auto';
}

function setupServiceDetailInteractions() {
    // Setup booking button in service detail
    const bookNowBtn = document.querySelector('.book-now-btn');
    if (bookNowBtn) {
        bookNowBtn.addEventListener('click', function() {
            const serviceName = this.getAttribute('data-service-name');
            startBooking(serviceName);
        });
    }
}

function startBooking(serviceName = '') {
    if (serviceName) {
        // Store selected service in localStorage for pre-selection
        localStorage.setItem('preSelectedService', serviceName);
    }
    
    // Redirect to booking page
    window.location.href = 'booking.php';
}

// Window resize handler
function handleWindowResize() {
    if (window.innerWidth > 768 && isMobileMenuOpen) {
        closeMobileMenu();
    }
}

// Keyboard event handler
function handleKeyboardEvents(e) {
    // Close modals with Escape key
    if (e.key === 'Escape') {
        closeGalleryModal();
        closeServiceDetail();
    }
    
    // Gallery navigation with arrow keys
    const galleryModal = document.getElementById('galleryModal');
    if (galleryModal.classList.contains('active')) {
        if (e.key === 'ArrowLeft') {
            navigateGallery(-1);
        } else if (e.key === 'ArrowRight') {
            navigateGallery(1);
        }
    }
}

// Utility Functions
function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
}

function formatNumber(number) {
    return new Intl.NumberFormat('id-ID').format(number);
}

// Smooth scroll for navigation links
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                const headerHeight = document.querySelector('.header').offsetHeight;
                const targetPosition = target.offsetTop - headerHeight;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
                
                // Close mobile menu if open
                if (isMobileMenuOpen) {
                    closeMobileMenu();
                }
            }
        });
    });
});

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    // Gallery modal
    const galleryModal = document.getElementById('galleryModal');
    if (e.target === galleryModal) {
        closeGalleryModal();
    }
    
    // Service detail modal
    const serviceDetailModal = document.getElementById('serviceDetailModal');
    if (e.target === serviceDetailModal) {
        closeServiceDetail();
    }
    
    // Mobile menu
    if (isMobileMenuOpen && !e.target.closest('.mobile-menu') && !e.target.closest('.mobile-menu-btn')) {
        closeMobileMenu();
    }
});

// Add CSS for loading states
const additionalStyles = `
    .loading-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--dark-3);
    }
    
    .loading-spinner {
        width: 40px;
        height: 40px;
        border: 4px solid var(--light-3);
        border-top: 4px solid var(--primary);
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 1rem;
    }
    
    .error-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--dark-3);
    }
    
    .error-state i {
        font-size: 3rem;
        color: var(--danger);
        margin-bottom: 1rem;
    }
    
    .error-state h3 {
        margin-bottom: 0.5rem;
        color: var(--dark);
    }
    
    .animate-in {
        animation: fadeInUp 0.6s ease-out;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    body.loaded .header {
        animation: slideDown 0.5s ease-out;
    }
    
    @keyframes slideDown {
        from {
            transform: translateY(-100%);
        }
        to {
            transform: translateY(0);
        }
    }
`;

// Inject additional styles
const styleSheet = document.createElement('style');
styleSheet.textContent = additionalStyles;
document.head.appendChild(styleSheet);

// Export functions for global access
window.toggleMobileMenu = toggleMobileMenu;
window.closeMobileMenu = closeMobileMenu;
window.openGalleryModal = openGalleryModal;
window.closeGalleryModal = closeGalleryModal;
window.navigateGallery = navigateGallery;
window.openServiceDetail = openServiceDetail;
window.closeServiceDetail = closeServiceDetail;
window.startBooking = startBooking;
window.scrollToTop = scrollToTop;