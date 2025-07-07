<?php
include_once 'dbConnection.php';
session_start();
$email = $_SESSION['email'] ?? null;

$is_admin_or_teacher = (isset($_SESSION['key']) && $_SESSION['key'] == 'prasanth123');

//======================================================================
// FUNGSI UNTUK SUPER ADMIN (headdash.php)
//======================================================================
// Hapus user oleh Super Admin
if (@$_GET['demail_student']) {
    $demail = $_GET['demail_student'];

    // Gunakan prepared statement untuk keamanan
    $stmt_rank = $con->prepare("DELETE FROM `rank` WHERE email=?");
    $stmt_rank->bind_param("s", $demail);
    $stmt_rank->execute();

    $stmt_history = $con->prepare("DELETE FROM history WHERE email=?");
    $stmt_history->bind_param("s", $demail);
    $stmt_history->execute();

    // TAMBAHAN: Hapus dari tabel jawaban siswa
    $stmt_user_answer = $con->prepare("DELETE FROM user_answer WHERE email=?");
    $stmt_user_answer->bind_param("s", $demail);
    $stmt_user_answer->execute();

    // TAMBAHAN: Hapus dari tabel lencana siswa
    $stmt_user_badges = $con->prepare("DELETE FROM user_badges WHERE user_email=?");
    $stmt_user_badges->bind_param("s", $demail);
    $stmt_user_badges->execute();

    // TAMBAHAN: Hapus dari tabel pesan siswa
    $stmt_messages = $con->prepare("DELETE FROM messages WHERE recipient_email=?");
    $stmt_messages->bind_param("s", $demail);
    $stmt_messages->execute();

    // Terakhir, hapus dari tabel user
    $stmt_user = $con->prepare("DELETE FROM user WHERE email=?");
    $stmt_user->bind_param("s", $demail);
    
    // Eksekusi dan periksa apakah berhasil
    if ($stmt_user->execute()) {
        header("location:headdash.php?q=1&status=deleted");
    } else {
        // Jika gagal, tampilkan error untuk debugging
        die('Gagal menghapus pengguna: ' . $stmt_user->error);
    }
    exit();
}
//======================================================================
// FUNGSI UNTUK GURU (dash.php)
//======================================================================
if ($is_admin_or_teacher) {
    // ... (Fungsi untuk guru tetap sama, tidak perlu diubah)
    // Hapus Kuis
    if (@$_GET['q'] == 'rmquiz') {
        $eid = @$_GET['eid'];
        $result = mysqli_query($con, "SELECT qid FROM questions WHERE eid='$eid'");
        while ($row = mysqli_fetch_array($result)) {
            $qid = $row['qid'];
            mysqli_query($con, "DELETE FROM options WHERE qid='$qid'");
            mysqli_query($con, "DELETE FROM answer WHERE qid='$qid'");
        }
        mysqli_query($con, "DELETE FROM questions WHERE eid='$eid'");
        mysqli_query($con, "DELETE FROM quiz WHERE eid='$eid'");
        mysqli_query($con, "DELETE FROM history WHERE eid='$eid'");
        header("location:dash.php?q=5");
        exit();
    }

    // Tambah Kuis
    if (@$_GET['q'] == 'addquiz') {
        $name = ucwords(strtolower($_POST['name']));
        $total = $_POST['total'];
        $sahi = $_POST['right'];
        $wrong = $_POST['wrong'];
        $time = $_POST['time'];
        $desc = $_POST['intro'];
        $id = uniqid();
        mysqli_query($con, "INSERT INTO quiz (eid, title, sahi, wrong, total, `time`, `intro`, email) VALUES ('$id', '$name', '$sahi', '$wrong', '$total', '$time', '$desc', '$email')");
        header("location:dash.php?q=4&step=2&eid=$id&n=$total");
        exit();
    }

    // Tambah Soal
    if (@$_GET['q'] == 'addqns') {
        $n = @$_GET['n'];
        $eid = @$_GET['eid'];
        $ch = 4;
        for ($i = 1; $i <= $n; $i++) {
            $qid = uniqid();
            $qns = mysqli_real_escape_string($con, $_POST['qns' . $i]);
            $explanation = mysqli_real_escape_string($con, $_POST['explanation' . $i]);
            mysqli_query($con, "INSERT INTO questions (eid, qid, qns, choice, sn, explanation) VALUES ('$eid', '$qid', '$qns', '$ch', '$i', '$explanation')");
            
            $options = [];
            for ($j = 1; $j <= 4; $j++) {
                $option_id = uniqid();
                $option_text = $_POST[$i . $j];
                $options[$j] = $option_id;
                mysqli_query($con, "INSERT INTO options VALUES ('$qid', '$option_text', '$option_id')");
            }
            
            $e = $_POST['ans' . $i];
            $ansid = $options[array_search($e, ['a', 'b', 'c', 'd']) + 1];
            mysqli_query($con, "INSERT INTO answer VALUES ('$qid', '$ansid')");
        }
        header("location:dash.php?q=0");
        exit();
    }

    // Edit Soal
    if (@$_GET['q'] == 'editqns') {
        $eid = @$_GET['eid'];
        $questions_result = mysqli_query($con, "SELECT * FROM questions WHERE eid='$eid' ORDER BY sn ASC");
        while ($question_row = mysqli_fetch_array($questions_result)) {
            $sn = $question_row['sn'];
            $qid = $_POST['qid' . $sn];
            $qns = mysqli_real_escape_string($con, $_POST['qns' . $sn]);
            $explanation = mysqli_real_escape_string($con, $_POST['explanation' . $sn]);
            mysqli_query($con, "UPDATE questions SET qns='$qns', explanation='$explanation' WHERE qid='$qid'");
            
            $options_result = mysqli_query($con, "SELECT * FROM options WHERE qid='$qid'");
            while ($option_row = mysqli_fetch_array($options_result)) {
                $option_id = $option_row['optionid'];
                $option_text = mysqli_real_escape_string($con, $_POST['opt_' . $sn . '_' . $option_id]);
                mysqli_query($con, "UPDATE options SET `option`='$option_text' WHERE optionid='$option_id'");
            }
            
            $new_ans_id = $_POST['ans' . $sn];
            mysqli_query($con, "UPDATE answer SET ansid='$new_ans_id' WHERE qid='$qid'");
        }
        header("location:dash.php?q=0&status=edited");
        exit();
    }

    // Tambah Materi Video
    if (@$_GET['q'] == 'add_material') {
        $title = mysqli_real_escape_string($con, $_POST['title']);
        $description = mysqli_real_escape_string($con, $_POST['description']);
        $youtube_link = $_POST['youtube_link'];
        $video_id = '';
        $regex = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/';
        if (preg_match($regex, $youtube_link, $matches)) {
            $video_id = $matches[1];
        }
        if (!empty($video_id)) {
            $stmt = $con->prepare("INSERT INTO materials (title, description, video_id, uploaded_by) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $title, $description, $video_id, $email);
            $stmt->execute();
            $stmt->close();
            header("location:dash.php?q=8&status=added");
        } else {
            header("location:dash.php?q=7&error=Link YouTube tidak valid.");
        }
        exit();
    }

    // Hapus Materi Video
    if (@$_GET['q'] == 'delete_material' && isset($_GET['id'])) {
        $material_id = $_GET['id'];
        $stmt = $con->prepare("DELETE FROM materials WHERE id = ? AND uploaded_by = ?");
        $stmt->bind_param("is", $material_id, $email);
        $stmt->execute();
        $stmt->close();
        header("location:dash.php?q=8&status=deleted");
        exit();
    }
}

//======================================================================
// FUNGSI UNTUK SISWA (account.php)
//======================================================================

// Proses Kuis
if (@$_GET['q'] == 'quiz' && @$_GET['step'] == 2) {
    $eid = @$_GET['eid'];
    $sn = @$_GET['n'];
    $total = @$_GET['t'];
    $ans = $_POST['ans'];
    $qid = @$_GET['qid'];

    $q = mysqli_query($con, "SELECT ansid FROM answer WHERE qid='$qid'");
    $row = mysqli_fetch_array($q);
    $correct_ansid = $row['ansid'];

    mysqli_query($con, "INSERT INTO user_answer(email, eid, qid, ans) VALUES('$email', '$eid', '$qid', '$ans')") or die('Error logging answer');

    if ($ans == $correct_ansid) {
        $q_quiz = mysqli_query($con, "SELECT sahi FROM quiz WHERE eid='$eid'");
        $sahi = mysqli_fetch_array($q_quiz)['sahi'];
        if ($sn == 1) {
            mysqli_query($con, "INSERT INTO history(email, eid, score, `level`, sahi, wrong, date) VALUES('$email', '$eid', 0, 0, 0, 0, NOW())");
        }
        mysqli_query($con, "UPDATE history SET score = score + $sahi, `level` = $sn, sahi = sahi + 1, date = NOW() WHERE email = '$email' AND eid = '$eid'");
    } else {
        $q_quiz = mysqli_query($con, "SELECT wrong FROM quiz WHERE eid='$eid'");
        $wrong = mysqli_fetch_array($q_quiz)['wrong'];
        if ($sn == 1) {
            mysqli_query($con, "INSERT INTO history(email, eid, score, `level`, sahi, wrong, date) VALUES('$email', '$eid', 0, 0, 0, 0, NOW())");
        }
        mysqli_query($con, "UPDATE history SET score = score - $wrong, `level` = $sn, wrong = wrong + 1, date = NOW() WHERE email = '$email' AND eid = '$eid'");
    }

    if ($sn != $total) {
        $sn++;
        header("location:account.php?q=quiz&step=2&eid=$eid&n=$sn&t=$total");
        exit();
    } else {
        // ======================================================
        // == LOGIKA BARU UNTUK MEMBERIKAN LENCANA ==
        // ======================================================
        $history_q = mysqli_query($con, "SELECT sahi FROM history WHERE eid='$eid' AND email='$email'");
        $history_data = mysqli_fetch_assoc($history_q);
        $correct_answers = $history_data['sahi'];
        
        $badge = null;
        if ($total > 0) {
            $percentage = ($correct_answers / $total) * 100;
            if ($percentage >= 71) {
                $badge = 'Gold';
            } elseif ($percentage >= 31) {
                $badge = 'Silver';
            } else {
                $badge = 'Bronze';
            }
        }
        
        if ($badge) {
            // Gunakan REPLACE INTO untuk memasukkan lencana baru atau memperbarui yang sudah ada
            $stmt = $con->prepare("REPLACE INTO user_badges (user_email, eid, badge_type, earned_date) VALUES (?, ?, ?, NOW())");
            $stmt->bind_param("sss", $email, $eid, $badge);
            $stmt->execute();
            $stmt->close();
        }
        // ======================================================
        // == AKHIR LOGIKA LENCANA ==
        // ======================================================

        // Update total rank
        $total_score_q = mysqli_query($con, "SELECT SUM(score) AS total_score FROM history WHERE email='$email'");
        $total_score_data = mysqli_fetch_assoc($total_score_q);
        $new_total_score = $total_score_data['total_score'] ?? 0;

        $q_rank = mysqli_query($con, "SELECT * FROM `rank` WHERE email='$email'");
        if (mysqli_num_rows($q_rank) == 0) {
            mysqli_query($con, "INSERT INTO `rank`(email, score, time) VALUES('$email', '$new_total_score', NOW())");
        } else {
            mysqli_query($con, "UPDATE `rank` SET score = '$new_total_score', time = NOW() WHERE email = '$email'");
        }
        
        header("location:account.php?q=result&eid=$eid");
        exit();
    }
}

// Hasil Kuis
if (@$_GET['q'] == 'result') {
    $eid = @$_GET['eid'];


    if ($sn != $total) {
        $sn++;
        header("location:account.php?q=quiz&step=2&eid=$eid&n=$sn&t=$total");
        exit();
    } else {
        // == BAGIAN YANG DIPERBAIKI: PENGHITUNGAN SKOR AKHIR DAN RANK ==

        // 1. Hitung ulang TOTAL skor dari SEMUA riwayat kuis pengguna
        $total_score_q = mysqli_query($con, "SELECT SUM(score) AS total_score FROM history WHERE email='$email'");
        $total_score_data = mysqli_fetch_assoc($total_score_q);
        $new_total_score = $total_score_data['total_score'];

        // 2. Periksa apakah pengguna sudah ada di tabel rank
        $q_rank = mysqli_query($con, "SELECT * FROM `rank` WHERE email='$email'");
        if (mysqli_num_rows($q_rank) == 0) {
            // Jika belum ada, buat entri baru
            mysqli_query($con, "INSERT INTO `rank`(email, score, time) VALUES('$email', '$new_total_score', NOW())") or die(mysqli_error($con));
        } else {
            // Jika sudah ada, perbarui dengan total skor yang baru
            mysqli_query($con, "UPDATE `rank` SET score = '$new_total_score', time = NOW() WHERE email = '$email'");
        }
        
        header("location:account.php?q=result&eid=$eid");
        exit();
    }
}


// Restart Kuis
if (@$_GET['q'] == 'quizre' && @$_GET['step'] == 25) {
    $eid = @$_GET['eid'];
    $total = @$_GET['t'];
    
    // Ambil skor kuis yang akan di-restart
    $q_history_score = mysqli_query($con, "SELECT score FROM history WHERE eid='$eid' AND email='$email'");
    if(mysqli_num_rows($q_history_score) > 0) {
        $s = mysqli_fetch_array($q_history_score)['score'];
        
        // Hapus riwayat kuis ini
        mysqli_query($con, "DELETE FROM history WHERE eid='$eid' AND email='$email'");
        
        // Hapus juga jawaban siswa untuk kuis ini
        mysqli_query($con, "DELETE FROM user_answer WHERE eid='$eid' AND email='$email'");
        
        // Perbarui total skor di tabel rank
        mysqli_query($con, "UPDATE `rank` SET score = score - $s, time=NOW() WHERE email= '$email'");
    }
    header("location:account.php?q=quiz&step=2&eid=$eid&n=1&t=$total");
    exit();
}

if (@$_GET['q'] == 'update_profile') {
    // Ambil data dari form
    $name = $_POST['name'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $update_query = "UPDATE user SET name = ? ";
    $params_type = "s";
    $params_value = [$name];

    // --- Proses Upload Avatar ---
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
        $upload_dir = 'img/avatars/';
        // Pastikan direktori ada
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $allowed_types = ['image/jpeg', 'image/png'];
        $file_type = $_FILES['avatar']['type'];

        if (in_array($file_type, $allowed_types)) {
            $file_extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            $new_filename = 'avatar_' . uniqid() . '_' . preg_replace("/[^a-zA-Z0-9]/", "", $email) . '.' . $file_extension;
            
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $upload_dir . $new_filename)) {
                // Tambahkan update avatar ke query
                $update_query .= ", avatar = ? ";
                $params_type .= "s";
                $params_value[] = $new_filename;
            }
        }
    }

    // --- Proses Ubah Password ---
    if (!empty($new_password) && !empty($current_password)) {
        if ($new_password === $confirm_password) {
            // Ambil hash password saat ini dari DB
            $user_q = mysqli_query($con, "SELECT password FROM user WHERE email='$email'");
            $user_data = mysqli_fetch_assoc($user_q);
            
            if (password_verify($current_password, $user_data['password'])) {
                // Jika password lama cocok, hash password baru
                $new_password_hashed = password_hash($new_password, PASSWORD_BCRYPT);
                $update_query .= ", password = ? ";
                $params_type .= "s";
                $params_value[] = $new_password_hashed;
            } else {
                header("location:account.php?q=4&error=Password lama salah!");
                exit();
            }
        } else {
            header("location:account.php?q=4&error=Konfirmasi password baru tidak cocok!");
            exit();
        }
    }

    // Eksekusi query update
    $update_query .= " WHERE email = ?";
    $params_type .= "s";
    $params_value[] = $email;

    $stmt = $con->prepare($update_query);
    // Gunakan "Splat Operator" (...) untuk memasukkan array parameter ke bind_param
    $stmt->bind_param($params_type, ...$params_value);
    $stmt->execute();
    $stmt->close();
    
    // Update session dengan nama baru
    $_SESSION['name'] = $name;

    header("location:account.php?q=4&status=success");
    exit();
}

?>
