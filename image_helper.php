<?php
function smartResizeImage($source_path, $target_path, $target_width, $target_height, $quality = 90, $fit_mode = 'contain') {
    if (!file_exists($source_path)) {
        return false;
    }
    
    $image_info = getimagesize($source_path);
    if ($image_info === false) {
        return false;
    }
    
    list($source_width, $source_height, $image_type) = $image_info;
    
    // Buat resource gambar
    switch ($image_type) {
        case IMAGETYPE_JPEG:
            $source_image = imagecreatefromjpeg($source_path);
            break;
        case IMAGETYPE_PNG:
            $source_image = imagecreatefrompng($source_path);
            break;
        case IMAGETYPE_GIF:
            $source_image = imagecreatefromgif($source_path);
            break;
        default:
            return false;
    }
    
    if (!$source_image) {
        return false;
    }
    
    // Hitung ratio
    $source_ratio = $source_width / $source_height;
    $target_ratio = $target_width / $target_height;
    
    if ($fit_mode === 'contain') {
        // MODE CONTAIN: Resize agar muat dalam frame, tambah padding jika perlu
        // Gambar tidak akan terpotong sama sekali
        
        if ($source_ratio > $target_ratio) {
            // Gambar lebih lebar, fit ke width
            $new_width = $target_width;
            $new_height = round($target_width / $source_ratio);
            $x_offset = 0;
            $y_offset = round(($target_height - $new_height) / 2);
        } else {
            // Gambar lebih tinggi, fit ke height
            $new_height = $target_height;
            $new_width = round($target_height * $source_ratio);
            $x_offset = round(($target_width - $new_width) / 2);
            $y_offset = 0;
        }
        
        // Buat canvas dengan background putih
        $target_image = imagecreatetruecolor($target_width, $target_height);
        $white = imagecolorallocate($target_image, 255, 255, 255);
        imagefill($target_image, 0, 0, $white);
        
        // Preserve transparency untuk PNG/GIF
        if ($image_type == IMAGETYPE_PNG || $image_type == IMAGETYPE_GIF) {
            imagealphablending($target_image, true);
            imagesavealpha($target_image, true);
        }
        
        // Copy gambar ke canvas
        imagecopyresampled(
            $target_image,
            $source_image,
            $x_offset, $y_offset,
            0, 0,
            $new_width, $new_height,
            $source_width, $source_height
        );
        
    } else {
        // MODE COVER: Resize minimal, crop hanya jika sangat perlu
        
        // Hitung ukuran resize yang lebih besar dari target
        $scale = max($target_width / $source_width, $target_height / $source_height);
        $temp_width = round($source_width * $scale);
        $temp_height = round($source_height * $scale);
        
        // Hitung posisi crop (center)
        $crop_x = round(($temp_width - $target_width) / 2);
        $crop_y = round(($temp_height - $target_height) / 2);
        
        // Buat canvas target
        $target_image = imagecreatetruecolor($target_width, $target_height);
        
        // Preserve transparency
        if ($image_type == IMAGETYPE_PNG || $image_type == IMAGETYPE_GIF) {
            imagealphablending($target_image, false);
            imagesavealpha($target_image, true);
            $transparent = imagecolorallocatealpha($target_image, 255, 255, 255, 127);
            imagefilledrectangle($target_image, 0, 0, $target_width, $target_height, $transparent);
        }
        
        // Buat gambar temporary dengan ukuran scaled
        $temp_image = imagecreatetruecolor($temp_width, $temp_height);
        
        if ($image_type == IMAGETYPE_PNG || $image_type == IMAGETYPE_GIF) {
            imagealphablending($temp_image, false);
            imagesavealpha($temp_image, true);
            $transparent = imagecolorallocatealpha($temp_image, 255, 255, 255, 127);
            imagefilledrectangle($temp_image, 0, 0, $temp_width, $temp_height, $transparent);
        }
        
        // Resize ke temporary image
        imagecopyresampled(
            $temp_image,
            $source_image,
            0, 0, 0, 0,
            $temp_width, $temp_height,
            $source_width, $source_height
        );
        
        // Crop dari center
        imagecopy(
            $target_image,
            $temp_image,
            0, 0,
            $crop_x, $crop_y,
            $target_width, $target_height
        );
        
        imagedestroy($temp_image);
    }
    
    // Simpan gambar hasil
    $result = false;
    switch ($image_type) {
        case IMAGETYPE_JPEG:
            $result = imagejpeg($target_image, $target_path, $quality);
            break;
        case IMAGETYPE_PNG:
            $png_quality = round((100 - $quality) / 11.11);
            $result = imagepng($target_image, $target_path, $png_quality);
            break;
        case IMAGETYPE_GIF:
            $result = imagegif($target_image, $target_path);
            break;
    }
    
    imagedestroy($source_image);
    imagedestroy($target_image);
    
    return $result;
}

/**
 * Validasi file upload gambar
 */
function validateImageUpload($file, $max_size = 5242880) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return [
            'success' => false,
            'message' => 'Terjadi error saat upload file.'
        ];
    }
    
    if ($file['size'] > $max_size) {
        $max_mb = round($max_size / 1048576, 1);
        return [
            'success' => false,
            'message' => "Ukuran file terlalu besar. Maksimal {$max_mb}MB."
        ];
    }
    
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mime_type, $allowed_types)) {
        return [
            'success' => false,
            'message' => 'Format file tidak didukung. Gunakan JPG, PNG, atau GIF.'
        ];
    }
    
    if (getimagesize($file['tmp_name']) === false) {
        return [
            'success' => false,
            'message' => 'File yang diupload bukan gambar valid.'
        ];
    }
    
    return [
        'success' => true,
        'message' => 'Validasi berhasil.'
    ];
}
?>