<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembuat Hash Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7fc; }
        .container { max-width: 600px; }
        .card { margin-top: 5rem; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4><i class="fas fa-key"></i> Pembuat Hash Password</h4>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="password" class="form-label">Masukkan Password Baru:</label>
                        <input type="text" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Buat Hash</button>
                </form>

                <?php
                if (isset($_POST['password'])) {
                    $password = $_POST['password'];
                    // Membuat hash dari password menggunakan algoritma BCRYPT (standar)
                    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                    echo '<div class="alert alert-success mt-4">';
                    echo '<strong>Password Asli:</strong><br>';
                    echo '<p>' . htmlspecialchars($password) . '</p>';
                    echo '<strong>Hash yang Dihasilkan (Salin ini):</strong><br>';
                    echo '<textarea class="form-control" rows="3" readonly>' . $hashed_password . '</textarea>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
