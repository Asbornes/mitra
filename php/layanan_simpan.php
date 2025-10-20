<?php
include '../koneksi.php';

$id = $_POST['id'] ?? '';
$nama = mysqli_real_escape_string($conn, $_POST['nama_layanan']);
$desk = mysqli_real_escape_string($conn, $_POST['deskripsi']);
$harga = intval($_POST['harga_mulai']);
$foto = '';

if (!empty($_FILES['foto']['name'])) {
    $targetDir = "../uploads/";
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
    // Tambah
    $sql = "INSERT INTO layanan (nama_layanan, deskripsi, harga_mulai, foto)
            VALUES ('$nama', '$desk', $harga, '$foto')";
} else {
    // Edit
    $sql = "UPDATE layanan 
            SET nama_layanan='$nama', deskripsi='$desk', harga_mulai=$harga";
    if ($foto != '') $sql .= ", foto='$foto'";
    $sql .= " WHERE id=$id";
}

if ($conn->query($sql)) {
    echo "Data layanan berhasil disimpan.";
} else {
    echo "Gagal menyimpan data: " . $conn->error;
}
?>
