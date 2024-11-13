<?php
include "Koneksi.php";

// Mengecek apakah form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Tangkap data dari form
    $nama_lengkap = $_POST['nama_lengkap'];
    $email = $_POST['email'];
    $no_hp = $_POST['no_hp'];
    $kategori_tiket = $_POST['kategori_tiket'];
    $jumlah_pesanan = (int)$_POST['jumlah_pesanan'];
    $metode_pembayaran = $_POST['metode_pembayaran'];

    // Hitung harga per tiket dan total harga berdasarkan kategori tiket
    $harga_per_tiket = 0;
    if ($kategori_tiket == "Early_Bird") {
        $harga_per_tiket = 15000;
    } elseif ($kategori_tiket == "Presale_1") {
        $harga_per_tiket = 25000;
    } elseif ($kategori_tiket == "Presale_2") {
        $harga_per_tiket = 40000;
    } elseif ($kategori_tiket == "Normal_Ticket") {
        $harga_per_tiket = 50000;
    }
    $total_harga = $harga_per_tiket * $jumlah_pesanan;

    // Cek stok yang tersisa di database
    $query = "SELECT stok FROM tiket WHERE kategori = '$kategori_tiket'";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();

    // Siapkan dan jalankan query untuk menyimpan data ke tabel
    $sql = "INSERT INTO pembayaran_tiket (nama_lengkap, email, no_hp, kategori_tiket, jumlah_pesanan, harga_per_tiket, total_harga, metode_pembayaran)
            VALUES ('$nama_lengkap', '$email', '$no_hp', '$kategori_tiket', $jumlah_pesanan, $harga_per_tiket, $total_harga, '$metode_pembayaran')";

    if ($row && $row['stok'] >= $jumlah_pesanan) {
        // Jika stok mencukupi, kurangi stok di database
        $newStok = $row['stok'] - $jumlah_pesanan;
        $updateQuery = "UPDATE tiket SET stok = $newStok WHERE kategori = '$kategori_tiket'";
        if ($conn->query($updateQuery)) {
            echo "<script>alert('Pemesanan berhasil! Sisa stok $kategori_tiket: $newStok');</script>";
        } else {
            echo "<script>alert('Error updating stock: " . $conn->error . "');</script>";
        }
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Data berhasil disimpan. Terima kasih atas pembelian tiket Anda.');</script>";
        } else {
            echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('Stok tiket tidak mencukupi untuk kategori $kategori_tiket.');</script>";
    }
}

echo "<script>window.location.href = 'https://forms.gle/7w3tnTZKz6uv626P7';</script>"; // Redirect setelah proses selesai
exit; // Pastikan script berhenti setelah redirect

// Menutup koneksi
$conn->close();
?>