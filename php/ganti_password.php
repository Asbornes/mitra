<?php
session_start();
if (!isset($_SESSION['adminLoggedIn'])) {
    header('Location: ../login.php');
    exit;
}
include '../koneksi.php';

$old_password = $_POST['old_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validasi input
if (empty($old_password) || empty($new_password) || empty($confirm_password)) {
    die("Semua field harus diisi.");
}

if ($new_password !== $confirm_password) {
    die("Password baru dan konfirmasi password tidak cocok.");
}

if (strlen($new_password) < 6) {
    die("Password baru minimal 6 karakter.");
}

// Cek password lama
$admin_id = $_SESSION['admin_id'];
$stmt = $conn->prepare("SELECT password FROM admin WHERE id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

if (md5($old_password) !== $admin['password']) {
    die("Password lama salah.");
}

// Update password baru
$new_password_hash = md5($new_password);
$update_stmt = $conn->prepare("UPDATE admin SET password = ? WHERE id = ?");
$update_stmt->bind_param("si", $new_password_hash, $admin_id);

if ($update_stmt->execute()) {
    echo "Password berhasil diubah.";
} else {
    echo "Gagal mengubah password: " . $update_stmt->error;
}

$stmt->close();
$update_stmt->close();
$conn->close();
?>