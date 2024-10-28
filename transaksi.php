<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Generate or retrieve a unique transaction number
$transactionNumber = uniqid('TRX');
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="bootstrap-5.0.2-dist/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/main.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kaisei+Tokumin:wght@400;500;700&family=Poppins:wght@300;400;500&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }

        .receipt {
            max-width: 800px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #ddd;
        }

        h2, h4 {
            font-family: 'Kaisei Tokumin', serif;
            color: #333333;
        }

        .text-center {
            text-align: center;
        }

        .table {
            margin-top: 20px;
        }

        .form-label {
            margin-top: 15px;
        }

        .btn-primary, .btn-secondary {
            width: 100%;
            margin-top: 20px;
        }

        .transaction-number {
            font-weight: 500;
            margin-bottom: 15px;
        }

        .cashier-name {
            font-weight: 500;
            margin-top: 15px;
            text-align: right;
        }
    </style>
</head>
<body>
    <!-- Transaction Receipt -->
    <div class="receipt">
        <div class="text-center">
            <h2>FIGNIME</h2>
            <h4>Rincian Transaksi</h4>
        </div>
        <div class="transaction-number">
            Nomor Transaksi: <?php echo $transactionNumber; ?>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Sub Total</th>
                </tr>
            </thead>
            <tbody id="transaksiTable">
                <!-- Data transaksi akan diisi oleh JavaScript -->
            </tbody>
        </table>
        <div class="text-center">
            <p class="fw-bold">Total: <span id="totalTransaksi">Rp 0</span></p>
            <div>
                <label for="paymentMethod" class="form-label">Metode Pembayaran:</label>
                <select class="form-select" id="paymentMethod" onchange="togglePaymentFields()">
                    <option value="QRIS">QRIS</option>
                    <option value="Shopeepay">Shopeepay</option>
                    <option value="Cash">Cash</option>
                </select>
            </div>
            <div id="cashPaymentFields" style="display: none;">
                <label for="amountPaid" class="form-label">Uang Dibayarkan:</label>
                <input type="number" class="form-control" id="amountPaid" placeholder="Masukkan jumlah uang" oninput="calculateChange()">
                <p class="mt-3 fw-bold">Kembalian: <span id="changeAmount">Rp 0</span></p>
            </div>
            <div class="cashier-name">Nama Kasir: <?php echo $username; ?></div>
            <button class="btn btn-primary" id="selesaiTransaksiBtn" onclick="selesaiTransaksi()">Selesai Transaksi</button>
            <button class="btn btn-secondary" id="cetakTransaksiBtn" onclick="cetakTransaksi()">Cetak Transaksi</button>
        </div>
    </div>
    <!-- End of Transaction Receipt -->

    <script>
        // Function to navigate back to the previous page
        function goBack() {
            window.history.back();
        }

        // Ambil data keranjang dari sessionStorage
        var cart = JSON.parse(sessionStorage.getItem("cart")) || [];

        function updateTransaksiTable() {
            var transaksiTableBody = document.getElementById("transaksiTable");
            transaksiTableBody.innerHTML = "";
            var total = 0;
            for (var i = 0; i < cart.length; i++) {
                var row = transaksiTableBody.insertRow();
                row.insertCell(0).innerText = cart[i].nama;
                row.insertCell(1).innerText = cart[i].harga;
                row.insertCell(2).innerText = cart[i].jumlah;
                row.insertCell(3).innerText = cart[i].subTotal;
                total += cart[i].subTotal;
            }
            document.getElementById("totalTransaksi").innerText = "Rp " + total;
        }

        function calculateChange() {
            var total = parseFloat(document.getElementById("totalTransaksi").innerText.replace('Rp ', ''));
            var amountPaid = parseFloat(document.getElementById("amountPaid").value) || 0;
            var change = amountPaid - total;
            document.getElementById("changeAmount").innerText = "Rp " + (change > 0 ? change : 0);
        }

        function selesaiTransaksi() {
        // Ambil data yang diperlukan
        var transactionNumber = "<?php echo $transactionNumber; ?>";
        var paymentMethod = document.getElementById("paymentMethod").value;
        
        // Ambil data dari keranjang
        var cart = JSON.parse(sessionStorage.getItem("cart")) || [];

        // Kirim data ke proses_masuk_database.php
        fetch("proses_selesai.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                transactionNumber: transactionNumber,
                items: cart,
                paymentMethod: paymentMethod
            })
        }).then(response => response.text())
          .then(data => {
              alert("Transaksi selesai dan disimpan ke database!");
              sessionStorage.removeItem("cart");
              window.location.href = "menu.php";
          }).catch(error => {
              console.error("Error:", error);
              alert("Terjadi kesalahan saat menyimpan transaksi.");
          });
    }

        function cetakTransaksi() {
            window.print();
            // Hide buttons after printing
            document.getElementById('selesaiTransaksiBtn').style.display = 'none';
            document.getElementById('cetakTransaksiBtn').style.display = 'none';
        }

        function togglePaymentFields() {
            var paymentMethod = document.getElementById("paymentMethod").value;
            var cashPaymentFields = document.getElementById("cashPaymentFields");
            if (paymentMethod === "Cash") {
                cashPaymentFields.style.display = "block";
            } else {
                cashPaymentFields.style.display = "none";
            }
        }

        // Panggil fungsi untuk memperbarui tabel transaksi saat halaman dimuat
        window.onload = updateTransaksiTable;
    </script>

    <!-- Bootstrap JS -->
    <script src="bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
