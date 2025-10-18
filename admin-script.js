// Check if admin is logged in
if (!localStorage.getItem('adminLoggedIn')) {
    window.location.href = 'admin-login.html';
}

// Initialize data
function initializeData() {
    if (!localStorage.getItem('layananData')) {
        localStorage.setItem('layananData', JSON.stringify([
            {
                id: 1,
                nama: 'Cuci Kering Setrika',
                deskripsi: 'Layanan lengkap mencuci, mengeringkan, dan menyetrika',
                harga: 7000,
                foto: ''
            },
            {
                id: 2,
                nama: 'Cuci Kering',
                deskripsi: 'Pakaian dicuci bersih dan dikeringkan',
                harga: 5000,
                foto: ''
            }
        ]));
    }

    if (!localStorage.getItem('hargaData')) {
        localStorage.setItem('hargaData', JSON.stringify([
            { id: 1, jenis: 'Cuci Kering Setrika', kategori: 'Per Kg', harga: 7000 },
            { id: 2, jenis: 'Cuci Kering Setrika', kategori: 'Per Load', harga: 35000 },
            { id: 3, jenis: 'Cuci Kering', kategori: 'Per Kg', harga: 5000 }
        ]));
    }

    if (!localStorage.getItem('galeriData')) {
        localStorage.setItem('galeriData', JSON.stringify([]));
    }

    if (!localStorage.getItem('profilData')) {
        localStorage.setItem('profilData', JSON.stringify({
            nama: 'deLondree',
            alamat: '2G93+4QF, Kadia, Kec. Kadia, Kota Kendari, Sulawesi Tenggara 93115',
            whatsapp: '6281234567890',
            email: 'info@delondree.com',
            jamBukaSenin: '08.00 - 20.00',
            jamBukaMinggu: '09.00 - 17.00'
        }));
    }
}

initializeData();
loadDashboardStats();
loadLayananTable();
loadHargaTable();
loadGaleriGrid();
loadProfilForm();

// Navigation
function showSection(sectionName) {
    // Hide all sections
    document.querySelectorAll('.content-section').forEach(section => {
        section.classList.remove('active');
    });

    // Show selected section
    document.getElementById('section-' + sectionName).classList.add('active');

    // Update navigation
    document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('active');
    });
    event.target.closest('.nav-item').classList.add('active');

    // Update page title
    const titles = {
        'dashboard': 'Dashboard',
        'layanan': 'Manajemen Layanan',
        'harga': 'Manajemen Harga',
        'galeri': 'Manajemen Galeri',
        'profil': 'Profil Laundry',
        'password': 'Ganti Password'
    };
    document.getElementById('pageTitle').textContent = titles[sectionName];
}

// Dashboard Stats
function loadDashboardStats() {
    const layanan = JSON.parse(localStorage.getItem('layananData'));
    const harga = JSON.parse(localStorage.getItem('hargaData'));
    const galeri = JSON.parse(localStorage.getItem('galeriData'));

    document.getElementById('totalLayanan').textContent = layanan.length;
    document.getElementById('totalHarga').textContent = harga.length;
    document.getElementById('totalGaleri').textContent = galeri.length;
}

// Layanan Management
function loadLayananTable() {
    const data = JSON.parse(localStorage.getItem('layananData'));
    const tbody = document.getElementById('layananTableBody');
    
    if (data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 2rem;">Belum ada data layanan</td></tr>';
        return;
    }

    tbody.innerHTML = data.map((item, index) => `
        <tr>
            <td>${index + 1}</td>
            <td>${item.nama}</td>
            <td>${item.deskripsi}</td>
            <td>Rp ${item.harga.toLocaleString('id-ID')}</td>
            <td>${item.foto ? '<img src="' + item.foto + '" alt="' + item.nama + '">' : '-'}</td>
            <td>
                <button onclick="editLayanan(${item.id})" class="btn-success">Edit</button>
                <button onclick="deleteLayanan(${item.id})" class="btn-danger">Hapus</button>
            </td>
        </tr>
    `).join('');
}

function openLayananModal(id = null) {
    const modal = document.getElementById('layananModal');
    const form = document.getElementById('layananForm');
    
    form.reset();
    
    if (id) {
        const data = JSON.parse(localStorage.getItem('layananData'));
        const item = data.find(l => l.id === id);
        
        document.getElementById('layananModalTitle').textContent = 'Edit Layanan';
        document.getElementById('layananId').value = item.id;
        document.getElementById('layananNama').value = item.nama;
        document.getElementById('layananDeskripsi').value = item.deskripsi;
        document.getElementById('layananHarga').value = item.harga;
        document.getElementById('layananFoto').value = item.foto;
    } else {
        document.getElementById('layananModalTitle').textContent = 'Tambah Layanan';
    }
    
    modal.classList.add('active');
}

