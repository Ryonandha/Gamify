
# 🎓 Gamify - Sistem Ujian Online (Ujian Ceria)

![License](https://img.shields.io/badge/License-CC%20BY--NC--ND%204.0-lightgrey)
![PHP](https://img.shields.io/badge/PHP-%3E%3D%207.4-blue)
![MySQL](https://img.shields.io/badge/Database-MySQL-orange)
![Bootstrap](https://img.shields.io/badge/Frontend-Bootstrap%205-purple)

**Gamify (Ujian Ceria)** adalah aplikasi Sistem Ujian Online berbasis web yang dirancang dengan antarmuka yang ceria, ramah anak, dan interaktif. Aplikasi ini bertujuan untuk memudahkan institusi pendidikan dalam melaksanakan evaluasi pembelajaran melalui kuis online, manajemen materi berbasis video, serta pemantauan nilai siswa secara *real-time*.

Proyek ini dikembangkan menggunakan **PHP Native** dan **MySQL**, serta memanfaatkan **Bootstrap 5** untuk memastikan tampilan yang responsif dan menarik di berbagai perangkat.

---

## ✨ Fitur Utama

Sistem ini membagi akses ke dalam tiga peran (role) utama dengan fungsionalitas yang spesifik:

### 👨‍🏫 Panel Guru (Teacher Panel)
* **Dashboard Informatif:** Ringkasan aktivitas dan navigasi cepat.
* **Manajemen Kuis:** Membuat, mengedit, dan menghapus kuis atau ujian.
* **Bank Soal:** Menambahkan pertanyaan pilihan ganda lengkap dengan kunci jawaban dan penjelasan.
* **Manajemen Materi:** Mengunggah dan mengelola materi pembelajaran berupa video (integrasi Embed YouTube).
* **Analitik Siswa:** Memantau skor siswa, melihat riwayat pengerjaan, dan daftar peringkat (Leaderboard).
* **Peringkat:** Melihat peringkat global siswa berdasarkan akumulasi skor.

### 👨‍🎓 Panel Siswa (Student Panel)
* **Antarmuka Ramah Anak:** Desain visual yang menarik ("Ujian Ceria") untuk meningkatkan minat belajar.
* **Ujian Interaktif:** Mengerjakan kuis yang tersedia dengan batasan waktu otomatis.
* **Riwayat Ujian:** Melihat kembali ujian yang telah dikerjakan beserta skor yang diperoleh.
* **Akses Materi:** Menonton video pembelajaran yang telah disediakan oleh guru sebelum atau sesudah ujian.
* **Peringkat:** Melihat posisi peringkat diri sendiri dibandingkan teman-teman lainnya.

### 👮 Panel Admin / Kepala Sekolah
* **Manajemen Pengguna:** Menambah akun guru baru atau menonaktifkan akun yang sudah ada.
* **Monitoring Feedback:** Membaca pesan, saran, atau masukan yang dikirimkan melalui halaman depan.
* **Pengawasan:** Memiliki akses menyeluruh untuk memantau aktivitas sistem.

---

## 🛠️ Teknologi yang Digunakan

* **Bahasa Pemrograman:** PHP (Native)
* **Database:** MySQL / MariaDB
* **Frontend Framework:** Bootstrap 5.3.2
* **Ikon & Font:** Font Awesome 6, Google Fonts ('Comic Neue' & 'Poppins')
* **Web Server:** Apache (via XAMPP/WAMP)

---

## 🚀 Panduan Instalasi (Localhost)

Ikuti langkah-langkah berikut untuk menjalankan proyek ini di komputer Anda:

### 1. Persiapan Lingkungan
Pastikan Anda telah menginstal aplikasi server lokal seperti **XAMPP**, **WAMP**, atau **MAMP**.

### 2. Instalasi File
1.  Unduh (*Download*) atau *Clone* repositori ini.
2.  Ekstrak folder proyek (jika berbentuk ZIP).
3.  Pindahkan folder proyek ke dalam direktori *root* web server Anda:
    * **XAMPP:** `C:/xampp/htdocs/Gamify`
    * **WAMP:** `C:/wamp64/www/Gamify`

### 3. Konfigurasi Database
1.  Buka **phpMyAdmin** di browser (biasanya `http://localhost/phpmyadmin`).
2.  Buat database baru dengan nama **`mathmath`**.
3.  Pilih database `mathmath` yang baru dibuat, lalu klik tab **Import**.
4.  Pilih file **`mathmath.sql`** yang terdapat di dalam folder proyek ini.
5.  Klik tombol **Go** atau **Kirim** untuk mengimpor struktur tabel dan data awal.

### 4. Konfigurasi Koneksi (Opsional)
Buka file `dbConnection.php` menggunakan teks editor (seperti VS Code). Pastikan konfigurasi sesuai dengan server lokal Anda (default XAMPP biasanya tidak perlu diubah):

```php
<?php
// Konfigurasi Database
$con = new mysqli('localhost', 'root', '', 'mathmath');

if ($con->connect_error) {
    die("Koneksi gagal: " . $con->connect_error);
}
?>

```

### 5. Menjalankan Aplikasi

Buka browser Anda dan akses alamat berikut:
`http://localhost/Gamify/`

---

## 🔑 Akun Default (Demo)

Gunakan akun berikut untuk mencoba fitur-fitur di dalam aplikasi:

| Role | Email | Password |
| --- | --- | --- |
| **Kepala Sekolah (Admin)** | `head@gmail.com` | `head` |
| **Guru** | `teacher1@gmail.com` | *(Lihat database atau buat baru via Admin)* |

*> **Catatan:** Untuk keamanan, disarankan segera mengganti password setelah login pertama kali atau membuat akun guru baru melalui panel Admin.*

---

## 👥 Tim Pengembang

Proyek ini dibuat dengan dedikasi tinggi oleh:

* **Ryonandha Mitchell** (NIM: 202201009)

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah **CC BY-NC-ND 4.0** (Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International).

* ✅ **Diizinkan:** Berbagi dan menyalin materi ini dalam format apapun.
* ❌ **Dilarang:** Menggunakan materi ini untuk kepentingan komersial.
* ❌ **Dilarang:** Mengubah, menggubah, atau membuat turunan dari materi ini dan mendistribusikannya.

---
