<?php
$notification = '';
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Database connection
    $conn = new mysqli("localhost", "root", "", "db_figur");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if username already exists
    $sql = "SELECT * FROM user WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $notification = "failedRegister";
    } else {
        // Insert new user
        $sql = "INSERT INTO user (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $password);

        if ($stmt->execute()) {
            $notification = "successRegister";
            header("Location: login.php");
            exit;
        } else {
            $notification = "errorRegister";
        }
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - FIGNIME</title>
    <link rel="stylesheet" href="bootstrap-5.0.2-dist/css/bootstrap.min.css">
    <style>
        body, html {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
        }
        .register-container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            max-width: 400px;
            width: 100%;
        }
        .register-container h2 {
            margin-bottom: 20px;
            font-family: 'Poppins', sans-serif;
        }
        .register-container .form-control {
            margin-bottom: 10px;
        }
        .register-container .btn {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2 class="text-center">Daftar Akun</h2>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Daftar</button>
        </form>
        <p class="text-center mt-3">Sudah punya akun? <a href="login.php">Login disini</a></p>
    </div>

    <!-- Notification Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const notification = "<?php echo $notification; ?>";
            if (notification === "successRegister") {
                alert("Pendaftaran berhasil! Silakan login.");
            } else if (notification === "failedRegister") {
                alert("Username sudah digunakan. Silakan pilih username lain.");
            } else if (notification === "errorRegister") {
                alert("Terjadi kesalahan saat mendaftar. Silakan coba lagi.");
            }
        });
    </script>
    <script src="bootstrap-5.0.2-dist/js/bootstrap.min.js"></script>
    <script src="bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
