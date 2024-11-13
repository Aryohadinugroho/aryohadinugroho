<?php
// get_stok.php

include "koneksi.php";

// Query untuk mengambil semua kategori tiket dan stoknya
$sql = "SELECT harga, kategori, stok FROM tiket";
$result = $conn->query($sql);

// Menyusun array untuk menyimpan stok tiket
$stokTiket = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $stokTiket[] = $row;
    }
}

// Menutup koneksi database
$conn->close();

// Mengirimkan data dalam format JSON
header('Content-Type: application/json');
echo json_encode($stokTiket);
?>
