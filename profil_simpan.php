<?php
session_start();
if (!isset($_SESSION['adminLoggedIn'])) {
    header('Location: ../login.php');
    exit;
}

include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Informasi Laundry
    $nama_laundry = $conn->real_escape_string($_POST['nama_laundry']);
    $alamat = $conn->real_escape_string($_POST['alamat']);
    $whatsapp = $conn->real_escape_string($_POST['whatsapp']);
    $email = $conn->real_escape_string($_POST['email']);
    $jam_senin = $conn->real_escape_string($_POST['jam_senin']);
    $jam_minggu = $conn->real_escape_string($_POST['jam_minggu']);
    
    // Hero Section
    $hero_title = $conn->real_escape_string($_POST['hero_title']);
    $hero_subtitle = $conn->real_escape_string($_POST['hero_subtitle']);
    
    // Tentang Kami
    $about_title = $conn->real_escape_string($_POST['about_title']);
    $about_paragraph1 = $conn->real_escape_string($_POST['about_paragraph1']);
    $about_paragraph2 = $conn->real_escape_string($_POST['about_paragraph2']);
    
    // 4 Features
    $feature1_icon = $conn->real_escape_string($_POST['feature1_icon']);
    $feature1_title = $conn->real_escape_string($_POST['feature1_title']);
    $feature1_desc = $conn->real_escape_string($_POST['feature1_desc']);
    
    $feature2_icon = $conn->real_escape_string($_POST['feature2_icon']);
    $feature2_title = $conn->real_escape_string($_POST['feature2_title']);
    $feature2_desc = $conn->real_escape_string($_POST['feature2_desc']);
    
    $feature3_icon = $conn->real_escape_string($_POST['feature3_icon']);
    $feature3_title = $conn->real_escape_string($_POST['feature3_title']);
    $feature3_desc = $conn->real_escape_string($_POST['feature3_desc']);
    
    $feature4_icon = $conn->real_escape_string($_POST['feature4_icon']);
    $feature4_title = $conn->real_escape_string($_POST['feature4_title']);
    $feature4_desc = $conn->real_escape_string($_POST['feature4_desc']);

    // Cek apakah data sudah ada
    $check = $conn->query("SELECT id FROM profil WHERE id = 1");
    
    if ($check->num_rows > 0) {
        // Update existing record
        $sql = "UPDATE profil SET 
                nama_laundry = '$nama_laundry',
                alamat = '$alamat',
                whatsapp = '$whatsapp',
                email = '$email',
                jam_senin = '$jam_senin',
                jam_minggu = '$jam_minggu',
                hero_title = '$hero_title',
                hero_subtitle = '$hero_subtitle',
                about_title = '$about_title',
                about_paragraph1 = '$about_paragraph1',
                about_paragraph2 = '$about_paragraph2',
                feature1_icon = '$feature1_icon',
                feature1_title = '$feature1_title',
                feature1_desc = '$feature1_desc',
                feature2_icon = '$feature2_icon',
                feature2_title = '$feature2_title',
                feature2_desc = '$feature2_desc',
                feature3_icon = '$feature3_icon',
                feature3_title = '$feature3_title',
                feature3_desc = '$feature3_desc',
                feature4_icon = '$feature4_icon',
                feature4_title = '$feature4_title',
                feature4_desc = '$feature4_desc'
                WHERE id = 1";
    } else {
        // Insert new record
        $sql = "INSERT INTO profil (
                    id, nama_laundry, alamat, whatsapp, email, 
                    jam_senin, jam_minggu, hero_title, hero_subtitle,
                    about_title, about_paragraph1, about_paragraph2,
                    feature1_icon, feature1_title, feature1_desc,
                    feature2_icon, feature2_title, feature2_desc,
                    feature3_icon, feature3_title, feature3_desc,
                    feature4_icon, feature4_title, feature4_desc
                ) VALUES (
                    1, '$nama_laundry', '$alamat', '$whatsapp', '$email', 
                    '$jam_senin', '$jam_minggu', '$hero_title', '$hero_subtitle',
                    '$about_title', '$about_paragraph1', '$about_paragraph2',
                    '$feature1_icon', '$feature1_title', '$feature1_desc',
                    '$feature2_icon', '$feature2_title', '$feature2_desc',
                    '$feature3_icon', '$feature3_title', '$feature3_desc',
                    '$feature4_icon', '$feature4_title', '$feature4_desc'
                )";
    }

    if ($conn->query($sql)) {
        echo "<script>
            alert('✅ Profil berhasil disimpan!\\n\\nPerubahan Hero Section, About, dan Features telah diupdate.');
            window.location.href = '../admin.php#profil';
        </script>";
    } else {
        echo "<script>
            alert('❌ Error: " . $conn->error . "');
            window.history.back();
        </script>";
    }
}

$conn->close();
?>