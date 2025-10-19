<?php
session_start();
include '../includes/db.php';

// Cek role
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Tambah resep/menu baru
if (isset($_POST['tambah_resep'])) {
    $nama = $_POST['nama_resep'];
    $deskripsi = $_POST['deskripsi'];
    $kalori = $_POST['total_kalori'];
    $protein = $_POST['total_protein'];
    $karbo = $_POST['total_karbo'];
    $lemak = $_POST['total_lemak'];

    $sql = "INSERT INTO resep (nama_resep, deskripsi, total_kalori, total_protein, total_karbo, total_lemak)
            VALUES ('$nama', '$deskripsi', '$kalori', '$protein', '$karbo', '$lemak')";
    mysqli_query($conn, $sql);
    header("Location: resep.php");
    exit;
}

// Edit resep/menu
if (isset($_POST['edit_resep'])) {
    $id = $_POST['id_resep'];
    $nama = $_POST['nama_resep'];
    $deskripsi = $_POST['deskripsi'];
    $kalori = $_POST['total_kalori'];
    $protein = $_POST['total_protein'];
    $karbo = $_POST['total_karbo'];
    $lemak = $_POST['total_lemak'];

    $sql = "UPDATE resep SET 
                nama_resep='$nama',
                deskripsi='$deskripsi',
                total_kalori='$kalori',
                total_protein='$protein',
                total_karbo='$karbo',
                total_lemak='$lemak'
            WHERE id_resep=$id";
    mysqli_query($conn, $sql);
    header("Location: resep.php");
    exit;
}

// Hapus resep/menu
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM resep WHERE id_resep=$id");
    header("Location: resep.php");
    exit;
}

// Ambil semua resep
$resep = mysqli_query($conn, "SELECT * FROM resep ORDER BY id_resep DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Resep / Menu - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
  <h3>Kelola Resep / Menu</h3>
  <a href="dashboard_admin.php" class="btn btn-secondary btn-sm mb-3">← Kembali</a>

  <!-- Form Tambah Resep -->
  <div class="card mb-4 shadow-sm">
    <div class="card-body">
      <h5 class="card-title">Tambah Resep Baru</h5>
      <form method="POST">
        <div class="row mb-2">
          <div class="col-md-4">
            <input type="text" name="nama_resep" class="form-control" placeholder="Nama Resep / Menu" required>
          </div>
          <div class="col-md-8">
            <input type="text" name="deskripsi" class="form-control" placeholder="Deskripsi (opsional)">
          </div>
        </div>

        <div class="row mb-2">
          <div class="col-md-3">
            <input type="number" step="0.01" name="total_kalori" class="form-control" placeholder="Total Kalori (kkal)" required>
          </div>
          <div class="col-md-3">
            <input type="number" step="0.01" name="total_protein" class="form-control" placeholder="Protein (gr)" required>
          </div>
          <div class="col-md-3">
            <input type="number" step="0.01" name="total_karbo" class="form-control" placeholder="Karbo (gr)" required>
          </div>
          <div class="col-md-3">
            <input type="number" step="0.01" name="total_lemak" class="form-control" placeholder="Lemak (gr)" required>
          </div>
        </div>

        <button type="submit" name="tambah_resep" class="btn btn-primary">Tambah Resep</button>
      </form>
    </div>
  </div>

  <!-- Daftar Resep -->
  <table class="table table-bordered table-striped">
    <thead class="table-dark text-center">
      <tr>
        <th>No</th>
        <th>Nama Resep / Menu</th>
        <th>Deskripsi</th>
        <th>Kalori</th>
        <th>Protein</th>
        <th>Karbohidrat</th>
        <th>Lemak</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php $no=1; while ($r = mysqli_fetch_assoc($resep)) { ?>
      <tr>
        <td><?= $no++; ?></td>
        <td><?= htmlspecialchars($r['nama_resep']); ?></td>
        <td><?= htmlspecialchars($r['deskripsi']); ?></td>
        <td><?= $r['total_kalori']; ?></td>
        <td><?= $r['total_protein']; ?></td>
        <td><?= $r['total_karbo']; ?></td>
        <td><?= $r['total_lemak']; ?></td>
        <td class="text-center">
          <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $r['id_resep']; ?>">Edit</button>
          <a href="resep.php?hapus=<?= $r['id_resep']; ?>" onclick="return confirm('Yakin ingin hapus resep ini?')" class="btn btn-danger btn-sm">Hapus</a>
        </td>
      </tr>

      <!-- Modal Edit -->
      <div class="modal fade" id="editModal<?= $r['id_resep']; ?>" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header bg-warning">
              <h5 class="modal-title">Edit Resep</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
              <div class="modal-body">
                <input type="hidden" name="id_resep" value="<?= $r['id_resep']; ?>">
                <div class="mb-2">
                  <label>Nama Resep</label>
                  <input type="text" name="nama_resep" class="form-control" value="<?= htmlspecialchars($r['nama_resep']); ?>" required>
                </div>
                <div class="mb-2">
                  <label>Deskripsi</label>
                  <input type="text" name="deskripsi" class="form-control" value="<?= htmlspecialchars($r['deskripsi']); ?>">
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <label>Kalori</label>
                    <input type="number" step="0.01" name="total_kalori" class="form-control" value="<?= $r['total_kalori']; ?>" required>
                  </div>
                  <div class="col-md-6">
                    <label>Protein</label>
                    <input type="number" step="0.01" name="total_protein" class="form-control" value="<?= $r['total_protein']; ?>" required>
                  </div>
                </div>
                <div class="row mt-2">
                  <div class="col-md-6">
                    <label>Karbohidrat</label>
                    <input type="number" step="0.01" name="total_karbo" class="form-control" value="<?= $r['total_karbo']; ?>" required>
                  </div>
                  <div class="col-md-6">
                    <label>Lemak</label>
                    <input type="number" step="0.01" name="total_lemak" class="form-control" value="<?= $r['total_lemak']; ?>" required>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" name="edit_resep" class="btn btn-warning">Simpan Perubahan</button>
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
