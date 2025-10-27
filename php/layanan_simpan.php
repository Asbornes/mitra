<?php
session_start();
if (!isset($_SESSION['adminLoggedIn'])) {
    header('Location: ../login.php');
    exit;
}
include '../koneksi.php';
include '../image_helper.php';

$id = $_POST['id'] ?? '';
$nama = $_POST['nama_layanan'] ?? '';
$desk = $_POST['deskripsi'] ?? '';
$harga = intval($_POST['harga_mulai']);
$foto = '';

// Handle file upload dengan smart resize
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
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
    $fileName = time() . "_layanan_" . uniqid() . "." . $ext;
    $tempFile = $_FILES["foto"]["tmp_name"];
    $targetFile = $targetDir . $fileName;
    
    // SMART RESIZE: 800x600px dengan mode 'contain'
    // Gambar akan di-resize proporsional, tidak akan terpotong
    // Jika rasio berbeda, akan ada padding putih
    if (smartResizeImage($tempFile, $targetFile, 800, 600, 90, 'contain')) {
        $foto = $fileName;
        
        // Hapus foto lama jika update
        if ($id != '') {
            $getOld = $conn->prepare("SELECT foto FROM layanan WHERE id = ?");
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
    if ($foto) {
        $stmt = $conn->prepare("INSERT INTO layanan (nama_layanan, deskripsi, harga_mulai, foto) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $nama, $desk, $harga, $foto);
    } else {
        $stmt = $conn->prepare("INSERT INTO layanan (nama_layanan, deskripsi, harga_mulai) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $nama, $desk, $harga);
    }
} else {
    // UPDATE
    if ($foto) {
        $stmt = $conn->prepare("UPDATE layanan SET nama_layanan=?, deskripsi=?, harga_mulai=?, foto=? WHERE id=?");
        $stmt->bind_param("ssisi", $nama, $desk, $harga, $foto, $id);
    } else {
        $stmt = $conn->prepare("UPDATE layanan SET nama_layanan=?, deskripsi=?, harga_mulai=? WHERE id=?");
        $stmt->bind_param("ssii", $nama, $desk, $harga, $id);
    }
}

if ($stmt->execute()) {
    echo "Data layanan berhasil disimpan. Gambar sudah disesuaikan ukurannya (tidak di-crop).";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>