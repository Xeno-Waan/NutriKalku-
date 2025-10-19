<?php
session_start();
include 'includes/db.php';

$error = "";

// Jika form dikirim (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Cek user di database
    $sql = "SELECT * FROM users WHERE username='$username' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        // --- Password sementara (belum di-hash) ---
        if ($password === $user['password']) {

            // Set session data
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = strtolower($user['role']); // ubah ke huruf kecil biar konsisten
            $_SESSION['status'] = $user['status'] ?? 'aktif'; // kalau status null, anggap aktif

            // Cek status akun
            if (isset($user['status']) && $user['status'] == 'nonaktif') {
                $error = "Akun ini telah dinonaktifkan!";
            } else {
                // Arahkan sesuai role pengguna
                switch ($_SESSION['role']) {
                    case 'superadmin':
                        header("Location: superadmin/dashboard_super.php");
                        exit;
                    case 'admin':
                        header("Location: admin/dashboard_admin.php");
                        exit;
                    case 'staff':
                        header("Location: staff/dashboard_staff.php");
                        exit;
                    default:
                        $error = "Role pengguna tidak dikenali!";
                        break;
                }
            }
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - NutriKalku</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100">
  <div class="card shadow p-4" style="width: 400px;">
    <h3 class="text-center mb-3">NutriKalku</h3>
    <h5 class="text-center mb-4 text-muted">Login</h5>

    <?php if ($error): ?>
      <div class="alert alert-danger py-2 text-center"><?= $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
      </div>

      <div class="mb-3 position-relative">
        <label class="form-label">Password</label>
        <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
        <button type="button" id="togglePassword" class="btn btn-sm btn-outline-secondary position-absolute top-50 end-0 translate-middle-y me-2">👁</button>
      </div>

      <button type="submit" class="btn btn-success w-100 mb-2">Login</button>

      <!-- Tombol Lupa Password -->
      <div class="text-center">
        <a href="lupa_pw.php" class="text-decoration-none text-success fw-semibold">Lupa Password?</a>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Tombol tampil/sembunyi password
document.getElementById('togglePassword').addEventListener('click', function() {
  const pass = document.getElementById('password');
  const type = pass.getAttribute('type') === 'password' ? 'text' : 'password';
  pass.setAttribute('type', type);
  this.textContent = type === 'password' ? '👁' : '🙈';
});
</script>
</body>
</html>
