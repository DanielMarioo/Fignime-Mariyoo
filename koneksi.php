<?php
$host = "localhost"; // Nama host, biasanya localhost
$user = "root";      // Nama pengguna database
$pass = "";          // Kata sandi database
$dbname = "db_figur"; // Nama database

// Membuat koneksi
$conn = new mysqli($host, $user, $pass, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Jika diperlukan, Anda bisa menambahkan pengaturan charset
$conn->set_charset("utf8");
?>
