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

    // Mengambil data makanan dari tabel barang
    $sql = "SELECT * FROM barang WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $item = $result->fetch_assoc();
    } else {
        // Redirect jika makanan tidak ditemukan
        header("Location: index.php");
        exit();
    }
} else {
    // Redirect jika tidak ada parameter id
    header("Location: tambah.php");
    exit();
}

// Memproses form jika ada data yang dikirimkan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data yang dikirimkan melalui form
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $deskripsi = $_POST['deskripsi'];

    // Menyimpan perubahan ke database
    $sql = "UPDATE barang SET nama='$nama', harga='$harga', deskripsi='$deskripsi' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        // Redirect ke halaman detail dengan id yang sama setelah berhasil mengedit
        header("Location: barang.php?id=$id");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Menutup koneksi
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Makanan</title>
</head>
<body>
    <h1>Edit Makanan</h1>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $id; ?>">
        <label for="nama">Nama Makanan:</label><br>
        <input type="text" id="nama" name="nama" value="<?php echo $item['nama']; ?>"><br>
        <label for="harga">Harga:</label><br>
        <input type="number" id="harga" name="harga" value="<?php echo $item['harga']; ?>"><br>
        <label for="deskripsi">Deskripsi:</label><br>
        <textarea id="deskripsi" name="deskripsi"><?php echo $item['deskripsi']; ?></textarea><br>
        <input type="submit" value="Simpan">
    </form>
</body>
</html>
