<?php
    // Koneksi ke database
    // Gantilah dengan detail koneksi sesuai dengan konfigurasi server Anda
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

    // Proses penyimpanan gambar
    $target_dir = "images/";
    $target_file = $target_dir . basename($_FILES["gambar"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["gambar"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }
    }

    // Cek apakah file sudah ada
    if (file_exists($target_file)) {
        echo "Maaf, file tersebut sudah ada.";
        $uploadOk = 0;
    }

    // Cek ukuran file
    if ($_FILES["gambar"]["size"] > 500000) {
        echo "Maaf, ukuran file terlalu besar.";
        $uploadOk = 0;
    }

    // Izinkan format file tertentu
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "Maaf, hanya format JPG, JPEG, PNG, & GIF yang diperbolehkan.";
        $uploadOk = 0;
    }

    // Jika tidak ada masalah, upload file
    if ($uploadOk == 0) {
        echo "Maaf, file Anda tidak diunggah.";
    } else {
        if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
            echo "File ". basename( $_FILES["gambar"]["name"]). " telah diunggah.";

            // Simpan data barang ke dalam database
            $nama = $_POST['nama_barang'];
            $harga = $_POST['harga'];
            $deskripsi = $_POST['deskripsi'];
            $gambar = $_FILES['gambar']['name']; // Nama file gambar

            $sql = "INSERT INTO barang (nama, harga, deskripsi, gambar) VALUES ('$nama', '$harga', '$deskripsi', '$gambar')";

            if ($conn->query($sql) === TRUE) {
                // Redirect ke halaman tambah_menu_makanan.php setelah berhasil menambahkan
                header("Location: tambah.php");
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Maaf, terjadi kesalahan saat mengunggah file.";
        }
    }

    $conn->close();
?>
