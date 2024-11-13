<?php
include "koneksi.php";
// Pastikan Anda mengaktifkan error reporting untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Mendapatkan kategori dari parameter GET
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';

if ($kategori) {

    // Query untuk mendapatkan stok berdasarkan kategori
    $query = "SELECT stok FROM tiket WHERE kategori = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $kategori);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Jika ada hasil
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(['stok' => $row['stok']]);
    } else {
        echo json_encode(['stok' => 0]);  // Kategori tidak ditemukan
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['stok' => 0]);  // Kategori kosong
}
?>
