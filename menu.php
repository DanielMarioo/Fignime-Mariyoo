<?php
session_start();
// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FIGNIME</title>
    <!-- fontawesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- bootstrap css -->
    <link rel="stylesheet" href="bootstrap-5.0.2-dist/css/bootstrap.min.css">
    <!-- custom css -->
    <link rel="stylesheet" href="css/main.css">
    <!-- bootstrap icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.css">
</head>
<body>
<style>
    table {
        width: 100%;
    }
    table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
    }
    th, td {
        padding: 10px;
        text-align: left;
    }
    .content-wrapper {
        display: flex;
        justify-content: space-between;
    }
    .collection-section, .cart-section {
        margin: 0 2rem;
    }
</style>

    <!-- navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white py-4 fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex justify-content-between align-items-center order-lg-0" href="home-produk-iklan-kontak.html">
                <span class="text-uppercase fw-lighter ms-2">FIGNIME</span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse order-lg-1" id="navMenu">
                <ul class="navbar-nav mx-auto text-center">
                    <li class="nav-item px-2 py-2">
                        <a class="nav-link text-uppercase text-dark" href="#header">home</a>
                    </li>
                    <li class="nav-item px-2 py-2">
                        <a class="nav-link text-uppercase text-dark" href="#koleksi">transaksi</a>
                    </li>
                    <li class="nav-item px-2 py-2 border-0">
                        <a class="nav-link text-uppercase text-dark" href="tambah.php">Tambah Barang</a>
                    </li>
                </ul>
            </div>

            <div class="order-lg-2 d-flex">
                <!-- Search form -->
                <form class="d-flex me-3">
                    <input class="form-control me-2" type="search" placeholder="Cari produk..." aria-label="Search" id="searchInput">
                    <button class="btn btn-outline-success" type="button" onclick="searchProduct()">Cari</button>
                </form>

                <!-- User dropdown -->
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php echo $_SESSION['username']; ?> <!-- Menampilkan nama pengguna -->
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <!-- end of navbar -->

    <!-- header -->
    <header id="header" class="vh-100 carousel slide" data-bs-ride="carousel" style="padding-top: 104px;">
        <div class="container h-100 d-flex align-items-center carousel-inner">
            <div class="text-center carousel-item active">
                <h2 class="text-capitalize text-white">Selamat Datang</h2>
                <h1 class="text-uppercase py-2 fw-bold text-white">Admin</h1>
                <a href="#hargaterbaik" class="btn mt-3 text-uppercase">MULAI TRANSAKSI</a>
            </div>
            <div class="text-center carousel-item">
                <h2 class="text-capitalize text-white">ATUR BARANG</h2>
                <a href="tambah.php" class="btn mt-3 text-uppercase">ATUR SEKARANG</a>
            </div>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#header" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#header" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </header>
    <!-- end of header -->

  <!-- collection and cart -->
  <section id="koleksi" class="py-5">
    <div class="container">
        <div class="content-wrapper"> <!-- Tambahkan wrapper untuk tata letak fleksibel -->
            <!-- Collection Section -->
            <div class="col-md-9 collection-section"> <!-- Tambahkan kelas collection-section di sini -->
                <div class="title text-center">
                    <h2 class="position-relative d-inline-block">KOLEKSI</h2>
                </div>
                <div class="collection-list mt-4 row gx-3 gy-3" id="productList"> 
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
                            while ($row = $result->fetch_assoc()) {
                                echo '<div class="col-md-4 product-item">'; // Tambahkan kelas "product-item" di sini
                                echo '<div class="menu-item text-center">';
                                echo '<div class="collection-img position-relative">';
                                echo '<img src="images/' . $row['gambar'] . '" alt="' . $row['nama'] . '" width="300" height="250" onclick="showProductModal(\'' . $row['gambar'] . '\', \'' . $row['nama'] . '\', \'' . $row['harga'] . '\', \'' . $row['deskripsi'] . '\')">'; // Tambahkan ukuran gambar di sini dan fungsi onclick
                                echo '</div>';
                                echo '<h5 class="text-capitalize my-1 product-name">' . $row['nama'] . '</h5>'; // Tambahkan kelas "product-name" di sini
                                echo '<p><span class="fw-bold">Harga: Rp ' . $row['harga'] . '</span><br></p>';
                                echo '<button class="btn btn-primary mt-3 beli-btn" onclick="pesanFigur(\'' . $row['nama'] . '\',' . $row['harga'] . ')">Pesan</button>';
                                echo '</div>'; // Menutup div.menu-item
                                echo '</div>'; // Menutup div.col-md-4
                            }
                        } else {
                            echo "Tidak ada barang yang tersedia.";
                        }
                        $conn->close();
                    ?>
                </div>
            </div>

            <!-- Cart Section -->
             <div class="col-md-4">
                <div class="title text-center">
                    <h2 class="position-relative d-inline-block">KERANJANG</h2>
                </div><br>
                <table id="cart" class="table">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Sub Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <div class="text-center">
                    <p class="fw-bold">Total: <span id="total">Rp 0</span></p>
                    <button class="btn btn-success" onclick="checkout()">Checkout</button>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end of collection and cart -->

    <!-- product modal -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Detail Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img id="modalProductImage" src="" alt="Gambar Produk" class="img-fluid mb-3">
                    <h5 id="modalProductName" class="text-capitalize"></h5>
                    <p id="modalProductPrice" class="fw-bold"></p>
                    <p id="modalProductDescription"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="addToCartButton">Pesan</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end of product modal -->

 
    <!-- bootstrap js -->
    <script src="bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>
    <!-- custom js -->
    <script>
        var cart = [];

        function pesanFigur(nama, harga) {
            var found = false;
            for (var i = 0; i < cart.length; i++) {
                if (cart[i].nama === nama) {
                    cart[i].jumlah++;
                    cart[i].subTotal = cart[i].jumlah * cart[i].harga;
                    found = true;
                    break;
                }
            }
            if (!found) {
                cart.push({ nama: nama, harga: harga, jumlah: 1, subTotal: harga });
            }
            updateCartTable();
        }

        function hapusItem(index) {
            cart.splice(index, 1);
            updateCartTable();
        }

        function updateCartTable() {
            var cartTableBody = document.getElementById("cart").getElementsByTagName("tbody")[0];
            cartTableBody.innerHTML = "";
            var total = 0;
            for (var i = 0; i < cart.length; i++) {
                var row = cartTableBody.insertRow();
                row.insertCell(0).innerText = cart[i].nama;
                row.insertCell(1).innerText = cart[i].harga;

                // Create an input for quantity
                var quantityInput = document.createElement("input");
                quantityInput.type = "number";
                quantityInput.value = cart[i].jumlah;
                quantityInput.min = "1";
                quantityInput.className = "form-control form-control-sm";
                quantityInput.onchange = (function(index) {
                    return function(event) {
                        updateQuantity(index, event.target.value);
                    };
                })(i);
                row.insertCell(2).appendChild(quantityInput);

                row.insertCell(3).innerText = cart[i].subTotal;
                
                var aksiCell = row.insertCell(4);
                var hapusButton = document.createElement("button");
                hapusButton.innerText = "Hapus";
                hapusButton.className = "btn btn-danger btn-sm";
                hapusButton.onclick = (function(index) {
                    return function() {
                        hapusItem(index);
                    };
                })(i);
                aksiCell.appendChild(hapusButton);
                
                total += cart[i].subTotal;
            }
            document.getElementById("total").innerText = "Rp " + total;
        }

        function updateQuantity(index, newQuantity) {
            var quantity = parseInt(newQuantity);
            if (isNaN(quantity) || quantity <= 0) {
                quantity = 1;
            }
            cart[index].jumlah = quantity;
            cart[index].subTotal = cart[index].jumlah * cart[index].harga;
            updateCartTable();
        }

        function checkout() {
            if (cart.length === 0) {
                alert("Keranjang belanja kosong. Tidak ada item untuk di-checkout.");
                return;
            }

            // Simpan data keranjang ke sessionStorage
            sessionStorage.setItem("cart", JSON.stringify(cart));

            // Arahkan ke halaman transaksi.php
            window.location.href = "transaksi.php";
        }

        function searchProduct() {
            var input = document.getElementById('searchInput').value.toLowerCase();
            var productList = document.getElementById('productList');
            var products = productList.getElementsByClassName('product-item');

            for (var i = 0; i < products.length; i++) {
                var productName = products[i].getElementsByClassName('product-name')[0].innerText.toLowerCase();
                if (productName.includes(input)) {
                    products[i].style.display = '';
                } else {
                    products[i].style.display = 'none';
                }
            }
        }

        function showProductModal(gambar, nama, harga, deskripsi) {
            document.getElementById('modalProductImage').src = 'images/' + gambar;
            document.getElementById('modalProductName').innerText = nama;
            document.getElementById('modalProductPrice').innerText = 'Harga: Rp ' + harga;
            document.getElementById('modalProductDescription').innerText = deskripsi;

            var addToCartButton = document.getElementById('addToCartButton');
            addToCartButton.onclick = function() {
                pesanFigur(nama, harga);
                var modal = bootstrap.Modal.getInstance(document.getElementById('productModal'));
                modal.hide();
            };

            var productModal = new bootstrap.Modal(document.getElementById('productModal'), {});
            productModal.show();
        }
    </script>
</body>
</html>
