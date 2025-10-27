// Admin Panel JavaScript
const adminUI = {
    // State management
    deleteTarget: { type: '', id: '' },
    currentSection: 'dashboard',

    // Initialize the admin panel
    init() {
        this.setupNavigation();
        this.setupFormHandlers();
        this.setupDeleteHandler();
        this.showInitialSection();
    },

    // Navigation between sections
    setupNavigation() {
        document.querySelectorAll('.nav-item').forEach(link => {
            link.addEventListener('click', e => {
                e.preventDefault();
                const sectionId = link.getAttribute('href').replace('#', '');
                this.showSection(sectionId);
                history.pushState(null, '', '#' + sectionId);
            });
        });

        // Handle browser back/forward buttons
        window.addEventListener('popstate', () => {
            const sectionId = window.location.hash.replace('#', '') || 'dashboard';
            this.showSection(sectionId);
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
            sectionId.charAt(0).toUpperCase() + sectionId.slice(1);

        // Update active nav item
        document.querySelectorAll('.nav-item').forEach(link => {
            link.classList.remove('active');
        });
        
        const activeLink = document.querySelector(`.nav-item[href="#${sectionId}"]`);
        if (activeLink) {
            activeLink.classList.add('active');
        }
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
                    alert('Data tidak ditemukan');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengambil data');
            });
    },

    // HARGA CRUD
    openHargaModal(edit = false, data = {}) {
        document.getElementById('modalHargaTitle').textContent = 
            edit ? 'Edit Paket Harga' : 'Tambah Paket Harga';
        document.getElementById('harga_id').value = data.id || '';
        document.getElementById('jenis_layanan').value = data.jenis_layanan || '';
        document.getElementById('kategori').value = data.kategori || '';
        document.getElementById('harga').value = data.harga || '';
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
        fetch(`php/${type}_hapus.php?id=${id}`)
            .then(r => r.text())
            .then(() => {
                this.closeModal();
                location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus data');
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

        // Galeri form
        document.getElementById('formGaleri').addEventListener('submit', async e => {
            e.preventDefault();
            await this.submitForm('formGaleri', 'php/galeri_simpan.php');
        });
    },

    async submitForm(formId, actionUrl) {
        const form = document.getElementById(formId);
        const formData = new FormData(form);
        
        try {
            const response = await fetch(actionUrl, {
                method: 'POST',
                body: formData
            });
            
            const result = await response.text();
            alert(result);
            
            this.closeModal();
            location.reload();
            
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyimpan data');
        }
    },

    // Logout function
    logout() {
        if (confirm('Yakin ingin logout?')) {
            location.href = 'logout.php';
        }
    }
};

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