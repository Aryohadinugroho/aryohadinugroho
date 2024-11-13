<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stok Tiket Real-Time</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .tiket-container {
            margin-top: 20px;
        }
        .tiket-item {
            margin: 10px 0;
        }
        .tiket-item span {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Stok Tiket Real-Time</h1>
    <div class="tiket-container" id="stokTiket">
        <!-- Stok tiket akan ditampilkan di sini -->
    </div>

    <script>
        // Fungsi untuk mengambil data stok tiket
        function updateStokTiket() {
            $.ajax({
                url: 'get_stok.php', // URL untuk mengambil data stok tiket
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Menampilkan stok tiket di dalam div #stokTiket
                    $('#stokTiket').empty(); // Kosongkan kontainer sebelum menambah data baru
                    response.forEach(function(tiket) {
                        $('#stokTiket').append(`
                            <div class="tiket-item">
                                <span>${tiket.kategori}</span>: ${tiket.stok} tiket tersedia
                            </div>
                        `);
                    });
                },
                error: function() {
                    alert("Gagal memuat data stok tiket.");
                }
            });
        }

        // Memperbarui stok tiket setiap 5 detik
        setInterval(updateStokTiket, 5000);

        // Memuat stok tiket pertama kali saat halaman dimuat
        $(document).ready(function() {
            updateStokTiket();
        });
    </script>
</body>
</html>
