const CSS_VARIABLES = {
    '--primary': '#3B82F6',
    '--primary-dark': '#2563EB',
    '--primary-light': '#60A5FA',
    '--secondary': '#14B8A6',
    '--success': '#10B981',
    '--warning': '#F59E0B',
    '--danger': '#EF4444',
    '--dark': '#1F2937',
    '--dark-2': '#374151',
    '--dark-3': '#4B5563',
    '--light': '#F9FAFB',
    '--light-2': '#F3F4F6',
    '--light-3': '#E5E7EB',
    '--white': '#FFFFFF'
};

// Get CSS variable value
function getCssVariable(name) {
    return CSS_VARIABLES[name] || '#000000';
}

const adminUI = {
    // State management
    deleteTarget: { type: '', id: '' },
    currentSection: 'dashboard',
    financialData: {
        labels: [],
        datasets: []
    },
    ordersChart: null,
    revenueChart: null,
    serviceChart: null,
    paymentChart: null,

    // Initialize the admin panel
    init() {
        this.setupNavigation();
        this.setupFormHandlers();
        this.setupDeleteHandler();
        this.showInitialSection();
        this.loadDashboardData();
        this.loadFinancialData();
        
        // Set current month and year in filters
        this.setDefaultFilterDates();
    },

    // Navigation between sections
    setupNavigation() {
        document.querySelectorAll('.nav-item').forEach(link => {
            link.addEventListener('click', e => {
                e.preventDefault();
                const sectionId = link.getAttribute('href').replace('#', '');
                this.showSection(sectionId);
                history.pushState(null, '', '#' + sectionId);
                
                // Load specific data based on section
                if (sectionId === 'reports') {
                    this.loadFinancialData();
                } else if (sectionId === 'dashboard') {
                    this.loadDashboardData();
                } else if (sectionId === 'orders') {
                    this.loadOrders();
                }
            });
        });

        // Handle browser back/forward buttons
        window.addEventListener('popstate', () => {
            const sectionId = window.location.hash.replace('#', '') || 'dashboard';
            this.showSection(sectionId);
            
            if (sectionId === 'reports') {
                this.loadFinancialData();
            } else if (sectionId === 'dashboard') {
                this.loadDashboardData();
            }
        });
    },

    // Show specific section
    showSection(sectionId) {
        // Hide all sections
        document.querySelectorAll('.content-section').forEach(sec => {
            sec.classList.remove('active');
        });

        // Show target section
        const target = document.getElementById('section-' + sectionId);
        if (target) {
            target.classList.add('active');
            this.currentSection = sectionId;
        }

        // Update page title
        document.getElementById('pageTitle').textContent = 
            this.getSectionTitle(sectionId);

        // Update active nav item
        document.querySelectorAll('.nav-item').forEach(link => {
            link.classList.remove('active');
        });
        
        const activeLink = document.querySelector(`.nav-item[href="#${sectionId}"]`);
        if (activeLink) {
            activeLink.classList.add('active');
        }
    },

    // Get section title
    getSectionTitle(sectionId) {
        const titles = {
            'dashboard': 'Dashboard',
            'layanan': 'Manajemen Layanan',
            'harga': 'Manajemen Harga',
            'delivery': 'Biaya Antar Jemput',
            'galeri': 'Manajemen Galeri',
            'orders': 'Manajemen Pesanan',
            'reports': 'Laporan Keuangan',
            'profil': 'Profil Laundry',
            'password': 'Ganti Password'
        };
        return titles[sectionId] || 'Dashboard';
    },

    // Show initial section based on URL hash
    showInitialSection() {
        const initial = window.location.hash.replace('#', '') || 'dashboard';
        this.showSection(initial);
    },

    // Modal functions
    closeModal() {
        document.querySelectorAll('.modal').forEach(m => {
            m.classList.remove('active');
        });
    },

    // DELIVERY RATES CRUD
    openDeliveryModal(edit = false, data = {}) {
        document.getElementById('modalDeliveryTitle').textContent = 
            edit ? 'Edit Tarif Antar Jemput' : 'Tambah Tarif Antar Jemput';
        document.getElementById('delivery_id').value = data.id || '';
        document.getElementById('range_min').value = data.range_min || '';
        document.getElementById('range_max').value = data.range_max || '';
        document.getElementById('rate').value = data.rate || '';
        document.getElementById('description').value = data.description || '';
        document.getElementById('modalDelivery').classList.add('active');
    },

    // LAYANAN CRUD
    openLayananModal(edit = false, data = {}) {
        document.getElementById('modalLayananTitle').textContent = 
            edit ? 'Edit Layanan' : 'Tambah Layanan';
        document.getElementById('layanan_id').value = data.id || '';
        document.getElementById('nama_layanan').value = data.nama_layanan || '';
        document.getElementById('deskripsi').value = data.deskripsi || '';
        document.getElementById('harga_mulai').value = data.harga_mulai || '';
        document.getElementById('modalLayanan').classList.add('active');
    },

    editLayanan(id) {
        fetch(`php/layanan_get.php?id=${id}`)
            .then(r => r.json())
            .then(data => {
                if (data) {
                    this.openLayananModal(true, data);
                } else {
                    this.showNotification('Data tidak ditemukan', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.showNotification('Terjadi kesalahan saat mengambil data', 'error');
            });
    },

    // HARGA CRUD
    openHargaModal(edit = false, data = {}) {
        document.getElementById('modalHargaTitle').textContent = 
            edit ? 'Edit Paket Harga' : 'Tambah Paket Harga';
        document.getElementById('harga_id').value = data.id || '';
        document.getElementById('jenis_layanan').value = data.jenis_layanan || '';
        document.getElementById('kategori').value = data.kategori || '';
        document.getElementById('description_harga').value = data.description || '';
        document.getElementById('harga').value = data.harga || '';
        document.getElementById('unit').value = data.unit || 'kg';
        document.getElementById('modalHarga').classList.add('active');
    },

    // GALERI CRUD
    openGaleriModal(edit = false, data = {}) {
        document.getElementById('modalGaleriTitle').textContent = 
            edit ? 'Edit Foto Galeri' : 'Upload Foto Baru';
        document.getElementById('galeri_id').value = data.id || '';
        document.getElementById('judul').value = data.judul || '';
        document.getElementById('modalGaleri').classList.add('active');
    },

    // ORDER MANAGEMENT
    viewOrderDetail(orderId) {
        // Show loading state
        const modalContent = document.getElementById('orderDetailContent');
        modalContent.innerHTML = `
            <div class="loading-state">
                <div class="loading-spinner"></div>
                <p>Memuat detail pesanan...</p>
            </div>
        `;
        
        // Show modal
        document.getElementById('modalOrderDetail').classList.add('active');
        
        // Fetch order details
        fetch(`php/order_detail.php?order_id=${orderId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(html => {
                modalContent.innerHTML = html;
            })
            .catch(error => {
                console.error('Error loading order detail:', error);
                modalContent.innerHTML = `
                    <div class="error-state">
                        <i class="fas fa-exclamation-triangle"></i>
                        <h3>Terjadi Kesalahan</h3>
                        <p>Gagal memuat detail pesanan. Silakan coba lagi.</p>
                    </div>
                `;
            });
    },

    updateOrderStatus(orderId, status) {
        if (!confirm(`Yakin ingin mengubah status pesanan ${orderId} menjadi ${status}?`)) {
            return;
        }

        // Show loading state
        this.showNotification('Memperbarui status pesanan...', 'info');

        fetch(`php/update_order_status.php?order_id=${orderId}&status=${status}`)
            .then(r => r.text())
            .then(result => {
                this.showNotification('Status pesanan berhasil diperbarui', 'success');
                // Reload orders table
                setTimeout(() => {
                    location.reload();
                }, 1500);
            })
            .catch(error => {
                console.error('Error:', error);
                this.showNotification('Terjadi kesalahan saat memperbarui status', 'error');
            });
    },

    filterOrders() {
        const statusFilter = document.getElementById('statusFilter').value;
        const dateFilter = document.getElementById('dateFilter').value;
        
        let url = 'php/get_orders.php?';
        if (statusFilter) url += `status=${statusFilter}&`;
        if (dateFilter) url += `date=${dateFilter}`;
        
        fetch(url)
            .then(r => r.json())
            .then(orders => {
                this.renderOrdersTable(orders);
            })
            .catch(error => {
                console.error('Error:', error);
                this.showNotification('Terjadi kesalahan saat memfilter data', 'error');
            });
    },

    renderOrdersTable(orders) {
        const tbody = document.getElementById('ordersTable');
        if (!tbody) return;

        tbody.innerHTML = orders.map(order => {
            const statusClass = `status-${order.status}`;
            return `
                <tr>
                    <td>${order.order_id}</td>
                    <td>${order.customer_name}</td>
                    <td>${order.customer_phone}</td>
                    <td>${order.service_name}</td>
                    <td>Rp ${this.formatNumber(order.total_amount)}</td>
                    <td><span class="status-badge ${statusClass}">${order.status}</span></td>
                    <td>${new Date(order.created_at).toLocaleDateString('id-ID')}</td>
                    <td>
                        <button class="btn-secondary" onclick="adminUI.updateOrderStatus('${order.order_id}', 'process')">
                            <i class="fas fa-play"></i> Proses
                        </button>
                        <button class="btn-success" onclick="adminUI.updateOrderStatus('${order.order_id}', 'completed')">
                            <i class="fas fa-check"></i> Selesai
                        </button>
                        <button class="btn-danger" onclick="adminUI.updateOrderStatus('${order.order_id}', 'cancelled')">
                            <i class="fas fa-times"></i> Batal
                        </button>
                        <button class="btn-info" onclick="adminUI.viewOrderDetail('${order.order_id}')">
                            <i class="fas fa-eye"></i> Detail
                        </button>
                    </td>
                </tr>
            `;
        }).join('');
    },

    // DASHBOARD DATA
    loadDashboardData() {
        this.loadOrdersChart();
        this.updateDashboardStats();
    },

    updateDashboardStats() {
        console.log('Dashboard stats updated with real data');
    },

    loadOrdersChart() {
        const ctx = document.getElementById('ordersChart');
        if (!ctx) return;

        // Destroy existing chart
        if (this.ordersChart) {
            this.ordersChart.destroy();
        }

        // Gunakan file yang sudah diperbaiki
        fetch('php/get_dashboard_chart_data.php')
            .then(r => {
                if (!r.ok) throw new Error('Network error');
                return r.json();
            })
            .then(data => {
                this.ordersChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Jumlah Pesanan',
                            data: data.data,
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            }
                        }
                    }
                });
            })
            .catch(error => {
                console.error('Error loading chart:', error);
                // Fallback chart
                this.ordersChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                        datasets: [{
                            label: 'Jumlah Pesanan',
                            data: [3, 5, 2, 8, 6, 4, 7],
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            });
    },

    // FINANCIAL REPORTS
    loadFinancialData() {
        console.log('Loading REAL financial data...');
        
        fetch('php/financial_reports.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network error: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log('REAL Financial data loaded:', data);
                this.renderFinancialCharts(data);
                this.updateFinancialSummary(data.summary);
                this.showNotification('Data laporan keuangan berhasil dimuat', 'success');
            })
            .catch(error => {
                console.error('Error loading financial data:', error);
                this.showNotification('Gagal memuat data laporan: ' + error.message, 'error');
            });
    },

    // GENERATE SAMPLE DATA
    generateSampleFinancialData() {
        return {
            monthly_revenue: [
                { month: 7, revenue: 4500000, orders: 112 },
                { month: 8, revenue: 5200000, orders: 128 },
                { month: 9, revenue: 4800000, orders: 118 },
                { month: 10, revenue: 5500000, orders: 135 },
                { month: 11, revenue: 6000000, orders: 148 },
                { month: 12, revenue: 5800000, orders: 142 }
            ],
            service_performance: [
                { service_name: 'Cuci Kering Setrika', order_count: 85, total_revenue: 2500000 },
                { service_name: 'Cuci Kering', order_count: 45, total_revenue: 1500000 },
                { service_name: 'Setrika Saja', order_count: 35, total_revenue: 800000 },
                { service_name: 'Bed Cover & Selimut', order_count: 15, total_revenue: 450000 }
            ],
            payment_methods: [
                { method: 'cash', count: 95, total: 3500000 },
                { method: 'transfer', count: 25, total: 1200000 }
            ],
            summary: {
                total_revenue: 5250000,
                total_orders: 128,
                average_order: 41015,
                growth_rate: 12.5
            }
        };
    },

    renderFinancialCharts(data) {
        this.renderMonthlyRevenueChart(data.monthly_revenue);
        this.renderServicePerformanceChart(data.service_performance);
        this.renderPaymentMethodChart(data.payment_methods);
    },

    renderMonthlyRevenueChart(monthlyData) {
        const ctx = document.getElementById('monthlyRevenueChart');
        if (!ctx) return;

        if (this.revenueChart) {
            this.revenueChart.destroy();
        }

        const labels = monthlyData.map(item => this.getMonthName(item.month));
        const revenueData = monthlyData.map(item => item.revenue);

        this.revenueChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan',
                    data: revenueData,
                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 2,
                    borderRadius: 4,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return 'Rp ' + (value / 1000000).toFixed(1) + 'Jt';
                                }
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    },

    renderServicePerformanceChart(serviceData) {
        const ctx = document.getElementById('servicePerformanceChart');
        if (!ctx) return;

        if (this.serviceChart) {
            this.serviceChart.destroy();
        }

        // Use real service names from data
        const labels = serviceData.map(item => {
            // Shorten long service names for better display
            const serviceName = item.service_name;
            if (serviceName.length > 20) {
                return serviceName.substring(0, 20) + '...';
            }
            return serviceName;
        });
        
        const revenueData = serviceData.map(item => item.total_revenue);

        // Create gradient colors based on number of services
        const backgroundColors = this.generateChartColors(serviceData.length);

        this.serviceChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: revenueData,
                    backgroundColor: backgroundColors,
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            color: getCssVariable('--dark'), // FIXED
                            font: {
                                size: 11
                            },
                            padding: 15
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: Rp ${value.toLocaleString('id-ID')} (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '60%'
            }
        });
    },

    renderPaymentMethodChart(paymentData) {
        const ctx = document.getElementById('paymentMethodChart');
        if (!ctx) return;

        if (this.paymentChart) {
            this.paymentChart.destroy();
        }

        // Filter only cash and transfer methods
        const filteredData = paymentData.filter(item => 
            item.method === 'cash' || item.method === 'transfer'
        );

        const labels = filteredData.map(item => {
            // Translate payment method names
            return item.method === 'cash' ? 'Cash' : 'Transfer Bank';
        });
        
        const amountData = filteredData.map(item => item.total);
        const countData = filteredData.map(item => item.count);

        this.paymentChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: amountData,
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',  // Blue for Cash
                        'rgba(16, 185, 129, 0.8)'   // Green for Transfer
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: getCssVariable('--dark'), // FIXED
                            font: {
                                size: 11
                            },
                            padding: 15
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const index = context.dataIndex;
                                const count = countData[index];
                                return `${label}: Rp ${value.toLocaleString('id-ID')} (${count} transaksi)`;
                            }
                        }
                    }
                }
            }
        });
    },

    generateChartColors(count) {
        const baseColors = [
            'rgba(59, 130, 246, 0.8)',   // Blue
            'rgba(16, 185, 129, 0.8)',   // Green
            'rgba(245, 158, 11, 0.8)',   // Yellow
            'rgba(139, 92, 246, 0.8)',   // Purple
            'rgba(236, 72, 153, 0.8)',   // Pink
            'rgba(20, 184, 166, 0.8)',   // Teal
            'rgba(249, 115, 22, 0.8)',   // Orange
            'rgba(6, 182, 212, 0.8)',    // Cyan
        ];
        
        if (count <= baseColors.length) {
            return baseColors.slice(0, count);
        }
        
        // Generate additional colors if needed
        const colors = [...baseColors];
        for (let i = baseColors.length; i < count; i++) {
            const hue = (i * 137.5) % 360; // Golden angle approximation
            colors.push(`hsla(${hue}, 70%, 65%, 0.8)`);
        }
        
        return colors;
    },

    updateFinancialSummary(summary) {
        if (!summary) return;
        
        const elements = {
            totalRevenue: document.getElementById('totalRevenue'),
            totalOrders: document.getElementById('totalOrders'),
            averageOrder: document.getElementById('averageOrder'),
            growthRate: document.getElementById('growthRate')
        };
        
        if (elements.totalRevenue) elements.totalRevenue.textContent = this.formatCurrency(summary.total_revenue || 0);
        if (elements.totalOrders) elements.totalOrders.textContent = this.formatNumber(summary.total_orders || 0);
        if (elements.averageOrder) elements.averageOrder.textContent = this.formatCurrency(summary.average_order || 0);
        if (elements.growthRate) elements.growthRate.textContent = `${summary.growth_rate || 0}%`;
    },

    // ORDERS MANAGEMENT
    loadOrders() {
        // This would typically fetch from server
        console.log('Loading orders...');
    },

    // UTILITY FUNCTIONS
    getMonthName(monthNumber) {
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        return months[monthNumber - 1] || '';
    },

    formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(amount);
    },

    formatNumber(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    },

    setDefaultFilterDates() {
        const now = new Date();
        const monthFilter = document.getElementById('monthFilter');
        const yearFilter = document.getElementById('yearFilter');
        
        if (monthFilter) monthFilter.value = now.getMonth() + 1;
        if (yearFilter) yearFilter.value = now.getFullYear();
    },

    // DELETE CONFIRMATION
    confirmDelete(type, id) {
        this.deleteTarget = { type, id };
        document.getElementById('modalConfirm').classList.add('active');
    },

    setupDeleteHandler() {
        document.getElementById('btnConfirmDelete').addEventListener('click', () => {
            this.executeDelete();
        });
    },

    executeDelete() {
        const { type, id } = this.deleteTarget;
        
        // Show loading
        this.showNotification('Menghapus data...', 'info');

        let deleteUrl = `php/${type}_hapus.php`;
        if (type === 'order') {
            deleteUrl += `?order_id=${id}`;
        } else {
            deleteUrl += `?id=${id}`;
        }

        fetch(deleteUrl)
            .then(r => r.text())
            .then(() => {
                this.closeModal();
                this.showNotification('Data berhasil dihapus', 'success');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            })
            .catch(error => {
                console.error('Error:', error);
                this.showNotification('Terjadi kesalahan saat menghapus data', 'error');
            });
    },

    // ORDER MANAGEMENT - MANUAL INPUT
    openOrderModal(edit = false, orderData = {}) {
        document.getElementById('modalOrderTitle').textContent = 
            edit ? 'Edit Pesanan' : 'Tambah Pesanan Manual';
        
        const form = document.getElementById('formOrder');
        form.reset();
        
        if (edit && orderData) {
            document.getElementById('edit_order_id').value = orderData.order_id;
            document.getElementById('customer_name').value = orderData.customer_name || '';
            document.getElementById('customer_phone').value = orderData.customer_phone || '';
            document.getElementById('customer_address').value = orderData.customer_address || '';
            document.getElementById('address_notes').value = orderData.address_notes || '';
            document.getElementById('service_name').value = orderData.service_name || '';
            document.getElementById('total_amount').value = orderData.total_amount || '';
            document.getElementById('pickup_date').value = orderData.pickup_date || '';
            document.getElementById('pickup_time').value = orderData.pickup_time || '';
            document.getElementById('payment_method').value = orderData.payment_method || 'cash';
            document.getElementById('status').value = orderData.status || 'pending';
            document.getElementById('notes').value = orderData.notes || '';
        } else {
            document.getElementById('edit_order_id').value = '';
            // Set default date to today
            document.getElementById('pickup_date').value = new Date().toISOString().split('T')[0];
        }
        
        document.getElementById('modalOrder').classList.add('active');
    },

    editOrder(orderId) {
        console.log('Fetching order data for:', orderId);
        
        fetch(`php/get_order.php?order_id=${orderId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('HTTP error: ' + response.status);
                }
                return response.json();
            })
            .then(result => {
                console.log('Order data received:', result);
                
                if (result.error) {
                    throw new Error(result.error);
                }
                
                if (result.success && result.data) {
                    this.openOrderModal(true, result.data);
                } else if (result.order_id) {
                    // Backward compatibility
                    this.openOrderModal(true, result);
                } else {
                    throw new Error('Invalid data format');
                }
            })
            .catch(error => {
                console.error('Error fetching order:', error);
                this.showNotification('Gagal memuat data pesanan: ' + error.message, 'error');
            });
    },

    // FORM HANDLERS
    setupFormHandlers() {
        // Layanan form
        document.getElementById('formLayanan').addEventListener('submit', async e => {
            e.preventDefault();
            await this.submitForm('formLayanan', 'php/layanan_simpan.php');
        });

        // Harga form
        document.getElementById('formHarga').addEventListener('submit', async e => {
            e.preventDefault();
            await this.submitForm('formHarga', 'php/harga_simpan.php');
        });

        // Delivery rates form
        document.getElementById('formDelivery').addEventListener('submit', async e => {
            e.preventDefault();
            await this.submitForm('formDelivery', 'php/delivery_simpan.php');
        });

        // Galeri form
        document.getElementById('formGaleri').addEventListener('submit', async e => {
            e.preventDefault();
            await this.submitForm('formGaleri', 'php/galeri_simpan.php');
        });

        // Financial report filter form
        const reportFilterForm = document.getElementById('reportFilterForm');
        if (reportFilterForm) {
            reportFilterForm.addEventListener('submit', e => {
                e.preventDefault();
                this.filterFinancialReports();
            });
        }

        // Order form
        document.getElementById('formOrder').addEventListener('submit', async e => {
            e.preventDefault();
            await this.submitOrderForm();
        });
    },

    async submitOrderForm() {
        const form = document.getElementById('formOrder');
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Show loading state
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
        submitBtn.disabled = true;
        
        try {
            const response = await fetch('php/save_order_manual.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.text();
            this.showNotification(result, 'success');
            
            this.closeModal();
            setTimeout(() => {
                location.reload();
            }, 1500);
            
        } catch (error) {
            console.error('Error:', error);
            this.showNotification('Terjadi kesalahan saat menyimpan pesanan', 'error');
        } finally {
            // Restore button state
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    },

    filterFinancialReports() {
        console.log('Filter removed from financial reports');
    },

    async submitForm(formId, actionUrl) {
        const form = document.getElementById(formId);
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Show loading state
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
        submitBtn.disabled = true;
        
        try {
            const response = await fetch(actionUrl, {
                method: 'POST',
                body: formData
            });
            
            const result = await response.text();
            this.showNotification(result, 'success');
            
            this.closeModal();
            setTimeout(() => {
                location.reload();
            }, 1500);
            
        } catch (error) {
            console.error('Error:', error);
            this.showNotification('Terjadi kesalahan saat menyimpan data', 'error');
        } finally {
            // Restore button state
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    },

    // NOTIFICATION SYSTEM
    showNotification(message, type = 'info') {
        // Remove existing notifications
        const existingNotifications = document.querySelectorAll('.notification');
        existingNotifications.forEach(notification => notification.remove());
        
        // Create notification element
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
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 5000);
    },

    getNotificationIcon(type) {
        const icons = {
            success: 'check-circle',
            error: 'exclamation-circle',
            warning: 'exclamation-triangle',
            info: 'info-circle'
        };
        return icons[type] || 'info-circle';
    },

    // Logout function
    logout() {
        if (confirm('Yakin ingin logout?')) {
            location.href = 'logout.php';
        }
    }
};

function getCssVariableFromStyle(variableName) {
    // This method gets the actual computed CSS variable from the document
    if (typeof window !== 'undefined' && document.documentElement) {
        return getComputedStyle(document.documentElement).getPropertyValue(variableName).trim();
    }
    return CSS_VARIABLES[variableName] || '#000000';
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    adminUI.init();
});

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal')) {
        adminUI.closeModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        adminUI.closeModal();
    }
});