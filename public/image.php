<?php

// Pastikan nama file gambar diterima dari URL
if (isset($_GET['filename'])) {
    $filename = $_GET['filename'];
    // Lokasi gambar di luar publik
    $imagePath = '../uploads/' . $filename;
   
    // Mengecek apakah file gambar ada
    if (file_exists($imagePath)) {
        // Mendapatkan tipe mime dari gambar
        $mimeType = mime_content_type($imagePath);
    
        // Mengatur header sesuai tipe mime
        header("Content-Type: $mimeType");

        // Memuat dan menampilkan gambar
        readfile($imagePath);
    } else {
        // Menampilkan gambar placeholder atau pesan kesalahan jika gambar tidak ditemukan
        header("Content-Type: image/png");
        $placeholderImage = file_get_contents('placeholder.png');
        echo $placeholderImage;
    }
} else {
    // Jika tidak ada parameter filename
    header("Content-Type: image/png");
    $placeholderImage = file_get_contents('placeholder.png');
    echo $placeholderImage;
}
