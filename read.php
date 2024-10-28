<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Barang</title>
    <!-- Import Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        img {
            max-width: 50px;
            max-height: 50px;
        }
        .action-links a {
            margin-right: 5px;
            color: whitesmoke;
            text-decoration: none;
        }
        .action-links a:hover {
            background-color: whitesmoke;
            color: black;
            text-decoration:none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>List Barang</h1>
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Deskripsi</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
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

                    // Mengambil data barang dari tabel barang
                    $sql = "SELECT * FROM barang";
                    $result = $conn->query($sql);

                    // Memeriksa apakah ada data yang diambil dari database
                    if ($result->num_rows > 0) {
                        // Menampilkan data barang dalam tabel
                        while($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $row['nama'] . '</td>';
                            echo '<td>' . $row['harga'] . '</td>';
                            echo '<td>' . $row['deskripsi'] . '</td>';
                            echo '<td><img src="images/' . $row['gambar'] . '" alt="' . $row['nama'] . '" class="img-thumbnail"></td>';
                            echo '<td class="action-links">';
                            echo '<a href="edit.php?id=' . $row['id'] . '" class="btn btn-primary btn-sm font-weight-bold">Edit</a>';
                            echo '<a href="hapus.php?id=' . $row['id'] . '" class="btn btn-danger btn-sm font-weight-bold">Hapus</a>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="5">Tidak ada barang yang tersedia.</td></tr>';
                    }
                    $conn->close();
                ?>
            </tbody>
        </table>
        <div class="footer">
        <a href="menu.php"> Menuju Halaman Kasir</a>    
    </div>
    </div>
                    
    <!-- Import Bootstrap JS (optional) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
