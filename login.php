<?php
// Start session
session_start();

// Check if form is submitted
$notification = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Database connection
    $conn = new mysqli("localhost", "root", "", "db_figur");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check user credentials
    $sql = "SELECT * FROM user WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Login successful
        $_SESSION['username'] = $username;
        $notification = "successLogin";
        header("Location: menu.php");
        exit;
    } else {
        // Login failed
        $notification = "failedLogin";
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
    <title>Login - FIGNIME</title>
    <link rel="stylesheet" href="bootstrap-5.0.2-dist/css/bootstrap.min.css">
    <style>
    body {
    background: linear-gradient(to right, #007bff, #ffffff);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
  }


    .login-container {
      display: flex;
      background-color: white;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      max-width: 900px;
      overflow: hidden;
    }

    .login-left {
      background-color: #dfeffc;
      padding: 40px;
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    .login-left img {
      max-width: 100%;
      height: auto;
    }

    .login-right {
      padding: 40px;
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .login-header {
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 20px;
    }

    .login-header img {
      max-width: 60px;
      margin-right: 10px;
    }

    .btn-custom {
      background-color: #007bff;
      color: white;
      width: 100%;
    }

    .btn-custom:hover {
      background-color: #66b3ff;
      color: white;
    }
  </style>
</head>
<body>
  <!-- Login Content -->
  <div class="login-container">
    <div class="login-left">
      <img src="images/fignime-logo.png" alt="gambar-a">
      <h2 class="text-center mt-4">LOGIN</h2>
      <p class="text-center">Sistem Kasir Penjualan Segala Jenis
        <br>Action Figur Anime
      </p>

    </div>
    <div class="login-right">
      <div class="login-header mt-0">
        <h3>FIGNIME</h3>
      </div><br>
      <form id="loginForm" action="" method="post">
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan Username"
            required>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Password"
            required>
        </div>
        <div class="mb-3">
          <div class="g-recaptcha" data-sitekey="your-site-key"></div>
        </div><br>
        <button type="submit" class="btn btn-custom">MASUK</button>
      </form>
    </div>
  </div>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <script>

    function showSuccessModal() {
      var successModal = new bootstrap.Modal(document.getElementById('successModal'));
      successModal.show();
    }
  </script>
  <!-- Login Content -->
  <script src="HOME/vendor/jquery/jquery.min.js"></script>
  <script src="HOME/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="HOME/vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="HOME/js/ruang-admin.min.js"></script>
</body>

</html>
