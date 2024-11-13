<?php
// update_stok.php

// Koneksi ke database
include('koneksi.php'); // Sesuaikan dengan file koneksi database Anda

// Ambil data kategori dan jumlah tiket yang dikirimkan
$kategoriTiket = $_POST['kategori'];
$jumlahTiket = $_POST['stok_baru'];

// Query untuk mengurangi stok tiket
$query = "UPDATE tiket SET stok = stok - ? WHERE kategori = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$jumlahTiket, $kategoriTiket]);

// Cek apakah update berhasil
if ($stmt->rowCount() > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>