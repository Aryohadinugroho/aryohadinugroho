<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>IDTH - Home</title>
    <link rel="stylesheet" href="Home.css" />
    <script src="https://kit.fontawesome.com/4592f70558.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <div class="navbar">
            <div class="logo">
                <a href="#"><img src="Foto/I.png" alt="Logo"></a>
            </div>
            <ul>
                <li><a href="Home.html">Home</a></li>
                <li><a href="About.html">About</a></li>
                <li><a href="Ticket.php">Ticket</a></li>
                <li><a href="Contact.html">Contact</a></li>
                <li><a href="Feedback.html">Feedback</a></li>
            </ul>
        </div>
    </header>

    <section class="venue">
        <hr />
        <h2>Seating Plan</h2>
        <img src="Foto/ser.jpg" alt="Venue Image" class="venue-image" />
        <br /><br />
        <table class="stock" id="stokTiket">
            <thead>
                <tr>
                    <th>Ticket Type</th>
                    <th>Stock</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data stok tiket akan ditampilkan di sini -->
            </tbody>
        </table>
        <script>
        // Fungsi untuk mengambil data stok tiket dan menampilkannya dalam tabel
        function updateStokTiket() {
            $.ajax({
                url: 'get_stok.php', // URL untuk mengambil data stok tiket
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Menampilkan stok tiket di dalam div #stokTiket
                    $('#stokTiket tbody').empty(); // Kosongkan kontainer sebelum menambah data baru
                    // Menambahkan data stok tiket ke dalam tabel
                    response.forEach(function(tiket) {
                        var hargaFormatted = parseFloat(tiket.harga).toLocaleString('id-ID', {
                            style: 'currency',
                            currency: 'IDR'
                        });
                        $('#stokTiket tbody').append(`
                            <tr>
                                <td>${tiket.kategori}</td>
                                <td>${tiket.stok}</td>
                                <td>${hargaFormatted}</td>
                            </tr>
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
        <table class="stock">
            <tbody>
                <tr class="action">
                    <td colspan="4">
                        <br />
                        <a href="Pembayaran.php" class="buy-tickets-btn">Payment Tickets Now!</a>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Spacing -->
        <br /><br />
        
    <!-- Footer Section -->
    <footer class="footer">
        <div class="container3">
            <div class="row">
                <!-- Company Links -->
                <div class="footer-col">
                    <h4>Company</h4>
                    <ul>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Our Services</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Affiliate Program</a></li>
                    </ul>
                </div>

                <!-- Help Links -->
                <div class="footer-col">
                    <h4>Get Help</h4>
                    <ul>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Shipping</a></li>
                        <li><a href="#">Returns</a></li>
                        <li><a href="#">Order Status</a></li>
                        <li><a href="#">Payment Options</a></li>
                    </ul>
                </div>

                <!-- Social Links -->
                <div class="footer-col">
                    <h4>Follow Us</h4>
                    <div class="social-links">
                        <!-- Social Media Icons -->
                        <a href="#"><i class="fab fa-tiktok"></i></a> 
                        <!-- Add a valid link for TikTok -->
                        <a href="#"><i class="fab fa-instagram"></i></a> 
                        <!-- Add a valid link for Instagram -->
                    </div>

                </div>

            </div>
            <!-- Copyright Notice -->
            <p>&copy; DEVASANA NIRMATA ISVARA TICKET 2024. All rights reserved.</p>

        </div>

    </footer>

  </body>
  </html>
