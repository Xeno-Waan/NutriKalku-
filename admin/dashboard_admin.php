<?php
session_start();
include '../includes/db.php';

// Cek login role
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin - NutriKalku</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .card {
      transition: all 0.3s ease-in-out;
      border: none;
      border-radius: 1rem;
    }
    .card:hover {
      transform: scale(1.03);
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }
    .navbar-brand {
      font-weight: bold;
      letter-spacing: 1px;
    }
    .feature-icon {
      font-size: 3rem;
      color: #198754;
    }
  </style>
</head>

<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">NutriKalku - Admin Panel</a>
    <div class="d-flex align-items-center">
      <span class="text-light me-3 fw-semibold">
        <?= ucfirst($_SESSION['username']); ?> (Admin)
      </span>
      <a href="../logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<!-- Container -->
<div class="container my-5">
  <div class="text-center mb-5">
    <h2 class="fw-bold text-success">Selamat Datang, <?= ucfirst($_SESSION['username']); ?>!</h2>
    <p class="text-muted">Anda memiliki hak penuh untuk mengelola bahan dan resep di sistem NutriKalku.</p>
  </div>

  <div class="row g-4">

    <!-- Kelola Bahan -->
    <div class="col-md-4">
      <div class="card text-center p-4">
        <div class="feature-icon mb-3">🥬</div>
        <h5 class="card-title fw-bold">Kelola Data Bahan</h5>
        <p class="text-muted">Tambah, ubah, atau hapus bahan makanan lengkap dengan nilai gizinya.</p>
        <a href="bahan.php" class="btn btn-success w-100">Masuk ke Data Bahan</a>
      </div>
    </div>

    <!-- Kelola Resep -->
    <div class="col-md-4">
      <div class="card text-center p-4">
        <div class="feature-icon mb-3">🍛</div>
        <h5 class="card-title fw-bold">Kelola Data Resep</h5>
        <p class="text-muted">Buat resep baru dan hitung total nutrisinya otomatis berdasarkan bahan.</p>
        <a href="resep.php" class="btn btn-success w-100">Masuk ke Data Resep</a>
      </div>
    </div>

    <!-- Analisis Gizi -->
    <div class="col-md-4">
      <div class="card text-center p-4">
        <div class="feature-icon mb-3">📊</div>
        <h5 class="card-title fw-bold">Analisis Total Nutrisi</h5>
        <p class="text-muted">Lihat ringkasan kalori, protein, lemak, dan karbohidrat dari semua resep aktif.</p>
        <a href="laporan_gizi.php" class="btn btn-success w-100">Lihat Analisis</a>
      </div>
    </div>

  </div>

  <!-- Divider -->
  <hr class="my-5">

  <!-- Info Section -->
  <div class="text-center">
    

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
