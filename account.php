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

// Query untuk menghitung pesan yang belum dibaca
// PASTIKAN 'user_email' adalah nama kolom yang benar di tabel 'messages' Anda
$unread_q = mysqli_query($con, "SELECT COUNT(*) as total FROM messages WHERE recipient_email='$email' AND is_read=0");
$unread_count = 0;
if ($unread_q) {
    $unread_data = mysqli_fetch_assoc($unread_q);
    $unread_count = $unread_data['total'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa - Ujian Ceria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Ubuntu:wght@700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2962ff;
            --secondary-color: #ffc107;
            --background-color: #f4f7fc;
            --card-bg: #ffffff;
            --text-color: #333;
            --header-color: #fff;
        }
        body { font-family: 'Poppins', sans-serif; background-color: var(--background-color); }
        .header { background: linear-gradient(90deg, #f4511e, #ff7043); color: white; padding: 10px 0; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header a { color: white; text-decoration: none; }
        .header a:hover { text-decoration: underline; }
        .navbar-brand b { font-family: 'Ubuntu', sans-serif; }
        .card { margin-bottom: 1.5rem; border: none; border-radius: 15px; box-shadow: 0 8px 25px rgba(0,0,0,0.07); transition: transform 0.3s; }
        .card:hover { transform: translateY(-5px); }
        .profile-card {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 2rem;
        }
        .profile-card h3 { color: white; }
        .stat-item { text-align: center; }
        .stat-item .stat-number { font-size: 2rem; font-weight: 700; }
        .stat-item .stat-label { font-size: 0.9rem; opacity: 0.8; }
        .quiz-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #f0f0f0;
        }
        .quiz-item:last-child { border-bottom: none; }
        .quiz-icon {
            font-size: 1.5rem;
            color: var(--primary-color);
            margin-right: 1.5rem;
            width: 40px;
            text-align: center;
        }
        .quiz-details { flex-grow: 1; }
        .quiz-details h6 { margin-bottom: 0.25rem; font-weight: 600; }
        .quiz-details p { margin-bottom: 0; font-size: 0.9rem; color: #6c757d; }
        .riddle-card { background-color: #fffbeb; border-left: 5px solid var(--secondary-color); }
        .video-responsive { position: relative; overflow: hidden; padding-top: 56.25%; border-radius: 12px 12px 0 0; }
        .video-responsive iframe { position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0; }
        .podium-container { display: flex; align-items: flex-end; justify-content: center; gap: 1rem; margin-bottom: 3rem; min-height: 250px; }
        .podium-item { text-align: center; width: 30%; }
        .podium-avatar { width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 5px solid; margin: 0 auto 1rem; background-color: #fff; }
        .podium-1 .podium-avatar { border-color: #ffd700; }
        .podium-2 .podium-avatar { border-color: #c0c0c0; }
        .podium-3 .podium-avatar { border-color: #cd7f32; }
        .podium-base { background-color: #e9ecef; border-radius: 10px 10px 0 0; padding: 1rem; color: #495057; font-weight: bold; }
        .podium-1 .podium-base { height: 150px; background-color: #ffd700; color: #333; }
        .podium-2 .podium-base { height: 120px; background-color: #c0c0c0; }
        .podium-3 .podium-base { height: 90px; background-color: #cd7f32; }
        .podium-rank { font-size: 2rem; font-weight: 700; }
        .profile-avatar { width: 150px; height: 150px; object-fit: cover; border-radius: 50%; border: 5px solid #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<div class="header">
    <div class="container d-flex justify-content-between align-items-center">
        <a href="account.php?q=1" class="h4 mb-0 text-white text-decoration-none">Ujian Ceria</a>
        <div>
            <span><i class="fas fa-user-circle"></i> Selamat Datang,</span>
            <a href="account.php?q=1"><?php echo htmlspecialchars($name); ?></a> | 
            <a href="logout.php?q=account.php"><i class="fas fa-sign-out-alt"></i> Keluar</a>
        </div>
    </div>
</div>
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="account.php?q=1"><b><i class="fas fa-user-graduate"></i> Dashboard Siswa</b></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link <?php if(@$_GET['q']==1) echo 'active'; ?>" href="account.php?q=1"><i class="fas fa-home"></i> Home</a></li>
                <li class="nav-item"><a class="nav-link <?php if(@$_GET['q']==2) echo 'active'; ?>" href="account.php?q=2"><i class="fas fa-history"></i> Riwayat</a></li>
                <li class="nav-item"><a class="nav-link <?php if(@$_GET['q']==3) echo 'active'; ?>" href="account.php?q=3"><i class="fas fa-trophy"></i> Peringkat</a></li>
                <li class="nav-item"><a class="nav-link <?php if(@$_GET['q']==5) echo 'active'; ?>" href="account.php?q=5"><i class="fas fa-book"></i> Materi Belajar</a></li>
                <li class="nav-item"><a class="nav-link <?php if(@$_GET['q']==6) echo 'active'; ?>" href="account.php?q=6"><i class="fas fa-award"></i> Lencana Saya</a></li>
                <li class="nav-item">
                    <a class="nav-link <?php if(@$_GET['q']==7) echo 'active'; ?>" href="account.php?q=7">
                        <i class="fas fa-envelope"></i> Kotak Masuk
                        <?php if($unread_count > 0): ?>
                            <span class="badge bg-danger rounded-pill ms-1"><?= $unread_count ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item"><a class="nav-link <?php if(@$_GET['q']==4) echo 'active'; ?>" href="account.php?q=4"><i class="fas fa-user"></i> Edit Profile</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-4">
    <div class="row">
        <div class="col-lg-4">
            <?php
            $user_profile_q = mysqli_query($con, "SELECT avatar FROM user WHERE email='$email'");
            $user_avatar = 'default.png';
            if (mysqli_num_rows($user_profile_q) > 0) {
                $user_avatar_data = mysqli_fetch_assoc($user_profile_q);
                if (!empty($user_avatar_data['avatar'])) {
                    $user_avatar = $user_avatar_data['avatar'];
                }
            }
            ?>
            <div class="card profile-card text-center">
                <img src="img/avatars/<?= htmlspecialchars($user_avatar) ?>" alt="Foto Profil" class="img-fluid rounded-circle mx-auto mb-3 mt-3" style="width: 120px; height: 120px; object-fit: cover; border: 4px solid white;" onerror="this.onerror=null;this.src='img/avatars/default.png';">
                <h3>Halo, <?= htmlspecialchars(explode(' ', $name)[0]) ?>!</h3>
                <p class="opacity-75">Terus semangat belajar ya!</p>
                <hr>
                <div class="row mt-3">
                    <?php
                    $rank_q = mysqli_query($con, "SELECT score FROM `rank` WHERE email='$email'");
                    $total_score = mysqli_num_rows($rank_q) > 0 ? mysqli_fetch_assoc($rank_q)['score'] : 0;
                    $history_q = mysqli_query($con, "SELECT COUNT(eid) as completed_quizzes FROM history WHERE email='$email'");
                    $completed_quizzes = mysqli_fetch_assoc($history_q)['completed_quizzes'];
                    ?>
                    <div class="col-6 stat-item">
                        <div class="stat-number"><?= $total_score ?></div>
                        <div class="stat-label">Total Poin</div>
                    </div>
                    <div class="col-6 stat-item">
                        <div class="stat-number"><?= $completed_quizzes ?></div>
                        <div class="stat-label">Kuis Selesai</div>
                    </div>
                </div>
            </div>
            
            <div class="card riddle-card">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-puzzle-piece text-warning"></i> Teka-Teki Hari Ini!</h5>
                    <p class="card-text">Aku punya kota, tapi tak punya rumah. Aku punya gunung, tapi tak punya pohon. Aku punya air, tapi tak punya ikan. Siapakah aku?</p>
                    <button class="btn btn-sm btn-warning" onclick="alert('Jawabannya adalah Peta!')">Lihat Jawaban</button>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            
            <?php if (@$_GET['q'] == 1): ?>
            <div class="card">
                <div class="card-header"><h5><i class="fas fa-pencil-alt"></i> Ayo Kerjakan Kuis Ini!</h5></div>
                <div class="card-body p-0">
                    <?php
                    $result = mysqli_query($con, "SELECT * FROM quiz ORDER BY date DESC") or die('Error');
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_array($result)) {
                            $eid = $row['eid'];
                            $q12 = mysqli_query($con, "SELECT score FROM history WHERE eid='$eid' AND email='$email'");
                            $rowcount = mysqli_num_rows($q12);
                            echo '<div class="quiz-item"><div class="quiz-icon"><i class="fas fa-calculator"></i></div><div class="quiz-details"><h6>'.htmlspecialchars($row['title']).'</h6><p>'.$row['total'].' Soal • '.$row['time'].' Menit</p></div>';
                            if ($rowcount > 0) {
                                echo '<a href="account.php?q=result&eid=' . $eid . '" class="btn btn-sm btn-info text-white ms-auto"><i class="fas fa-eye"></i> Lihat Hasil</a>';
                            } else {
                                echo '<a href="account.php?q=quiz&step=2&eid=' . $eid . '&n=1&t=' . $row['total'] . '" class="btn btn-sm btn-success ms-auto"><i class="fas fa-play"></i> Mulai</a>';
                            }
                            echo '</div>';
                        }
                    } else {
                        echo '<p class="text-center p-3">Belum ada kuis yang tersedia saat ini.</p>';
                    }
                    ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if (@$_GET['q'] == 2): ?>
            <div class="card">
                <div class="card-header"><h4>Riwayat Pengerjaan Kuis</h4></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead><tr><th>No.</th><th>Kuis</th><th>Soal Dikerjakan</th><th>Benar</th><th>Salah</th><th>Skor</th></tr></thead>
                            <tbody>
                            <?php
                            $q = mysqli_query($con, "SELECT * FROM history WHERE email='$email' ORDER BY date DESC ") or die('Error197');
                            $c = 0;
                            while ($row = mysqli_fetch_array($q)) {
                                $eid = $row['eid'];
                                $q23 = mysqli_query($con, "SELECT title FROM quiz WHERE eid='$eid'") or die('Error208');
                                $quiz_title = mysqli_fetch_array($q23)['title'];
                                $c++;
                                echo '<tr><td>' . $c . '</td><td>' . htmlspecialchars($quiz_title) . '</td><td>' . $row['level'] . '</td><td class="text-success">' . $row['sahi'] . '</td><td class="text-danger">' . $row['wrong'] . '</td><td><b>' . $row['score'] . '</b></td></tr>';
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if (@$_GET['q'] == 3): ?>
            <div class="card">
                <div class="card-header text-center"><h4><i class="fas fa-trophy" style="color: #ffd700;"></i> Papan Peringkat</h4></div>
                <div class="card-body">
                    <?php
                    $query = "SELECT r.score, u.name, u.avatar, (SELECT badge_type FROM user_badges ub WHERE ub.user_email = r.email ORDER BY FIELD(badge_type, 'Gold', 'Silver', 'Bronze') LIMIT 1) as badge_type FROM `rank` r JOIN `user` u ON r.email = u.email ORDER BY r.score DESC";
                    $q = mysqli_query($con, $query);
                    $ranks = [];
                    if ($q) {
                        while($row = mysqli_fetch_assoc($q)) {
                            $ranks[] = $row;
                        }
                    }
                    ?>
                    <div class="podium-container">
                        <?php
                        if (isset($ranks[1])) { echo '<div class="podium-item podium-2"><img src="img/avatars/'.htmlspecialchars($ranks[1]['avatar']).'" class="podium-avatar" onerror="this.src=\'img/avatars/default.png\'"><div class="podium-base"><div class="podium-rank">2</div><strong>'.htmlspecialchars($ranks[1]['name']).'</strong><br><span>'.$ranks[1]['score'].' Poin</span></div></div>'; }
                        if (isset($ranks[0])) { echo '<div class="podium-item podium-1"><img src="img/avatars/'.htmlspecialchars($ranks[0]['avatar']).'" class="podium-avatar" onerror="this.src=\'img/avatars/default.png\'"><div class="podium-base"><div class="podium-rank">1</div><strong>'.htmlspecialchars($ranks[0]['name']).'</strong><br><span>'.$ranks[0]['score'].' Poin</span></div></div>'; }
                        if (isset($ranks[2])) { echo '<div class="podium-item podium-3"><img src="img/avatars/'.htmlspecialchars($ranks[2]['avatar']).'" class="podium-avatar" onerror="this.src=\'img/avatars/default.png\'"><div class="podium-base"><div class="podium-rank">3</div><strong>'.htmlspecialchars($ranks[2]['name']).'</strong><br><span>'.$ranks[2]['score'].' Poin</span></div></div>'; }
                        ?>
                    </div>
                    <?php if (empty($ranks)): ?>
                        <div class="alert alert-info text-center">Belum ada data peringkat yang tersedia.</div>
                    <?php endif; ?>
                    <hr>
                    <h5 class="text-center mb-3">Peringkat Lainnya</h5>
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead><tr><th>Peringkat</th><th>Nama</th><th>Lencana</th><th>Skor</th></tr></thead>
                            <tbody>
                            <?php
                            if (count($ranks) > 3) {
                                for ($i = 3; $i < count($ranks); $i++) {
                                    $badge_display = '-';
                                    $badge_images = [
                                        'Gold'   => '/img/badge/GOLD.png',
                                        'Silver' => '/img/badge/SILVER.png',
                                        'Bronze' => '/img/badge/BRONZE.png'
                                    ];
                                    $badge_type = $ranks[$i]['badge_type'];
                                    if (isset($badge_images[$badge_type])) {
                                        $badge_img_src = $badge_images[$badge_type];
                                        $badge_display = '<img src="'.$badge_img_src.'" alt="'.$badge_type.' Badge" style="width: 25px; height: 25px;">';
                                    }
                                    echo '<tr><td class="fw-bold">'.($i + 1).'</td><td><img src="img/avatars/'.htmlspecialchars($ranks[$i]['avatar']).'" width="30" height="30" class="rounded-circle me-2" onerror="this.src=\'img/avatars/default.png\'">'.htmlspecialchars($ranks[$i]['name']).'</td><td class="text-center">'.$badge_display.'</td><td>'.$ranks[$i]['score'].'</td></tr>';
                                }
                            } else if (count($ranks) > 0 && count($ranks) <= 3) {
                                echo '<tr><td colspan="4" class="text-center text-muted">Tidak ada peringkat lainnya untuk ditampilkan.</td></tr>';
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (@$_GET['q'] == 4): ?>
            <div class="card">
                <div class="card-header"><h4><i class="fas fa-user-edit"></i> Edit Profil dan Avatar</h4></div>
                <div class="card-body">
                    <?php
                    $user_q = mysqli_query($con, "SELECT * FROM user WHERE email='$email'");
                    $user_data = mysqli_fetch_assoc($user_q);
                    ?>
                    <?php if(isset($_GET['status']) && $_GET['status'] == 'success'): ?>
                        <div class="alert alert-success">Profil berhasil diperbarui!</div>
                    <?php endif; ?>
                    <?php if(isset($_GET['error'])): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
                    <?php endif; ?>
                    <form action="update.php?q=update_profile" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <img src="img/avatars/<?= htmlspecialchars($user_data['avatar']) ?>" class="profile-avatar mb-3" onerror="this.onerror=null;this.src='img/avatars/default.png';">
                                <div class="mb-3">
                                    <label for="avatar" class="form-label">Ubah Foto Profil</label>
                                    <input class="form-control form-control-sm" type="file" name="avatar" id="avatar" accept="image/png, image/jpeg">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-3"><label for="name" class="form-label">Nama Lengkap</label><input type="text" class="form-control" name="name" id="name" value="<?= htmlspecialchars($user_data['name']) ?>" required></div>
                                <div class="mb-3"><label for="email" class="form-label">Email</label><input type="email" class="form-control" id="email" value="<?= htmlspecialchars($user_data['email']) ?>" readonly disabled></div>
                                <div class="mb-3"><label for="college" class="form-label">NIS</label><input type="text" class="form-control" id="college" name="college" value="<?= htmlspecialchars($user_data['college']) ?>" required></div>
                                <hr>
                                <h5 class="mt-4">Ubah Password (Opsional)</h5>
                                <p class="text-muted">Kosongkan jika tidak ingin mengubah password.</p>
                                <div class="mb-3"><label for="current_password" class="form-label">Password Saat Ini</label><input type="password" class="form-control" name="current_password" id="current_password"></div>
                                <div class="mb-3"><label for="new_password" class="form-label">Password Baru</label><input type="password" class="form-control" name="new_password" id="new_password"></div>
                                <div class="mb-3"><label for="confirm_password" class="form-label">Konfirmasi Password Baru</label><input type="password" class="form-control" name="confirm_password" id="confirm_password"></div>
                            </div>
                        </div>
                        <div class="text-end mt-4"><button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button></div>
                    </form>
                </div>
            </div>
            <?php endif; ?>

            <?php if(@$_GET['q']==5): ?>
            <div class="row">
                <div class="col-12"><h3 class="mb-4"><i class="fas fa-book"></i> Materi Belajar</h3></div>
                <?php
                $result = mysqli_query($con, "SELECT m.*, a.name as teacher_name FROM materials m JOIN admin a ON m.uploaded_by = a.email ORDER BY m.upload_date DESC");
                if(mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_array($result)) {
                        echo '<div class="col-md-6 col-lg-4 mb-4"><div class="card h-100 shadow-sm"><div class="video-responsive"><iframe src="https://www.youtube.com/embed/'.htmlspecialchars($row['video_id']).'" title="'.htmlspecialchars($row['title']).'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div><div class="card-body d-flex flex-column"><h5 class="card-title">'.htmlspecialchars($row['title']).'</h5><p class="card-text text-muted flex-grow-1">'.htmlspecialchars($row['description']).'</p><small class="text-muted">Diunggah oleh: '.htmlspecialchars($row['teacher_name']).'</small></div><div class="card-footer bg-white border-0"><small class="text-muted">Pada: '.date('d M Y', strtotime($row['upload_date'])).'</small></div></div></div>';
                    }
                } else {
                    echo '<div class="col-12"><div class="alert alert-info">Belum ada materi yang diunggah oleh guru.</div></div>';
                }
                ?>
            </div>
            <?php endif; ?>
            
            <?php if (@$_GET['q'] == 6): ?>
            <div class="card">
                <div class="card-header text-center"><h4><i class="fas fa-award"></i> Koleksi Lencana Saya</h4></div>
                <div class="card-body">
                    <div class="row">
                        <?php
                        $badge_q = mysqli_query($con, "SELECT b.badge_type, b.earned_date, q.title FROM user_badges b JOIN quiz q ON b.eid = q.eid WHERE b.user_email='$email' ORDER BY FIELD(b.badge_type, 'Gold', 'Silver', 'Bronze'), b.earned_date DESC");
                        $badge_images = [
                            'Gold'   => '/img/badge/GOLD.png',
                            'Silver' => '/img/badge/SILVER.png',
                            'Bronze' => '/img/badge/BRONZE.png'
                        ];
                        if(mysqli_num_rows($badge_q) > 0) {
                            while($badge = mysqli_fetch_assoc($badge_q)) {
                                $badge_img_src = ''; 
                                $badge_type = $badge['badge_type'];
                                if (isset($badge_images[$badge_type])) {
                                    $badge_img_src = $badge_images[$badge_type];
                                }
                                echo '<div class="col-md-4 mb-4"><div class="card badge-card h-100 text-center"><div class="card-body"><img src="'.$badge_img_src.'" alt="Lencana '.$badge_type.'" class="img-fluid mb-3" style="width: 100px; height: 100px; object-fit: contain;"><h5 class="mt-3">Lencana '.$badge_type.'</h5><p class="text-muted mb-0">Diraih dari kuis:</p><p class="fw-bold">'.htmlspecialchars($badge['title']).'</p></div></div></div>';
                            }
                        } else {
                            echo '<div class="col-12"><div class="alert alert-info">Kamu belum mendapatkan lencana. Ayo kerjakan kuis dan raih semuanya!</div></div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if (@$_GET['q'] == 7): ?>
            <div class="card">
                <div class="card-header"><h4><i class="fas fa-envelope-open-text"></i> Kotak Masuk Pesan</h4></div>
                <div class="card-body">
                    <?php
    $messages_q = mysqli_query($con, "SELECT * FROM messages WHERE recipient_email='$email' ORDER BY sent_date DESC");
                    if (mysqli_num_rows($messages_q) > 0) {
                        while ($msg = mysqli_fetch_assoc($messages_q)) {
                            $alert_class = $msg['is_read'] == 0 ? 'alert-primary' : 'alert-light';
                            echo '<div class="alert '.$alert_class.'" role="alert"><div class="d-flex justify-content-between"><h6 class="alert-heading mb-0">Dari: '.htmlspecialchars($msg['sender_name']).'</h6><small class="text-muted">'.date('d M Y, H:i', strtotime($msg['sent_date'])).'</small></div><hr><p class="mb-0">'.nl2br(htmlspecialchars($msg['message'])).'</p></div>';
                        }
    mysqli_query($con, "UPDATE messages SET is_read=1 WHERE recipient_email='$email'");
                    } else {
                        echo '<div class="alert alert-info text-center">Anda belum memiliki pesan apapun.</div>';
                    }
                    ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if (@$_GET['q'] == 'quiz' && @$_GET['step'] == 2): ?>
            <div class="card">
                <div class="card-body">
                    <?php
                    $eid = @$_GET['eid'];
                    $sn = @$_GET['n'];
                    $total = @$_GET['t'];
                    $q = mysqli_query($con, "SELECT * FROM questions WHERE eid='$eid' AND sn='$sn'");
                    if ($row = mysqli_fetch_array($q)) {
                        $qns = $row['qns'];
                        $qid = $row['qid'];
                        echo '<h4>Pertanyaan ' . $sn . ' dari ' . $total . '</h4>';
                        echo '<p class="lead">' . nl2br(htmlspecialchars($qns)) . '</p>';
                        $options_q = mysqli_query($con, "SELECT * FROM options WHERE qid='$qid'");
                        echo '<form action="update.php?q=quiz&step=2&eid=' . $eid . '&n=' . $sn . '&t=' . $total . '&qid=' . $qid . '" method="POST" class="form-horizontal">';
                        while ($option_row = mysqli_fetch_array($options_q)) {
                            echo '<div class="form-check my-3"><input class="form-check-input" type="radio" name="ans" id="opt' . $option_row['optionid'] . '" value="' . $option_row['optionid'] . '" required><label class="form-check-label" for="opt' . $option_row['optionid'] . '">' . htmlspecialchars($option_row['option']) . '</label></div>';
                        }
                        echo '<br/><button type="submit" class="btn btn-primary"><i class="fas fa-lock"></i> Submit Jawaban</button></form>';
                    }
                    ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if (@$_GET['q'] == 'result' && @$_GET['eid']): ?>
            <div class="card mb-4">
                <div class="card-header bg-primary text-white"><h3><i class="fas fa-poll"></i> Hasil Kuis Anda</h3></div>
                <div class="card-body">
                    <?php
                    $eid = @$_GET['eid'];
                    $q_history = mysqli_query($con, "SELECT * FROM history WHERE eid='$eid' AND email='$email'") or die('Error157');
                    if ($row = mysqli_fetch_array($q_history)) {
                        echo '<ul class="list-group list-group-flush fs-5"><li class="list-group-item d-flex justify-content-between"><span>Total Pertanyaan</span> <strong>' . $row['level'] . '</strong></li><li class="list-group-item d-flex justify-content-between"><span><i class="fas fa-check-circle text-success"></i> Jawaban Benar</span> <strong class="text-success">' . $row['sahi'] . '</strong></li><li class="list-group-item d-flex justify-content-between"><span><i class="fas fa-times-circle text-danger"></i> Jawaban Salah</span> <strong class="text-danger">' . $row['wrong'] . '</strong></li><li class="list-group-item d-flex justify-content-between"><span><i class="fas fa-star text-warning"></i> Skor Akhir</span> <strong>' . $row['score'] . '</strong></li></ul>';
                    }
                    ?>
                </div>
            </div>
            <h3><i class="fas fa-book-reader"></i> Pembahasan Soal</h3>
            <?php
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

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>