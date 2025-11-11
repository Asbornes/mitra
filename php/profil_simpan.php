<?php
session_start();
if (!isset($_SESSION['adminLoggedIn'])) {
    header('Location: ../login.php');
    exit;
}
include '../koneksi.php';

$nama_laundry = mysqli_real_escape_string($conn, $_POST['nama_laundry']);
$alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
$whatsapp = mysqli_real_escape_string($conn, $_POST['whatsapp']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$jam_senin = mysqli_real_escape_string($conn, $_POST['jam_senin']);
$jam_minggu = mysqli_real_escape_string($conn, $_POST['jam_minggu']);

// Cek apakah data profil sudah ada
$check = $conn->query("SELECT id FROM profil LIMIT 1");

if ($check->num_rows > 0) {
    // UPDATE
    $stmt = $conn->prepare("UPDATE profil SET nama_laundry=?, alamat=?, whatsapp=?, email=?, jam_senin=?, jam_minggu=? WHERE id=1");
    $stmt->bind_param("ssssss", $nama_laundry, $alamat, $whatsapp, $email, $jam_senin, $jam_minggu);
} else {
    // INSERT
    $stmt = $conn->prepare("INSERT INTO profil (id, nama_laundry, alamat, whatsapp, email, jam_senin, jam_minggu) VALUES (1, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nama_laundry, $alamat, $whatsapp, $email, $jam_senin, $jam_minggu);
}

if ($stmt->execute()) {
    echo "Profil laundry berhasil diperbarui.";
} else {
    echo "Gagal menyimpan profil: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>