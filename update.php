<?php
include_once 'dbConnection.php';
session_start();
$email = $_SESSION['email'] ?? null;

$is_admin_or_teacher = (isset($_SESSION['key']) && $_SESSION['key'] == 'prasanth123');

//======================================================================
// FUNGSI UNTUK SUPER ADMIN (headdash.php)
//======================================================================
if ($is_admin_or_teacher) {
    // Hapus feedback oleh Super Admin
    if (@$_GET['fdid']) {
        $id = @$_GET['fdid'];
        mysqli_query($con, "DELETE FROM feedback WHERE id='$id'") or die('Error');
        header("location:headdash.php?q=3");
        exit();
    }

    // Hapus user oleh Super Admin
    if (@$_GET['demail']) {
        $demail = @$_GET['demail'];
        mysqli_query($con, "DELETE FROM `rank` WHERE email='$demail'") or die('Error');
        mysqli_query($con, "DELETE FROM history WHERE email='$demail'") or die('Error');
        mysqli_query($con, "DELETE FROM user WHERE email='$demail'") or die('Error');
        header("location:headdash.php?q=1");
        exit();
    }

    // Hapus admin oleh Super Admin
    if (@$_GET['demail1']) {
        $demail1 = @$_GET['demail1'];
        mysqli_query($con, "DELETE FROM admin WHERE email='$demail1' AND role='admin'") or die('Error');
        header("location:headdash.php?q=5");
        exit();
    }
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
?>
