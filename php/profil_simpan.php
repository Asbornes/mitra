<?php
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama_laundry'];
    $alamat = $_POST['alamat'];
    $wa = $_POST['whatsapp'];
    $email = $_POST['email'];
    $senin = $_POST['jam_senin'];
    $minggu = $_POST['jam_minggu'];

    $cek = $conn->query("SELECT * FROM profil WHERE id=1");
    if ($cek->num_rows > 0) {
        $conn->query("UPDATE profil SET nama_laundry='$nama', alamat='$alamat', whatsapp='$wa', email='$email', jam_senin='$senin', jam_minggu='$minggu' WHERE id=1");
    } else {
        $conn->query("INSERT INTO profil (id, nama_laundry, alamat, whatsapp, email, jam_senin, jam_minggu)
                      VALUES (1,'$nama','$alamat','$wa','$email','$senin','$minggu')");
    }
    header("Location: ../admin.php#profil");
}
?>
