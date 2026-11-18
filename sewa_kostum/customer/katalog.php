<?php
require '../config/database.php';

if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}

// Tambah ke keranjang
if (isset($_POST['tambah_keranjang'])) {
    $id_kostum = (int)$_POST['id_kostum'];
    if (!in_array($id_kostum, $_SESSION['keranjang'])) {
        $_SESSION['keranjang'][] = $id_kostum;
    }
    header("Location: katalog.php?added=1");
    exit;
}

$query = mysqli_query($conn, "SELECT * FROM kostum ORDER BY id ASC");
$jumlah_keranjang = count($_SESSION['keranjang']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Kostum - SMK Batik Perbaik Purworejo</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="app-container">
    <div class="top-nav">
        <div class="nav-brand">
            <img src="../assets/img/logo-smk.png" alt="Logo">
            <div>
                <h2 style="font-size: 1.15rem; font-weight: 800;">WELCOME CUSTOMER.</h2>
                <small style="color: #64748B;">Pilih kostum terbaik untuk acaramu</small>
            </div>
        </div>
        <a href="../index.php" style="text-decoration:none; font-weight:700; color:#1E293B;">🏠 Beranda</a>
    </div>

    <?php if (isset($_GET['added'])): ?>
        <div style="background:#DCFCE7; color:#166534; padding:12px 18px; border-radius:14px; margin-bottom:20px; font-weight:600;">
            ✅ Kostum berhasil ditambahkan ke keranjang!
        </div>
    <?php endif; ?>

    <div class="catalog-grid">
        <?php while ($row = mysqli_fetch_assoc($query)): 
            $statusClass = 'status-tersedia';
            if ($row['status'] == 'Sedang Disewa') $statusClass = 'status-disewa';
            if ($row['status'] == 'Dalam Antrian') $statusClass = 'status-antrian';
        ?>
        <div class="costume-card">
            <span class="status-badge <?= $statusClass ?>"><?= htmlspecialchars($row['status']) ?></span>
            
            <div class="costume-img-wrap">
                <img src="../assets/uploads/<?= htmlspecialchars($row['gambar']) ?>" 
                     onerror="this.src='../assets/img/bg-maskot.jpg'" 
                     alt="<?= htmlspecialchars($row['nama_kostum']) ?>">
                <div class="img-hover-overlay">
                    <p><?= htmlspecialchars($row['deskripsi'] ?: 'Klik lihat informasi kostum untuk jadwal penyewaan.') ?></p>
                </div>
            </div>

            <div class="costume-info">
                <div class="costume-title"><?= htmlspecialchars($row['nama_kostum']) ?></div>
                <div class="costume-price"><?= formatRupiah($row['harga']) ?></div>

                <a href="detail_kostum.php?id=<?= $row['id'] ?>" class="btn-info-kostum">
                    Lihat informasi kostum
                </a>

                <form method="POST">
                    <input type="hidden" name="id_kostum" value="<?= $row['id'] ?>">
                    <?php if ($row['status'] == 'Tersedia'): ?>
                        <button type="submit" name="tambah_keranjang" class="btn-add-cart">
                            🛒 Masukkan keranjang
                        </button>
                    <?php else: ?>
                        <button type="button" class="btn-add-cart" disabled>
                            Tidak Tersedia (<?= $row['status'] ?>)
                        </button>
                    <?php endif; ?>
                </form>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Floating Bottom Bar -->
<div class="bottom-bar">
    <a href="keranjang.php" class="btn-cart-float">
        🛒 Lihat Keranjang & Mulai Pesan (<?= $jumlah_keranjang ?>)
    </a>
    <a href="riwayat.php" class="btn-history-float">
        📋 Lihat Riwayat
    </a>
</div>

<script src="../assets/js/script.js"></script>
</body>
</html>