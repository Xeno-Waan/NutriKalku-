<?php
session_start();
include '../includes/db.php';

// cek login role
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'staff') {
    header("Location: ../login.php");
    exit;
}

// cek apakah ada id_resep
if (!isset($_GET['id_resep'])) {
    header("Location: input_resep.php");
    exit;
}

$id_resep = $_GET['id_resep'];

// ambil data resep
$resep = mysqli_query($conn, "SELECT * FROM resep WHERE id_resep=$id_resep");
$r = mysqli_fetch_assoc($resep);

// ambil bahan-bahan resep
$query = "
  SELECT b.nama_bahan, rb.jumlah, b.kalori, b.protein, b.lemak, b.karbohidrat
  FROM resep_bahan rb
  JOIN bahan b ON rb.id_bahan = b.id_bahan
  WHERE rb.id_resep = $id_resep
";
$detail = mysqli_query($conn, $query);

// hitung total
$total_kalori = $total_protein = $total_lemak = $total_karbo = 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Hasil Nutrisi - Staff</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h3>Hasil Nutrisi Resep</h3>
  <a href="input_resep.php" class="btn btn-secondary btn-sm mb-3">← Kembali</a>

  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="card-title"><?= $r['nama_resep']; ?></h5>
      <p><?= $r['deskripsi']; ?></p>

      <table class="table table-bordered">
        <thead class="table-dark">
          <tr>
            <th>Bahan</th>
            <th>Jumlah (gram)</th>
            <th>Kalori</th>
            <th>Protein</th>
            <th>Lemak</th>
            <th>Karbohidrat</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($d = mysqli_fetch_assoc($detail)) : 
            $faktor = $d['jumlah'] / 100;
            $kal = $d['kalori'] * $faktor;
            $pro = $d['protein'] * $faktor;
            $lem = $d['lemak'] * $faktor;
            $kar = $d['karbohidrat'] * $faktor;

            $total_kalori += $kal;
            $total_protein += $pro;
            $total_lemak += $lem;
            $total_karbo += $kar;
          ?>
          <tr>
            <td><?= $d['nama_bahan']; ?></td>
            <td><?= $d['jumlah']; ?> g</td>
            <td><?= number_format($kal, 2); ?></td>
            <td><?= number_format($pro, 2); ?></td>
            <td><?= number_format($lem, 2); ?></td>
            <td><?= number_format($kar, 2); ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
        <tfoot class="table-secondary">
          <tr>
            <th colspan="2">Total</th>
            <th><?= number_format($total_kalori, 2); ?></th>
            <th><?= number_format($total_protein, 2); ?></th>
            <th><?= number_format($total_lemak, 2); ?></th>
            <th><?= number_format($total_karbo, 2); ?></th>
          </tr>
        </tfoot>
      </table>

      <!-- Tombol Cetak -->
      <a href="laporan.php?id_resep=<?= $id_resep; ?>" target="_blank" class="btn btn-success">Cetak Laporan</a>
    </div>
  </div>
</div>
</body>
</html>
