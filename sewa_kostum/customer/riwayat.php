<?php
require '../config/database.php';

$riwayat = mysqli_query($conn, "
    SELECT k.nama_kostum, k.gambar, p.tanggal_pemakaian, p.status_pesanan, p.nama_pemesan 
    FROM detail_pesanan dp
    JOIN pesanan p ON dp.pesanan_id = p.id
    JOIN kostum k ON dp.kostum_id = k.id
    ORDER BY p.id DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya - Sewa Kostum</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="app-container" style="max-width: 600px;">
    <h2 style="text-align:center; margin-bottom:20px;">PESANAN SAYA</h2>

    <?php while ($r = mysqli_fetch_assoc($riwayat)): 
        $badgeBg = ($r['status_pesanan'] == 'Dikonfirmasi') ? '#DCFCE7; color:#166534;' : '#FEF3C7; color:#92400E;';
    ?>
    <div class="card-panel" style="display:flex; align-items:center; gap:16px; padding:14px;">
        <img src="../assets/uploads/<?= htmlspecialchars($r['gambar']) ?>" onerror="this.src='../assets/img/bg-maskot.jpg'" style="width:75px; height:75px; border-radius:14px; object-fit:cover;">
        <div style="flex:1;">
            <h3 style="font-size:1.05rem;"><?= htmlspecialchars($r['nama_kostum']) ?></h3>
            <div style="font-size:0.8rem; color:#64748B; margin:3px 0;">
                Pemesan: <?= htmlspecialchars($r['nama_pemesan']) ?> | Tgl: <?= date('d/m/Y', strtotime($r['tanggal_pemakaian'])) ?>
            </div>
            <span style="display:inline-block; padding:4px 12px; border-radius:8px; font-size:0.78rem; font-weight:700; background:<?= $badgeBg ?>">
                <?= $r['status_pesanan'] ?>
            </span>
        </div>
    </div>
    <?php endwhile; ?>

    <div style="text-align:center; margin-top:20px;">
        <a href="katalog.php" class="btn-primary" style="text-decoration:none;">Kembali</a>
    </div>
</div>
</body>
</html>