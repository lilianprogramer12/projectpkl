<?php
require '../config/database.php';
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$total_pesanan   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as jml FROM pesanan"))['jml'];
$pesanan_baru    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as jml FROM pesanan WHERE status_pesanan='Menunggu Konfirmasi'"))['jml'];
$kostum_tersedia = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as jml FROM kostum WHERE status='Tersedia'"))['jml'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Sewa Kostum</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="app-container" style="max-width: 850px;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <span class="pill-header">WELCOME ADMIN!</span>
        <a href="logout.php" style="background:#BA1833; color:#fff; padding:8px 16px; border-radius:12px; text-decoration:none; font-size:0.85rem; font-weight:700;">Logout</a>
    </div>

    <!-- Statistik Real-time -->
    <div class="stats-grid">
        <div class="stat-card">
            <div style="font-weight:700; color:#475569;">🟢 Total Pesanan</div>
            <div class="stat-badge" style="background:#22C55E;"><?= $total_pesanan ?></div>
        </div>
        <div class="stat-card">
            <div style="font-weight:700; color:#475569;">🟡 Pesanan Baru</div>
            <div class="stat-badge" style="background:#EAB308;"><?= $pesanan_baru ?></div>
        </div>
        <div class="stat-card">
            <div style="font-weight:700; color:#475569;">⚫ Kostum Tersedia</div>
            <div class="stat-badge" style="background:#1E293B;"><?= $kostum_tersedia ?></div>
        </div>
    </div>

    <h3 style="margin-bottom: 14px;">Menu Cepat</h3>
    <div class="quick-menu-grid">
        <a href="data_pemesan.php" class="menu-box">
            <div style="font-size:2rem; margin-bottom:8px;">📋</div>
            Data Pemesan
        </a>
        <a href="konfirmasi_kostum.php" class="menu-box">
            <div style="font-size:2rem; margin-bottom:8px;">🎭</div>
            Konfirmasi Kostum
        </a>
        <a href="pengaturan_harga.php" class="menu-box">
            <div style="font-size:2rem; margin-bottom:8px;">🏷️</div>
            Pengaturan Harga
        </a>
        <a href="tambah_kostum.php" class="menu-box">
            <div style="font-size:2rem; margin-bottom:8px;">➕</div>
            Tambah Kostum
        </a>
    </div>
</div>
</body>
</html>