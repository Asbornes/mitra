<?php
session_start();
if (!isset($_SESSION['adminLoggedIn'])) {
    header('Location: ../login.php');
    exit;
}
include '../koneksi.php';
include '../image_helper.php';

$id = $_POST['id'] ?? '';
$judul = mysqli_real_escape_string($conn, $_POST['judul']);
$foto = '';

// Handle file upload
if (!empty($_FILES['foto']['name'])) {
    // Validasi file
    $validation = validateImageUpload($_FILES['foto'], 5242880); // 5MB
    
    if (!$validation['success']) {
        die($validation['message']);
    }
    
    $targetDir = "../uploads/";
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    
    $ext = strtolower(pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION));
    $fileName = time() . "_galeri_" . uniqid() . "." . $ext;
    $tempFile = $_FILES["foto"]["tmp_name"];
    $targetFile = $targetDir . $fileName;
    
    // SMART RESIZE: 600x400px dengan mode 'cover'
    if (smartResizeImage($tempFile, $targetFile, 600, 400, 90, 'cover')) {
        $foto = $fileName;
        
        // Hapus foto lama jika update
        if ($id != '') {
            $getOld = $conn->prepare("SELECT foto FROM galeri WHERE id = ?");
            $getOld->bind_param("i", $id);
            $getOld->execute();
            $result = $getOld->get_result();
            if ($row = $result->fetch_assoc()) {
                $oldFile = $targetDir . $row['foto'];
                if (file_exists($oldFile) && $row['foto'] != '') {
                    unlink($oldFile);
                }
            }
            $getOld->close();
        }
    } else {
        die("Gagal memproses gambar.");
    }
}

// Simpan ke database
if ($id == '') {
    // INSERT
    if ($foto == '') {
        die("Foto harus diupload untuk galeri baru!");
    }
    
    $stmt = $conn->prepare("INSERT INTO galeri (judul, foto) VALUES (?, ?)");
    $stmt->bind_param("ss", $judul, $foto);
} else {
    // UPDATE
    if ($foto != '') {
        $stmt = $conn->prepare("UPDATE galeri SET judul=?, foto=? WHERE id=?");
        $stmt->bind_param("ssi", $judul, $foto, $id);
    } else {
        $stmt = $conn->prepare("UPDATE galeri SET judul=? WHERE id=?");
        $stmt->bind_param("si", $judul, $id);
    }
}

if ($stmt->execute()) {
    echo "Data galeri berhasil disimpan.";
} else {
    echo "Gagal menyimpan data: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>