// Navigasi antar section
function showSection(sectionId) {
  document.querySelectorAll('.content-section').forEach(sec => sec.classList.remove('active'));
  const target = document.getElementById('section-' + sectionId);
  if (target) target.classList.add('active');
  document.getElementById('pageTitle').textContent = sectionId.charAt(0).toUpperCase() + sectionId.slice(1);
  document.querySelectorAll('.nav-item').forEach(link => link.classList.remove('active'));
  const activeLink = document.querySelector(`.nav-item[href="#${sectionId}"]`);
  if (activeLink) activeLink.classList.add('active');
}

document.addEventListener('DOMContentLoaded', () => {
  const initial = window.location.hash.replace('#', '') || 'dashboard';
  showSection(initial);
  document.querySelectorAll('.nav-item').forEach(link => {
    link.addEventListener('click', e => {
      e.preventDefault();
      const sectionId = link.getAttribute('href').replace('#', '');
      showSection(sectionId);
      history.pushState(null, '', '#' + sectionId);
    });
  });
});

function closeModal() { 
  document.querySelectorAll('.modal').forEach(m => m.classList.remove('active')); 
}

// === CRUD LAYANAN ===
function openLayananModal() {
  document.getElementById('modalLayananTitle').textContent = 'Tambah Layanan';
  document.getElementById('formLayanan').reset();
  document.getElementById('modalLayanan').classList.add('active');
}

function editLayanan(id) {
  fetch(`php/layanan_get.php?id=${id}`).then(r => r.json()).then(d => {
    document.getElementById('modalLayananTitle').textContent = 'Edit Layanan';
    document.getElementById('layanan_id').value = d.id;
    document.getElementById('nama_layanan').value = d.nama_layanan;
    document.getElementById('deskripsi').value = d.deskripsi;
    document.getElementById('harga_mulai').value = d.harga_mulai;
    document.getElementById('modalLayanan').classList.add('active');
  });
}

document.getElementById('formLayanan').addEventListener('submit', e => {
  e.preventDefault();
  const fd = new FormData(e.target);
  fetch('php/layanan_simpan.php', { method: 'POST', body: fd })
    .then(r => r.text()).then(msg => { alert(msg); closeModal(); location.reload(); });
});

// === CRUD HARGA ===
function openHargaModal(edit = false, d = {}) {
  document.getElementById('modalHargaTitle').textContent = edit ? 'Edit Paket Harga' : 'Tambah Paket Harga';
  document.getElementById('harga_id').value = d.id || '';
  document.getElementById('jenis_layanan').value = d.jenis_layanan || '';
  document.getElementById('kategori').value = d.kategori || '';
  document.getElementById('harga').value = d.harga || '';
  document.getElementById('modalHarga').classList.add('active');
}

document.getElementById('formHarga').addEventListener('submit', async e => {
  e.preventDefault();
  const fd = new FormData(e.target);
  const res = await fetch('php/harga_simpan.php', { method: 'POST', body: fd });
  const txt = await res.text();
  alert(txt); closeModal(); location.hash = '#harga'; location.reload();
});

// === CRUD GALERI ===
function openGaleriModal(edit = false, d = {}) {
  document.getElementById('modalGaleriTitle').textContent = edit ? 'Edit Foto Galeri' : 'Upload Foto Baru';
  document.getElementById('galeri_id').value = d.id || '';
  document.getElementById('judul').value = d.judul || '';
  document.getElementById('modalGaleri').classList.add('active');
}

document.getElementById('formGaleri').addEventListener('submit', async e => {
  e.preventDefault();
  const fd = new FormData(e.target);
  const res = await fetch('php/galeri_simpan.php', { method: 'POST', body: fd });
  const txt = await res.text();
  alert(txt); closeModal(); location.hash = '#galeri'; location.reload();
});

// === KONFIRMASI HAPUS ===
let deleteTarget = { type: '', id: '' };

function confirmDelete(type, id) { 
  deleteTarget = { type, id }; 
  document.getElementById('modalConfirm').classList.add('active'); 
}

document.getElementById('btnConfirmDelete').addEventListener('click', () => {
  fetch(`php/${deleteTarget.type}_hapus.php?id=${deleteTarget.id}`)
    .then(r => r.text()).then(() => { closeModal(); location.reload(); });
});

function logout() { 
  if (confirm('Yakin ingin logout?')) location.href = 'logout.php'; 
}