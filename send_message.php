<?php
include_once 'dbConnection.php';
session_start();

$from_email = $_SESSION['email'] ?? 'admin@example.com'; // fallback

// Ambil semua ranking
$result = mysqli_query($con, "SELECT email FROM rank ORDER BY score DESC") or die('Error mengambil data ranking');

$ranked_users = [];
while ($row = mysqli_fetch_assoc($result)) {
    $ranked_users[] = $row['email'];
}

$total_users = count($ranked_users);
if ($total_users == 0) {
    header("Location: dash.php?q=3&msg=Tidak ada data ranking");
    exit();
}

// Ambil 5 teratas
$top_users = array_slice($ranked_users, 0, min(5, $total_users));
// Ambil 5 terbawah
$bottom_users = array_slice($ranked_users, -min(5, $total_users));

// Kirim pesan untuk top 5
foreach ($top_users as $email) {
    $msg = "Selamat! Kamu termasuk 5 besar leaderboard 🎉";
    mysqli_query($con, "INSERT INTO messages (from_email, to_email, content) VALUES ('$from_email', '$email', '$msg')") or die("Error kirim pesan top");
}

// Kirim pesan untuk bottom 5
foreach ($bottom_users as $email) {
    $msg = "Tetap semangat! Jangan menyerah dan terus tingkatkan prestasimu 💪";
    mysqli_query($con, "INSERT INTO messages (from_email, to_email, content) VALUES ('$from_email', '$email', '$msg')") or die("Error kirim pesan bottom");
}

// Redirect dengan notifikasi sukses
header("Location: dash.php?q=3&msg=Pesan berhasil dikirim!");
exit();
?>
