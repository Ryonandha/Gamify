<?php
// Selalu letakkan session_start() di paling atas
session_start();
include_once 'dbConnection.php';

// Redirect jika guru belum login
if (!isset($_SESSION['email'])) {
    header("location:index.php");
    exit();
} else {
    $email = $_SESSION['email'];
    $name = $_SESSION['name'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru - Ujian Ceria</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #2962ff;
            --secondary-color: #00b0ff;
            --background-color: #f4f7fc;
            --card-bg: #ffffff;
            --text-color: #333;
            --header-color: #fff;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--background-color);
        }
        .navbar {
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand, .nav-link {
            color: var(--header-color) !important;
            font-weight: 500;
        }
        .nav-link.active, .nav-link:hover, .dropdown-item:active {
            font-weight: 600;
        }
        .nav-link i {
            margin-right: 8px;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-top: 2rem;
        }
        .card-header {
            background-color: var(--card-bg);
            color: var(--primary-color);
            font-weight: 600;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #eee;
        }
        .card-header i {
            margin-right: 10px;
        }
        .card-body {
            padding: 1.5rem;
        }
        .form-label {
            font-weight: 500;
        }
        .video-responsive {
            overflow: hidden;
            padding-bottom: 56.25%;
            position: relative;
            height: 0;
            border-radius: 8px;
        }
        .video-responsive iframe {
            left: 0;
            top: 0;
            height: 100%;
            width: 100%;
            position: absolute;
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="dash.php?q=0"><b><i class="fas fa-chalkboard-teacher"></i> Dashboard Guru</b></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?php if(@$_GET['q']==0) echo 'active'; ?>" href="dash.php?q=0"><i class="fas fa-home"></i>Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if(@$_GET['q']==1) echo 'active'; ?>" href="dash.php?q=1"><i class="fas fa-clipboard-list"></i>Skor Siswa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if(@$_GET['q']==2) echo 'active'; ?>" href="dash.php?q=2"><i class="fas fa-trophy"></i>Peringkat</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php if(in_array(@$_GET['q'], [4, 5, 6])) echo 'active'; ?>" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-edit"></i>Manajemen Kuis
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="dash.php?q=4">Tambah Kuis</a></li>
                        <li><a class="dropdown-item" href="dash.php?q=6">Edit Kuis</a></li>
                        <li><a class="dropdown-item" href="dash.php?q=5">Hapus Kuis</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php if(in_array(@$_GET['q'], [7, 8])) echo 'active'; ?>" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-book-open"></i>Manajemen Materi
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="dash.php?q=7">Tambah Materi</a></li>
                        <li><a class="dropdown-item" href="dash.php?q=8">Lihat Materi</a></li>
                    </ul>
                </li>
            </ul>
            <span class="navbar-text">
                <i class="fas fa-user-circle"></i> Halo, <?php echo htmlspecialchars($name); ?> | <a href="logout.php?q=dash.php" class="text-white">Keluar <i class="fas fa-sign-out-alt"></i></a>
            </span>
        </div>
    </div>
</nav>

<div class="container my-4">
    <div class="row">
        <div class="col-md-12">

        <?php if(@$_GET['q']==0): ?>
            <?php
            $result = mysqli_query($con, "SELECT * FROM quiz WHERE email='$email' ORDER BY date DESC") or die('Error');
            echo '<div class="card">
                    <div class="card-header"><i class="fas fa-list-alt"></i>Daftar Kuis yang Anda Buat</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead><tr><th>No.</th><th>Topik</th><th>Total Soal</th><th>Poin Benar</th><th>Waktu</th><th>Aksi</th></tr></thead>
                                <tbody>';
            $c = 1;
            while ($row = mysqli_fetch_array($result)) {
                echo '<tr>
                        <td>'.$c++.'</td>
                        <td>'.htmlspecialchars($row['title']).'</td>
                        <td>'.$row['total'].'</td>
                        <td>'.$row['sahi'].'</td>
                        <td>'.$row['time'].'&nbsp;menit</td>
                        <td>
                            <a href="dash.php?q=6&eid='.$row['eid'].'" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> Edit Soal
                            </a>
                        </td>
                      </tr>';
            }
            echo '</tbody></table></div></div></div>';
            ?>
        <?php endif; ?>

        <?php if(@$_GET['q']==1): ?>
            <?php
            $q = mysqli_query($con, "SELECT distinct q.title,u.name,u.college,h.score,h.date from user u,history h,quiz q where q.email='$email' and q.eid=h.eid and h.email=u.email order by q.eid DESC") or die('Error197');
            echo '<div class="card">
                    <div class="card-header"><i class="fas fa-clipboard-list"></i>Detail Skor Siswa</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead><tr><th>No.</th><th>Kuis</th><th>Nama Siswa</th><th>Sekolah/NIS</th><th>Skor</th><th>Tanggal</th></tr></thead>
                                <tbody>';
            $c = 1;
            while ($row = mysqli_fetch_array($q)) {
                echo '<tr>
                        <td>'.$c++.'</td>
                        <td>'.htmlspecialchars($row['title']).'</td>
                        <td>'.htmlspecialchars($row['name']).'</td>
                        <td>'.htmlspecialchars($row['college']).'</td>
                        <td>'.$row['score'].'</td>
                        <td>'.$row['date'].'</td>
                      </tr>';
            }
            echo '</tbody></table></div></div></div>';
            ?>
        <?php endif; ?>
        
        <?php if(@$_GET['q']==2): ?>
            <?php
            $q = mysqli_query($con, "SELECT * FROM rank ORDER BY score DESC") or die('Error223');
            echo '<div class="card">
                    <div class="card-header"><i class="fas fa-trophy"></i>Peringkat Umum Siswa</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead><tr><th>Peringkat</th><th>Nama</th><th>Gender</th><th>Sekolah/NIS</th><th>Skor</th></tr></thead>
                                <tbody>';
            $c = 0;
            while ($row = mysqli_fetch_array($q)) {
                $e = $row['email'];
                $s = $row['score'];
                $q12 = mysqli_query($con, "SELECT * FROM user WHERE email='$e'") or die('Error231');
                if ($row2 = mysqli_fetch_array($q12)) {
                    $c++;
                    echo '<tr>
                            <td class="fw-bold">'.$c.'</td>
                            <td>'.htmlspecialchars($row2['name']).'</td>
                            <td>'.($row2['gender'] == 'M' ? 'Laki-laki' : 'Perempuan').'</td>
                            <td>'.htmlspecialchars($row2['college']).'</td>
                            <td>'.$s.'</td>
                          </tr>';
                }
            }
            echo '</tbody></table></div>
                  <div class="card-footer text-end">
                      <form action="send_message.php" method="POST">
                          <input type="hidden" name="action" value="send_message_auto">
                          <button type="submit" class="btn btn-primary" onclick="return confirm(\'Yakin ingin mengirim pesan ke 5 teratas dan 5 terbawah?\')">
                              <i class="fas fa-envelope"></i> Kirim Apresiasi Otomatis
                          </button>
                      </form>
                  </div></div>';
            ?>
        <?php endif; ?>

        <?php if(@$_GET['q']==4 && !(@$_GET['step'])): ?>
            <div class="card">
                <div class="card-header"><i class="fas fa-plus-circle"></i>Buat Kuis Baru</div>
                <div class="card-body">
                    <form name="form" action="update.php?q=addquiz" method="POST">
                        <div class="mb-3"><label for="name" class="form-label">Judul Kuis</label><input id="name" name="name" placeholder="Contoh: Matematika Dasar Bab 1" class="form-control" type="text" required></div>
                        <div class="row">
                            <div class="col-md-6 mb-3"><label for="total" class="form-label">Jumlah Pertanyaan</label><input id="total" name="total" placeholder="Contoh: 10" class="form-control" type="number" required></div>
                            <div class="col-md-6 mb-3"><label for="time" class="form-label">Waktu Pengerjaan (Menit)</label><input id="time" name="time" placeholder="Contoh: 20" class="form-control" min="1" type="number" required></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3"><label for="right" class="form-label">Poin per Jawaban Benar</label><input id="right" name="right" placeholder="Contoh: 10" class="form-control" min="0" type="number" required></div>
                            <div class="col-md-6 mb-3"><label for="wrong" class="form-label">Poin Minus per Jawaban Salah</label><input id="wrong" name="wrong" placeholder="Contoh: 5 (isi tanpa tanda minus)" class="form-control" min="0" type="number" required></div>
                        </div>
                        <div class="mb-3"><label for="desc" class="form-label">Deskripsi Singkat</label><textarea rows="4" name="desc" class="form-control" placeholder="Tulis deskripsi singkat tentang kuis ini..."></textarea></div>
                        <div class="d-grid"><button type="submit" class="btn btn-primary"><i class="fas fa-arrow-right"></i> Lanjut ke Tahap Soal</button></div>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <?php if(@$_GET['q']==4 && (@$_GET['step'])==2): ?>
            <div class="card">
                <div class="card-header"><i class="fas fa-tasks"></i>Masukkan Detail Pertanyaan</div>
                <div class="card-body">
                    <form name="form" action="update.php?q=addqns&n=<?=@$_GET['n']?>&eid=<?=@$_GET['eid']?>&ch=4" method="POST">
                        <?php for ($i = 1; $i <= @$_GET['n']; $i++): ?>
                          <fieldset class="border p-3 mb-4 rounded"><legend class="float-none w-auto px-3 h6">Pertanyaan ke-<?= $i ?></legend>
                              <div class="mb-3"><label for="qns<?= $i ?>" class="form-label">Teks Pertanyaan</label><textarea rows="3" name="qns<?= $i ?>" class="form-control" placeholder="Tuliskan pertanyaan nomor <?= $i ?> di sini..." required></textarea></div>
                              <div class="mb-3"><label for="explanation<?= $i ?>" class="form-label">Penjelasan Jawaban</label><textarea rows="3" name="explanation<?= $i ?>" class="form-control" placeholder="Jelaskan mengapa jawaban ini benar..."></textarea></div>
                              <div class="row">
                                  <div class="col-md-6 mb-3"><input name="<?= $i ?>1" class="form-control" placeholder="Pilihan A" type="text" required></div>
                                  <div class="col-md-6 mb-3"><input name="<?= $i ?>2" class="form-control" placeholder="Pilihan B" type="text" required></div>
                                  <div class="col-md-6 mb-3"><input name="<?= $i ?>3" class="form-control" placeholder="Pilihan C" type="text" required></div>
                                  <div class="col-md-6 mb-3"><input name="<?= $i ?>4" class="form-control" placeholder="Pilihan D" type="text" required></div>
                              </div>
                              <div class="mb-3"><label for="ans<?= $i ?>" class="form-label">Kunci Jawaban</label><select id="ans<?= $i ?>" name="ans<?= $i ?>" class="form-select" required><option selected disabled value="">Pilih jawaban yang benar</option><option value="a">Pilihan A</option><option value="b">Pilihan B</option><option value="c">Pilihan C</option><option value="d">Pilihan D</option></select></div>
                          </fieldset>
                        <?php endfor; ?>
                        <div class="d-grid"><button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan Semua Pertanyaan</button></div>
                    </form>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if(@$_GET['q']==5): ?>
            <?php
            $result = mysqli_query($con, "SELECT * FROM quiz WHERE email='$email' ORDER BY date DESC") or die('Error');
            echo '<div class="card"><div class="card-header"><i class="fas fa-trash-alt"></i>Hapus Kuis</div><div class="card-body"><div class="table-responsive"><table class="table table-hover"><thead><tr><th>No.</th><th>Topik</th><th>Total Soal</th><th>Total Poin</th><th>Waktu</th><th>Aksi</th></tr></thead><tbody>';
            $c = 1;
            while ($row = mysqli_fetch_array($result)) {
                echo '<tr><td>'.$c++.'</td><td>'.htmlspecialchars($row['title']).'</td><td>'.$row['total'].'</td><td>'.($row['sahi'] * $row['total']).'</td><td>'.$row['time'].'&nbsp;menit</td><td><a href="update.php?q=rmquiz&eid='.$row['eid'].'" class="btn btn-danger btn-sm" onclick="return confirm(\'Anda yakin ingin menghapus kuis ini?\')"><i class="fas fa-trash"></i> Hapus</a></td></tr>';
            }
            echo '</tbody></table></div></div></div>';
            ?>
        <?php endif; ?>

        <?php if(@$_GET['q']==6): ?>
            <div class="card">
                <div class="card-header"><i class="fas fa-edit"></i>Edit Kuis dan Soal</div>
                <div class="card-body">
                    <?php if (!isset($_GET['eid'])): ?>
                        <h5 class="card-title">Pilih Kuis yang Ingin Diedit</h5>
                        <p class="card-text">Silakan pilih salah satu kuis dari daftar di bawah ini untuk mulai mengedit pertanyaan.</p>
                        <div class="table-responsive"><table class="table table-hover"><thead><tr><th>Topik</th><th>Jumlah Soal</th><th>Aksi</th></tr></thead><tbody>
                        <?php
                            $result = mysqli_query($con, "SELECT * FROM quiz WHERE email='$email' ORDER BY date DESC") or die('Error');
                            if(mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_array($result)) {
                                    echo '<tr><td>'.htmlspecialchars($row['title']).'</td><td>'.$row['total'].'</td><td><a href="dash.php?q=6&eid='.$row['eid'].'" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Edit</a></td></tr>';
                                }
                            } else {
                                echo '<tr><td colspan="3" class="text-center">Anda belum membuat kuis apapun.</td></tr>';
                            }
                        ?>
                        </tbody></table></div>
                    <?php else: ?>
                        <?php
                            $eid = @$_GET['eid'];
                            $quiz_q = mysqli_query($con, "SELECT * FROM quiz WHERE eid='$eid' AND email='$email'");
                            if(mysqli_num_rows($quiz_q) > 0):
                                $quiz_data = mysqli_fetch_array($quiz_q);
                        ?>
                        <h5 class="card-title">Mengedit Soal untuk Kuis: "<?= htmlspecialchars($quiz_data['title']) ?>"</h5><hr>
                        <form name="form" action="update.php?q=editqns&eid=<?= $eid ?>" method="POST">
                            <?php
                                $qns_q = mysqli_query($con, "SELECT * FROM questions WHERE eid='$eid' ORDER BY sn ASC");
                                while ($qns_data = mysqli_fetch_array($qns_q)):
                                    $qid = $qns_data['qid'];
                            ?>
                            <fieldset class="border p-3 mb-4 rounded bg-light"><legend class="float-none w-auto px-3 h6">Pertanyaan ke-<?= $qns_data['sn'] ?></legend>
                                <input type="hidden" name="qid<?= $qns_data['sn'] ?>" value="<?= $qid ?>">
                                <div class="mb-3"><label class="form-label">Teks Pertanyaan</label><textarea rows="3" name="qns<?= $qns_data['sn'] ?>" class="form-control" required><?= htmlspecialchars($qns_data['qns']) ?></textarea></div>
                                <div class="mb-3"><label class="form-label">Penjelasan Jawaban</label><textarea rows="3" name="explanation<?= $qns_data['sn'] ?>" class="form-control"><?= htmlspecialchars($qns_data['explanation']) ?></textarea></div>
                                <div class="row">
                                    <?php
                                        $opt_q = mysqli_query($con, "SELECT * FROM options WHERE qid='$qid'");
                                        $ans_q = mysqli_query($con, "SELECT * FROM answer WHERE qid='$qid'");
                                        $ans_id = mysqli_fetch_array($ans_q)['ansid'];
                                        $options = [];
                                        while($opt = mysqli_fetch_array($opt_q)) { $options[$opt['optionid']] = $opt['option']; }
                                        $option_keys = ['A', 'B', 'C', 'D'];
                                        $i = 0;
                                        foreach($options as $optid => $opt_text):
                                    ?>
                                    <div class="col-md-6 mb-3"><label class="form-label">Pilihan <?= $option_keys[$i] ?></label><input name="opt_<?= $qns_data['sn'] ?>_<?= $optid ?>" class="form-control" value="<?= htmlspecialchars($opt_text) ?>" type="text" required></div>
                                    <?php $i++; endforeach; ?>
                                </div>
                                <div class="mb-3"><label class="form-label">Kunci Jawaban</label><select name="ans<?= $qns_data['sn'] ?>" class="form-select" required>
                                    <?php
                                    $i = 0;
                                    foreach($options as $optid => $opt_text):
                                    ?>
                                    <option value="<?= $optid ?>" <?= ($optid == $ans_id) ? 'selected' : '' ?>>Pilihan <?= $option_keys[$i] ?></option>
                                    <?php $i++; endforeach; ?>
                                </select></div>
                            </fieldset>
                            <?php endwhile; ?>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="dash.php?q=6" class="btn btn-secondary me-md-2">Batal</a>
                                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan Semua Perubahan</button>
                            </div>
                        </form>
                        <?php else: ?>
                            <div class="alert alert-danger">Kuis tidak ditemukan atau Anda tidak memiliki akses untuk mengeditnya.</div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- ======================================================= -->
        <!-- == BLOK KODE UNTUK MANAJEMEN MATERI (q=7 dan q=8) == -->
        <!-- ======================================================= -->

        <?php if(@$_GET['q']==7): ?>
        <div class="card">
            <div class="card-header"><i class="fas fa-plus-circle"></i>Tambah Materi Video Baru</div>
            <div class="card-body">
                <form name="form" action="update.php?q=add_material" method="POST">
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Materi</label>
                        <input id="title" name="title" placeholder="Contoh: Belajar Penjumlahan Dasar" class="form-control" type="text" required>
                    </div>
                    <div class="mb-3">
                        <label for="youtube_link" class="form-label">Link Video YouTube</label>
                        <input id="youtube_link" name="youtube_link" placeholder="Contoh: https://www.youtube.com/watch?v=xxxxxxxxxxx" class="form-control" type="url" required>
                        <div class="form-text">Salin dan tempel link lengkap video dari YouTube.</div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi Singkat</label>
                        <textarea rows="4" name="description" class="form-control" placeholder="Jelaskan sedikit tentang isi video ini..."></textarea>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Materi</button>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>

        <?php if(@$_GET['q']==8): ?>
        <div class="card">
            <div class="card-header"><i class="fas fa-video"></i>Daftar Materi yang Telah Diunggah</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead><tr><th>No.</th><th>Judul Materi</th><th>Video</th><th>Aksi</th></tr></thead>
                        <tbody>
                        <?php
                            $result = mysqli_query($con, "SELECT * FROM materials WHERE uploaded_by='$email' ORDER BY upload_date DESC") or die('Error');
                            if(mysqli_num_rows($result) > 0) {
                                $c = 1;
                                while ($row = mysqli_fetch_array($result)) {
                                    echo '<tr>
                                            <td>'.$c++.'</td>
                                            <td>'.htmlspecialchars($row['title']).'</td>
                                            <td><div class="video-responsive"><iframe src="https://www.youtube.com/embed/'.$row['video_id'].'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div></td>
                                            <td><a href="update.php?q=delete_material&id='.$row['id'].'" class="btn btn-danger btn-sm" onclick="return confirm(\'Anda yakin ingin menghapus materi ini?\')"><i class="fas fa-trash"></i> Hapus</a></td>
                                          </tr>';
                                }
                            } else {
                                echo '<tr><td colspan="4" class="text-center">Anda belum mengunggah materi apapun.</td></tr>';
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>