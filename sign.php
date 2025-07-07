<?php
include_once 'dbConnection.php';

// Memeriksa apakah form telah di-submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Ambil semua data dari form
    $name = ucwords(strtolower($_POST['name']));
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $college = $_POST['college'];
    $mob = $_POST['mob'];
    $password_plain = $_POST['password'];

    // 2. Hashing password dengan metode yang aman (BCRYPT)
    $password_hashed = password_hash($password_plain, PASSWORD_BCRYPT);

    // 3. Atur nama file avatar default secara otomatis
    $default_avatar = 'default.jpg';

    // 4. Gunakan Prepared Statements untuk mencegah SQL Injection
    $stmt_check = $con->prepare("SELECT email FROM user WHERE email = ?");
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    // Cek apakah email sudah terdaftar
    if ($result_check->num_rows > 0) {
        // Jika email sudah ada, kembali dengan pesan error
        header("location:headdash.php?q=6&error=Email sudah terdaftar!");
        exit();
    }
    $stmt_check->close();

    // 5. Siapkan query INSERT yang aman dengan menyebutkan nama kolom
    $stmt = $con->prepare("INSERT INTO user (name, gender, college, email, mob, password, avatar) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    // Bind semua parameter ke query
    $stmt->bind_param("ssssiss", $name, $gender, $college, $email, $mob, $password_hashed, $default_avatar);

    // 6. Eksekusi query dan redirect berdasarkan hasilnya
    if ($stmt->execute()) {
        // Jika berhasil, redirect ke halaman daftar siswa
        header("location:headdash.php?q=7&status=user_added");
    } else {
        // Jika gagal karena alasan lain
        header("location:headdash.php?q=6&error=Gagal menambahkan siswa.");
    }
    
    $stmt->close();
    $con->close();
    exit();

} else {
    // Jika file diakses secara langsung, redirect ke halaman utama
    header("location:index.php");
    exit();
}
?>