function closeLayananModal() {
    document.getElementById('layananModal').classList.remove('active');
}

function editLayanan(id) {
    openLayananModal(id);
}

function deleteLayanan(id) {
    if (confirm('Yakin ingin menghapus layanan ini?')) {
        let data = JSON.parse(localStorage.getItem('layananData'));
        data = data.filter(item => item.id !== id);
        localStorage.setItem('layananData', JSON.stringify(data));
        loadLayananTable();
        loadDashboardStats();
    }
}

document.getElementById('layananForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const id = document.getElementById('layananId').value;
    const data = JSON.parse(localStorage.getItem('layananData'));

    // ambil input file foto
    const fotoInput = document.getElementById('layananFoto');
    let foto = '';

    // kalau ada file yang diupload, buat URL lokal (blob)
    if (fotoInput.files.length > 0) {
        foto = URL.createObjectURL(fotoInput.files[0]);
    }

    const layanan = {
        id: id ? parseInt(id) : Date.now(),
        nama: document.getElementById('layananNama').value,
        deskripsi: document.getElementById('layananDeskripsi').value,
        harga: parseInt(document.getElementById('layananHarga').value),
        foto: foto
    };
    
    if (id) {
        const index = data.findIndex(item => item.id === parseInt(id));
        data[index] = layanan;
    } else {
        data.push(layanan);
    }
    
    localStorage.setItem('layananData', JSON.stringify(data));
    closeLayananModal();
    loadLayananTable();
    loadDashboardStats();
});

// Harga Management
function loadHargaTable() {
    const data = JSON.parse(localStorage.getItem('hargaData'));
    const tbody = document.getElementById('hargaTableBody');
    
    if (data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 2rem;">Belum ada data harga</td></tr>';
        return;
    }

    tbody.innerHTML = data.map((item, index) => `
        <tr>
            <td>${index + 1}</td>
            <td>${item.jenis}</td>
            <td>${item.kategori}</td>
            <td>Rp ${item.harga.toLocaleString('id-ID')}</td>
            <td>
                <button onclick="editHarga(${item.id})" class="btn-success">Edit</button>
                <button onclick="deleteHarga(${item.id})" class="btn-danger">Hapus</button>
            </td>
        </tr>
    `).join('');
}

function openHargaModal(id = null) {
    const modal = document.getElementById('hargaModal');
    const form = document.getElementById('hargaForm');
    
    form.reset();
    
    if (id) {
        const data = JSON.parse(localStorage.getItem('hargaData'));
        const item = data.find(h => h.id === id);
        
        document.getElementById('hargaModalTitle').textContent = 'Edit Paket Harga';
        document.getElementById('hargaId').value = item.id;
        document.getElementById('hargaJenis').value = item.jenis;
        document.getElementById('hargaKategori').value = item.kategori;
        document.getElementById('hargaNilai').value = item.harga;
    } else {
        document.getElementById('hargaModalTitle').textContent = 'Tambah Paket Harga';
    }
    
    modal.classList.add('active');
}

function closeHargaModal() {
    document.getElementById('hargaModal').classList.remove('active');
}

function editHarga(id) {
    openHargaModal(id);
}

function deleteHarga(id) {
    if (confirm('Yakin ingin menghapus paket harga ini?')) {
        let data = JSON.parse(localStorage.getItem('hargaData'));
        data = data.filter(item => item.id !== id);
        localStorage.setItem('hargaData', JSON.stringify(data));
        loadHargaTable();
        loadDashboardStats();
    }
}

document.getElementById('hargaForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const id = document.getElementById('hargaId').value;
    const data = JSON.parse(localStorage.getItem('hargaData'));
    
    const harga = {
        id: id ? parseInt(id) : Date.now(),
        jenis: document.getElementById('hargaJenis').value,
        kategori: document.getElementById('hargaKategori').value,
        harga: parseInt(document.getElementById('hargaNilai').value)
    };
    
    if (id) {
        const index = data.findIndex(item => item.id === parseInt(id));
        data[index] = harga;
    } else {
        data.push(harga);
    }
    
    localStorage.setItem('hargaData', JSON.stringify(data));
    closeHargaModal();
    loadHargaTable();
    loadDashboardStats();
});

