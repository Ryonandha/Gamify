<?php
include_once 'dbConnection.php';
session_start();

// Pastikan form telah di-submit
if (isset($_POST['email'], $_POST['password'])) {
    
    // 1. Ambil data dari form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // 2. Siapkan "Prepared Statement" untuk keamanan
    $stmt = $con->prepare("SELECT email, password, name FROM user WHERE email = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($con->error));
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // 3. Cek apakah pengguna ditemukan
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        
        // 4. Verifikasi password yang di-input dengan HASH di database
        if (password_verify($password, $row['password'])) {
            // == LOGIN BERHASIL ==

            // Reset sesi lama untuk keamanan
            session_unset();
            session_destroy();
            session_start();

            // Atur sesi baru untuk pengguna
            $_SESSION["name"] = $row['name'];
            $_SESSION["email"] = $row['email'];

            // ======================================================
            // == PERUBAHAN DI SINI ==
            // Arahkan ke halaman loading, bukan langsung ke account.php
            // ======================================================
            header("location:loading.php");
            exit(); // Hentikan eksekusi skrip setelah redirect

        }
    }

    // Jika email tidak ditemukan ATAU password salah, akan sampai ke baris ini
    $errorMessage = "Login Gagal! Email atau password yang Anda masukkan salah.";
    header("location:index.php?error=" . urlencode($errorMessage));
    exit();

} else {
    // Jika ada yang mencoba mengakses file ini secara langsung
    header("location:index.php");
    exit();
}

$stmt->close();
$con->close();
?>
