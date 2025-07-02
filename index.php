<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ujian Ceria Anak Hebat!</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <link href="https://fonts.googleapis.com/css2?family=Comic+Neue:wght@400;700&display=swap" rel="stylesheet">
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    :root {
      --blue-sky: #87CEEB;
      --yellow-sun: #FFD700;
      --green-grass: #90EE90;
      --white-cloud: #ffffff;
      --dark-text: #0056b3;
    }

    body {
      font-family: 'Comic Neue', cursive;
      /* Latar belakang dengan pola awan yang halus */
      background-color: #f0f8ff;
      background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23d1e7ff' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    .navbar {
      background-color: var(--blue-sky);
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .navbar-brand, .nav-link {
      color: var(--white-cloud) !important;
      font-weight: 700;
      font-size: 1.1rem;
      text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
    }
    .nav-link:hover {
      transform: scale(1.1);
      transition: transform 0.2s;
    }

    .hero-section {
      background: linear-gradient(135deg, var(--blue-sky), var(--yellow-sun));
      color: var(--white-cloud);
      padding: 6rem 2rem;
      text-align: center;
      border-bottom-left-radius: 50px;
      border-bottom-right-radius: 50px;
      margin-top: 56px; /* Tinggi navbar */
    }
    .hero-section h1 {
      font-size: 3.5rem;
      font-weight: 700;
      color: var(--white-cloud);
      text-shadow: 3px 3px 5px rgba(0,0,0,0.25);
    }

    .section-card {
      background-color: var(--white-cloud);
      padding: 40px;
      margin-top: -50px; /* Membuat kartu menimpa sedikit hero section */
      border-radius: 25px;
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
      position: relative;
      z-index: 2;
    }

    .section {
      padding: 60px 20px;
    }
    
    .section h2 {
      color: var(--dark-text);
      font-weight: 700;
      margin-bottom: 2rem;
    }
    .section h2 .fa-solid {
      color: var(--yellow-sun);
    }

    .developer-card {
      background: #fdfdfd;
      padding: 20px;
      border-radius: 20px;
      transition: transform 0.3s, box-shadow 0.3s;
    }
    .developer-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 25px rgba(0,0,0,0.1);
    }
    .developer-img {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      border: 5px solid var(--yellow-sun);
      object-fit: cover;
    }
    
    .btn-fun {
      background-color: var(--green-grass);
      border: none;
      color: var(--white-cloud);
      font-weight: bold;
      padding: 12px 25px;
      border-radius: 50px;
      transition: transform 0.2s, background-color 0.2s;
    }
    .btn-fun:hover {
      background-color: #76d776;
      transform: scale(1.05);
      color: var(--white-cloud);
    }

    .form-control {
      border-radius: 15px;
      padding: 12px;
      border: 2px solid #ddd;
    }
    .form-control:focus {
      border-color: var(--blue-sky);
      box-shadow: 0 0 8px rgba(135, 206, 235, 0.5);
    }

    footer {
      background-color: var(--blue-sky);
      padding: 30px 0;
      text-align: center;
      color: var(--white-cloud);
      font-size: 1.2rem;
      margin-top: 40px;
    }

    .modal-header {
      background-color: var(--yellow-sun);
      color: var(--dark-text);
      border-bottom: none;
      border-top-left-radius: 20px;
      border-top-right-radius: 20px;
    }
    .modal-content {
        border-radius: 20px;
        border: none;
    }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg fixed-top">
  <div class="container">
    <a class="navbar-brand" href="#"><i class="fa-solid fa-star"></i> Ujian Ceria</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="#developers"><i class="fa-solid fa-users"></i> Tim Hebat</a></li>
        <li class="nav-item"><a class="nav-link" href="#about"><i class="fa-solid fa-book-open"></i> Tentang</a></li>
        <li class="nav-item"><a class="nav-link" href="#contact"><i class="fa-solid fa-paper-plane"></i> Kontak</a></li>
        <li class="nav-item"><a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#loginAdmin"><i class="fa-solid fa-user-tie"></i> Guru</a></li>
        <li class="nav-item"><a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#loginUser"><i class="fa-solid fa-user-graduate"></i> Siswa</a></li>
      </ul>
    </div>
  </div>
</nav>

