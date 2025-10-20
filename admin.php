<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin - deLondree</title>
  <link rel="stylesheet" href="admin.css">
</head>
<body>
  <div class="admin-container">

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <main class="main-content">
      <div class="topbar">
        <h2 id="pageTitle">Dashboard</h2>
        <div class="admin-info"><span>Admin</span></div>
      </div>

      <!-- DASHBOARD -->
      <section id="section-dashboard" class="content-section active">
        <div class="stats-grid">
          <div class="stat-card">
            <div class="stat-icon blue"></div>
            <div class="stat-info">
              <h3>
                <?php $q = $conn->query("SELECT COUNT(*) AS total FROM layanan");
                echo $q->fetch_assoc()['total']; ?>
              </h3>
              <p>Total Layanan</p>
            </div>
          </div>
          <div class="stat-card">
            <div class="stat-icon green"></div>
            <div class="stat-info">
              <h3>
                <?php $q = $conn->query("SELECT COUNT(*) AS total FROM harga");
                echo $q->fetch_assoc()['total']; ?>
              </h3>
              <p>Paket Harga</p>
            </div>
          </div>
          <div class="stat-card">
            <div class="stat-icon purple"></div>
            <div class="stat-info">
              <h3>
                <?php $q = $conn->query("SELECT COUNT(*) AS total FROM galeri");
                echo $q->fetch_assoc()['total']; ?>
              </h3>
              <p>Foto Galeri</p>
            </div>
          </div>
        </div>

        <div class="welcome-card">
          <h3>Selamat Datang di Admin Panel deLondree!</h3>
          <p>Kelola semua data laundry Anda dengan mudah melalui panel admin ini.</p>
        </div>
      </section>

      <!-- LAYANAN -->
      <section id="section-layanan" class="content-section">
        <div class="section-header">
          <h3>Manajemen Layanan</h3>
          <button class="btn-primary" onclick="openLayananModal()">Tambah Layanan</button>
        </div>

        <div class="table-container">
          <table class="data-table">
            <thead>
              <tr>
                <th>No</th><th>Nama</th><th>Deskripsi</th><th>Harga</th><th>Foto</th><th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              $result = $conn->query("SELECT * FROM layanan");
              while ($row = $result->fetch_assoc()) {
                echo "<tr>
                  <td>{$no}</td>
                  <td>{$row['nama_layanan']}</td>
                  <td>{$row['deskripsi']}</td>
                  <td>Rp " . number_format($row['harga_mulai']) . "</td>
                  <td><img src='uploads/{$row['foto']}' width='60'></td>
                  <td>
                    <button class='btn-secondary' onclick='editLayanan({$row['id']})'>Edit</button>
                    <button class='btn-danger' onclick='confirmDelete(\"layanan\", {$row['id']})'>Hapus</button>
                  </td>
                </tr>";
                $no++;
              }
              ?>
            </tbody>
          </table>
        </div>
      </section>

      <!-- HARGA -->
      <section id="section-harga" class="content-section">
        <div class="section-header">
          <h3>Manajemen Harga</h3>
          <button class="btn-primary" onclick="openHargaModal()">Tambah Paket Harga</button>
        </div>

        <div class="table-container">
          <table class="data-table">
            <thead>
              <tr>
                <th>No</th><th>Jenis</th><th>Kategori</th><th>Harga</th><th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              $result = $conn->query("SELECT * FROM harga");
              while ($row = $result->fetch_assoc()) {
                echo "<tr>
                  <td>{$no}</td>
                  <td>{$row['jenis_layanan']}</td>
                  <td>{$row['kategori']}</td>
                  <td>Rp " . number_format($row['harga']) . "</td>
                  <td>
                    <button class='btn-secondary' onclick='openHargaModal(true, {
                      id: {$row['id']},
                      jenis_layanan: \"{$row['jenis_layanan']}\",
                      kategori: \"{$row['kategori']}\",
                      harga: {$row['harga']}
                    })'>Edit</button>
                    <button class='btn-danger' onclick='confirmDelete(\"harga\", {$row['id']})'>Hapus</button>
                  </td>
                </tr>";
                $no++;
              }
              ?>
            </tbody>
          </table>
        </div>
      </section>

      <!-- GALERI -->
      <section id="section-galeri" class="content-section">
        <div class="section-header">
          <h3>Manajemen Galeri</h3>
          <button class="btn-primary" onclick="openGaleriModal()">Upload Foto</button>
        </div>

        <div class="gallery-grid">
          <?php
          $result = $conn->query("SELECT * FROM galeri");
          while ($row = $result->fetch_assoc()) {
            echo "
            <div class='gallery-item'>
              <img src='uploads/{$row['foto']}' alt='{$row['judul']}'>
              <p>{$row['judul']}</p>
              <div style='padding:0 1rem 1rem;'>
                <button class='btn-secondary' onclick='openGaleriModal(true, {
                  id: {$row['id']},
                  judul: \"{$row['judul']}\"
                })'>Edit</button>
                <button class='btn-danger' onclick='confirmDelete(\"galeri\", {$row['id']})'>Hapus</button>
              </div>
            </div>
            ";
          }
          ?>
        </div>
      </section>

      <!-- PROFIL -->
      <section id="section-profil" class="content-section">
        <div class="section-header"><h3>Profil Laundry</h3></div>
        <form action="php/profil_simpan.php" method="POST">
          <label>Nama Laundry</label><input type="text" name="nama_laundry" required>
          <label>Alamat Lengkap</label><textarea name="alamat" rows="3" required></textarea>
          <label>Nomor WhatsApp</label><input type="text" name="whatsapp" required>
          <label>Email</label><input type="email" name="email">
          <label>Jam Buka (Senin–Sabtu)</label><input type="text" name="jam_senin" required>
          <label>Jam Buka (Minggu)</label><input type="text" name="jam_minggu" required>
          <button type="submit" class="btn-primary">Simpan</button>
        </form>
      </section>

      <!-- PASSWORD -->
      <section id="section-password" class="content-section">
        <div class="section-header"><h3>Ganti Password</h3></div>
        <form action="php/ganti_password.php" method="POST" style="max-width:500px;">
          <label>Password Lama</label><input type="password" name="old_password" required>
          <label>Password Baru</label><input type="password" name="new_password" required>
          <button type="submit" class="btn-primary">Ganti Password</button>
        </form>
      </section>
    </main>
  </div>

  <!-- === MODAL LAYANAN === -->
  <div id="modalLayanan" class="modal">
    <div class="modal-content">
      <h3 id="modalLayananTitle">Tambah Layanan</h3>
      <form id="formLayanan" enctype="multipart/form-data">
        <input type="hidden" name="id" id="layanan_id">
        <label>Nama Layanan</label><input type="text" name="nama_layanan" id="nama_layanan" required>
        <label>Deskripsi</label><textarea name="deskripsi" id="deskripsi" required></textarea>
        <label>Harga Mulai</label><input type="number" name="harga_mulai" id="harga_mulai" required>
        <label>Foto</label><input type="file" name="foto" id="foto">
        <div class="modal-actions">
          <button type="submit" class="btn-primary">Simpan</button>
          <button type="button" class="btn-secondary" onclick="closeModal()">Batal</button>
        </div>
      </form>
    </div>
  </div>

  <!-- === MODAL HARGA === -->
  <div id="modalHarga" class="modal">
    <div class="modal-content">
      <h3 id="modalHargaTitle">Tambah Paket Harga</h3>
      <form id="formHarga">
        <input type="hidden" name="id" id="harga_id">
        <label>Jenis Layanan</label><input type="text" name="jenis_layanan" id="jenis_layanan" required>
        <label>Kategori</label><input type="text" name="kategori" id="kategori" required>
        <label>Harga (Rp)</label><input type="number" name="harga" id="harga" required>
        <div class="modal-actions">
          <button type="submit" class="btn-primary">Simpan</button>
          <button type="button" class="btn-secondary" onclick="closeModal()">Batal</button>
        </div>
      </form>
    </div>
  </div>

  <!-- === MODAL GALERI === -->
  <div id="modalGaleri" class="modal">
    <div class="modal-content">
      <h3 id="modalGaleriTitle">Upload Foto Galeri</h3>
      <form id="formGaleri" enctype="multipart/form-data">
        <input type="hidden" name="id" id="galeri_id">
        <label>Judul Foto</label><input type="text" name="judul" id="judul" required>
        <label>Foto</label><input type="file" name="foto" id="foto_galeri">
        <div class="modal-actions">
          <button type="submit" class="btn-primary">Simpan</button>
          <button type="button" class="btn-secondary" onclick="closeModal()">Batal</button>
        </div>
      </form>
    </div>
  </div>

  <!-- === MODAL KONFIRMASI === -->
  <div id="modalConfirm" class="modal">
    <div class="modal-content small">
      <h3>Yakin ingin menghapus data ini?</h3>
      <div class="modal-actions">
        <button id="btnConfirmDelete" class="btn-danger">Hapus</button>
        <button class="btn-secondary" onclick="closeModal()">Batal</button>
      </div>
    </div>
  </div>

  <script>
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

  function closeModal() { document.querySelectorAll('.modal').forEach(m => m.classList.remove('active')); }

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
  function confirmDelete(type, id) { deleteTarget = { type, id }; document.getElementById('modalConfirm').classList.add('active'); }
  document.getElementById('btnConfirmDelete').addEventListener('click', () => {
    fetch(`php/${deleteTarget.type}_hapus.php?id=${deleteTarget.id}`)
      .then(r => r.text()).then(() => { closeModal(); location.reload(); });
  });

  function logout() { if (confirm('Yakin ingin logout?')) location.href = 'logout.php'; }
  </script>
</body>
</html>
