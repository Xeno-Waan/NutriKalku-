<?php
session_start();
include '../includes/db.php';

// Pastikan hanya superadmin yang bisa akses
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'superadmin') {
    header("Location: ../login.php");
    exit;
}

// Menangani aksi aktif/nonaktif user
if (isset($_GET['toggle'])) {
    $id_user = $_GET['toggle'];
    $query = mysqli_query($conn, "SELECT status FROM users WHERE id_user='$id_user'");
    $data = mysqli_fetch_assoc($query);

    $new_status = ($data['status'] == 'aktif') ? 'nonaktif' : 'aktif';
    mysqli_query($conn, "UPDATE users SET status='$new_status' WHERE id_user='$id_user'");
    header("Location: dashboard_super.php");
    exit;
}

// Menangani aksi hapus user
if (isset($_GET['hapus'])) {
    $id_user = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM users WHERE id_user='$id_user'");
    header("Location: dashboard_super.php");
    exit;
}

// Menangani tambah user baru (admin/staff)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tambah_user'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    mysqli_query($conn, "INSERT INTO users (nama, username, password, role, status) 
                         VALUES ('$nama', '$username', '$password', '$role', 'aktif')");
    header("Location: dashboard_super.php");
    exit;
}

// Ambil semua user kecuali superadmin
$users = mysqli_query($conn, "SELECT * FROM users WHERE role != 'superadmin' ORDER BY id_user ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard SuperAdmin - NutriKalku</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="#">NutriKalku SuperAdmin</a>
    <div class="d-flex">
      <a href="../logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<!-- Konten -->
<div class="container mt-4">
  <h3 class="mb-4">👑 Dashboard SuperAdmin</h3>

  <!-- Form Tambah User -->
  <div class="card mb-4">
    <div class="card-header bg-success text-white">Tambah Admin / Staff Baru</div>
    <div class="card-body">
      <form method="POST" class="row g-3">
        <div class="col-md-3">
          <input type="text" name="username" class="form-control" placeholder="Username" required>
        </div>
        <div class="col-md-3">
          <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <div class="col-md-2">
          <select name="role" class="form-select" required>
            <option value="">Pilih Role</option>
            <option value="admin">Admin</option>
            <option value="staff">Staff</option>
          </select>
        </div>
        <div class="col-12 text-end">
          <button type="submit" name="tambah_user" class="btn btn-success">Tambah User</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Daftar User -->
  <div class="card shadow-sm">
    <div class="card-header bg-success text-white">Daftar Pengguna Sistem</div>
    <div class="card-body table-responsive">
      <table class="table table-bordered table-hover align-middle text-center">
        <thead class="table-success">
          <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Username</th>
            <th>Role</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
        <?php
        $no = 1;
        while ($row = mysqli_fetch_assoc($users)) {
        ?>
          <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars($row['nama'] ?? '-'); ?></td>
            <td><?= htmlspecialchars($row['username'] ?? '-'); ?></td>
            <td><?= ucfirst($row['role'] ?? '-'); ?></td>
            <td>
              <?php if (($row['status'] ?? 'nonaktif') == 'aktif'): ?>
                <span class="badge bg-success">Aktif</span>
              <?php else: ?>
                <span class="badge bg-danger">Nonaktif</span>
              <?php endif; ?>
            </td>
            <td>
              <a href="?toggle=<?= $row['id_user']; ?>" 
                 class="btn btn-sm <?= ($row['status'] == 'aktif') ? 'btn-warning' : 'btn-success'; ?>">
                 <?= ($row['status'] == 'aktif') ? 'Nonaktifkan' : 'Aktifkan'; ?>
              </a>
              <a href="?hapus=<?= $row['id_user']; ?>" 
                 class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus user ini?')">Hapus</a>
            </td>
          </tr>
        <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

</body>
</html>
