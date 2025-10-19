<?php
session_start();
include 'includes/db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Validasi input
    if ($new_password !== $confirm_password) {
        $message = "<div class='alert alert-danger'>Konfirmasi password tidak sama!</div>";
    } else {
        // Cek apakah username ada
        $check = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' LIMIT 1");
        if (mysqli_num_rows($check) > 0) {
            mysqli_query($conn, "UPDATE users SET password='$new_password' WHERE username='$username'");
            $message = "<div class='alert alert-success'>Password berhasil diubah! Silakan login kembali.</div>";
        } else {
            $message = "<div class='alert alert-danger'>Username tidak ditemukan!</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lupa Password - NutriKalku</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100">
  <div class="card shadow p-4" style="width: 400px;">
    <h3 class="text-center mb-3 text-success">NutriKalku</h3>
    <h5 class="text-center mb-4 text-muted">Lupa Password</h5>

    <?= $message; ?>

    <form method="POST" action="">
      <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
      </div>

      <div class="mb-3 position-relative">
        <label class="form-label">Password Baru</label>
        <input type="password" name="new_password" id="new_password" class="form-control" placeholder="Masukkan password baru" required>
        <button type="button" id="toggleNewPass" class="btn btn-sm btn-outline-secondary position-absolute top-50 end-0 translate-middle-y me-2">👁</button>
      </div>

      <div class="mb-3 position-relative">
        <label class="form-label">Konfirmasi Password</label>
        <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Konfirmasi password baru" required>
        <button type="button" id="toggleConfirmPass" class="btn btn-sm btn-outline-secondary position-absolute top-50 end-0 translate-middle-y me-2">👁</button>
      </div>

      <button type="submit" class="btn btn-success w-100">Ubah Password</button>
      <div class="text-center mt-3">
        <a href="login.php" class="text-decoration-none text-success">Kembali ke Login</a>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Tombol tampil/sembunyi password baru
document.getElementById('toggleNewPass').addEventListener('click', function() {
  const pass = document.getElementById('new_password');
  const type = pass.getAttribute('type') === 'password' ? 'text' : 'password';
  pass.setAttribute('type', type);
  this.textContent = type === 'password' ? '👁' : '🙈';
});

// Tombol tampil/sembunyi konfirmasi password
document.getElementById('toggleConfirmPass').addEventListener('click', function() {
  const pass = document.getElementById('confirm_password');
  const type = pass.getAttribute('type') === 'password' ? 'text' : 'password';
  pass.setAttribute('type', type);
  this.textContent = type === 'password' ? '👁' : '🙈';
});
</script>
</body>
</html>
