<?php
include '../koneksi.php';
$id = $_GET['id'];
$data = $conn->query("SELECT * FROM harga WHERE id=$id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jenis = $_POST['jenis_layanan'];
    $kategori = $_POST['kategori'];
    $harga = $_POST['harga'];

    $conn->query("UPDATE harga SET jenis_layanan='$jenis', kategori='$kategori', harga='$harga' WHERE id=$id");
    header("Location: ../admin.php#harga");
}
?>
<form method="POST">
    <h3>Edit Harga</h3>
    <input type="text" name="jenis_layanan" value="<?= $data['jenis_layanan'] ?>"><br>
    <input type="text" name="kategori" value="<?= $data['kategori'] ?>"><br>
    <input type="number" name="harga" value="<?= $data['harga'] ?>"><br>
    <button type="submit">Update</button>
</form>
