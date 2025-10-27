<?php
session_start();
if (!isset($_SESSION['adminLoggedIn'])) {
    header('Location: ../login.php');
    exit;
}
include '../koneksi.php';
?>
<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['adminLoggedIn'])) {
    die('Akses ditolak');
}

$id = $_POST['id'] ?? '';
$jenis = mysqli_real_escape_string($conn, $_POST['jenis_layanan']);
$kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
$harga = intval($_POST['harga']);

if ($id == '') {
    // INSERT dengan prepared statement
    $stmt = $conn->prepare("INSERT INTO harga (jenis_layanan, kategori, harga) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $jenis, $kategori, $harga);
} else {
    // UPDATE dengan prepared statement
    $stmt = $conn->prepare("UPDATE harga SET jenis_layanan=?, kategori=?, harga=? WHERE id=?");
    $stmt->bind_param("ssii", $jenis, $kategori, $harga, $id);
}

if ($stmt->execute()) {
    echo "Data harga berhasil disimpan.";
} else {
    echo "Gagal menyimpan data: " . $stmt->error;
}

$stmt->close();
?>