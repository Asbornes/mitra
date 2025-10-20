<?php
include '../koneksi.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    $foto = $_FILES['foto']['name'];
    $tmp = $_FILES['foto']['tmp_name'];
    move_uploaded_file($tmp, "../uploads/".$foto);

    $conn->query("INSERT INTO galeri (judul, foto) VALUES ('$judul','$foto')");
    header("Location: ../admin.php#galeri");
}
?>
<form method="POST" enctype="multipart/form-data">
    <h3>Upload Foto Galeri</h3>
    <input type="text" name="judul" placeholder="Judul Foto" required><br>
    <input type="file" name="foto" required><br>
    <button type="submit">Upload</button>
</form>
