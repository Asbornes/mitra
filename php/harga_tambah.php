<?php
include '../koneksi.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jenis = $_POST['jenis_layanan'];
    $kategori = $_POST['kategori'];
    $harga = $_POST['harga'];

    $conn->query("INSERT INTO harga (jenis_layanan, kategori, harga)
                  VALUES ('$jenis', '$kategori', '$harga')");
    header("Location: ../admin.php#harga");
}
?>
<form method="POST">
    <h3>Tambah Paket Harga</h3>
    <input type="text" name="jenis_layanan" placeholder="Jenis Layanan" required><br>
    <input type="text" name="kategori" placeholder="Kategori" required><br>
    <input type="number" name="harga" placeholder="Harga" required><br>
    <button type="submit">Simpan</button>
</form>
