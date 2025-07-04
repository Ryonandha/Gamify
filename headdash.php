<?php
session_start();
// Redirect to index if not logged in
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}
// Include database connection
include_once 'dbConnection.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Super Admin - Ujian Ceria</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  
  <style>
    :root {
      --primary-color: #3d5afe;
      --secondary-color: #00b0ff;
      --background-color: #f4f7fc;
      --text-color: #333;
      --card-bg: #ffffff;
    }
    body {
      font-family: 'Poppins', sans-serif;
      background-color: var(--background-color);
    }
    .navbar {
      background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .navbar-brand, .nav-link, .navbar-text {
      color: #fff !important;
      font-weight: 500;
    }
    .nav-link.active, .nav-link:hover {
      color: #fff !important;
      font-weight: 600;
      transform: translateY(-2px);
      transition: all 0.2s ease-in-out;
    }
    .nav-link i {
      margin-right: 8px;
    }
    .dropdown-item {
        color: var(--text-color) !important;
    }
    .dropdown-item:active {
        background-color: var(--primary-color);
        color: #fff !important;
    }
    .card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.05);
      overflow: hidden; /* Ensures child elements conform to border radius */
    }
    .card-header {
      background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
      color: #fff;
      font-weight: 600;
      padding: 1rem 1.5rem;
      border-bottom: none;
    }
    .card-body {
        padding: 1.5rem;
    }
    .table-responsive {
        margin-top: 1rem;
    }
    .stat-card {
      color: #fff;
      border-radius: 10px;
      padding: 20px;
    }
    .stat-card .stat-icon {
      font-size: 3rem;
      opacity: 0.8;
    }
    .stat-card .stat-number {
      font-size: 2.5rem;
      font-weight: 700;
    }
    .bg-users { background: #ff9f43; }
    .bg-quizzes { background: #1e90ff; }
    .bg-feedback { background: #32cd32; }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
  <div class="container">
    <a class="navbar-brand" href="headdash.php?q=0"><i class="fas fa-star"></i> Ujian Ceria Admin</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navCer">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navCer">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link <?php if(@$_GET['q']==0) echo'active'; ?>" href="headdash.php?q=0"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
        <li class="nav-item"><a class="nav-link <?php if(@$_GET['q']==1) echo'active'; ?>" href="headdash.php?q=1"><i class="fas fa-users"></i>Pengguna</a></li>
        <li class="nav-item"><a class="nav-link <?php if(@$_GET['q']==2) echo'active'; ?>" href="headdash.php?q=2"><i class="fas fa-trophy"></i>Peringkat</a></li>
        <li class="nav-item"><a class="nav-link <?php if(@$_GET['q']==3) echo'active'; ?>" href="headdash.php?q=3"><i class="fas fa-comments"></i>Feedback</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle <?php if(@$_GET['q']==4 || @$_GET['q']==5 || @$_GET['q']==6 || @$_GET['q']==7) echo'active'; ?>" href="#" role="button" data-bs-toggle="dropdown"><i class="fas fa-tasks"></i>Manajemen</a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="headdash.php?q=6">Tambah Siswa</a></li>
            <li><a class="dropdown-item" href="headdash.php?q=7">Hapus Siswa</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="headdash.php?q=4">Tambah Admin</a></li>
            <li><a class="dropdown-item" href="headdash.php?q=5">Hapus Admin</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="logout.php?q=headdash.php"><i class="fas fa-sign-out-alt"></i>Keluar</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container my-4">

  <?php if(@$_GET['q']==0) {
      // Fetching stats
      $user_count = mysqli_num_rows(mysqli_query($con, "SELECT * FROM user"));
      $quiz_count = mysqli_num_rows(mysqli_query($con, "SELECT * FROM quiz"));
      $feedback_count = mysqli_num_rows(mysqli_query($con, "SELECT * FROM feedback"));
  ?>
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stat-card bg-users">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Total Siswa</h5>
                        <p class="stat-number mb-0"><?= $user_count ?></p>
                    </div>
                    <i class="fas fa-user-graduate stat-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card bg-quizzes">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Jumlah Soal</h5>
                        <p class="stat-number mb-0"><?= $quiz_count ?></p>
                    </div>
                    <i class="fas fa-book-open stat-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card bg-feedback">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Feedback Masuk</h5>
                        <p class="stat-number mb-0"><?= $feedback_count ?></p>
                    </div>
                    <i class="fas fa-comment-dots stat-icon"></i>
                </div>
            </div>
        </div>
    </div>
  <?php
    $result = mysqli_query($con,"SELECT * FROM quiz ORDER BY date DESC") or die('Error');
    echo '<div class="card"><div class="card-header"><i class="fas fa-list-alt me-2"></i>Daftar Soal Tersedia</div><div class="card-body"><div class="table-responsive"><table class="table table-hover"><thead><tr><th>No</th><th>Topik</th><th>Jumlah Soal</th><th>Skor Total</th><th>Benar</th><th>Salah</th><th>Waktu</th></tr></thead><tbody>';
    $c=1;
    while($row = mysqli_fetch_array($result)) {
      echo '<tr><td>'.$c++.'</td><td>'.htmlspecialchars($row['title']).'</td><td>'.$row['total'].'</td><td>'.($row['sahi']*$row['total']).'</td><td>'.$row['sahi'].'</td><td>'.$row['wrong'].'</td><td>'.$row['time'].' menit</td></tr>';
    }
    echo '</tbody></table></div></div></div>';
  } ?>

  <?php if(@$_GET['q']==1) {
    $result = mysqli_query($con,"SELECT * FROM user") or die('Error');
    echo '<div class="card"><div class="card-header"><i class="fas fa-users me-2"></i>Daftar Pengguna (Siswa)</div><div class="card-body"><div class="table-responsive"><table class="table table-hover"><thead><tr><th>No</th><th>Nama</th><th>Jenis Kelamin</th><th>NIS/Sekolah</th><th>Email</th></tr></thead><tbody>';
    $c=1;
    while($row = mysqli_fetch_array($result)) {
      echo '<tr><td>'.$c++.'</td><td>'.htmlspecialchars($row['name']).'</td><td>'.($row['gender'] == 'M' ? 'Laki-laki' : 'Perempuan').'</td><td>'.htmlspecialchars($row['college']).'</td><td>'.htmlspecialchars($row['email']).'</td></tr>';
    }
    echo '</tbody></table></div></div></div>';
  } ?>

  <?php if(@$_GET['q']==2) {
    $q=mysqli_query($con,"SELECT * FROM rank ORDER BY score DESC") or die('Error');
    echo '<div class="card"><div class="card-header"><i class="fas fa-trophy me-2"></i>Peringkat Siswa</div><div class="card-body"><div class="table-responsive"><table class="table table-hover"><thead><tr><th>Peringkat</th><th>Nama</th><th>Sekolah</th><th>Nilai</th></tr></thead><tbody>';
    $c=1;
    while($row=mysqli_fetch_array($q)) {
      $q2=mysqli_query($con,"SELECT * FROM user WHERE email='".mysqli_real_escape_string($con, $row['email'])."'") or die('Error');
      while($user=mysqli_fetch_array($q2)) {
        echo '<tr><td>'.$c++.'</td><td>'.htmlspecialchars($user['name']).'</td><td>'.htmlspecialchars($user['college']).'</td><td>'.htmlspecialchars($row['score']).'</td></tr>';
      }
    }
    echo '</tbody></table></div></div></div>';
  } ?>

  <?php if(@$_GET['q']==3) {
    $result = mysqli_query($con,"SELECT * FROM feedback ORDER BY date DESC") or die('Error');
    echo '<div class="card"><div class="card-header"><i class="fas fa-comments me-2"></i>Kritik & Saran Masuk</div><div class="card-body"><div class="table-responsive"><table class="table table-hover"><thead><tr><th>No</th><th>Dari</th><th>Email</th><th>Pesan</th></tr></thead><tbody>';
    $c=1;
    while($row = mysqli_fetch_array($result)) {
      echo '<tr><td>'.$c++.'</td><td>'.htmlspecialchars($row['name']).'</td><td>'.htmlspecialchars($row['email']).'</td><td>'.htmlspecialchars($row['feedback']).'</td></tr>';
    }
    echo '</tbody></table></div></div></div>';
  } ?>

  <?php if(@$_GET['q']==6) {
    echo '<div class="card"><div class="card-header"><i class="fas fa-user-plus me-2"></i>Formulir Tambah Siswa Baru</div><div class="card-body"><form action="sign.php?q=headdash.php?q=6" method="POST"><div class="mb-3"><label for="name" class="form-label">Nama Lengkap</label><input type="text" class="form-control" name="name" required></div><div class="mb-3"><label for="gender" class="form-label">Jenis Kelamin</label><select class="form-select" name="gender" required><option selected disabled value="">-- Pilih --</option><option value="M">Laki-laki</option><option value="F">Perempuan</option></select></div><div class="mb-3"><label for="college" class="form-label">NIS / Nama Sekolah</label><input type="text" class="form-control" name="college" required></div><div class="mb-3"><label for="mob" class="form-label">No. HP (Orang Tua)</label><input type="tel" class="form-control" name="mob" required></div><div class="mb-3"><label for="email" class="form-label">Alamat Email</label><input type="email" class="form-control" name="email" required></div><div class="mb-3"><label for="password" class="form-label">Password</label><input type="password" class="form-control" name="password" required></div><button type="submit" class="btn btn-primary"><i class="fas fa-check me-2"></i>Tambahkan Siswa</button></form></div></div>';
  } ?>

  <?php if(@$_GET['q']==7) {
    $result = mysqli_query($con,"SELECT * FROM user") or die('Error');
    echo '<div class="card"><div class="card-header"><i class="fas fa-user-times me-2"></i>Hapus Data Siswa</div><div class="card-body"><div class="table-responsive"><table class="table table-hover"><thead><tr><th>Nama</th><th>Email</th><th>No HP</th><th>Sekolah</th><th>Aksi</th></tr></thead><tbody>';
    while($row = mysqli_fetch_array($result)) {
        echo '<tr>
                <td>'.htmlspecialchars($row['name']).'</td>
                <td>'.htmlspecialchars($row['email']).'</td>
                <td>'.htmlspecialchars($row['mob']).'</td>
                <td>'.htmlspecialchars($row['college']).'</td>
                
                <!-- PERUBAHAN DI SINI: ganti `demail1` menjadi `demail_student` -->
                <td><a href="update.php?demail_student='.urlencode($row['email']).'" class="btn btn-danger btn-sm" onclick="return confirm(\'Anda yakin ingin menghapus siswa ini?\')">
                    <i class="fas fa-trash-alt"></i> Hapus
                </a></td>
              </tr>';
    }

    echo '</tbody></table></div></div></div>';
  } ?>

  <?php if(@$_GET['q']==4) {
    echo '<div class="card"><div class="card-header"><i class="fas fa-user-shield me-2"></i>Formulir Tambah Admin Baru</div><div class="card-body"><form action="signadmin.php?q=headdash.php?q=4" method="POST"><div class="mb-3"><label for="email" class="form-label">Email Admin</label><input type="email" class="form-control" name="email" required></div><div class="mb-3"><label for="password" class="form-label">Password</label><input type="password" class="form-control" name="password" required></div><button type="submit" class="btn btn-primary"><i class="fas fa-check me-2"></i>Tambahkan Admin</button></form></div></div>';
  } ?>

  <?php if(@$_GET['q']==5) {
    $result = mysqli_query($con,"SELECT * FROM admin WHERE role='admin'") or die('Error');
    echo '<div class="card"><div class="card-header"><i class="fas fa-user-slash me-2"></i>Hapus Data Admin</div><div class="card-body"><div class="table-responsive"><table class="table table-hover"><thead><tr><th>Email</th><th>Aksi</th></tr></thead><tbody>';
    while($row = mysqli_fetch_array($result)) {
      echo '<tr><td>'.htmlspecialchars($row['email']).'</td><td><a href="update.php?demail1='.urlencode($row['email']).'" class="btn btn-danger btn-sm" onclick="return confirm(\'PERHATIAN: Anda akan menghapus akun admin. Lanjutkan?\')"><i class="fas fa-trash-alt"></i> Hapus</a></td></tr>';
    }
    echo '</tbody></table></div></div></div>';
  } ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>