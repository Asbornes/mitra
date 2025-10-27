<?php
session_start();
if (!isset($_SESSION['adminLoggedIn'])) {
    header('Location: ../login.php');
    exit;
}
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama_laundry'] ?? '';
    $alamat = $_POST['alamat'] ?? '';
    $wa = $_POST['whatsapp'] ?? '';
    $email = $_POST['email'] ?? '';
    $senin = $_POST['jam_senin'] ?? '';
    $minggu = $_POST['jam_minggu'] ?? '';

    // Cek apakah data profil sudah ada
    $cek = $conn->query("SELECT * FROM profil WHERE id=1");
    
    if ($cek->num_rows > 0) {
        // UPDATE dengan prepared statement
        $stmt = $conn->prepare("UPDATE profil SET nama_laundry=?, alamat=?, whatsapp=?, email=?, jam_senin=?, jam_minggu=? WHERE id=1");
        $stmt->bind_param("ssssss", $nama, $alamat, $wa, $email, $senin, $minggu);
    } else {
        // INSERT dengan prepared statement
        $stmt = $conn->prepare("INSERT INTO profil (id, nama_laundry, alamat, whatsapp, email, jam_senin, jam_minggu) VALUES (1, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $nama, $alamat, $wa, $email, $senin, $minggu);
    }
    
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: ../admin.php#profil");
        exit;
    } else {
        echo "Gagal menyimpan profil: " . $stmt->error;
        $stmt->close();
    }
}
$conn->close();
?>