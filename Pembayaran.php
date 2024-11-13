<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IDTH-Payment</title>
    <link rel="stylesheet" href="Pembayaran.css">
    <link href="https://fonts.googleapis.com/css?family=Cabin|Indie+Flower|Inknut+Antiqua|Lora|Ravi+Prakash" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
    <script src="https://kit.fontawesome.com/4592f70558.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>

        function checkOut() {
            var kategoriTiket = document.getElementById("kategori_tiket").value;
            var jumlahTiket = parseInt(document.getElementById("jumlah_pesanan").value);

            // Melakukan pengecekan stok tiket di server
            return $.ajax({
                url: 'cek_stok.php',
                method: 'GET',
                dataType: 'json',
                data: { kategori: kategoriTiket }, // Kirim data kategori untuk pengecekan stok
                success: function(response) {
                    // Pastikan response memiliki data stok yang valid
                    if (response && response.stok !== undefined) {
                        var stokTiket = response.stok;
                        console.log("Stok Tiket:", stokTiket);

                        // Cek apakah ada tiket yang tersedia sesuai jumlah yang dipilih
                        if (stokTiket >= jumlahTiket) {
                            // Kurangi stok berdasarkan jumlah tiket yang dipesan
                            stokTiket -= jumlahTiket;

                            // Update stok di server jika perlu
                            // Kirimkan request ke server untuk mengupdate stok (opsional)
                            $.ajax({
                                url: 'update_stok.php',  // Ganti dengan URL endpoint update stok jika diperlukan
                                method: 'POST',
                                data: {
                                    kategori: kategoriTiket,
                                    stok_baru: stokTiket
                                },
                                success: function() {
                                    alert("Pemesanan berhasil! Sisa stok " + kategoriTiket + ": " + stokTiket);
                                    document.getElementById("checkout-form").submit();
                                },
                                error: function() {
                                    alert("Klik Tutup Untuk Masuk Ke Page Berikutnya");
                                }
                            });

                        } else {
                            alert("Stok tiket " + kategoriTiket + " tidak mencukupi. Tersisa: " + stokTiket);
                        }
                    } else {
                        alert("Gagal mendapatkan data stok. Silakan coba lagi.");
                    }
                },
                error: function() {
                    alert(" Klik Tutup Untuk Masuk Ke Page Berikutnya");
                }
            });
        }



        // Menangani proses submit form
        $('#checkout-form').submit(function(event) {
            event.preventDefault(); // Mencegah form untuk submit secara langsung

            var kategoriTiket = $('#kategori_tiket').val();
            var jumlahPesanan = $('#jumlah_pesanan').val();

            // Mengecek stok tiket terlebih dahulu
            checkStokTiket(kategoriTiket, jumlahPesanan).then(function(isStokCukup) {
                if (isStokCukup) {
                    // Jika stok cukup, lakukan submit form secara manual
                    this.submit();
                }
            });
        });

        // Menentukan harga untuk tiap kategori tiket
        const hargaTiket = {
            "Early_Bird": 15000, // Harga tiket Early Bird
            "Presale_1": 25000,  // Harga tiket Presale 1
            "Presale_2": 40000,  // Harga tiket Presale 2
            "Normal_Ticket": 50000 // Harga tiket Normal
        };

        // Fungsi untuk memvalidasi jumlah tiket berdasarkan kategori
        function checkTicketLimit() {
            var kategoriTiket = document.getElementById("kategori_tiket").value;
            var jumlahTiketSelect = document.getElementById("jumlah_pesanan");
            var options = jumlahTiketSelect.options; // Ambil semua option dalam select
            
            // Hapus semua opsi tiket terlebih dahulu
            for (var i = options.length - 1; i > 0; i--) {
                options[i].remove();
            }

            // Tambahkan opsi sesuai dengan kategori tiket yang dipilih
            if (kategoriTiket !== "Early_Bird") {
                // Jika kategori bukan Early_Bird, tambahkan opsi 1-5
                for (var i = 1; i <= 5; i++) {
                    var option = new Option(i, i);
                    jumlahTiketSelect.add(option);
                }
            } else {
                // Jika kategori Early_Bird, hanya tambahkan opsi 1-3
                for (var i = 1; i <= 3; i++) {
                    var option = new Option(i, i);
                    jumlahTiketSelect.add(option);
                }
            }
        }

        // Fungsi untuk memperbarui harga tiket berdasarkan kategori dan jumlah
        function updateTicketPrice() {
            var kategoriTiket = document.getElementById("kategori_tiket").value;
            var jumlahTiket = document.getElementById("jumlah_pesanan").value;

            if (kategoriTiket && jumlahTiket) {
                var hargaPerTiket = hargaTiket[kategoriTiket];
                var totalHarga = hargaPerTiket * jumlahTiket;

                // Update harga per tiket dan total harga
                document.getElementById("harga_per_tiket").textContent = "Harga per tiket: Rp " + hargaPerTiket.toLocaleString();
                document.getElementById("total_harga").textContent = "Total Harga: Rp " + totalHarga.toLocaleString();
            }
        }
        
        // Fungsi untuk memeriksa tanggal saat ini dan mengaktifkan/menonaktifkan kategori tiket
        function checkTicketAvailabilityByDate() {
            var today = new Date();
            var kategoriTiketSelect = document.getElementById("kategori_tiket");

            // Tentukan waktu penting untuk setiap kategori tiket
            var tanggalPresale1Awal = new Date("2024-11-15T00:00:00");
            var tanggalPresale1Akhir = new Date("2024-11-20T23:59:59");
            var tanggalPresale2Awal = new Date("2024-11-23T00:00:00");
            var tanggalPresale2Akhir = new Date("2024-12-03T23:59:59");
            var tanggalNormalAwal = new Date("2024-12-07T00:00:00");
            var tanggalNormalAkhir = new Date("2024-12-27T23:59:59");

            // Menonaktifkan atau mengaktifkan kategori tiket berdasarkan tanggal
            if (today >= tanggalPresale1Awal && today < tanggalPresale1Akhir) {
                // Presale 1 aktif, yang lain dinonaktifkan
                kategoriTiketSelect.querySelector('option[value="Early_Bird"]').disabled = true;
                kategoriTiketSelect.querySelector('option[value="Presale_1"]').disabled = false;
                kategoriTiketSelect.querySelector('option[value="Presale_2"]').disabled = true;
                kategoriTiketSelect.querySelector('option[value="Normal_Ticket"]').disabled = true;
            } else if (today >= tanggalPresale2Awal && today < tanggalPresale2Akhir) {
                // Presale 2 aktif, yang lain dinonaktifkan
                kategoriTiketSelect.querySelector('option[value="Early_Bird"]').disabled = true;
                kategoriTiketSelect.querySelector('option[value="Presale_1"]').disabled = true;
                kategoriTiketSelect.querySelector('option[value="Presale_2"]').disabled = false;
                kategoriTiketSelect.querySelector('option[value="Normal_Ticket"]').disabled = true;
            } else if (today >= tanggalNormalAwal && today < tanggalNormalAkhir) {
                // Normal Ticket aktif
                kategoriTiketSelect.querySelector('option[value="Early_Bird"]').disabled = true;
                kategoriTiketSelect.querySelector('option[value="Presale_1"]').disabled = true;
                kategoriTiketSelect.querySelector('option[value="Presale_2"]').disabled = true;
                kategoriTiketSelect.querySelector('option[value="Normal_Ticket"]').disabled = false;
            }
        }
        
        // Menambahkan event listener untuk memanggil fungsi setiap kali kategori tiket atau jumlah tiket berubah
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("kategori_tiket").addEventListener("change", checkTicketLimit);
            document.getElementById("jumlah_pesanan").addEventListener("change", updateTicketPrice);
            document.querySelector(".submit-btn").addEventListener("click", function(event) {
                checkOut();
                checkTicketAvailabilityByDate();
                checkStokTiket();
                updateTicketPrice();
                checkTicketLimit();
            });
        });
    </script>    
