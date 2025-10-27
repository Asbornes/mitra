<?php 
session_start();
if (!isset($_SESSION['adminLoggedIn'])) {
    header('Location: login.php');
    exit;
}
include 'koneksi.php'; 
?>
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

    <!-- Sidebar (digabung dari sidebar.php) -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <img src="hero.jpg"
                 alt="Logo" class="sidebar-logo">
            <h3>Admin Panel</h3>
        </div>

        <nav class="sidebar-nav">
            <a href="#dashboard" class="nav-item active">
                Dashboard
            </a>
            <a href="#layanan" class="nav-item">
                Layanan
            </a>
            <a href="#harga" class="nav-item">
                Harga
            </a>
            <a href="#galeri" class="nav-item">
                Galeri
            </a>
            <a href="#profil" class="nav-item">
                Profil Laundry
            </a>
            <a href="#password" class="nav-item">
                Ganti Password
            </a>
        </nav>

        <div class="sidebar-footer">
            <button onclick="adminUI.logout()" class="btn-logout">
                Logout
            </button>
        </div>
    </aside>

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
          <button class="btn-primary" onclick="adminUI.openLayananModal()">Tambah Layanan</button>
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
                    <button class='btn-secondary' onclick='adminUI.editLayanan({$row['id']})'>Edit</button>
                    <button class='btn-danger' onclick='adminUI.confirmDelete(\"layanan\", {$row['id']})'>Hapus</button>
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
          <button class="btn-primary" onclick="adminUI.openHargaModal()">Tambah Paket Harga</button>
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
                    <button class='btn-secondary' onclick='adminUI.openHargaModal(true, {
                      id: {$row['id']},
                      jenis_layanan: \"{$row['jenis_layanan']}\",
                      kategori: \"{$row['kategori']}\",
                      harga: {$row['harga']}
                    })'>Edit</button>
                    <button class='btn-danger' onclick='adminUI.confirmDelete(\"harga\", {$row['id']})'>Hapus</button>
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
          <button class="btn-primary" onclick="adminUI.openGaleriModal()">Upload Foto</button>
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
                <button class='btn-secondary' onclick='adminUI.openGaleriModal(true, {
                  id: {$row['id']},
                  judul: \"{$row['judul']}\"
                })'>Edit</button>
                <button class='btn-danger' onclick='adminUI.confirmDelete(\"galeri\", {$row['id']})'>Hapus</button>
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
          <?php
          $profil = $conn->query("SELECT * FROM profil LIMIT 1");
          $data = $profil->num_rows > 0 ? $profil->fetch_assoc() : [];
          ?>
          <label>Nama Laundry</label>
          <input type="text" name="nama_laundry" value="<?= $data['nama_laundry'] ?? '' ?>" required>
          
          <label>Alamat Lengkap</label>
          <textarea name="alamat" rows="3" required><?= $data['alamat'] ?? '' ?></textarea>
          
          <label>Nomor WhatsApp</label>
          <input type="text" name="whatsapp" value="<?= $data['whatsapp'] ?? '' ?>" required>
          
          <label>Email</label>
          <input type="email" name="email" value="<?= $data['email'] ?? '' ?>">
          
          <label>Jam Buka (Senin–Sabtu)</label>
          <input type="text" name="jam_senin" value="<?= $data['jam_senin'] ?? '' ?>" required>
          
          <label>Jam Buka (Minggu)</label>
          <input type="text" name="jam_minggu" value="<?= $data['jam_minggu'] ?? '' ?>" required>
          
          <button type="submit" class="btn-primary">Simpan</button>
        </form>
      </section>

      <!-- PASSWORD -->
      <section id="section-password" class="content-section">
        <div class="section-header"><h3>Ganti Password</h3></div>
        <form action="php/ganti_password.php" method="POST" style="max-width:500px;">
          <label>Password Lama</label><input type="password" name="old_password" required>
          <label>Password Baru</label><input type="password" name="new_password" required>
          <label>Konfirmasi Password Baru</label><input type="password" name="confirm_password" required>
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
          <button type="button" class="btn-secondary" onclick="adminUI.closeModal()">Batal</button>
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
          <button type="button" class="btn-secondary" onclick="adminUI.closeModal()">Batal</button>
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
          <button type="button" class="btn-secondary" onclick="adminUI.closeModal()">Batal</button>
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
        <button class="btn-secondary" onclick="adminUI.closeModal()">Batal</button>
      </div>
    </div>
  </div>

  <script src="admin.js"></script>
</body>
</html>