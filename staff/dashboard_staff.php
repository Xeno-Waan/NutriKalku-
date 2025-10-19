<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'staff') {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Staff - NutriKalku</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .navbar {
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .card {
      transition: transform 0.2s ease;
    }
    .card:hover {
      transform: scale(1.02);
    }
  </style>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="#">NutriKalku</a>
    <div class="d-flex align-items-center">
      <span class="text-white me-3">
        👤 <?php echo htmlspecialchars($_SESSION['nama_user'] ?? 'Staff'); ?>
      </span>
      <a href="../logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-5">
  <h3 class="fw-semibold mb-4">Selamat datang di Dashboard Staff Catering 🍽️</h3>
  <p class="text-muted mb-5">Gunakan menu di bawah ini untuk mengelola resep dan menghitung kandungan nutrisi makanan.</p>

  <div class="row g-4">
    <!-- Input Resep -->
    <div class="col-md-6 col-lg-4">
      <div class="card border-0 shadow-sm">
        <div class="card-body text-center">
          <h5 class="card-title fw-semibold mb-3">Input Resep</h5>
          <p class="text-muted">Tambahkan resep baru dan komposisi bahan untuk perhitungan gizi otomatis.</p>
          <a href="input_resep.php" class="btn btn-success btn-sm">Masuk</a>
        </div>
      </div>
    </div>

    <!-- Lihat Hasil Nutrisi -->
    <div class="col-md-6 col-lg-4">
      <div class="card border-0 shadow-sm">
        <div class="card-body text-center">
          <h5 class="card-title fw-semibold mb-3">Lihat Hasil Nutrisi</h5>
          <p class="text-muted">Tampilkan hasil perhitungan total kalori, protein, lemak, dan karbohidrat.</p>
          <a href="hasil.php" class="btn btn-success btn-sm">Masuk</a>
        </div>
      </div>
    </div>

    <!-- Kelola Bahan -->
    <div class="col-md-6 col-lg-4">
      <div class="card border-0 shadow-sm">
        <div class="card-body text-center">
          <h5 class="card-title fw-semibold mb-3">Kelola Bahan</h5>
          <p class="text-muted">Tambahkan atau ubah data bahan makanan beserta nilai gizinya.</p>
          <a href="kelola_bahan.php" class="btn btn-success btn-sm">Masuk</a>
        </div>
      </div>
    </div>

    <!-- Cetak Laporan Nutrisi -->
    <div class="col-md-6 col-lg-4">
      <div class="card border-0 shadow-sm">
        <div class="card-body text-center">
          <h5 class="card-title fw-semibold mb-3">Cetak Laporan Nutrisi</h5>
          <p class="text-muted">Unduh atau cetak laporan hasil perhitungan nutrisi resep.</p>
          <a href="laporan_nutrisi.php" class="btn btn-success btn-sm">Masuk</a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Footer -->
<footer class="text-center mt-5 py-3 text-muted small">
  © 2025 NutriKalku — Sistem Kalkulator Nutrisi Makanan
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
