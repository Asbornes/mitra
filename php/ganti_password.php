<?php
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old = md5($_POST['old_password']);
    $new = md5($_POST['new_password']);
    $confirm = md5($_POST['confirm_password']);

    $data = $conn->query("SELECT * FROM admin WHERE id=1")->fetch_assoc();

    if ($old !== $data['password']) {
        echo "Password lama salah!";
    } elseif ($new !== $confirm) {
        echo "Konfirmasi password tidak cocok!";
    } else {
        $conn->query("UPDATE admin SET password='$new' WHERE id=1");
        echo "Password berhasil diubah!";
    }
}
?>
<form method="POST">
    <h3>Ganti Password</h3>
    <input type="password" name="old_password" placeholder="Password Lama" required><br>
    <input type="password" name="new_password" placeholder="Password Baru" required><br>
    <input type="password" name="confirm_password" placeholder="Konfirmasi Password" required><br>
    <button type="submit">Ganti</button>
</form>
