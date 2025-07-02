<?php
// Selalu letakkan session_start() di paling atas
session_start();
include_once 'dbConnection.php';

// Redirect jika user belum login
if (!isset($_SESSION['email'])) {
    header("location:index.php");
    exit();
} else {
    $name = $_SESSION['name'];
    $email = $_SESSION['email'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Examiner - Dashboard Siswa</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Ubuntu:wght@700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fc;
        }
        .header {
            background: linear-gradient(90deg, #f4511e, #ff7043);
            color: white;
            padding: 10px 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header a {
            color: white;
            text-decoration: none;
        }
        .header a:hover {
            text-decoration: underline;
        }
        .navbar-brand b {
            font-family: 'Ubuntu', sans-serif;
        }
        .card {
            margin-bottom: 1.5rem;
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .btn-start {
            background-color: #4caf50;
            color: white;
        }
        .btn-start:hover {
            background-color: #45a049;
            color: white;
        }
        
        /* == CSS PENTING UNTUK VIDEO == */
        .video-responsive {
            position: relative;
            overflow: hidden;
            padding-top: 56.25%; /* 16:9 Aspect Ratio */
            border-radius: 12px 12px 0 0;
        }
        .video-responsive iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }
        /* == AKHIR CSS VIDEO == */

    </style>

    <?php if(@$_GET['w']) { echo'<script>alert("'.htmlspecialchars(@$_GET['w']).'");</script>'; } ?>
</head>
<body>

<div class="header">
    <div class="container d-flex justify-content-between align-items-center">
        <a href="account.php?q=1" class="h4 mb-0 text-white text-decoration-none">Ujian Ceria</a>
        <div>
            <span><i class="fas fa-user-circle"></i> Selamat Datang,</span>
            <a href="account.php?q=1"><?php echo htmlspecialchars($name); ?></a>&nbsp;|&nbsp;
            <a href="logout.php?q=account.php"><i class="fas fa-sign-out-alt"></i> Keluar</a>
        </div>
    </div>
</div>

<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="account.php?q=1"><b><i class="fas fa-user-graduate"></i> Dashboard Siswa</b></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link <?php if(@$_GET['q']==1) echo 'active'; ?>" href="account.php?q=1"><i class="fas fa-home"></i> Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if(@$_GET['q']==2) echo 'active'; ?>" href="account.php?q=2"><i class="fas fa-history"></i> Riwayat</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if(@$_GET['q']==3) echo 'active'; ?>" href="account.php?q=3"><i class="fas fa-trophy"></i> Peringkat</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if(@$_GET['q']==5) echo 'active'; ?>" href="account.php?q=5"><i class="fas fa-book"></i> Materi Belajar</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">

            <?php if (@$_GET['q'] == 1): // Main Content: Home ?>
                <?php
                // Pesan dari guru
                $qmsg = mysqli_query($con, "SELECT * FROM messages WHERE to_email='$email' ORDER BY date DESC");
                if (mysqli_num_rows($qmsg) > 0) {
                    echo '<div class="card"><div class="card-header"><h4>Pesan dari Guru</h4></div><ul class="list-group list-group-flush">';
                    while ($msg = mysqli_fetch_array($qmsg)) {
                        echo '<li class="list-group-item"><b>[' . date('d M Y', strtotime($msg['date'])) . ']</b> ' . htmlspecialchars($msg['content']) . '</li>';
                    }
                    echo '</ul></div>';
                }

                // Daftar Kuis
                $result = mysqli_query($con, "SELECT * FROM quiz ORDER BY date DESC") or die('Error');
                echo '<div class="card"><div class="card-header"><h4>Daftar Kuis Tersedia</h4></div><div class="card-body"><div class="table-responsive"><table class="table table-striped table-hover">
                      <thead><tr><th>No.</th><th>Topik</th><th>Total Soal</th><th>Waktu</th><th></th></tr></thead><tbody>';
                $c = 1;
                while ($row = mysqli_fetch_array($result)) {
                    $eid = $row['eid'];
                    $q12 = mysqli_query($con, "SELECT score FROM history WHERE eid='$eid' AND email='$email'") or die('Error98');
                    $rowcount = mysqli_num_rows($q12);
                    
                    echo '<tr><td>' . $c++ . '</td><td>' . htmlspecialchars($row['title']);
                    if ($rowcount > 0) {
                        echo '&nbsp;<span class="badge bg-success">Selesai</span>';
                    }
                    echo '</td><td>' . $row['total'] . '</td><td>' . $row['time'] . '&nbsp;menit</td><td>';
                    
                    if ($rowcount == 0) {
                        echo '<a href="account.php?q=quiz&step=2&eid=' . $eid . '&n=1&t=' . $row['total'] . '" class="btn btn-sm btn-start"><i class="fas fa-play"></i> Mulai</a>';
                    } else {
                        echo '<a href="account.php?q=result&eid=' . $eid . '" class="btn btn-sm btn-info text-white"><i class="fas fa-eye"></i> Lihat Hasil</a>';
                    }
                    echo '</td></tr>';
                }
                echo '</tbody></table></div></div></div>';
                ?>
            <?php endif; ?>

            <?php if (@$_GET['q'] == 2): // Main Content: History ?>
                <?php
                $q = mysqli_query($con, "SELECT * FROM history WHERE email='$email' ORDER BY date DESC ") or die('Error197');
                echo '<div class="card"><div class="card-header"><h4>Riwayat Pengerjaan Kuis</h4></div><div class="card-body"><div class="table-responsive"><table class="table table-striped table-hover">
                      <thead><tr><th>No.</th><th>Kuis</th><th>Soal Dikerjakan</th><th>Benar</th><th>Salah</th><th>Skor</th></tr></thead><tbody>';
                $c = 0;
                while ($row = mysqli_fetch_array($q)) {
                    $eid = $row['eid'];
                    $q23 = mysqli_query($con, "SELECT title FROM quiz WHERE eid='$eid'") or die('Error208');
                    $quiz_title = mysqli_fetch_array($q23)['title'];
                    $c++;
                    echo '<tr><td>' . $c . '</td><td>' . htmlspecialchars($quiz_title) . '</td><td>' . $row['level'] . '</td><td class="text-success">' . $row['sahi'] . '</td><td class="text-danger">' . $row['wrong'] . '</td><td><b>' . $row['score'] . '</b></td></tr>';
                }
                echo '</tbody></table></div></div></div>';
                ?>
            <?php endif; ?>

            <?php if (@$_GET['q'] == 3): // Main Content: Ranking ?>
                <?php
                $q = mysqli_query($con, "SELECT * FROM rank ORDER BY score DESC ") or die('Error223');
                echo '<div class="card"><div class="card-header"><h4>Peringkat Peserta</h4></div><div class="card-body"><div class="table-responsive"><table class="table table-striped table-hover">
                      <thead><tr><th>Peringkat</th><th>Nama</th><th>Asal Sekolah</th><th>Skor</th></tr></thead><tbody>';
                $c = 0;
                while ($row = mysqli_fetch_array($q)) {
                    $e = $row['email'];
                    $q12 = mysqli_query($con, "SELECT name, college FROM user WHERE email='$e'") or die('Error231');
                    $user_data = mysqli_fetch_array($q12);
                    $c++;
                    echo '<tr><td class="fw-bold">' . $c . '</td><td>' . htmlspecialchars($user_data['name']) . '</td><td>' . htmlspecialchars($user_data['college']) . '</td><td>' . $row['score'] . '</td></tr>';
                }
                echo '</tbody></table></div></div></div>';
                ?>
            <?php endif; ?>

            <?php if (@$_GET['q'] == 'quiz' && @$_GET['step'] == 2): // Main Content: Quiz Start ?>
                <?php
                $eid = @$_GET['eid'];
                $sn = @$_GET['n'];
                $total = @$_GET['t'];
                $q = mysqli_query($con, "SELECT * FROM questions WHERE eid='$eid' AND sn='$sn'");
                
                echo '<div class="card"><div class="card-body">';
                if ($row = mysqli_fetch_array($q)) {
                    $qns = $row['qns'];
                    $qid = $row['qid'];
                    echo '<h4>Pertanyaan ' . $sn . ' dari ' . $total . '</h4>';
                    echo '<p class="lead">' . nl2br(htmlspecialchars($qns)) . '</p>';
                    
                    $options_q = mysqli_query($con, "SELECT * FROM options WHERE qid='$qid'");
                    echo '<form action="update.php?q=quiz&step=2&eid=' . $eid . '&n=' . $sn . '&t=' . $total . '&qid=' . $qid . '" method="POST" class="form-horizontal">';
                    
                    while ($option_row = mysqli_fetch_array($options_q)) {
                        echo '<div class="form-check my-3"><input class="form-check-input" type="radio" name="ans" id="opt' . $option_row['optionid'] . '" value="' . $option_row['optionid'] . '" required>
                              <label class="form-check-label" for="opt' . $option_row['optionid'] . '">' . htmlspecialchars($option_row['option']) . '</label></div>';
                    }
                    
                    echo '<br/><button type="submit" class="btn btn-primary"><i class="fas fa-lock"></i> Submit Jawaban</button></form>';
                }
                echo '</div></div>';
                ?>
            <?php endif; ?>

            <?php if (@$_GET['q'] == 'result' && @$_GET['eid']): // Main Content: Result Display ?>
                <?php
                $eid = @$_GET['eid'];
                // Tampilkan Ringkasan Skor
                $q_history = mysqli_query($con, "SELECT * FROM history WHERE eid='$eid' AND email='$email'") or die('Error157');
                echo '<div class="card mb-4"><div class="card-header bg-primary text-white"><h3><i class="fas fa-poll"></i> Hasil Kuis Anda</h3></div><div class="card-body">';
                if ($row = mysqli_fetch_array($q_history)) {
                    echo '<ul class="list-group list-group-flush fs-5">
                            <li class="list-group-item d-flex justify-content-between"><span>Total Pertanyaan</span> <strong>' . $row['level'] . '</strong></li>
                            <li class="list-group-item d-flex justify-content-between"><span><i class="fas fa-check-circle text-success"></i> Jawaban Benar</span> <strong class="text-success">' . $row['sahi'] . '</strong></li>
                            <li class="list-group-item d-flex justify-content-between"><span><i class="fas fa-times-circle text-danger"></i> Jawaban Salah</span> <strong class="text-danger">' . $row['wrong'] . '</strong></li>
                            <li class="list-group-item d-flex justify-content-between"><span><i class="fas fa-star text-warning"></i> Skor Akhir</span> <strong>' . $row['score'] . '</strong></li>
                          </ul>';
                }
                echo '</div></div>';

                // Tampilkan Pembahasan Jawaban
                echo '<h3><i class="fas fa-book-reader"></i> Pembahasan Soal</h3>';
                $q_questions = mysqli_query($con, "SELECT * FROM questions WHERE eid='$eid' ORDER BY sn ASC");
                $no = 1;
                while ($question = mysqli_fetch_array($q_questions)) {
                    $qid = $question['qid'];
                    $q_options = mysqli_query($con, "SELECT * FROM options WHERE qid='$qid'");
                    $options = [];
                    while($opt = mysqli_fetch_array($q_options)){ $options[$opt['optionid']] = $opt['option']; }
                    $q_correct_ans = mysqli_query($con, "SELECT ansid FROM answer WHERE qid='$qid'");
                    $correct_ans_id = mysqli_fetch_array($q_correct_ans)['ansid'];
                    $q_user_ans = mysqli_query($con, "SELECT ans FROM user_answer WHERE eid='$eid' AND email='$email' AND qid='$qid'");
                    $user_ans_id = mysqli_num_rows($q_user_ans) > 0 ? mysqli_fetch_array($q_user_ans)['ans'] : null;

                    echo '<div class="card mb-3"><div class="card-header bg-light"><strong>Pertanyaan ' . $no++ . ':</strong></div><div class="card-body"><p class="card-text">' . nl2br(htmlspecialchars($question['qns'])) . '</p><ul class="list-group">';
                    foreach($options as $option_id => $option_text) {
                        $is_user_answer = ($option_id == $user_ans_id);
                        $is_correct_answer = ($option_id == $correct_ans_id);
                        $extra_class = ''; $icon = '';
                        if ($is_correct_answer) { $extra_class = 'list-group-item-success'; $icon = ' <i class="fas fa-check-circle"></i> <strong>(Jawaban Benar)</strong>'; }
                        if ($is_user_answer && !$is_correct_answer) { $extra_class = 'list-group-item-danger'; $icon = ' <i class="fas fa-times-circle"></i> <strong>(Jawaban Kamu)</strong>'; }
                        elseif ($is_user_answer && $is_correct_answer) { $icon = ' <i class="fas fa-check-circle"></i> <strong>(Jawaban Kamu Benar)</strong>'; }
                        echo '<li class="list-group-item ' . $extra_class . '">' . htmlspecialchars($option_text) . $icon . '</li>';
                    }
                    echo '</ul>';
                    if (!empty($question['explanation'])) {
                        echo '<div class="alert alert-info mt-3"><strong><i class="fas fa-lightbulb"></i> Penjelasan:</strong><br>' . nl2br(htmlspecialchars($question['explanation'])) . '</div>';
                    }
                    echo '</div></div>';
                }
                echo '<div class="mt-4 text-center"><a href="account.php?q=1" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali ke Home</a></div>';
                ?>
            <?php endif; ?>

            <!-- ======================================================= -->
            <!-- == BAGIAN BARU UNTUK MATERI (q=5) == -->
            <!-- ======================================================= -->
            <?php if(@$_GET['q']==5): ?>
            <div class="row">
                <div class="col-12">
                    <h3 class="mb-4"><i class="fas fa-book"></i> Materi Belajar</h3>
                </div>
                <?php
                    // Query untuk mengambil materi dan nama guru yang mengunggah
                    $result = mysqli_query($con, "SELECT m.*, a.name as teacher_name FROM materials m JOIN admin a ON m.uploaded_by = a.email ORDER BY m.upload_date DESC");
                    if(mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_array($result)) {
                            echo '<div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100 shadow-sm">
                                        <div class="video-responsive">
                                            <iframe src="https://www.youtube.com/embed/'.htmlspecialchars($row['video_id']).'" title="'.htmlspecialchars($row['title']).'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                        </div>
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title">'.htmlspecialchars($row['title']).'</h5>
                                            <p class="card-text text-muted flex-grow-1">'.htmlspecialchars($row['description']).'</p>
                                            <small class="text-muted">Diunggah oleh: '.htmlspecialchars($row['teacher_name']).'</small>
                                        </div>
                                        <div class="card-footer bg-white border-0">
                                            <small class="text-muted">Pada: '.date('d M Y', strtotime($row['upload_date'])).'</small>
                                        </div>
                                    </div>
                                  </div>';
                        }
                    } else {
                        echo '<div class="col-12"><div class="alert alert-info">Belum ada materi yang diunggah oleh guru.</div></div>';
                    }
                ?>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>