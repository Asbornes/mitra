<?php
include '../koneksi.php';

$id = $_POST['id'] ?? '';
$jenis = mysqli_real_escape_string($conn, $_POST['jenis_layanan']);
$kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
$harga = intval($_POST['harga']);

if ($id == '') {
    $sql = "INSERT INTO harga (jenis_layanan, kategori, harga) VALUES ('$jenis', '$kategori', $harga)";
} else {
    $sql = "UPDATE harga SET jenis_layanan='$jenis', kategori='$kategori', harga=$harga WHERE id=$id";
}

if ($conn->query($sql)) {
    echo "Data harga berhasil disimpan.";
} else {
    echo "Gagal menyimpan data: " . $conn->error;
}
?>
