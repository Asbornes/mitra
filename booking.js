class BookingSystem {
    constructor() {
        this.currentStep = 1;
        this.selectedServices = [];
        this.bookingData = {};
        this.deliveryRates = [];
        this.isSubmitting = false;
        
        this.init();
    }

    init() {
        document.addEventListener('DOMContentLoaded', () => {
            this.initializeDatePicker();
            this.loadDeliveryRates();
            this.setupTimeSlots();
            this.setupEventListeners();
            this.updateSelectedServicesList();
            
            console.log('Booking system initialized');
        });
    }

    setupEventListeners() {
        // Form submission prevention
        document.getElementById('bookingInfoForm').addEventListener('submit', (e) => {
            e.preventDefault();
        });

        // Floating label functionality
        this.setupFloatingLabels();
        
        // Input validation
        this.setupInputValidation();
    }

    setupFloatingLabels() {
        const inputs = document.querySelectorAll('.floating-label input, .floating-label textarea, .floating-label select');
        
        inputs.forEach(input => {
            // Check initial value
            if (input.value) {
                input.parentElement.classList.add('has-value');
            }

            input.addEventListener('focus', () => {
                input.parentElement.classList.add('focused');
            });

            input.addEventListener('blur', () => {
                if (!input.value) {
                    input.parentElement.classList.remove('focused', 'has-value');
                } else {
                    input.parentElement.classList.remove('focused');
                    input.parentElement.classList.add('has-value');
                }
            });

            input.addEventListener('input', () => {
                if (input.value) {
                    input.parentElement.classList.add('has-value');
                } else {
                    input.parentElement.classList.remove('has-value');
                }
            });
        });
    }

    setupInputValidation() {
        const phoneInput = document.getElementById('phone');
        if (phoneInput) {
            phoneInput.addEventListener('input', (e) => {
                let value = e.target.value.replace(/\D/g, '');
                if (value.startsWith('0')) {
                    value = '62' + value.substring(1);
                }
                e.target.value = value;
            });
        }
    }

    async loadDeliveryRates() {
        try {
            const response = await fetch('php/get_delivery_rates.php');
            this.deliveryRates = await response.json();
        } catch (error) {
            console.error('Error loading delivery rates:', error);
            // Default rates
            this.deliveryRates = [
                { range_min: 0, range_max: 2, rate: 0, description: 'Gratis Ongkir' },
                { range_min: 3, range_max: 6, rate: 5000, description: 'Area Dalam Kota' },
                { range_min: 7, range_max: 10, rate: 8000, description: 'Area Luar Kota' }
            ];
        }
    }

    setupTimeSlots() {
        const pickupDate = document.getElementById('pickupDate');
        const pickupTime = document.getElementById('pickupTime');
        
        pickupDate.addEventListener('change', () => {
            this.updateTimeSlots(pickupDate.value);
        });
        
        // Set default date (tomorrow)
        const today = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);
        
        pickupDate.min = today.toISOString().split('T')[0];
        pickupDate.value = tomorrow.toISOString().split('T')[0];
        this.updateTimeSlots(pickupDate.value);
    }

    updateTimeSlots(dateString) {
        const date = new Date(dateString);
        const day = date.getDay();
        const pickupTime = document.getElementById('pickupTime');
        
        pickupTime.innerHTML = '<option value=""></option>';
        
        let timeSlots = [];
        
        if (day >= 1 && day <= 6) {
            timeSlots = [
                '08:00-10:00', '10:00-12:00', '12:00-14:00', 
                '14:00-16:00', '16:00-18:00', '18:00-20:00', '20:00-21:00'
            ];
        } else {
            timeSlots = [
                '16:00-18:00', '18:00-20:00', '20:00-21:00'
            ];
        }
        
        timeSlots.forEach(slot => {
            const option = document.createElement('option');
            option.value = slot;
            option.textContent = slot;
            pickupTime.appendChild(option);
        });

        // Trigger floating label update
        if (pickupTime.parentElement.classList.contains('floating-label')) {
            pickupTime.dispatchEvent(new Event('change'));
        }
    }

    initializeDatePicker() {
        const pickupDate = document.getElementById('pickupDate');
        const today = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);
        
        pickupDate.min = today.toISOString().split('T')[0];
        pickupDate.value = tomorrow.toISOString().split('T')[0];
    }

    updateQuantity(serviceId, change) {
        if (this.isSubmitting) return;

        const input = document.getElementById('qty_' + serviceId);
        let newValue = parseInt(input.value) + change;
        
        if (newValue < 0) newValue = 0;
        
        // Enhanced animation
        input.classList.add('quantity-changing');
        setTimeout(() => {
            input.classList.remove('quantity-changing');
        }, 300);
        
        input.value = newValue;
        
        const serviceElement = document.querySelector(`[data-service-id="${serviceId}"]`);
        if (!serviceElement) return;

        const serviceName = serviceElement.querySelector('h4').textContent;
        const servicePrice = parseInt(serviceElement.querySelector('.service-price').textContent.replace(/\D/g, ''));
        const serviceUnit = serviceElement.querySelector('.service-unit').textContent.replace('Per ', '');
        
        const existingIndex = this.selectedServices.findIndex(s => s.id === serviceId);
        
        if (newValue === 0 && existingIndex !== -1) {
            this.selectedServices.splice(existingIndex, 1);
            this.showNotification(`Layanan ${serviceName} dihapus`, 'info');
        } else if (newValue > 0) {
            if (existingIndex !== -1) {
                this.selectedServices[existingIndex].quantity = newValue;
            } else {
                this.selectedServices.push({
                    id: serviceId,
                    name: serviceName,
                    price: servicePrice,
                    quantity: newValue,
                    unit: serviceUnit
                });
                this.showNotification(`Layanan ${serviceName} ditambahkan`, 'success');
            }
        }
        
        this.updateSelectedServicesList();
        
        if (this.currentStep === 3) {
            this.updateOrderSummary();
        }
    }

    updateSelectedServicesList() {
        const selectedServicesList = document.getElementById('selectedServicesList');
        const selectedServicesPreview = document.getElementById('selectedServicesPreview');
        const selectedCount = document.getElementById('selectedCount');
        const nextToStep3Btn = document.getElementById('nextToStep3Btn');
        
        if (this.selectedServices.length === 0) {
            selectedServicesList.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-cart-plus"></i>
                    <p>Belum ada layanan dipilih</p>
                </div>
            `;
            selectedCount.textContent = '0 item';
            if (nextToStep3Btn) {
                nextToStep3Btn.disabled = true;
            }
            return;
        }
        
        selectedCount.textContent = `${this.selectedServices.length} item`;
        if (nextToStep3Btn) {
            nextToStep3Btn.disabled = false;
        }
        
        selectedServicesList.innerHTML = '';
        
        this.selectedServices.forEach(service => {
            const serviceElement = document.createElement('div');
            serviceElement.className = 'selected-service-preview';
            serviceElement.innerHTML = `
                <div class="service-preview-info">
                    <h5>${service.name}</h5>
                    <div class="service-preview-meta">
                        <span>${service.quantity} ${service.unit}</span>
                        <span class="service-preview-price">Rp ${service.price.toLocaleString('id-ID')}/${service.unit}</span>
                    </div>
                </div>
                <div class="service-preview-total">
                    <strong>Rp ${(service.price * service.quantity).toLocaleString('id-ID')}</strong>
                </div>
            `;
            selectedServicesList.appendChild(serviceElement);
        });
    }

    validateStep1() {
        const form = document.getElementById('bookingInfoForm');
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.style.borderColor = 'var(--danger)';
                
                // Add shake animation
                field.style.animation = 'shake 0.5s ease';
                setTimeout(() => {
                    field.style.animation = '';
                }, 500);
            } else {
                field.style.borderColor = '';
            }
        });

        // Validate phone number
        const phoneField = document.getElementById('phone');
        if (phoneField.value && !/^62\d{9,12}$/.test(phoneField.value)) {
            isValid = false;
            phoneField.style.borderColor = 'var(--danger)';
            this.showNotification('Format nomor WhatsApp tidak valid. Gunakan format 62...', 'error');
        }
        
        return isValid;
    }

    nextToStep2() {
        if (!this.validateStep1()) {
            this.showNotification('Harap isi semua field yang wajib diisi dengan benar', 'error');
            return;
        }
        
        const form = document.getElementById('bookingInfoForm');
        const formData = new FormData(form);
        this.bookingData = {
            first_name: formData.get('first_name'),
            last_name: formData.get('last_name'),
            gender: formData.get('gender'),
            phone: formData.get('phone'),
            full_address: formData.get('full_address'),
            address_notes: formData.get('address_notes'),
            pickup_date: formData.get('pickup_date'),
            pickup_time: formData.get('pickup_time')
        };
        
        this.changeStep(2);
        this.showNotification('Data berhasil disimpan! Sekarang pilih layanan yang diinginkan.', 'success');
    }

    nextToStep3() {
        if (this.selectedServices.length === 0) {
            this.showNotification('Pilih minimal satu layanan untuk melanjutkan', 'error');
            return;
        }
        
        this.changeStep(3);
        this.updateOrderSummary();
        this.showNotification('Pesanan berhasil dikumpulkan! Tinjau sebelum konfirmasi.', 'success');
    }

    backToStep1() {
        this.changeStep(1);
    }

    backToStep2() {
        this.changeStep(2);
    }

    changeStep(step) {
        // Hide all steps
        document.querySelectorAll('.form-step').forEach(stepElement => {
            stepElement.classList.remove('active');
        });
        
        // Show target step
        document.getElementById('step' + step).classList.add('active');
        
        this.currentStep = step;
        this.updateProgressSteps();
        
        // Scroll to top dengan smooth animation
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    updateProgressSteps() {
        document.querySelectorAll('.step').forEach((step, index) => {
            const stepNumber = index + 1;
            
            if (stepNumber < this.currentStep) {
                step.classList.add('completed');
                step.classList.remove('active');
            } else if (stepNumber === this.currentStep) {
                step.classList.add('active');
                step.classList.remove('completed');
            } else {
                step.classList.remove('active', 'completed');
            }
        });
        
        // Update connectors
        document.querySelectorAll('.step-connector').forEach((connector, index) => {
            if (index + 1 < this.currentStep) {
                connector.classList.add('completed');
            } else {
                connector.classList.remove('completed');
            }
        });
    }

    updateOrderSummary() {
        const orderServicesList = document.getElementById('orderServicesList');
        const priceBreakdown = document.getElementById('priceBreakdown');
        const totalAmount = document.getElementById('totalAmount');
        const customerInfo = document.getElementById('customerInfo');
        
        if (this.selectedServices.length === 0) {
            orderServicesList.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-list"></i>
                    <p>Belum ada layanan dipilih</p>
                </div>
            `;
            priceBreakdown.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-calculator"></i>
                    <p>Menunggu data pesanan...</p>
                </div>
            `;
            totalAmount.textContent = 'Rp 0';
            return;
        }
        
        // Update services list
        let subtotal = 0;
        orderServicesList.innerHTML = '';
        
        this.selectedServices.forEach(service => {
            const serviceTotal = service.price * service.quantity;
            subtotal += serviceTotal;
            
            const serviceElement = document.createElement('div');
            serviceElement.className = 'order-service-item';
            serviceElement.innerHTML = `
                <div class="order-service-info">
                    <h5>${service.name}</h5>
                    <div class="order-service-meta">
                        <span>${service.quantity} ${service.unit} × Rp ${service.price.toLocaleString('id-ID')}</span>
                    </div>
                </div>
                <div class="order-service-price">
                    Rp ${serviceTotal.toLocaleString('id-ID')}
                </div>
            `;
            orderServicesList.appendChild(serviceElement);
        });
        
        // Update price breakdown
        priceBreakdown.innerHTML = '';
        
        // Subtotal
        const subtotalElement = document.createElement('div');
        subtotalElement.className = 'price-item';
        subtotalElement.innerHTML = `
            <span>Subtotal Layanan</span>
            <span>Rp ${subtotal.toLocaleString('id-ID')}</span>
        `;
        priceBreakdown.appendChild(subtotalElement);
        
        // Delivery note
        const deliveryNote = document.createElement('div');
        deliveryNote.className = 'delivery-note';
        deliveryNote.innerHTML = `
            <i class="fas fa-info-circle"></i>
            <span>Biaya antar jemput akan dihitung oleh admin berdasarkan jarak dan diberitahukan via WhatsApp</span>
        `;
        priceBreakdown.appendChild(deliveryNote);
        
        // Total
        const totalElement = document.createElement('div');
        totalElement.className = 'price-item total';
        totalElement.innerHTML = `
            <span>Total Pembayaran</span>
            <span>Rp ${subtotal.toLocaleString('id-ID')}</span>
        `;
        priceBreakdown.appendChild(totalElement);
        
        // Update total amount
        totalAmount.textContent = `Rp ${subtotal.toLocaleString('id-ID')}`;
        
        // Update customer info
        customerInfo.innerHTML = `
            <div class="customer-info-item">
                <span class="customer-info-label">Nama</span>
                <span class="customer-info-value">${this.bookingData.first_name} ${this.bookingData.last_name}</span>
            </div>
            <div class="customer-info-item">
                <span class="customer-info-label">WhatsApp</span>
                <span class="customer-info-value">${this.bookingData.phone}</span>
            </div>
            <div class="customer-info-item">
                <span class="customer-info-label">Alamat</span>
                <span class="customer-info-value">${this.bookingData.full_address}</span>
            </div>
            <div class="customer-info-item">
                <span class="customer-info-label">Tanggal Jemput</span>
                <span class="customer-info-value">${this.formatDate(this.bookingData.pickup_date)} ${this.bookingData.pickup_time}</span>
            </div>
        `;
    }

    formatDate(dateString) {
        const date = new Date(dateString);
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        return date.toLocaleDateString('id-ID', options);
    }

    async submitOrder() {
        if (this.isSubmitting) return;

        const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
        
        if (!paymentMethod) {
            this.showNotification('Pilih metode pembayaran terlebih dahulu', 'error');
            return;
        }
        
        if (this.selectedServices.length === 0) {
            this.showNotification('Pilih minimal satu layanan', 'error');
            return;
        }
        
        const subtotal = this.selectedServices.reduce((sum, service) => sum + (service.price * service.quantity), 0);
        
        const orderData = {
            first_name: this.bookingData.first_name,
            last_name: this.bookingData.last_name || '',
            phone: this.bookingData.phone,
            full_address: this.bookingData.full_address,
            address_notes: this.bookingData.address_notes || '',
            pickup_date: this.bookingData.pickup_date,
            pickup_time: this.bookingData.pickup_time,
            services: this.selectedServices,
            subtotal: subtotal,
            total_amount: subtotal,
            payment_method: paymentMethod.value
        };
        
        console.log('Submitting order:', orderData);
        
        // Show loading
        this.showLoading(true);
        this.isSubmitting = true;
        
        const submitBtn = document.querySelector('#step3 .btn-primary');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        submitBtn.disabled = true;
        
        try {
            const saveResponse = await this.saveOrderToDatabase(orderData);
            
            if (saveResponse.success) {
                const orderId = saveResponse.order_id;
                this.sendToWhatsApp(orderData, orderId);
                this.showSuccessModal({ order_id: orderId });
                this.showNotification('Pesanan berhasil dibuat!', 'success');
            } else {
                throw new Error(saveResponse.message || 'Gagal menyimpan pesanan');
            }
        } catch (error) {
            console.error('Submit order error:', error);
            this.showNotification(error.message, 'error');
        } finally {
            this.showLoading(false);
            this.isSubmitting = false;
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    }

    async saveOrderToDatabase(orderData) {
        try {
            console.log('Saving order to database:', orderData);
            
            const formattedServices = orderData.services.map(service => ({
                name: service.name || 'Layanan',
                quantity: parseFloat(service.quantity) || 1,
                price: parseInt(service.price) || 0,
                unit: service.unit || 'kg'
            }));

            const paymentMethod = orderData.payment_method === 'transfer' ? 'transfer' : 'cash';

            const payload = {
                first_name: orderData.first_name || 'Customer',
                last_name: orderData.last_name || '',
                phone: orderData.phone || '081234567890',
                full_address: orderData.full_address || 'Alamat tidak diisi',
                address_notes: orderData.address_notes || '',
                pickup_date: orderData.pickup_date || new Date().toISOString().split('T')[0],
                pickup_time: orderData.pickup_time || '10:00-12:00',
                payment_method: paymentMethod,
                services: formattedServices
            };

            console.log('Payload to send:', payload);

            const response = await fetch('php/save_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(payload)
            });
            
            console.log('Response status:', response.status);
            
            if (!response.ok) {
                const errorText = await response.text();
                console.error('Server response:', errorText);
                throw new Error(`HTTP error! status: ${response.status}, message: ${errorText}`);
            }
            
            const result = await response.json();
            console.log('Save order result:', result);
            
            if (!result.success) {
                throw new Error(result.message || 'Gagal menyimpan pesanan');
            }
            
            return result;
            
        } catch (error) {
            console.error('Error saving order:', error);
            throw new Error('Gagal menyimpan pesanan: ' + error.message);
        }
    }

    sendToWhatsApp(orderData, orderId) {
        const phoneNumber = '6281818710655';
        
        let message = `Halo deLondree! Saya ingin memesan layanan laundry:\n\n`;
        message += `*ID Pesanan:* ${orderId}\n`;
        message += `*Detail Pelanggan:*\n`;
        message += `Nama: ${orderData.first_name} ${orderData.last_name}\n`;
        message += `WhatsApp: ${orderData.phone}\n`;
        message += `Alamat: ${orderData.full_address}\n`;
        if (orderData.address_notes) {
            message += `Keterangan Alamat: ${orderData.address_notes}\n`;
        }
        message += `Jadwal Penjemputan: ${this.formatDate(orderData.pickup_date)} ${orderData.pickup_time}\n\n`;
        
        message += `*Layanan yang Dipesan:*\n`;
        orderData.services.forEach(service => {
            message += `• ${service.name} (${service.quantity} ${service.unit}) - Rp ${(service.price * service.quantity).toLocaleString('id-ID')}\n`;
        });
        
        message += `\n*Subtotal: Rp ${orderData.total_amount.toLocaleString('id-ID')}*\n\n`;
        message += `Metode Pembayaran: ${orderData.payment_method === 'cod' ? 'Cash on Delivery (COD)' : 'Transfer Bank'}\n\n`;
        message += `Silakan konfirmasi pesanan ini dan beritahu total biaya termasuk ongkos kirim. Terima kasih!`;
        
        const encodedMessage = encodeURIComponent(message);
        const whatsappUrl = `https://wa.me/${phoneNumber}?text=${encodedMessage}`;
        
        window.open(whatsappUrl, '_blank');
    }

    showSuccessModal(orderData) {
        const modal = document.createElement('div');
        modal.className = 'modal active';
        modal.innerHTML = `
            <div class="modal-content">
                <div class="success-modal">
                    <div class="success-icon">
                        <i class="fas fa-check"></i>
                    </div>
                    <h3>Pesanan Berhasil!</h3>
                    <p>Pesanan laundry Anda telah berhasil dibuat dan sedang diproses.</p>
                    <div class="order-id">${orderData.order_id}</div>
                    <p>Simpan ID pesanan ini untuk tracking status laundry Anda.</p>
                    
                    <div class="success-divider">Langkah Selanjutnya</div>
                    
                    <div class="success-actions">
                        <a href="https://wa.me/6281818710655" class="whatsapp-btn-success" target="_blank">
                            <i class="fab fa-whatsapp"></i>
                            Konfirmasi via WhatsApp
                        </a>
                        <button class="btn btn-outline" onclick="bookingSystem.closeSuccessModal()">
                            <i class="fas fa-home"></i> Kembali ke Beranda
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Add confetti effect
        this.createConfetti();
        
        // Prevent background scroll
        document.body.style.overflow = 'hidden';
        
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                this.closeSuccessModal();
            }
        });
    }

    createConfetti() {
        const colors = ['#6366f1', '#8b5cf6', '#f59e0b', '#10b981', '#ef4444'];
        const container = document.querySelector('.modal');
        
        for (let i = 0; i < 50; i++) {
            const confetti = document.createElement('div');
            confetti.className = 'confetti';
            confetti.style.left = Math.random() * 100 + '%';
            confetti.style.background = colors[Math.floor(Math.random() * colors.length)];
            confetti.style.animationDelay = Math.random() * 2 + 's';
            confetti.style.width = Math.random() * 10 + 5 + 'px';
            confetti.style.height = Math.random() * 10 + 5 + 'px';
            
            container.appendChild(confetti);
            
            // Remove confetti after animation
            setTimeout(() => {
                if (confetti.parentElement) {
                    confetti.remove();
                }
            }, 3000);
        }
    }

    closeSuccessModal() {
        const modal = document.querySelector('.modal');
        if (modal) {
            modal.style.animation = 'fadeOut 0.3s ease';
            setTimeout(() => {
                modal.remove();
                document.body.style.overflow = '';
            }, 300);
        }
        
        // Redirect to home page after a delay
        setTimeout(() => {
            window.location.href = 'index.php';
        }, 500);
    }

    showLoading(show) {
        const spinner = document.getElementById('loadingSpinner');
        if (show) {
            spinner.classList.add('active');
        } else {
            spinner.classList.remove('active');
        }
    }

    showNotification(message, type = 'info') {
        const existingNotifications = document.querySelectorAll('.notification');
        existingNotifications.forEach(notification => notification.remove());
        
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas fa-${this.getNotificationIcon(type)}"></i>
                <span>${message}</span>
            </div>
            <button class="notification-close" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 5000);
    }

    getNotificationIcon(type) {
        const icons = {
            success: 'check-circle',
            error: 'exclamation-circle',
            warning: 'exclamation-triangle',
            info: 'info-circle'
        };
        return icons[type] || 'info-circle';
    }
}

// Initialize booking system
const bookingSystem = new BookingSystem();

// Global functions for HTML onclick
window.nextToStep2 = () => bookingSystem.nextToStep2();
window.nextToStep3 = () => bookingSystem.nextToStep3();
window.backToStep1 = () => bookingSystem.backToStep1();
window.backToStep2 = () => bookingSystem.backToStep2();
window.updateQuantity = (serviceId, change) => bookingSystem.updateQuantity(serviceId, change);
window.submitOrder = () => bookingSystem.submitOrder();
window.closeSuccessModal = () => bookingSystem.closeSuccessModal();

// Add shake animation for invalid fields
const style = document.createElement('style');
style.textContent = `
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
`;
document.head.appendChild(style);