// Galeri Management
function loadGaleriGrid() {
    const data = JSON.parse(localStorage.getItem('galeriData'));
    const grid = document.getElementById('galeriGrid');
    
    if (data.length === 0) {
        grid.innerHTML = '<p style="text-align: center; padding: 2rem; color: var(--gray-600);">Belum ada foto di galeri</p>';
        return;
    }

    grid.innerHTML = data.map(item => `
        <div class="gallery-item">
            <img src="${item.foto}" alt="${item.judul}">
            <div class="gallery-info">
                <h4>${item.judul}</h4>
                <div class="gallery-actions">
                    <button onclick="deleteGaleri(${item.id})" class="btn-danger">Hapus</button>
                </div>
            </div>
        </div>
    `).join('');
}

function openGaleriModal() {
    document.getElementById('galeriModal').classList.add('active');
    document.getElementById('galeriForm').reset();
}

function closeGaleriModal() {
    document.getElementById('galeriModal').classList.remove('active');
}

function deleteGaleri(id) {
    if (confirm('Yakin ingin menghapus foto ini?')) {
        let data = JSON.parse(localStorage.getItem('galeriData'));
        data = data.filter(item => item.id !== id);
        localStorage.setItem('galeriData', JSON.stringify(data));
        loadGaleriGrid();
        loadDashboardStats();
    }
}

document.getElementById('galeriForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const data = JSON.parse(localStorage.getItem('galeriData'));
    
    // ambil input file dari form
    const fotoInput = document.getElementById('galeriFoto');
    let foto = '';

    // kalau user upload file, buat URL blob-nya
    if (fotoInput.files.length > 0) {
        foto = URL.createObjectURL(fotoInput.files[0]);
    }

    const galeri = {
        id: Date.now(),
        judul: document.getElementById('galeriJudul').value,
        foto: foto
    };
    
    data.push(galeri);
    localStorage.setItem('galeriData', JSON.stringify(data));
    closeGaleriModal();
    loadGaleriGrid();
    loadDashboardStats();
});


// Profil Management
function loadProfilForm() {
    const data = JSON.parse(localStorage.getItem('profilData'));
    
    document.getElementById('namaLaundry').value = data.nama;
    document.getElementById('alamatLaundry').value = data.alamat;
    document.getElementById('whatsappLaundry').value = data.whatsapp;
    document.getElementById('emailLaundry').value = data.email;
    document.getElementById('jamBukaSenin').value = data.jamBukaSenin;
    document.getElementById('jamBukaMinggu').value = data.jamBukaMinggu;
}

document.getElementById('profilForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const profil = {
        nama: document.getElementById('namaLaundry').value,
        alamat: document.getElementById('alamatLaundry').value,
        whatsapp: document.getElementById('whatsappLaundry').value,
        email: document.getElementById('emailLaundry').value,
        jamBukaSenin: document.getElementById('jamBukaSenin').value,
        jamBukaMinggu: document.getElementById('jamBukaMinggu').value
    };
    
    localStorage.setItem('profilData', JSON.stringify(profil));
    alert('Profil berhasil disimpan!');
});

// Password Management
document.getElementById('passwordForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const oldPassword = document.getElementById('oldPassword').value;
    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    const adminData = JSON.parse(localStorage.getItem('adminData'));
    const alertBox = document.getElementById('passwordAlert');
    
    if (oldPassword !== adminData.password) {
        alertBox.textContent = 'Password lama salah!';
        alertBox.className = 'alert alert-error';
        alertBox.style.display = 'block';
        return;
    }
    
    if (newPassword !== confirmPassword) {
        alertBox.textContent = 'Password baru tidak cocok!';
        alertBox.className = 'alert alert-error';
        alertBox.style.display = 'block';
        return;
    }
    
    if (newPassword.length < 6) {
        alertBox.textContent = 'Password minimal 6 karakter!';
        alertBox.className = 'alert alert-error';
        alertBox.style.display = 'block';
        return;
    }
    
    adminData.password = newPassword;
    localStorage.setItem('adminData', JSON.stringify(adminData));
    
    alertBox.textContent = 'Password berhasil diubah!';
    alertBox.className = 'alert alert-success';
    alertBox.style.display = 'block';
    
    document.getElementById('passwordForm').reset();
    
    setTimeout(() => {
        alertBox.style.display = 'none';
    }, 3000);
});

// Logout
function logout() {
    if (confirm('Yakin ingin logout?')) {
        localStorage.removeItem('adminLoggedIn');
        window.location.href = 'login.html';
    }
}

// Close modals when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.classList.remove('active');
    }
}