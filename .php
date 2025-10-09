<?php
// Blok PHP untuk memproses formulir
$status_message = ''; // Variabel untuk menyimpan pesan status (sukses/gagal)

// Cek apakah formulir sudah di-submit dengan metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // GANTI DENGAN ALAMAT EMAIL ANDA UNTUK MENERIMA PESAN
    $penerima = "vellumgracia@gmail.com";

    // Ambil dan bersihkan data dari form untuk keamanan
    $nama = strip_tags(trim($_POST["nama"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $pesan = trim($_POST["pesan"]);

    // Validasi dasar: pastikan tidak ada kolom yang kosong dan email valid
    if (empty($nama) || empty($pesan) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Jika ada data yang tidak valid, siapkan pesan error
        $status_message = '<div class="status-error">Gagal! Harap lengkapi semua kolom dengan data yang benar.</div>';
    } else {
        // Jika data valid, susun email
        $subjek = "Pesan Baru dari Website Bisnis Kita dari $nama";
        $konten_email = "Nama Pengirim: $nama\n";
        $konten_email .= "Email Pengirim: $email\n\n";
        $konten_email .= "Isi Pesan:\n$pesan\n";

        // Buat header email
        $headers = "From: $nama <$email>";

        // Kirim email menggunakan fungsi mail() bawaan PHP
        if (mail($penerima, $subjek, $konten_email, $headers)) {
            // Jika berhasil, siapkan pesan sukses
            $status_message = '<div class="status-sukses">Terima kasih! Pesan Anda telah berhasil dikirim.</div>';
        } else {
            // Jika gagal, siapkan pesan error
            $status_message = '<div class="status-error">Oops! Terjadi kesalahan pada server. Pesan Anda tidak dapat dikirim.</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bisnis Kita | Solusi Digital Profesional</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
  
  <style>
    /* ... (Semua kode CSS Anda dari sebelumnya tetap sama) ... */
    
    /* PENAMBAHAN CSS untuk pesan status dari PHP */
    .status-sukses {
        padding: 1rem;
        margin-bottom: 1.5rem;
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        border-radius: 8px;
        text-align: center;
        font-weight: 500;
    }
    .status-error {
        padding: 1rem;
        margin-bottom: 1.5rem;
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
        border-radius: 8px;
        text-align: center;
        font-weight: 500;
    }

    /* --- General Styling --- */
    html {
      scroll-behavior: smooth;
    }

    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      background-color: #F0F4F8; /* Latar belakang biru muda */
      color: #333;
    }
    
    /* ... (Sisa kode CSS Anda yang lain, tidak perlu diubah) ... */
    header {
      background-color: #0D2A4C;
      color: white;
      text-align: center;
      padding: 1rem;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    nav {
      background-color: #1A4A8D;
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 1.5rem;
      padding: 0.75rem;
      position: sticky;
      top: 0;
      z-index: 1000;
    }

    nav a {
      color: white;
      text-decoration: none;
      font-weight: 500;
      transition: color 0.3s ease;
    }

    nav a:hover {
      color: #6699CC;
    }

    main {
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    section {
      padding: 3rem 1.5rem;
      max-width: 900px;
      width: 100%;
      box-sizing: border-box;
    }
    
    h2 {
      text-align: center;
      margin-bottom: 2rem;
      color: #0D2A4C;
      font-size: 2.2rem;
    }

    .hero {
      text-align: center;
      padding: 4rem 1rem;
      background-color: #e9eef3;
    }

    .hero h1 {
      margin-bottom: 0.5rem;
      font-size: 2.8rem;
      color: #0D2A4C;
    }

    .service-card, .testimoni-card, .team-card {
      background-color: white;
      margin-bottom: 1.5rem;
      padding: 1.5rem;
      border-radius: 10px;
      border-left: 5px solid #1A4A8D;
      box-shadow: 0 4px 8px rgba(0,0,0,0.08);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .service-card:hover, .testimoni-card:hover, .team-card:hover {
      transform: translateY(-5px) scale(1.02);
      box-shadow: 0 6px 12px rgba(0,0,0,0.12);
    }
    
    .team-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 1.5rem;
      text-align: center;
    }

    .team-card img {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      object-fit: cover;
      margin-bottom: 1rem;
      border: 3px solid #1A4A8D;
    }
    
    .team-card h3 {
      margin: 0.5rem 0 0.2rem 0;
      color: #0D2A4C;
    }
    .contact-form {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .contact-form input, .contact-form textarea {
      padding: 0.8rem;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-family: 'Poppins', sans-serif;
      font-size: 1rem;
      transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
    
    .contact-form input:focus, .contact-form textarea:focus {
        outline: none;
        border-color: #1A4A8D;
        box-shadow: 0 0 5px rgba(26, 74, 141, 0.5);
    }
    .faq-item {
      background: white;
      margin: 0.5rem 0;
      padding: 1.2rem;
      border-radius: 8px;
      cursor: pointer;
      border: 1px solid #e0e0e0;
      transition: background-color 0.3s;
    }

    .faq-item:hover {
      background-color: #e9eef3;
    }

    .faq-answer {
      display: none;
      padding-top: 1rem;
      border-top: 1px solid #BCCCDC;
      margin-top: 1rem;
      color: #555;
    }
    
    button {
      background-color: #1A4A8D;
      color: white;
      border: none;
      padding: 0.8rem 1.8rem;
      border-radius: 5px;
      cursor: pointer;
      font-weight: 500;
      font-family: 'Poppins', sans-serif;
      font-size: 1rem;
      transition: background-color 0.3s ease, transform 0.2s ease;
    }

    button:hover {
      background-color: #0D2A4C;
      transform: scale(1.05);
    }

    footer {
      text-align: center;
      background-color: #0D2A4C;
      color: white;
      padding: 1.5rem;
      margin-top: 2rem;
    }
  </style>
</head>
<body>
  <header>
    <h1>Bisnis Kita</h1>
  </header>
  
  <nav>
    <a href="#about">Tentang</a>
    <a href="#services">Layanan</a>
    <a href="#testimoni">Testimoni</a>
    <a href="#team">Tim</a>
    <a href="#contact">Kontak</a>
    <a href="#faq">FAQ</a>
  </nav>

  <main>
    <section class="hero">
      <h1>Solusi Digital untuk Bisnis Anda</h1>
      <p>Kembangkan bisnis Anda ke level berikutnya bersama kami.</p>
      <a href="#contact"><button>Hubungi Kami</button></a>
    </section>

    <section id="about">
      <h2>Tentang Kami</h2>
      <p>Kami adalah tim profesional yang berdedikasi membantu bisnis berkembang melalui strategi digital yang efektif, inovatif, dan terukur untuk mencapai hasil yang maksimal.</p>
    </section>

    <section id="services">
      <h2>Layanan Kami</h2>
      <div class="service-card">
        <h3>Desain Website Profesional</h3>
        <p>Tampilan modern, fungsional, dan responsif untuk semua perangkat.</p>
      </div>
      <div class="service-card">
        <h3>Pemasaran Digital Terpadu</h3>
        <p>Tingkatkan visibilitas dan jangkau audiens yang tepat dengan strategi SEO & SEM.</p>
      </div>
    </section>
    
    <section id="testimoni">
      <h2>Testimoni Klien</h2>
      <div class="testimoni-card">
        <p>"Website kami meningkat 3x lipat pengunjungnya setelah bekerja sama dengan Bisnis Kita!"</p>
        <strong>- Andi, Pemilik Toko Online</strong>
      </div>
      <div class="testimoni-card">
        <p>"Tim Bisnis Kita benar-benar profesional dan cepat tanggap. Sangat direkomendasikan!"</p>
        <strong>- Rina, Konsultan UMKM</strong>
      </div>
    </section>

    <section id="team">
      <h2>Tim Profesional Kami</h2>
      <div class="team-grid">
        <div class="team-card">
          <img src="https://via.placeholder.com/150/0D2A4C/ffffff?text=ZF" alt="Foto Zulfa Fahmiy">
          <h3>ZULFA FAHMIY</h3>
          <p>CEO & Founder</p>
        </div>
        <div class="team-card">
          <img src="https://via.placeholder.com/150/1A4A8D/ffffff?text=CG" alt="Foto Chat GPT">
          <h3>CHAT GPT</h3>
          <p>Desainer UI/UX</p>
        </div>
        <div class="team-card">
          <img src="https://via.placeholder.com/150/6699CC/ffffff?text=AM" alt="Foto Al Murtadlo">
          <h3>AL MURTADLO</h3>
          <p>Digital Marketing</p>
        </div>
      </div>
    </section>

    <section id="contact">
      <h2>Hubungi Kami</h2>
      <p>Isi formulir di bawah ini dan tim kami akan segera menghubungi Anda.</p>
      
      <!-- PHP akan menampilkan pesan status di sini setelah form dikirim -->
      <?php echo $status_message; ?>

      <!--
        MODIFIKASI FORM:
        1. action="" -> Mengirim data ke halaman ini sendiri untuk diproses oleh PHP di atas.
        2. method="post" -> Metode pengiriman data.
        3. onsubmit="..." Dihapus karena tidak lagi memakai JavaScript untuk proses ini.
        4. Setiap input DIBERI atribut "name" agar bisa dibaca oleh PHP.
      -->
      <form class="contact-form" action="" method="POST">
        <input type="text" id="nama" name="nama" placeholder="Nama Anda" required aria-label="Nama Anda">
        <input type="email" id="email" name="email" placeholder="Email Anda" required aria-label="Email Anda">
        <textarea id="pesan" name="pesan" rows="5" placeholder="Tulis pesan Anda di sini..." required aria-label="Pesan Anda"></textarea>
        <button type="submit">Kirim Pesan</button>
      </form>
    </section>

    <section id="faq">
      <h2>Pertanyaan Umum (FAQ)</h2>
      <div class="faq-item" onclick="toggleFAQ(this)">
        <strong>Berapa lama proses pembuatan website?</strong>
        <div class="faq-answer">Biasanya antara 1–2 minggu, tergantung tingkat kompleksitas fitur dan kelengkapan materi dari klien.</div>
      </div>
      <div class="faq-item" onclick="toggleFAQ(this)">
        <strong>Apakah ada layanan maintenance website?</strong>
        <div class="faq-answer">Ya, kami menyediakan paket pemeliharaan bulanan untuk memastikan website Anda selalu aman, cepat, dan ter-update.</div>
      </div>
    </section>
  </main>
  
  <footer>
    <p>© 2025 Bisnis Kita. Semua Hak Cipta Dilindungi.</p>
    <p id="waktu"></p>
  </footer>

  <script>
    console.log("Website Bisnis Kita siap digunakan!");
    
    function toggleFAQ(element) {
      const answer = element.querySelector('.faq-answer');
      const isVisible = answer.style.display === 'block';
      document.querySelectorAll('.faq-answer').forEach(ans => {
          if (ans !== answer) {
              ans.style.display = 'none';
          }
      });
      answer.style.display = isVisible ? 'none' : 'block';
    }

    // FUNGSI kirimPesan() SUDAH DIHAPUS DARI SINI
    // Proses pengiriman pesan sekarang sepenuhnya ditangani oleh PHP.

    // Jam dinamis di footer
    function updateTime() {
      const timeElem = document.getElementById("waktu");
      if (timeElem) {
        const now = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit', timeZoneName: 'short' };
        timeElem.textContent = now.toLocaleString("id-ID", options);
      }
    }
    
    updateTime();  
    setInterval(updateTime, 1000);
  </script>
</body>
</html>