</head>
<body>
    <header>
        <div class="navbar">
          <div class="logo">
            <a href="#"><img src="Foto/I.png" alt=""></a>
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
    <div class="container">
        <form id="checkout-form" action="proses_pembayaran.php" method="post">
            <div class="row">
                <div class="col">
                    <h3 class="title">PEMBAYARAN KONSER</h3>
                    <div class="inputbox">
                        <span>Nama Lengkap :</span>
                        <input type="text" name="nama_lengkap" placeholder="Nama Lengkap Anda.." required>
                    </div>
                    <div class="inputbox">
                        <span>Email :</span>
                        <input type="email" name="email" placeholder="example@gmail.com" required>
                    </div>
                    <div class="inputbox">
                        <span>No. HP :</span>
                        <input type="text" name="no_hp" value="+62" placeholder="+62" required>
                    </div>
                    <style>
                        .inputbox {
                            margin: 10px 0;
                        }
                    
                        select {
                            font-size: 16px; /* Ukuran font lebih besar */
                            padding: 10px;   /* Padding untuk ruang di dalam dropdown */
                            width: 100%;      /* Lebar penuh untuk responsif */
                            border: 1px solid #ccc; /* Border untuk tampilan yang lebih baik */
                            border-radius: 5px; /* Sudut yang membulat */
                            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Bayangan untuk efek kedalaman */
                        }
                    </style>
                    <div class="inputbox">
                        <span>Kategori Tiket :</span>
                        <select name="kategori_tiket" id="kategori_tiket" onchange="checkTicketLimit(), updateTicketPrice(), checkTicketAvailabilityByDate()" required>
                            <option value=""disabled selected>Pilih Kategori Tiket</option>
                            <option value="Early_Bird">Early Bird (Sold Out)</option>
                            <option value="Presale_1" disabled>Presale 1</option>
                            <option value="Presale_2" disabled>Presale 2</option>
                            <option value="Normal_Ticket" disabled>Normal Ticket</option>
                        </select>
                    </div>
                    <div class="inputbox">
                        <span>Jumlah Tiket :</span>
                        <select name="jumlah_pesanan" id="jumlah_pesanan" required>
                            <option value="" disabled selected>Pilih Jumlah Tiket</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>

                    <!-- Menampilkan harga per tiket dan total harga -->
                    <div id="harga_section" style="margin-top: 30px; margin-bottom: 25px;">
                        <p id="harga_per_tiket" style="margin-bottom: 10px;">Harga per tiket : -</p>
                        <p id="total_harga">Total Harga : -</p>
                    </div>

                    <div class="inputbox">
                        <span>Metode Pembayaran :</span>
                        <select name="metode_pembayaran" required>
                            <option value="" disabled selected>Pilih Metode Pembayaran</option>
                            <option value="BRI"> BRI : 0920 0106 0188 533 (DHINI DELIANA PUTRI)</option>
                            <option value="DANA"> DANA : 088706398538 (DEFI YULI SUBEHNI)</option>
                        </select>
                    </div>

                    <div class="inputbox">
                        <span style="color: red;">Pastikan Anda Screenshoot Bukti Pembayaran!</span>
                    </div>

                </div>
            </div>
            <a href="Ticket.php" class="button-85" role="button">BACK</a>
            <input type="submit" value="procces to checkout" class="submit-btn">
        </form>
        <div id="stokStatus"></div>
    </div>

    <footer class="footer">
        <div class="f2">
            <div class="row">
                <div class="footer-col">
                    <h4>company</h4>
                    <ul>
                        <li><a href="#">about us</a></li>
                        <li><a href="#">our services</a></li>
                        <li><a href="#">privacy policy</a></li>
                        <li><a href="#">affiliate program</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>get help</h4>
                    <ul>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">shipping</a></li>
                        <li><a href="#">returns</a></li>
                        <li><a href="#">order status</a></li>
                        <li><a href="#">payment options</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>follow us</h4>
                    <div class="social-links">
                        <a href="https://www.tiktok.com/@devasana.upi?_t=8rCN21wx4Q4&_r=1"><i class="fab fa-tiktok"></i></a>
                        <a href="https://www.instagram.com/devasanaupi?igsh=MWZucWdrcDA2ZG9mdw=="><i class="fab fa-instagram"></i></a>
                      </div>
                    <div id="audio-container">
                        <audio id="song" autoplay loop>
                          <source src="audio/save-and-sound.mp3" type="audio/mp3">
                        </audio>
                    
                        <div class="audio-icon-wrapper" style="display: none;">
                          <i class="bi bi-disc"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br><hr><br>
        <p> &copy;DEVASANA NIRMATA ISVARA Ticket 2024</p>
    </footer>
</body>
</html>