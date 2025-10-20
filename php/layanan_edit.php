<?php
include '../koneksi.php';
$id = $_GET['id'];
$data = $conn->query("SELECT * FROM layanan WHERE id=$id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama_layanan'];
    $desk = $_POST['deskripsi'];
    $harga = $_POST['harga_mulai'];

    if (!empty($_FILES['foto']['name'])) {
        $foto = $_FILES['foto']['name'];
        $tmp = $_FILES['foto']['tmp_name'];
        move_uploaded_file($tmp, "../uploads/$foto");
        $conn->query("UPDATE layanan SET nama_layanan='$nama', deskripsi='$desk', harga_mulai='$harga', foto='$foto' WHERE id=$id");
    } else {
        $conn->query("UPDATE layanan SET nama_layanan='$nama', deskripsi='$desk', harga_mulai='$harga' WHERE id=$id");
    }
    header("Location: ../admin.php#layanan");
}
?>
<form method="POST" enctype="multipart/form-data">
    <h3>Edit Layanan</h3>
    <input type="text" name="nama_layanan" value="<?= $data['nama_layanan'] ?>" required><br>
    <textarea name="deskripsi"><?= $data['deskripsi'] ?></textarea><br>
    <input type="number" name="harga_mulai" value="<?= $data['harga_mulai'] ?>"><br>
    <input type="file" name="foto"><br>
    <button type="submit">Update</button>
</form>
