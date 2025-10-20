<?php
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama_layanan'];
    $desk = $_POST['deskripsi'];
    $harga = $_POST['harga_mulai'];

    $foto = $_FILES['foto']['name'];
    $tmp = $_FILES['foto']['tmp_name'];
    $folder = "../uploads/" . $foto;
    move_uploaded_file($tmp, $folder);

    $conn->query("INSERT INTO layanan (nama_layanan, deskripsi, harga_mulai, foto)
                  VALUES ('$nama', '$desk', '$harga', '$foto')");
    header("Location: ../admin.php#layanan");
}
?>
<form method="POST" enctype="multipart/form-data">
    <h3>Tambah Layanan</h3>
    <input type="text" name="nama_layanan" placeholder="Nama Layanan" required><br>
    <textarea name="deskripsi" placeholder="Deskripsi"></textarea><br>
    <input type="number" name="harga_mulai" placeholder="Harga" required><br>
    <input type="file" name="foto"><br>
    <button type="submit">Simpan</button>
</form>
