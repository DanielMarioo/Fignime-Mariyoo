<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_figur";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Memeriksa apakah parameter id telah diberikan
if(isset($_GET['id'])) {
    $id = $_GET['id'];

    // Menghapus data makanan dari tabel menu_makanan
    $sql = "DELETE FROM barang WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        // Redirect ke halaman tambah setelah berhasil menghapus
        header("Location: tambah.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    // Redirect jika tidak ada parameter id
    header("Location: tambah.php");
    exit();
}

// Menutup koneksi
$conn->close();
?>
