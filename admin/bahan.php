<?php
session_start();
include '../includes/db.php';

// Pastikan hanya admin yang bisa akses
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

$alert = "";

// Tambah bahan
if (isset($_POST['tambah'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_bahan']);
    $kalori = floatval($_POST['kalori']);
    $protein = floatval($_POST['protein']);
    $lemak = floatval($_POST['lemak']);
    $karbo = floatval($_POST['karbo']);
    $satuan = mysqli_real_escape_string($conn, $_POST['satuan']);

    $sql = "INSERT INTO bahan (nama_bahan, kalori, protein, lemak, karbohidrat, satuan) 
            VALUES ('$nama', '$kalori', '$protein', '$lemak', '$karbo', '$satuan')";
    if (mysqli_query($conn, $sql)) {
        $alert = "<div class='alert alert-success'>✅ Data bahan berhasil ditambahkan!</div>";
    } else {
        $alert = "<div class='alert alert-danger'>❌ Gagal menambah bahan!</div>";
    }
}

// Hapus bahan
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    mysqli_query($conn, "DELETE FROM bahan WHERE id_bahan=$id");
    $alert = "<div class='alert alert-warning'>🗑️ Data bahan telah dihapus!</div>";
}

// Edit bahan
if (isset($_POST['edit'])) {
    $id = intval($_POST['id_bahan']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama_bahan']);
    $kalori = floatval($_POST['kalori']);
    $protein = floatval($_POST['protein']);
    $lemak = floatval($_POST['lemak']);
    $karbo = floatval($_POST['karbo']);
    $satuan = mysqli_real_escape_string($conn, $_POST['satuan']);

    $sql = "UPDATE bahan SET 
                nama_bahan='$nama', 
                kalori='$kalori', 
                protein='$protein', 
                lemak='$lemak', 
                karbohidrat='$karbo', 
                satuan='$satuan'
            WHERE id_bahan=$id";
    if (mysqli_query($conn, $sql)) {
        $alert = "<div class='alert alert-success'>✅ Data bahan berhasil diperbarui!</div>";
    } else {
        $alert = "<div class='alert alert-danger'>❌ Gagal memperbarui data bahan!</div>";
    }
}

// Ambil data bahan
$result = mysqli_query($conn, "SELECT * FROM bahan ORDER BY id_bahan DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelola Data Bahan - NutriKalku Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-success">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="dashboard_admin.php">NutriKalku - Admin</a>
    <div class="d-flex">
      <a href="../logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-4 mb-5">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="text-success fw-bold">Kelola Data Bahan</h3>
    <a href="dashboard_admin.php" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
  </div>

  <?= $alert; ?>

  <!-- Form Tambah Bahan -->
  <div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
      <h5 class="card-title text-success"><i class="bi bi-plus-circle"></i> Tambah Bahan Baru</h5>
      <form method="POST">
        <div class="row g-2 mb-3">
          <div class="col-md-3">
            <input type="text" name="nama_bahan" class="form-control" placeholder="Nama Bahan" required>
          </div>
          <div class="col-md-2">
            <input type="number" step="0.01" name="kalori" class="form-control" placeholder="Kalori" required>
          </div>
          <div class="col-md-2">
            <input type="number" step="0.01" name="protein" class="form-control" placeholder="Protein" required>
          </div>
          <div class="col-md-2">
            <input type="number" step="0.01" name="lemak" class="form-control" placeholder="Lemak" required>
          </div>
          <div class="col-md-2">
            <input type="number" step="0.01" name="karbo" class="form-control" placeholder="Karbohidrat" required>
          </div>
          <div class="col-md-1">
            <input type="text" name="satuan" class="form-control" value="gram" required>
          </div>
        </div>
        <button type="submit" name="tambah" class="btn btn-success"><i class="bi bi-save"></i> Simpan</button>
      </form>
    </div>
  </div>

  <!-- Tabel Data Bahan -->
  <div class="card shadow-sm border-0">
    <div class="card-body">
      <h5 class="card-title text-success mb-3"><i class="bi bi-list-ul"></i> Daftar Bahan Makanan</h5>
      <div class="table-responsive">
        <table class="table table-bordered align-middle text-center">
          <thead class="table-success">
            <tr>
              <th>No</th>
              <th>Nama Bahan</th>
              <th>Kalori</th>
              <th>Protein</th>
              <th>Lemak</th>
              <th>Karbohidrat</th>
              <th>Satuan</th>
              <th width="150">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1; while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
              <td><?= $no++; ?></td>
              <td><?= htmlspecialchars($row['nama_bahan']); ?></td>
              <td><?= $row['kalori']; ?></td>
              <td><?= $row['protein']; ?></td>
              <td><?= $row['lemak']; ?></td>
              <td><?= $row['karbohidrat']; ?></td>
              <td><?= htmlspecialchars($row['satuan']); ?></td>
              <td>
                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id_bahan']; ?>"><i class="bi bi-pencil-square"></i></button>
                <a href="bahan.php?hapus=<?= $row['id_bahan']; ?>" onclick="return confirm('Yakin ingin hapus bahan ini?')" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></a>
              </td>
            </tr>

            <!-- Modal Edit -->
            <div class="modal fade" id="editModal<?= $row['id_bahan']; ?>" tabindex="-1">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title"><i class="bi bi-pencil"></i> Edit Bahan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <form method="POST">
                    <div class="modal-body">
                      <input type="hidden" name="id_bahan" value="<?= $row['id_bahan']; ?>">
                      <div class="mb-2">
                        <label>Nama Bahan</label>
                        <input type="text" name="nama_bahan" class="form-control" value="<?= htmlspecialchars($row['nama_bahan']); ?>" required>
                      </div>
                      <div class="mb-2"><label>Kalori</label>
                        <input type="number" step="0.01" name="kalori" class="form-control" value="<?= $row['kalori']; ?>" required>
                      </div>
                      <div class="mb-2"><label>Protein</label>
                        <input type="number" step="0.01" name="protein" class="form-control" value="<?= $row['protein']; ?>" required>
                      </div>
                      <div class="mb-2"><label>Lemak</label>
                        <input type="number" step="0.01" name="lemak" class="form-control" value="<?= $row['lemak']; ?>" required>
                      </div>
                      <div class="mb-2"><label>Karbohidrat</label>
                        <input type="number" step="0.01" name="karbo" class="form-control" value="<?= $row['karbohidrat']; ?>" required>
                      </div>
                      <div class="mb-2"><label>Satuan</label>
                        <input type="text" name="satuan" class="form-control" value="<?= htmlspecialchars($row['satuan']); ?>" required>
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
    </div>
  </div>
</div>

<footer class="text-center text-muted py-3 small">
  &copy; <?= date('Y'); ?> NutriKalku — Data Bahan Admin
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
