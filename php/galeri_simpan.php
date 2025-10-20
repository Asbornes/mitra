<?php
include '../koneksi.php';

$id = $_POST['id'] ?? '';
$judul = mysqli_real_escape_string($conn, $_POST['judul']);
$foto = '';

if (!empty($_FILES['foto']['name'])) {
    $targetDir = "../uploads/";
    if (!file_exists($targetDir)) mkdir($targetDir);
    $fileName = time() . "_" . basename($_FILES["foto"]["name"]);
    $targetFile = $targetDir . $fileName;
    $ext = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $targetFile)) {
            $foto = $fileName;
        }
    }
}

if ($id == '') {
    $sql = "INSERT INTO galeri (judul, foto) VALUES ('$judul', '$foto')";
} else {
    $sql = "UPDATE galeri SET judul='$judul'";
    if ($foto != '') $sql .= ", foto='$foto'";
    $sql .= " WHERE id=$id";
}

if ($conn->query($sql)) {
    echo "Data galeri berhasil disimpan.";
} else {
    echo "Gagal menyimpan data: " . $conn->error;
}
?>
