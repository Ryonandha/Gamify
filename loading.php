<?php
session_start();

// Jika pengguna mencoba mengakses halaman ini tanpa login, kembalikan ke index.
if (!isset($_SESSION['email'])) {
    header("location:index.php");
    exit();
}

$name = htmlspecialchars($_SESSION['name']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang!</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Comic+Neue:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Comic Neue', cursive;
            background-color: #f0f8ff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            overflow: hidden;
        }
        .loading-container {
            text-align: center;
            color: #0056b3;
        }
        .loading-video {
            width: 100%;
            /* PERUBAHAN DI SINI: Ukuran video diperbesar */
            max-width: 500px; 
            border-radius: 20px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        h1 {
            font-weight: 700;
            margin-top: 1.5rem;
        }
    </style>
</head>
<body>

    <div class="loading-container">
        <!-- Video pemuatan yang lucu dan singkat -->
        <video class="loading-video" autoplay loop muted playsinline>
            <source src="videos/loading.mp4" type="video/mp4">
            Browser Anda tidak mendukung tag video.
        </video>
        
        <h1>Selamat Datang, <?php echo $name; ?>!</h1>
        <p class="lead">Sedang menyiapkan halamanmu...</p>
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <script>
        // Setelah 8 detik (8000 milidetik), arahkan ke halaman dashboard.
        setTimeout(function() {
            window.location.href = 'account.php?q=1';
        }, 8000); 
    </script>

</body>
</html>
