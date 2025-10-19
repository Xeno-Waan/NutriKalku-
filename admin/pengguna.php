<?php
session_start();
include '../includes/db.php';

// cek login role
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Tambah pengguna (HASH password)
if (isset($_POST['tambah'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // 🔒 hash password
    $role     = $_POST['role'];

    $sql = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')";
    mysqli_query($conn, $sql);
    header("Location: pengguna.php");
    exit;
}

// Edit pengguna
if (isset($_POST['edit'])) {
    $id = $_POST['id_user'];
    $username = $_POST['username'];
    $role     = $_POST['role'];

    if (!empty($_POST['password'])) {
        // kalau password baru diisi → hash ulang
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql = "UPDATE users SET username='$username', password='$password', role='$role' WHERE id_user=$id";
    } else {
        // kalau kosong → jangan ubah password
        $sql = "UPDATE users SET username='$username', role='$role' WHERE id_user=$id";
    }
    mysqli_query($conn, $sql);
    header("Location: pengguna.php");
    exit;
}

// Hapus pengguna
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM users WHERE id_user=$id");
    header("Location: pengguna.php");
    exit;
}

// Ambil semua user
$result = mysqli_query($conn, "SELECT * FROM users ORDER BY id_user DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Pengguna - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h3>Kelola Pengguna</h3>
  <a href="dashboard_admin.php" class="btn btn-secondary btn-sm mb-3">← Kembali</a>

  <!-- Form Tambah Pengguna -->
  <div class="card mb-4">
    <div class="card-body">
      <h5 class="card-title">Tambah Pengguna Baru</h5>
      <form method="POST">
        <div class="row mb-2">
          <div class="col-md-3">
            <input type="text" name="username" class="form-control" placeholder="Username" required>
          </div>
          <div class="col-md-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
          </div>
          <div class="col-md-3">
            <select name="role" class="form-select" required>
              <option value="staff">Staff</option>
              <option value="admin">Admin</option>
            </select>
          </div>
          <div class="col-md-3">
            <button type="submit" name="tambah" class="btn btn-primary">Tambah</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Tabel Data Pengguna -->
  <table class="table table-bordered">
    <thead class="table-dark">
      <tr>
        <th>No</th>
        <th>Username</th>
        <th>Role</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php $no=1; while ($row = mysqli_fetch_assoc($result)) { ?>
      <tr>
        <td><?= $no++; ?></td>
        <td><?= $row['username']; ?></td>
        <td><?= ucfirst($row['role']); ?></td>
        <td>
          <a href="pengguna.php?hapus=<?= $row['id_user']; ?>" 
             onclick="return confirm('Yakin ingin hapus user ini?')" 
             class="btn btn-danger btn-sm">Hapus</a>
          <!-- Tombol Edit -->
          <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id_user']; ?>">Edit</button>
        </td>
      </tr>

      <!-- Modal Edit -->
      <div class="modal fade" id="editModal<?= $row['id_user']; ?>" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header bg-warning">
              <h5 class="modal-title">Edit Pengguna</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
              <div class="modal-body">
                <input type="hidden" name="id_user" value="<?= $row['id_user']; ?>">
                <div class="mb-2">
                  <label>Username</label>
                  <input type="text" name="username" class="form-control" value="<?= $row['username']; ?>" required>
                </div>
                <div class="mb-2">
                  <label>Password (kosongkan jika tidak diganti)</label>
                  <input type="password" name="password" class="form-control">
                </div>
                <div class="mb-2">
                  <label>Role</label>
                  <select name="role" class="form-select" required>
                    <option value="staff" <?= $row['role']=="staff"?"selected":""; ?>>Staff</option>
                    <option value="admin" <?= $row['role']=="admin"?"selected":""; ?>>Admin</option>
                  </select>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" name="edit" class="btn btn-warning">Simpan Perubahan</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <?php } ?>
    </tbody>
  </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
