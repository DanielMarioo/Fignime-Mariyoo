<?php
session_start();
include 'koneksi.php'; // Pastikan file koneksi database tersedia

// Ambil data JSON dari permintaan
$data = json_decode(file_get_contents("php://input"), true);

$transactionNumber = $data['transactionNumber'];
$paymentMethod = $data['paymentMethod'];
$items = $data['items'];

// Buat koneksi ke database
$conn = new mysqli("localhost", "root", "", "db_figur");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Iterasi item dalam transaksi dan masukkan ke tabel `histori`
foreach ($items as $item) {
    $namaBarang = $item['nama'];
    $subTotal = $item['subTotal'];

    $stmt = $conn->prepare("INSERT INTO histori (nomor_transaksi, nama_barang, sub_total, pembayaran) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $transactionNumber, $namaBarang, $subTotal, $paymentMethod);
    $stmt->execute();
}

$stmt->close();
$conn->close();

echo "Data transaksi berhasil disimpan.";
?>
