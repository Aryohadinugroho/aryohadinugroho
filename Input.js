document.getElementById('feedbackForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Mencegah pengiriman form secara default

    // Mengambil nilai dari form
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const message = document.getElementById('message').value;

    // Menampilkan pesan respon
    const responseMessage = document.getElementById('responseMessage');
    
    // Validasi dan simpan data (di sini hanya contoh, tidak menyimpan ke server)
    if (name && email && message) {
        responseMessage.innerHTML = `<p>Terima kasih, ${name}! Feedback Anda telah diterima.</p>`;
        responseMessage.style.color = 'green';

        // Reset form setelah pengiriman
        document.getElementById('feedbackForm').reset();
        
        // Log data ke konsol (untuk keperluan debugging)
        console.log({ name, email, message });
        
        // Di sini Anda bisa menambahkan kode untuk mengirim data ke server jika diperlukan
    } else {
        responseMessage.innerHTML = `<p>Silakan lengkapi semua field.</p>`;
        responseMessage.style.color = 'red';
    }
});