<?php 
session_start();

// Cek login
if (!isset($_SESSION['adminLoggedIn'])) {
    header('Location: login.php');
    exit;
}

include 'koneksi.php';

// =====================================================
// HANDLE PROFIL SIMPAN (dari profil_simpan.php)
// =====================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan_profil'])) {
    // Ambil data POST
    $hero_title = $_POST['hero_title'] ?? '';
    $hero_subtitle = $_POST['hero_subtitle'] ?? '';
    $nama_laundry = $_POST['nama_laundry'] ?? '';
    $alamat = $_POST['alamat'] ?? '';
    $whatsapp = $_POST['whatsapp'] ?? '';
    $email = $_POST['email'] ?? '';
    $jam_senin = $_POST['jam_senin'] ?? '';
    $jam_minggu = $_POST['jam_minggu'] ?? '';

    $about_title = $_POST['about_title'] ?? '';
    $about_paragraph1 = $_POST['about_paragraph1'] ?? '';
    $about_paragraph2 = $_POST['about_paragraph2'] ?? '';

    $feature1_icon = $_POST['feature1_icon'] ?? '';
    $feature1_title = $_POST['feature1_title'] ?? '';
    $feature1_desc = $_POST['feature1_desc'] ?? '';

    $feature2_icon = $_POST['feature2_icon'] ?? '';
    $feature2_title = $_POST['feature2_title'] ?? '';
    $feature2_desc = $_POST['feature2_desc'] ?? '';

    $feature3_icon = $_POST['feature3_icon'] ?? '';
    $feature3_title = $_POST['feature3_title'] ?? '';
    $feature3_desc = $_POST['feature3_desc'] ?? '';

    $feature4_icon = $_POST['feature4_icon'] ?? '';
    $feature4_title = $_POST['feature4_title'] ?? '';
    $feature4_desc = $_POST['feature4_desc'] ?? '';

    // Escape string untuk keamanan
    $hero_title = $conn->real_escape_string($hero_title);
    $hero_subtitle = $conn->real_escape_string($hero_subtitle);
    $nama_laundry = $conn->real_escape_string($nama_laundry);
    $alamat = $conn->real_escape_string($alamat);
    $whatsapp = $conn->real_escape_string($whatsapp);
    $email = $conn->real_escape_string($email);
    $jam_senin = $conn->real_escape_string($jam_senin);
    $jam_minggu = $conn->real_escape_string($jam_minggu);

    $about_title = $conn->real_escape_string($about_title);
    $about_paragraph1 = $conn->real_escape_string($about_paragraph1);
    $about_paragraph2 = $conn->real_escape_string($about_paragraph2);

    $feature1_icon = $conn->real_escape_string($feature1_icon);
    $feature1_title = $conn->real_escape_string($feature1_title);
    $feature1_desc = $conn->real_escape_string($feature1_desc);

    $feature2_icon = $conn->real_escape_string($feature2_icon);
    $feature2_title = $conn->real_escape_string($feature2_title);
    $feature2_desc = $conn->real_escape_string($feature2_desc);

    $feature3_icon = $conn->real_escape_string($feature3_icon);
    $feature3_title = $conn->real_escape_string($feature3_title);
    $feature3_desc = $conn->real_escape_string($feature3_desc);

    $feature4_icon = $conn->real_escape_string($feature4_icon);
    $feature4_title = $conn->real_escape_string($feature4_title);
    $feature4_desc = $conn->real_escape_string($feature4_desc);

    // Cek apakah data profil sudah ada (id=1)
    $check = $conn->query("SELECT id FROM profil WHERE id = 1");
    if ($check->num_rows > 0) {
        // UPDATE data yang sudah ada
        $sql = "UPDATE profil SET
            hero_title = '$hero_title',
            hero_subtitle = '$hero_subtitle',
            nama_laundry = '$nama_laundry',
            alamat = '$alamat',
            whatsapp = '$whatsapp',
            email = '$email',
            jam_senin = '$jam_senin',
            jam_minggu = '$jam_minggu',
            about_title = '$about_title',
            about_paragraph1 = '$about_paragraph1',
            about_paragraph2 = '$about_paragraph2',
            feature1_icon = '$feature1_icon',
            feature1_title = '$feature1_title',
            feature1_desc = '$feature1_desc',
            feature2_icon = '$feature2_icon',
            feature2_title = '$feature2_title',
            feature2_desc = '$feature2_desc',
            feature3_icon = '$feature3_icon',
            feature3_title = '$feature3_title',
            feature3_desc = '$feature3_desc',
            feature4_icon = '$feature4_icon',
            feature4_title = '$feature4_title',
            feature4_desc = '$feature4_desc',
            updated_at = NOW()
        WHERE id = 1";
    } else {
        // INSERT data baru jika belum ada
        $sql = "INSERT INTO profil (
            id, hero_title, hero_subtitle, nama_laundry, alamat, whatsapp, email, 
            jam_senin, jam_minggu, about_title, about_paragraph1, about_paragraph2,
            feature1_icon, feature1_title, feature1_desc,
            feature2_icon, feature2_title, feature2_desc,
            feature3_icon, feature3_title, feature3_desc,
            feature4_icon, feature4_title, feature4_desc
        ) VALUES (
            1, '$hero_title', '$hero_subtitle', '$nama_laundry', '$alamat', '$whatsapp', '$email',
            '$jam_senin', '$jam_minggu', '$about_title', '$about_paragraph1', '$about_paragraph2',
            '$feature1_icon', '$feature1_title', '$feature1_desc',
            '$feature2_icon', '$feature2_title', '$feature2_desc',
            '$feature3_icon', '$feature3_title', '$feature3_desc',
            '$feature4_icon', '$feature4_title', '$feature4_desc'
        )";
    }

    // Eksekusi query dan set session message
    if ($conn->query($sql)) {
        $_SESSION['success_message'] = 'Profil berhasil disimpan! Perubahan telah diterapkan.';
    } else {
        $_SESSION['error_message'] = 'Gagal menyimpan profil: ' . $conn->error;
    }

    // Redirect ke halaman profil
    header("Location: admin.php#profil");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin - deLondree</title>
  <link rel="stylesheet" href="admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

  <!-- Tampilkan pesan sukses/error jika ada -->
  <?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success" style="position: fixed; top: 20px; right: 20px; z-index: 9999; padding: 15px 25px; background: #10b981; color: white; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
      <i class="fas fa-check-circle"></i> <?= $_SESSION['success_message'] ?>
    </div>
    <script>setTimeout(() => document.querySelector('.alert-success').remove(), 5000);</script>
    <?php unset($_SESSION['success_message']); ?>
  <?php endif; ?>

  <?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-error" style="position: fixed; top: 20px; right: 20px; z-index: 9999; padding: 15px 25px; background: #ef4444; color: white; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
      <i class="fas fa-exclamation-circle"></i> <?= $_SESSION['error_message'] ?>
    </div>
    <script>setTimeout(() => document.querySelector('.alert-error').remove(), 5000);</script>
    <?php unset($_SESSION['error_message']); ?>
  <?php endif; ?>

  <div class="admin-container">

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <img src="hero.jpg" alt="Logo" class="sidebar-logo">
            <h3>Admin Panel</h3>
        </div>

        <nav class="sidebar-nav">
            <a href="#dashboard" class="nav-item active">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard
            </a>
            <a href="#layanan" class="nav-item">
                <i class="fas fa-concierge-bell"></i>
                Layanan
            </a>
            <a href="#harga" class="nav-item">
                <i class="fas fa-tags"></i>
                Harga
            </a>
            <a href="#delivery" class="nav-item">
                <i class="fas fa-truck"></i>
                Biaya Antar Jemput
            </a>
            <a href="#galeri" class="nav-item">
                <i class="fas fa-images"></i>
                Galeri
            </a>
            <a href="#orders" class="nav-item">
                <i class="fas fa-shopping-cart"></i>
                Pesanan
            </a>
            <a href="#reports" class="nav-item">
                <i class="fas fa-chart-bar"></i>
                Laporan Keuangan
            </a>
            <a href="#profil" class="nav-item">
                <i class="fas fa-store"></i>
                Profil Laundry
            </a>
            <a href="#password" class="nav-item">
                <i class="fas fa-key"></i>
                Ganti Password
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="admin-user-info">
                <i class="fas fa-user-circle"></i>
                <span><?= $_SESSION['admin_username'] ?></span>
            </div>
            <button onclick="adminUI.logout()" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </button>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      <div class="topbar">
        <h2 id="pageTitle">Dashboard</h2>
        <div class="topbar-actions">
        </div>
      </div>

      <!-- DASHBOARD -->
      <section id="section-dashboard" class="content-section active">
        <div class="stats-grid">
          <div class="stat-card">
            <div class="stat-icon blue">
              <i class="fas fa-concierge-bell"></i>
            </div>
            <div class="stat-info">
              <h3>
                <?php 
                $q = $conn->query("SELECT COUNT(*) AS total FROM layanan");
                echo $q->fetch_assoc()['total']; 
                ?>
              </h3>
              <p>Total Layanan</p>
            </div>
          </div>
          <div class="stat-card">
            <div class="stat-icon green">
              <i class="fas fa-tags"></i>
            </div>
            <div class="stat-info">
              <h3>
                <?php 
                $q = $conn->query("SELECT COUNT(*) AS total FROM harga");
                echo $q->fetch_assoc()['total']; 
                ?>
              </h3>
              <p>Paket Harga</p>
            </div>
          </div>
          <div class="stat-card">
            <div class="stat-icon purple">
              <i class="fas fa-images"></i>
            </div>
            <div class="stat-info">
              <h3>
                <?php 
                $q = $conn->query("SELECT COUNT(*) AS total FROM galeri");
                echo $q->fetch_assoc()['total']; 
                ?>
              </h3>
              <p>Foto Galeri</p>
            </div>
          </div>
          <div class="stat-card">
            <div class="stat-icon orange">
              <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stat-info">
              <h3>
                <?php 
                $q = $conn->query("SELECT COUNT(*) AS total FROM orders");
                echo $q->fetch_assoc()['total']; 
                ?>
              </h3>
              <p>Total Pesanan</p>
            </div>
          </div>
        </div>

        <div class="dashboard-content">
          <div class="welcome-card">
            <h3>Selamat Datang di Admin Panel deLondree!</h3>
            <p>Kelola semua data laundry Anda dengan mudah melalui panel admin ini.</p>
            <div class="quick-stats">
              <div class="quick-stat">
                <span class="stat-value">
                  <?php 
                  $today = date('Y-m-d');
                  $q = $conn->query("SELECT COUNT(*) AS total FROM orders WHERE DATE(created_at) = '$today'");
                  echo $q->fetch_assoc()['total'];
                  ?>
                </span>
                <span class="stat-label">Pesanan Hari Ini</span>
              </div>
              <div class="quick-stat">
                <span class="stat-value">
                  <?php 
                  $month = date('Y-m');
                  $q = $conn->query("SELECT COUNT(*) AS total FROM orders WHERE DATE_FORMAT(created_at, '%Y-%m') = '$month'");
                  echo $q->fetch_assoc()['total'];
                  ?>
                </span>
                <span class="stat-label">Pesanan Bulan Ini</span>
              </div>
              <div class="quick-stat">
                <span class="stat-value">
                  <?php 
                  $q = $conn->query("SELECT SUM(total_amount) AS total FROM orders WHERE status = 'completed' AND DATE_FORMAT(created_at, '%Y-%m') = '$month'");
                  $revenue = $q->fetch_assoc()['total'] ?? 0;
                  echo 'Rp ' . number_format($revenue);
                  ?>
                </span>
                <span class="stat-label">Pendapatan Bulan Ini</span>
              </div>
            </div>
          </div>

          <div class="recent-activity">
            <h4>Aktivitas Terbaru</h4>
            <div class="activity-list">
              <?php
              $activities = $conn->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 5");
              while ($activity = $activities->fetch_assoc()) {
                $statusClass = '';
                switch($activity['status']) {
                  case 'pending': $statusClass = 'pending'; break;
                  case 'process': $statusClass = 'process'; break;
                  case 'completed': $statusClass = 'completed'; break;
                  case 'cancelled': $statusClass = 'cancelled'; break;
                }
                
                echo '
                <div class="activity-item">
                  <i class="fas fa-shopping-cart activity-icon"></i>
                  <div class="activity-content">
                    <p>Pesanan baru #' . $activity['order_id'] . '</p>
                    <span class="activity-time">' . date('d M Y H:i', strtotime($activity['created_at'])) . '</span>
                    <span class="status-badge ' . $statusClass . '">' . $activity['status'] . '</span>
                  </div>
                </div>';
              }
              ?>
            </div>
          </div>
        </div>

        <!-- Recent Orders Chart -->
        <div class="chart-section">
          <h3>Statistik Pesanan 7 Hari Terakhir</h3>
          <div class="chart-container">
            <canvas id="ordersChart"></canvas>
          </div>
        </div>
      </section>

      <!-- LAYANAN -->
      <section id="section-layanan" class="content-section">
        <div class="section-header">
          <h3></h3>
          <button class="btn-primary" onclick="adminUI.openLayananModal()">
            <i class="fas fa-plus"></i>
            Tambah Layanan
          </button>
        </div>

        <div class="table-container">
          <table class="data-table">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Harga Mulai</th>
                <th>Foto</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              $result = $conn->query("SELECT * FROM layanan");
              while ($row = $result->fetch_assoc()) {
                $foto = !empty($row['foto']) ? 'uploads/' . $row['foto'] : 'assets/placeholder.jpg';
                echo "<tr>
                  <td>{$no}</td>
                  <td>{$row['nama_layanan']}</td>
                  <td>{$row['deskripsi']}</td>
                  <td>Rp " . number_format($row['harga_mulai']) . "</td>
                  <td><img src='{$foto}' width='60' height='60' style='object-fit: cover; border-radius: 8px;'></td>
                  <td>
                    <button class='btn-secondary' onclick='adminUI.editLayanan({$row['id']})'>
                      <i class='fas fa-edit'></i> Edit
                    </button>
                    <button class='btn-danger' onclick='adminUI.confirmDelete(\"layanan\", {$row['id']})'>
                      <i class='fas fa-trash'></i> Hapus
                    </button>
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
              <h3></h3>
              <button class="btn-primary" onclick="adminUI.openHargaModal()">
                  <i class="fas fa-plus"></i>
                  Tambah Paket Harga
              </button>
          </div>

          <?php
          // PAGINATION --------------------------
          $limit = 7;
          $page = isset($_GET['page_harga']) ? (int)$_GET['page_harga'] : 1;
          $offset = ($page - 1) * $limit;

          $totalData = $conn->query("SELECT COUNT(*) AS total FROM harga")->fetch_assoc()['total'];
          $totalPages = ceil($totalData / $limit);

          $result = $conn->query("
              SELECT h.*, l.nama_layanan 
              FROM harga h 
              LEFT JOIN layanan l ON h.service_id = l.id 
              ORDER BY h.jenis_layanan, h.harga
              LIMIT $offset, $limit
          ");
          ?>

          <div class="table-container">
              <table class="data-table">
                  <thead>
                      <tr>
                          <th>No</th>
                          <th>Jenis Layanan</th>
                          <th>Kategori</th>
                          <th>Deskripsi</th>
                          <th>Harga</th>
                          <th>Unit</th>
                          <th>Aksi</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php
                      $no = $offset + 1;
                      while ($row = $result->fetch_assoc()) {
                          echo "<tr>
                            <td>{$no}</td>
                            <td>{$row['jenis_layanan']}</td>
                            <td>{$row['kategori']}</td>
                            <td>{$row['description']}</td>
                            <td>Rp " . number_format($row['harga']) . "</td>
                            <td>{$row['unit']}</td>
                            <td>
                              <button class='btn-secondary' onclick='adminUI.openHargaModal(true, {
                                id: {$row['id']},
                                service_id: \"{$row['service_id']}\",
                                jenis_layanan: \"{$row['jenis_layanan']}\",
                                kategori: \"{$row['kategori']}\",
                                description: \"{$row['description']}\",
                                harga: {$row['harga']},
                                unit: \"{$row['unit']}\"
                              })'>
                                <i class=\"fas fa-edit\"></i> Edit
                              </button>
                              <button class='btn-danger' onclick='adminUI.confirmDelete(\"harga\", {$row['id']})'>
                                <i class=\"fas fa-trash\"></i> Hapus
                              </button>
                            </td>
                          </tr>";
                          $no++;
                      }
                      ?>
                  </tbody>
              </table>

              <!-- PAGINATION -->
              <div class="pagination">
                  <?php if ($page > 1): ?>
                      <a href="?page_harga=1#harga"><<</a>
                      <a href="?page_harga=<?= $page - 1 ?>#harga"><</a>
                  <?php endif; ?>

                  <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                      <a class="<?= ($i == $page ? 'active' : '') ?>"
                        href="?page_harga=<?= $i ?>#harga">
                          <?= $i ?>
                      </a>
                  <?php endfor; ?>

                  <?php if ($page < $totalPages): ?>
                      <a href="?page_harga=<?= $page + 1 ?>#harga">></a>
                      <a href="?page_harga=<?= $totalPages ?>#harga">>></a>
                  <?php endif; ?>
              </div>
          </div>
      </section>

      <!-- DELIVERY RATES -->
      <section id="section-delivery" class="content-section">
        <div class="section-header">
          <h3></h3>
          <button class="btn-primary" onclick="adminUI.openDeliveryModal()">
            <i class="fas fa-plus"></i>
            Tambah Tarif
          </button>
        </div>

        <div class="table-container">
          <table class="data-table">
            <thead>
              <tr>
                <th>No</th>
                <th>Jarak Min (km)</th>
                <th>Jarak Max (km)</th>
                <th>Biaya</th>
                <th>Keterangan</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              $rates = $conn->query("SELECT * FROM delivery_rates ORDER BY range_min");
              while ($rate = $rates->fetch_assoc()) {
                  echo "<tr>
                      <td>{$no}</td>
                      <td>{$rate['range_min']}</td>
                      <td>{$rate['range_max']}</td>
                      <td>Rp " . number_format($rate['rate']) . "</td>
                      <td>{$rate['description']}</td>
                      <td>
                          <button class='btn-secondary' onclick='adminUI.openDeliveryModal(true, {
                              id: {$rate['id']},
                              range_min: {$rate['range_min']},
                              range_max: {$rate['range_max']},
                              rate: {$rate['rate']},
                              description: \"{$rate['description']}\"
                          })'>
                              <i class='fas fa-edit'></i> Edit
                          </button>
                          <button class='btn-danger' onclick='adminUI.confirmDelete(\"delivery\", {$rate['id']})'>
                              <i class='fas fa-trash'></i> Hapus
                          </button>
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
          <h3></h3>
          <button class="btn-primary" onclick="adminUI.openGaleriModal()">
            <i class="fas fa-plus"></i>
            Upload Foto
          </button>
        </div>

        <div class="gallery-grid">
          <?php
          $result = $conn->query("SELECT * FROM galeri");
          while ($row = $result->fetch_assoc()) {
            echo "
            <div class='gallery-item'>
              <img src='uploads/{$row['foto']}' alt='{$row['judul']}'>
              <p>{$row['judul']}</p>
              <div class='gallery-actions'>
                <button class='btn-secondary' onclick='adminUI.openGaleriModal(true, {
                  id: {$row['id']},
                  judul: \"{$row['judul']}\"
                })'>
                  <i class='fas fa-edit'></i> Edit
                </button>
                <button class='btn-danger' onclick='adminUI.confirmDelete(\"galeri\", {$row['id']})'>
                  <i class='fas fa-trash'></i> Hapus
                </button>
              </div>
            </div>
            ";
          }
          ?>
        </div>
      </section>

      <!-- ORDERS -->
      <section id="section-orders" class="content-section">
          <div class="section-header">
              <h3></h3>
              <div class="section-actions">
                  <button class="btn-primary" onclick="adminUI.openOrderModal()">
                      <i class="fas fa-plus"></i>
                      Tambah Pesanan Manual
                  </button>
              </div>
          </div>

          <?php
          // PAGINATION ORDERS
          $limitOrders = 10;
          $pageOrders = isset($_GET['page_orders']) ? (int)$_GET['page_orders'] : 1;
          $offsetOrders = ($pageOrders - 1) * $limitOrders;

          $totalOrdersData = $conn->query("SELECT COUNT(*) AS total FROM orders")->fetch_assoc()['total'];
          $totalOrdersPages = ceil($totalOrdersData / $limitOrders);

          $orders = $conn->query("
              SELECT * FROM orders 
              ORDER BY created_at DESC
              LIMIT $offsetOrders, $limitOrders
          ");
          ?>

          <div class="table-container">
              <table class="data-table">
                  <thead>
                      <tr>
                          <th>ID Pesanan</th>
                          <th>Pelanggan</th>
                          <th>WhatsApp</th>
                          <th>Layanan</th>
                          <th>Total</th>
                          <th>Status</th>
                          <th>Tanggal</th>
                          <th>Aksi</th>
                      </tr>
                  </thead>
                  <tbody id="ordersTable">
                      <?php
                      while ($order = $orders->fetch_assoc()) {
                          $statusClass = match ($order['status']) {
                              'pending' => 'pending',
                              'process' => 'process',
                              'completed' => 'completed',
                              'cancelled' => 'cancelled',
                              default => ''
                          };

                          echo "
                          <tr>
                              <td>{$order['order_id']}</td>
                              <td>{$order['customer_name']}</td>
                              <td>{$order['customer_phone']}</td>
                              <td>{$order['service_name']}</td>
                              <td>Rp " . number_format($order['total_amount']) . "</td>
                              <td><span class='status-badge {$statusClass}'>{$order['status']}</span></td>
                              <td>" . date('d M Y', strtotime($order['created_at'])) . "</td>
                              <td>
                                  <button class='btn-secondary' onclick='adminUI.updateOrderStatus(\"{$order['order_id']}\", \"process\")'>
                                      <i class='fas fa-play'></i> Proses
                                  </button>
                                  <button class='btn-success' onclick='adminUI.updateOrderStatus(\"{$order['order_id']}\", \"completed\")'>
                                      <i class='fas fa-check'></i> Selesai
                                  </button>
                                  <button class='btn-danger' onclick='adminUI.updateOrderStatus(\"{$order['order_id']}\", \"cancelled\")'>
                                      <i class='fas fa-times'></i> Batal
                                  </button>
                                  <button class='btn-info' onclick='adminUI.viewOrderDetail(\"{$order['order_id']}\")'>
                                      <i class='fas fa-eye'></i> Detail
                                  </button>
                                  <button class='btn-warning' onclick='adminUI.editOrder(\"{$order['order_id']}\")'>
                                      <i class='fas fa-edit'></i> Edit
                                  </button>
                                  <button class='btn-danger' onclick='adminUI.confirmDelete(\"order\", \"{$order['order_id']}\")'>
                                      <i class='fas fa-trash'></i> Hapus
                                  </button>
                              </td>
                          </tr>";
                      }
                      ?>
                  </tbody>
              </table>

              <!-- PAGINATION -->
              <div class="pagination">
                  <?php if ($pageOrders > 1): ?>
                      <a href="?page_orders=1#orders"><<</a>
                      <a href="?page_orders=<?= $pageOrders - 1 ?>#orders"><</a>
                  <?php endif; ?>

                  <?php for ($i = 1; $i <= $totalOrdersPages; $i++): ?>
                      <a class="<?= ($i == $pageOrders ? 'active' : '') ?>"
                        href="?page_orders=<?= $i ?>#orders">
                          <?= $i ?>
                      </a>
                  <?php endfor; ?>

                  <?php if ($pageOrders < $totalOrdersPages): ?>
                      <a href="?page_orders=<?= $pageOrders + 1 ?>#orders">></a>
                      <a href="?page_orders=<?= $totalOrdersPages ?>#orders">>></a>
                  <?php endif; ?>
              </div>

          </div>
      </section>

      <!-- LAPORAN KEUANGAN -->
      <section id="section-reports" class="content-section">
          <div class="section-header">
              <p class="section-subtitle">Data real-time berdasarkan transaksi yang tercatat dalam sistem</p>
          </div>

          <div class="financial-summary">
              <div class="summary-card">
                  <div class="summary-icon">
                      <i class="fas fa-money-bill-wave"></i>
                  </div>
                  <div class="summary-content">
                      <h4 id="totalRevenue">Rp 0</h4>
                      <p>Total Pendapatan Bulan Ini</p>
                  </div>
              </div>
              <div class="summary-card">
                  <div class="summary-icon">
                      <i class="fas fa-shopping-cart"></i>
                  </div>
                  <div class="summary-content">
                      <h4 id="totalOrders">0</h4>
                      <p>Total Pesanan Selesai</p>
                  </div>
              </div>
              <div class="summary-card">
                  <div class="summary-icon">
                      <i class="fas fa-chart-line"></i>
                  </div>
                  <div class="summary-content">
                      <h4 id="averageOrder">Rp 0</h4>
                      <p>Rata-rata per Pesanan</p>
                  </div>
              </div>
              <div class="summary-card">
                  <div class="summary-icon">
                      <i class="fas fa-trending-up"></i>
                  </div>
                  <div class="summary-content">
                      <h4 id="growthRate">0%</h4>
                      <p>Pertumbuhan dari Bulan Lalu</p>
                  </div>
              </div>
          </div>

          <div class="charts-container">

              <!-- CHART PENDAPATAN TAHUNAN - FULL WIDTH -->
              <div class="chart-card chart-large">
                  <h4>Pendapatan Tahunan <?= date('Y') ?></h4>
                  <div class="chart-wrapper">
                      <canvas id="monthlyRevenueChart"></canvas>
                  </div>
              </div>

              <!-- CHART PERFORMA LAYANAN -->
              <div class="chart-card">
                  <h4>Performa Layanan</h4>
                  <div class="chart-wrapper">
                      <canvas id="servicePerformanceChart"></canvas>
                  </div>
              </div>

              <!-- CHART METODE PEMBAYARAN -->
              <div class="chart-card">
                  <h4>Metode Pembayaran</h4>
                  <div class="chart-wrapper">
                      <canvas id="paymentMethodChart"></canvas>
                  </div>
              </div>

          </div>


          <div class="recent-orders">
              <h4>Pesanan Terbaru yang Selesai</h4>
              <div class="table-container">
                  <table class="data-table">
                      <thead>
                          <tr>
                              <th>ID Pesanan</th>
                              <th>Pelanggan</th>
                              <th>Layanan</th>
                              <th>Total</th>
                              <th>Metode Bayar</th>
                              <th>Tanggal Selesai</th>
                          </tr>
                      </thead>
                      <tbody id="recentOrdersTable">
                          <?php
                          $recentOrders = $conn->query("
                              SELECT * FROM orders 
                              WHERE status = 'completed' 
                              ORDER BY updated_at DESC 
                              LIMIT 8
                          ");
                          while ($order = $recentOrders->fetch_assoc()) {
                              $payment_method = $order['payment_method'] == 'cash' ? 'Cash' : 'Transfer Bank';
                              echo "
                              <tr>
                                  <td>{$order['order_id']}</td>
                                  <td>{$order['customer_name']}</td>
                                  <td>{$order['service_name']}</td>
                                  <td>Rp " . number_format($order['total_amount']) . "</td>
                                  <td>{$payment_method}</td>
                                  <td>" . date('d M Y', strtotime($order['updated_at'])) . "</td>
                              </tr>";
                          }
                          ?>
                      </tbody>
                  </table>
              </div>
          </div>
      </section>

      <!-- PROFIL (FORM DIUBAH - action ke admin.php) -->
      <section id="section-profil" class="content-section">
        <form action="admin.php" method="POST" class="profile-form">
          <input type="hidden" name="simpan_profil" value="1">
          <?php
          $profil = $conn->query("SELECT * FROM profil LIMIT 1");
          $data = $profil->num_rows > 0 ? $profil->fetch_assoc() : [];
          ?>
          
          <!-- HERO SECTION -->
          <div class="form-section">
            <h4><i class="fas fa-star"></i> Hero Section (Halaman Utama)</h4>
            <p class="form-section-note">Ubah judul dan subtitle utama yang muncul pertama kali di halaman beranda</p>
            
            <div class="form-group">
              <label>Judul Utama (Hero Title)</label>
              <input type="text" 
                    name="hero_title" 
                    value="<?= $data['hero_title'] ?? 'Layanan Laundry Profesional' ?>" 
                    required
                    placeholder="Contoh: Layanan Laundry Profesional">
              <small>Judul besar yang muncul di bagian paling atas halaman</small>
            </div>
            
            <div class="form-group">
              <label>Subtitle (Hero Subtitle)</label>
              <input type="text" 
                    name="hero_subtitle" 
                    value="<?= $data['hero_subtitle'] ?? 'Bersih, Rapi, dan Wangi - Kepercayaan Anda adalah Prioritas Kami' ?>" 
                    required
                    placeholder="Contoh: Bersih, Rapi, dan Wangi - Kepercayaan Anda adalah Prioritas Kami">
              <small>Subtitle/tagline yang muncul di bawah judul utama</small>
            </div>
          </div>

          <!-- INFORMASI LAUNDRY -->
          <div class="form-section">
            <h4><i class="fas fa-store"></i> Informasi Laundry</h4>
            
            <div class="form-group">
              <label>Nama Laundry</label>
              <input type="text" name="nama_laundry" value="<?= $data['nama_laundry'] ?? '' ?>" required>
            </div>
            
            <div class="form-group">
              <label>Alamat Lengkap</label>
              <textarea name="alamat" rows="3" required><?= $data['alamat'] ?? '' ?></textarea>
            </div>
            
            <div class="form-row">
              <div class="form-group">
                <label>Nomor WhatsApp</label>
                <input type="text" name="whatsapp" value="<?= $data['whatsapp'] ?? '' ?>" required>
              </div>
              
              <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?= $data['email'] ?? '' ?>">
              </div>
            </div>
            
            <div class="form-row">
              <div class="form-group">
                <label>Jam Buka (Senin-Sabtu)</label>
                <input type="text" name="jam_senin" value="<?= $data['jam_senin'] ?? '' ?>" required>
              </div>
              
              <div class="form-group">
                <label>Jam Buka (Minggu)</label>
                <input type="text" name="jam_minggu" value="<?= $data['jam_minggu'] ?? '' ?>" required>
              </div>
            </div>
          </div>

          <!-- TENTANG KAMI -->
          <div class="form-section">
            <h4><i class="fas fa-info-circle"></i> Deskripsi "Tentang Kami"</h4>
            <p class="form-section-note">Ubah konten yang ditampilkan di halaman utama bagian "Tentang deLondree"</p>
            
            <div class="form-group">
              <label>Judul Section</label>
              <input type="text" 
                    name="about_title" 
                    value="<?= $data['about_title'] ?? 'deLondree - Laundry & Dry Cleaning Specialist' ?>" 
                    required
                    placeholder="Contoh: deLondree - Laundry & Dry Cleaning Specialist">
              <small>Judul utama yang akan ditampilkan di bagian "Tentang Kami"</small>
            </div>
            
            <div class="form-group">
              <label>Paragraf Pertama</label>
              <textarea name="about_paragraph1" 
                        rows="4" 
                        required
                        placeholder="Tulis deskripsi paragraf pertama tentang laundry Anda..."><?= $data['about_paragraph1'] ?? 'deLondree hadir sebagai penyedia jasa laundry profesional yang mengutamakan kualitas, kecepatan, dan kepuasan pelanggan. Dengan pengalaman bertahun-tahun dalam industri laundry, kami memahami betul kebutuhan akan pakaian bersih, rapi, dan wangi.' ?></textarea>
              <small>Paragraf pertama yang menjelaskan tentang layanan dan pengalaman</small>
            </div>
            
            <div class="form-group">
              <label>Paragraf Kedua</label>
              <textarea name="about_paragraph2" 
                        rows="4" 
                        required
                        placeholder="Tulis deskripsi paragraf kedua tentang keunggulan laundry Anda..."><?= $data['about_paragraph2'] ?? 'Kami menggunakan peralatan modern dan detergen berkualitas tinggi yang ramah lingkungan untuk memastikan pakaian Anda mendapatkan perawatan terbaik. Setiap pakaian ditangani dengan penuh perhatian oleh tim profesional yang terlatih.' ?></textarea>
              <small>Paragraf kedua yang menjelaskan tentang peralatan dan tim</small>
            </div>
          </div>

          <!-- 4 FITUR UNGGULAN -->
          <div class="form-section">
            <h4><i class="fas fa-th-large"></i> 4 Fitur Unggulan (Features)</h4>
            <p class="form-section-note">4 kartu fitur yang ditampilkan di bawah section "Tentang Kami"</p>
            
            <!-- Feature 1 -->
            <div class="feature-editor">
              <h5><i class="fas fa-square"></i> Fitur 1</h5>
              <div class="form-row">
                <div class="form-group" style="flex: 0 0 200px;">
                  <label>Icon (FontAwesome)</label>
                  <input type="text" 
                        name="feature1_icon" 
                        value="<?= $data['feature1_icon'] ?? 'fa-tools' ?>" 
                        placeholder="fa-tools">
                  <small>Lihat icon di <a href="https://fontawesome.com/icons" target="_blank">fontawesome.com</a></small>
                </div>
                <div class="form-group">
                  <label>Judul Fitur</label>
                  <input type="text" 
                        name="feature1_title" 
                        value="<?= $data['feature1_title'] ?? 'Peralatan Modern' ?>" 
                        required>
                </div>
              </div>
              <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="feature1_desc" rows="2" required><?= $data['feature1_desc'] ?? 'Menggunakan mesin laundry terbaru dan teknologi canggih' ?></textarea>
              </div>
            </div>

            <!-- Feature 2 -->
            <div class="feature-editor">
              <h5><i class="fas fa-square"></i> Fitur 2</h5>
              <div class="form-row">
                <div class="form-group" style="flex: 0 0 200px;">
                  <label>Icon (FontAwesome)</label>
                  <input type="text" 
                        name="feature2_icon" 
                        value="<?= $data['feature2_icon'] ?? 'fa-leaf' ?>" 
                        placeholder="fa-leaf">
                  <small>Lihat icon di <a href="https://fontawesome.com/icons" target="_blank">fontawesome.com</a></small>
                </div>
                <div class="form-group">
                  <label>Judul Fitur</label>
                  <input type="text" 
                        name="feature2_title" 
                        value="<?= $data['feature2_title'] ?? 'Ramah Lingkungan' ?>" 
                        required>
                </div>
              </div>
              <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="feature2_desc" rows="2" required><?= $data['feature2_desc'] ?? 'Detergen biodegradable yang aman untuk kulit dan lingkungan' ?></textarea>
              </div>
            </div>

            <!-- Feature 3 -->
            <div class="feature-editor">
              <h5><i class="fas fa-square"></i> Fitur 3</h5>
              <div class="form-row">
                <div class="form-group" style="flex: 0 0 200px;">
                  <label>Icon (FontAwesome)</label>
                  <input type="text" 
                        name="feature3_icon" 
                        value="<?= $data['feature3_icon'] ?? 'fa-user-tie' ?>" 
                        placeholder="fa-user-tie">
                  <small>Lihat icon di <a href="https://fontawesome.com/icons" target="_blank">fontawesome.com</a></small>
                </div>
                <div class="form-group">
                  <label>Judul Fitur</label>
                  <input type="text" 
                        name="feature3_title" 
                        value="<?= $data['feature3_title'] ?? 'Tim Profesional' ?>" 
                        required>
                </div>
              </div>
              <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="feature3_desc" rows="2" required><?= $data['feature3_desc'] ?? 'Staff berpengalaman dan terlatih dalam menangani berbagai jenis pakaian' ?></textarea>
              </div>
            </div>

            <!-- Feature 4 -->
            <div class="feature-editor">
              <h5><i class="fas fa-square"></i> Fitur 4</h5>
              <div class="form-row">
                <div class="form-group" style="flex: 0 0 200px;">
                  <label>Icon (FontAwesome)</label>
                  <input type="text" 
                        name="feature4_icon" 
                        value="<?= $data['feature4_icon'] ?? 'fa-truck-fast' ?>" 
                        placeholder="fa-truck-fast">
                  <small>Lihat icon di <a href="https://fontawesome.com/icons" target="_blank">fontawesome.com</a></small>
                </div>
                <div class="form-group">
                  <label>Judul Fitur</label>
                  <input type="text" 
                        name="feature4_title" 
                        value="<?= $data['feature4_title'] ?? 'Layanan Cepat' ?>" 
                        required>
                </div>
              </div>
              <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="feature4_desc" rows="2" required><?= $data['feature4_desc'] ?? 'Proses cepat dengan hasil maksimal dan pengantaran tepat waktu' ?></textarea>
              </div>
            </div>
          </div>
          
          <div class="form-actions">
            <button type="submit" class="btn-primary">
              <i class="fas fa-save"></i> Simpan Semua Perubahan
            </button>
            <button type="button" class="btn-secondary" onclick="location.reload()">
              <i class="fas fa-undo"></i> Reset
            </button>
          </div>
        </form>
      </section>

      <!-- PASSWORD -->
      <section id="section-password" class="content-section active">
        <form action="php/ganti_password.php" method="POST" class="password-form">
          <div class="form-group">
            <label>Password Lama</label>
            <input type="password" name="old_password" required>
          </div>
          <div class="form-group">
            <label>Password Baru</label>
            <input type="password" name="new_password" required>
          </div>
          <div class="form-group">
            <label>Konfirmasi Password Baru</label>
            <input type="password" name="confirm_password" required>
          </div>
          <button type="submit" class="btn-primary">
            <i class="fas fa-key"></i> Ganti Password
          </button>
        </form>
      </section>
    </main>
  </div>

  <!-- MODAL DELIVERY RATES -->
  <div id="modalDelivery" class="modal">
      <div class="modal-content">
          <h3 id="modalDeliveryTitle">Tambah Tarif Antar Jemput</h3>
          <form id="formDelivery">
              <input type="hidden" name="id" id="delivery_id">
              <div class="form-row">
                  <div class="form-group">
                      <label>Jarak Minimum (km)</label>
                      <input type="number" name="range_min" id="range_min" required min="0" step="0.1">
                  </div>
                  <div class="form-group">
                      <label>Jarak Maksimum (km)</label>
                      <input type="number" name="range_max" id="range_max" required min="0" step="0.1">
                  </div>
              </div>
              <div class="form-group">
                  <label>Biaya (Rp)</label>
                  <input type="number" name="rate" id="rate" required min="0">
              </div>
              <div class="form-group">
                  <label>Keterangan</label>
                  <input type="text" name="description" id="description" required>
              </div>
              <div class="modal-actions">
                  <button type="submit" class="btn-primary">
                      <i class="fas fa-save"></i> Simpan
                  </button>
                  <button type="button" class="btn-secondary" onclick="adminUI.closeModal()">
                      <i class="fas fa-times"></i> Batal
                  </button>
              </div>
          </form>
      </div>
  </div>

  <!-- MODAL LAYANAN -->
  <div id="modalLayanan" class="modal">
    <div class="modal-content">
      <h3 id="modalLayananTitle">Tambah Layanan</h3>
      <form id="formLayanan" enctype="multipart/form-data">
        <input type="hidden" name="id" id="layanan_id">
        <div class="form-group">
          <label>Nama Layanan</label>
          <input type="text" name="nama_layanan" id="nama_layanan" required>
        </div>
        <div class="form-group">
          <label>Deskripsi</label>
          <textarea name="deskripsi" id="deskripsi" required></textarea>
        </div>
        <div class="form-group">
          <label>Harga Mulai</label>
          <input type="number" name="harga_mulai" id="harga_mulai" required>
        </div>
        <div class="form-group">
          <label>Foto</label>
          <input type="file" name="foto" id="foto" accept="image/*">
        </div>
        <div class="modal-actions">
          <button type="submit" class="btn-primary">
            <i class="fas fa-save"></i> Simpan
          </button>
          <button type="button" class="btn-secondary" onclick="adminUI.closeModal()">
            <i class="fas fa-times"></i> Batal
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- MODAL HARGA -->
  <div id="modalHarga" class="modal">
    <div class="modal-content">
      <h3 id="modalHargaTitle">Tambah Paket Harga</h3>
      <form id="formHarga">
        <input type="hidden" name="id" id="harga_id">
        <div class="form-group">
          <label>Jenis Layanan</label>
          <input type="text" name="jenis_layanan" id="jenis_layanan" required>
        </div>
        <div class="form-group">
          <label>Kategori</label>
          <input type="text" name="kategori" id="kategori" required>
        </div>
        <div class="form-group">
          <label>Deskripsi</label>
          <textarea name="description" id="description_harga"></textarea>
        </div>
        <div class="form-group">
          <label>Harga (Rp)</label>
          <input type="number" name="harga" id="harga" required>
        </div>
        <div class="form-group">
          <label>Unit</label>
          <select name="unit" id="unit" required>
            <option value="kg">Kilogram (kg)</option>
            <option value="buah">Buah</option>
            <option value="meter">Meter</option>
            <option value="lusin">Lusin</option>
          </select>
        </div>
        <div class="modal-actions">
          <button type="submit" class="btn-primary">
            <i class="fas fa-save"></i> Simpan
          </button>
          <button type="button" class="btn-secondary" onclick="adminUI.closeModal()">
            <i class="fas fa-times"></i> Batal
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- MODAL GALERI -->
  <div id="modalGaleri" class="modal">
    <div class="modal-content">
      <h3 id="modalGaleriTitle">Upload Foto Galeri</h3>
      <form id="formGaleri" enctype="multipart/form-data">
        <input type="hidden" name="id" id="galeri_id">
        <div class="form-group">
          <label>Judul Foto</label>
          <input type="text" name="judul" id="judul" required>
        </div>
        <div class="form-group">
          <label>Foto</label>
          <input type="file" name="foto" id="foto_galeri" accept="image/*" required>
        </div>
        <div class="modal-actions">
          <button type="submit" class="btn-primary">
            <i class="fas fa-save"></i> Simpan
          </button>
          <button type="button" class="btn-secondary" onclick="adminUI.closeModal()">
            <i class="fas fa-times"></i> Batal
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- MODAL TAMBAH/EDIT PESANAN -->
  <div id="modalOrder" class="modal">
    <div class="modal-content large">
      <h3 id="modalOrderTitle">Tambah Pesanan Manual</h3>
      <form id="formOrder">
        <input type="hidden" name="order_id" id="edit_order_id">
        <div class="form-row">
          <div class="form-group">
            <label>Nama Pelanggan *</label>
            <input type="text" name="customer_name" id="customer_name" required>
          </div>
          <div class="form-group">
            <label>Nomor WhatsApp *</label>
            <input type="tel" name="customer_phone" id="customer_phone" required>
          </div>
        </div>
        
        <div class="form-group">
          <label>Alamat Lengkap *</label>
          <textarea name="customer_address" id="customer_address" required></textarea>
        </div>
        
        <div class="form-group">
          <label>Keterangan Alamat</label>
          <input type="text" name="address_notes" id="address_notes">
        </div>
        
        <div class="form-row">
          <div class="form-group">
            <label>Layanan *</label>
            <input type="text" name="service_name" id="service_name" required placeholder="Contoh: Cuci Kering Setrika (2kg), Setrika Saja (1kg)">
          </div>
          <div class="form-group">
            <label>Total Amount (Rp) *</label>
            <input type="number" name="total_amount" id="total_amount" required>
          </div>
        </div>
        
        <div class="form-row">
          <div class="form-group">
            <label>Tanggal Penjemputan *</label>
            <input type="date" name="pickup_date" id="pickup_date" required>
          </div>
          <div class="form-group">
            <label>Waktu Penjemputan *</label>
            <input type="text" name="pickup_time" id="pickup_time" required placeholder="Contoh: 10:00-12:00">
          </div>
        </div>
        
        <div class="form-row">
          <div class="form-group">
            <label>Metode Pembayaran *</label>
            <select name="payment_method" id="payment_method" required>
              <option value="cash">Cash</option>
              <option value="transfer">Transfer Bank</option>
            </select>
          </div>
          <div class="form-group">
            <label>Status *</label>
            <select name="status" id="status" required>
              <option value="pending">Pending</option>
              <option value="process">Process</option>
              <option value="completed">Completed</option>
              <option value="cancelled">Cancelled</option>
            </select>
          </div>
        </div>
        
        <div class="form-group">
          <label>Catatan (Opsional)</label>
          <textarea name="notes" id="notes"></textarea>
        </div>
        
        <div class="modal-actions">
          <button type="submit" class="btn-primary">
            <i class="fas fa-save"></i> Simpan Pesanan
          </button>
          <button type="button" class="btn-secondary" onclick="adminUI.closeModal()">
            <i class="fas fa-times"></i> Batal
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- MODAL ORDER DETAIL -->
  <div id="modalOrderDetail" class="modal">
    <div class="modal-content large">
      <h3 id="modalOrderDetailTitle">Detail Pesanan</h3>
      <div id="orderDetailContent">
        <!-- Content will be loaded here -->
      </div>
      <div class="modal-actions">
        <button type="button" class="btn-secondary" onclick="adminUI.closeModal()">
          <i class="fas fa-times"></i> Tutup
        </button>
      </div>
    </div>
  </div>

  <!-- MODAL KONFIRMASI -->
  <div id="modalConfirm" class="modal">
    <div class="modal-content small">
      <h3><i class="fas fa-exclamation-triangle"></i> Konfirmasi Hapus</h3>
      <p>Yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.</p>
      <div class="modal-actions">
        <button id="btnConfirmDelete" class="btn-danger">
          <i class="fas fa-trash"></i> Hapus
        </button>
        <button class="btn-secondary" onclick="adminUI.closeModal()">
          <i class="fas fa-times"></i> Batal
        </button>
      </div>
    </div>
  </div>

  <script src="admin.js"></script>
  <script>
document.addEventListener("DOMContentLoaded", function() {
    const hash = window.location.hash;

    // Sembunyikan alert laporan jika bukan berada di #finance
    if (hash !== "#finance" && hash !== "#laporan") {
        const alerts = document.querySelectorAll(".finance-alert");
        alerts.forEach(a => a.style.display = "none");
    }
});
</script>

</body>
</html>
<?php $conn->close(); ?>