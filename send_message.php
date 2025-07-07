<?php
session_start();
include_once 'dbConnection.php';

// Pastikan hanya guru yang login yang bisa mengakses
if (!isset($_SESSION['email'])) {
    header("location:index.php");
    exit();
}

// Pastikan ini adalah admin/guru
$sender_name = $_SESSION['name'];

// Ambil 5 siswa dengan skor tertinggi
$top_5_query = mysqli_query($con, "SELECT email FROM `rank` ORDER BY score DESC LIMIT 5");
$top_5_users = [];
while ($row = mysqli_fetch_assoc($top_5_query)) {
    $top_5_users[] = $row['email'];
}

// Ambil 5 siswa dengan skor terendah
$bottom_5_query = mysqli_query($con, "SELECT email FROM `rank` ORDER BY score ASC LIMIT 5");
$bottom_5_users = [];
while ($row = mysqli_fetch_assoc($bottom_5_query)) {
    $bottom_5_users[] = $row['email'];
}

// --- PENGIRIMAN PESAN YANG DIOPTIMALKAN ---

// 1. Siapkan statement satu kali di luar loop
$stmt = $con->prepare("INSERT INTO messages (recipient_email, sender_name, message) VALUES (?, ?, ?)");

// 2. Bind parameter yang akan diubah di dalam loop
$stmt->bind_param("sss", $recipient_email, $sender_name, $message_text);

// Pesan Apresiasi untuk 5 Teratas
$message_text = "Kerja bagus! Prestasimu sangat membanggakan. Terus pertahankan semangat belajarmu!";
foreach ($top_5_users as $email) {
    $recipient_email = $email; // Set email penerima untuk iterasi ini
    $stmt->execute();
}

// Pesan Semangat untuk 5 Terbawah
$message_text = "Jangan patah semangat! Setiap langkah adalah bagian dari proses belajar. Terus berusaha, kamu pasti bisa lebih baik lagi!";
foreach ($bottom_5_users as $email) {
    // Pastikan tidak mengirim pesan ganda
    if (!in_array($email, $top_5_users)) {
        $recipient_email = $email; // Set email penerima untuk iterasi ini
        $stmt->execute();
    }
}

// 3. Tutup statement setelah selesai digunakan
$stmt->close();

// Redirect kembali ke dashboard guru dengan notifikasi
header("location: dash.php?q=2&status=messagesent");
exit();
?>