<?php
if (isset($_GET['error'])) {
    echo '<div style="margin-top: 70px;"></div>'; // Memberi jarak dari navbar
    echo '<div class="container">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Oops!</strong> ' . htmlspecialchars($_GET['error']) . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          </div>';
}
?>
<div class="hero-section">
  <h1 class="display-4">🎉 Ayo Mulai Ujian Seru! 🎉</h1>
  <p class="lead">Belajar dan bermain jadi satu di sini. Kamu pasti bisa jadi juara!</p>
</div>

<div class="container">
  <div class="section-card">
    <section id="developers" class="text-center">
      <h2 class="mb-5"><i class="fa-solid fa-palette"></i> Tim Hebat Pembuat Ujian Ceria <i class="fa-solid fa-palette"></i></h2>
      <div class="row g-5 justify-content-center">
        <div class="col-md-5">
          <div class="developer-card">
            <img src="photo.png" alt="RYONANDHA" class="developer-img mb-3">
            <h5 class="mt-3" style="color: var(--dark-text);">RYONANDHA MITCHELL</h5>
            <p class="text-muted">202201009</p>
          </div>
        </div>
        <div class="col-md-5">
          <div class="developer-card">
            <img src="profilepic.png" alt="EVAN" class="developer-img mb-3">
            <h5 class="mt-3" style="color: var(--dark-text);">EVAN FARREL</h5>
            <p class="text-muted">202201007</p>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>
<section id="about" class="section">
  <div class="container text-center">
    <h2 class="mb-4"><i class="fa-solid fa-rocket"></i> Tentang Ujian Ceria <i class="fa-solid fa-rocket"></i></h2>
    <p class="fs-5 text-secondary">Ujian Ceria dibuat khusus buat kamu, anak hebat Indonesia! Di sini, ujian jadi tidak membosankan karena penuh dengan warna dan tantangan seru. Kamu bisa belajar kapan saja dan di mana saja. Semangat!</p>
  </div>
</section>
<section id="contact" class="section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8 text-center">
          <h2 class="mb-4"><i class="fa-solid fa-envelope-open-text"></i> Punya Pertanyaan? <i class="fa-solid fa-envelope-open-text"></i></h2>
          <form action="feed.php?q=index.php" method="post" class="mt-4">
            <div class="mb-3">
              <input type="text" name="name" class="form-control" placeholder="Tulis namamu di sini..." required>
            </div>
            <div class="mb-3">
              <input type="email" name="email" class="form-control" placeholder="Email Ayah atau Bunda..." required>
            </div>
            <div class="mb-3">
              <input type="text" name="subject" class="form-control" placeholder="Judul pesannya apa?" required>
            </div>
            <div class="mb-3">
              <textarea name="feedback" class="form-control" rows="4" placeholder="Tulis pesanmu untuk kami..."></textarea>
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-fun">Kirim Pesan <i class="fa-solid fa-paper-plane"></i></button>
            </div>
          </form>
      </div>
    </div>
  </div>
</section>
<footer>
  <p>🌟 Dibuat dengan <i class="fa-solid fa-heart" style="color: #ff6b6b;"></i> untuk Anak-Anak Hebat Indonesia! 🌟</p>
</footer>
<div class="modal fade" id="loginAdmin" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold"><i class="fa-solid fa-chalkboard-user"></i> Masuk untuk Bapak/Ibu Guru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="login_handler.php" method="POST">
        <div class="modal-body p-4">
          <input type="text" name="uname" class="form-control mb-3" placeholder="ID Pengguna" required>
          <input type="password" name="password" class="form-control" placeholder="Kata Sandi" required>
        </div>
        <div class="modal-footer border-0">
          <button type="submit" class="btn btn-fun mx-auto">Masuk</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" id="loginUser" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold"><i class="fa-solid fa-child-reaching"></i> Masuk untuk Siswa Hebat</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="login.php?q=index.php" method="POST">
        <div class="modal-body p-4">
          <input type="email" name="email" class="form-control mb-3" placeholder="Email kamu" required>
          <input type="password" name="password" class="form-control" placeholder="Password rahasia" required>
        </div>
        <div class="modal-footer border-0">
          <button type="submit" class="btn btn-fun mx-auto">Ayo Masuk!</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>