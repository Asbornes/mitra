<?php
session_start();
if (!isset($_SESSION['adminLoggedIn'])) {
    header('Location: ../login.php');
    exit;
}
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old = md5($_POST['old_password']);
    $new = md5($_POST['new_password']);
    $confirm = md5($_POST['confirm_password']);

    // Ambil password saat ini dengan prepared statement
    $stmt = $conn->prepare("SELECT password FROM admin WHERE id=1");
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();

    if (!$data) {
        echo "Data admin tidak ditemukan!";
    } elseif ($old !== $data['password']) {
        echo "Password lama salah!";
    } elseif ($new !== $confirm) {
        echo "Konfirmasi password tidak cocok!";
    } elseif (strlen($_POST['new_password']) < 6) {
        echo "Password baru minimal 6 karakter!";
    } else {
        // Update password dengan prepared statement
        $stmt = $conn->prepare("UPDATE admin SET password=? WHERE id=1");
        $stmt->bind_param("s", $new);
        
        if ($stmt->execute()) {
            echo "Password berhasil diubah!";
        } else {
            echo "Gagal mengubah password: " . $stmt->error;
        }
        $stmt->close();
    }
}
$conn->close();
?>
<form method="POST">
    <h3>Ganti Password</h3>
    <input type="password" name="old_password" placeholder="Password Lama" required><br>
    <input type="password" name="new_password" placeholder="Password Baru (min 6 karakter)" required><br>
    <input type="password" name="confirm_password" placeholder="Konfirmasi Password" required><br>
    <button type="submit">Ganti</button>
</form>