<?php
include_once 'dbConnection.php';
session_start();

// Pastikan form telah di-submit
if (isset($_POST['uname'], $_POST['password'])) {
    
    // 1. Ambil data dari form
    $email = $_POST['uname'];
    $password = $_POST['password'];

    // 2. Siapkan "Prepared Statement" untuk keamanan dari SQL Injection
    $stmt = $con->prepare("SELECT email, password, role, name FROM admin WHERE email = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($con->error));
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // 3. Cek apakah pengguna ditemukan
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        
        // 4. Verifikasi password yang di-input dengan hash di database
        if (password_verify($password, $row['password'])) {
            // == LOGIN BERHASIL ==

            // Reset sesi lama untuk keamanan
            session_unset();
            session_destroy();
            session_start();

            // Atur sesi baru untuk pengguna
            $_SESSION["key"] = 'prasanth123'; // Kunci otorisasi Anda
            $_SESSION["email"] = $row['email'];
            $_SESSION["name"] = $row['name']; // Mengambil nama asli dari database

            // Arahkan berdasarkan peran (role)
            if ($row['role'] == 'head') {
                header("location:headdash.php?q=0");
            } else { // Asumsikan peran lain adalah 'admin' untuk guru
                header("location:dash.php?q=0");
            }
            exit(); // Penting: Hentikan eksekusi skrip setelah redirect

        }
    }

    // Jika email tidak ditemukan atau password salah, akan sampai ke baris ini
    $errorMessage = "Login Gagal! Periksa kembali email dan password Anda.";
    header("location:index.php?error=" . urlencode($errorMessage));
    exit();

} else {
    // Jika ada yang mencoba mengakses file ini secara langsung tanpa mengirim data
    header("location:index.php");
    exit();
}

$stmt->close();
$con->close();
?>