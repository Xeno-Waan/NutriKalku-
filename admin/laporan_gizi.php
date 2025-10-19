<?php
session_start();
include '../includes/db.php';

// Cek role admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Ambil semua data resep
$resep = mysqli_query($conn, "SELECT * FROM resep ORDER BY id_resep DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Laporan Gizi - NutriKalku</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
  <h3 class="mb-4">📊 Laporan Gizi Resep / Menu</h3>
  <a href="dashboard_admin.php" class="btn btn-secondary btn-sm mb-3">← Kembali</a>

  <?php while ($r = mysqli_fetch_assoc($resep)) : ?>
    <div class="card mb-4 shadow-sm">
      <div class="card-body">
        <h5><?= htmlspecialchars($r['nama_resep']); ?></h5>
        <p><?= htmlspecialchars($r['deskripsi']); ?></p>

        <table class="table table-bordered table-striped">
          <thead class="table-dark text-center">
            <tr>
              <th>Kalori</th>
              <th>Protein</th>
              <th>Lemak</th>
              <th>Karbohidrat</th>
            </tr>
          </thead>
          <tbody class="text-center">
            <tr>
              <td><?= isset($r['total_kalori']) ? $r['total_kalori'] : 0; ?> kkal</td>
              <td><?= isset($r['total_protein']) ? $r['total_protein'] : 0; ?> gr</td>
              <td><?= isset($r['total_lemak']) ? $r['total_lemak'] : 0; ?> gr</td>
              <td><?= isset($r['total_karbo']) ? $r['total_karbo'] : 0; ?> gr</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  <?php endwhile; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
