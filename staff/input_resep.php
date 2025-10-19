<?php
session_start();
include '../includes/db.php';

// cek login role
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'staff') {
    header("Location: ../login.php");
    exit;
}

// ambil daftar resep dari database
$resep = mysqli_query($conn, "SELECT * FROM resep ORDER BY nama_resep ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Input Resep - Staff</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h3>Pilih Resep</h3>
  <a href="dashboard_staff.php" class="btn btn-secondary btn-sm mb-3">← Kembali</a>

  <div class="card">
    <div class="card-body">
      <form method="GET" action="hasil.php">
        <div class="mb-3">
          <label for="id_resep" class="form-label">Pilih Resep</label>
          <select name="id_resep" id="id_resep" class="form-select" required>
            <option value="">-- Pilih Resep --</option>
            <?php while ($r = mysqli_fetch_assoc($resep)) : ?>
              <option value="<?= $r['id_resep']; ?>"><?= $r['nama_resep']; ?></option>
            <?php endwhile; ?>
          </select>
        </div>
        <button type="submit" class="btn btn-primary">Lihat Hasil Nutrisi</button>
      </form>
    </div>
  </div>
</div>
</body>
</html>
