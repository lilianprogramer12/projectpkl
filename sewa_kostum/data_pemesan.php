<?php
require '../config/database.php';
if (!isset($_SESSION['admin_logged_in'])) { header("Location: login.php"); exit; }

// Simpan catatan atau konfirmasi pesanan
if (isset($_POST['update_pesanan'])) {
    $pid = (int)$_POST['pesanan_id'];
    $catatan = mysqli_real_escape_string($conn, $_POST['catatan']);
    $status = mysqli_real_escape_string($conn, $_POST['status_pesanan']);

    mysqli_query($conn, "UPDATE pesanan SET catatan='$catatan', status_pesanan='$status' WHERE id=$pid");

    // Jika dikonfirmasi, ubah status semua kostum di pesanan ini menjadi Sedang Disewa
    if ($status == 'Dikonfirmasi') {
        mysqli_query($conn, "UPDATE kostum k JOIN detail_pesanan dp ON k.id = dp.kostum_id SET k.status='Sedang Disewa' WHERE dp.pesanan_id=$pid");
    } elseif ($status == 'Selesai') {
        mysqli_query($conn, "UPDATE kostum k JOIN detail_pesanan dp ON k.id = dp.kostum_id SET k.status='Tersedia' WHERE dp.pesanan_id=$pid");
    }
    header("Location: data_pemesan.php?updated=1");
    exit;
}

$pesanan = mysqli_query($conn, "SELECT * FROM pesanan ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pemesan - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="app-container" style="max-width: 750px;">
    <a href="dashboard.php" class="btn-back">← <span class="pill-header">Data Pemesan</span></a>

    <div class="form-group">
        <input type="text" id="searchPenyewa" class="form-control" placeholder="🔍 Cari nama penyewa...">
    </div>

    <?php $no = 1; while ($p = mysqli_fetch_assoc($pesanan)): 
        $pid = $p['id'];
        $q_kostum = mysqli_query($conn, "SELECT k.nama_kostum FROM detail_pesanan dp JOIN kostum k ON dp.kostum_id=k.id WHERE dp.pesanan_id=$pid");
        $list_kostum = [];
        while ($lk = mysqli_fetch_assoc($q_kostum)) { $list_kostum[] = $lk['nama_kostum']; }
    ?>
    <form method="POST" class="card-panel pesanan-item">
        <input type="hidden" name="pesanan_id" value="<?= $pid ?>">
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:12px;">
            <div class="form-group">
                <label><?= $no++ ?>. Nama Pemesan:</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($p['nama_pemesan']) ?>" readonly>
            </div>
            <div class="form-group">
                <label>Nomor HP:</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($p['no_hp']) ?>" readonly>
            </div>
        </div>

        <div class="form-group">
            <label>Alamat Pemesan:</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($p['alamat']) ?>" readonly>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:12px;">
            <div class="form-group">
                <label>Kostum Dipesan:</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars(implode(', ', $list_kostum)) ?>" readonly>
            </div>
            <div class="form-group">
                <label>Status Pesanan:</label>
                <select name="status_pesanan" class="form-control">
                    <option value="Menunggu Konfirmasi" <?= $p['status_pesanan']=='Menunggu Konfirmasi'?'selected':'' ?>>Menunggu Konfirmasi</option>
                    <option value="Dikonfirmasi" <?= $p['status_pesanan']=='Dikonfirmasi'?'selected':'' ?>>Dikonfirmasi (Sedang Disewa)</option>
                    <option value="Selesai" <?= $p['status_pesanan']=='Selesai'?'selected':'' ?>>Selesai (Kembalikan Kostum)</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Catatan:</label>
            <input type="text" name="catatan" class="form-control" value="<?= htmlspecialchars($p['catatan']) ?>" placeholder="Tambahkan catatan...">
        </div>

        <button type="submit" name="update_pesanan" class="btn-primary">Simpan & Update Status</button>
    </form>
    <?php endwhile; ?>
</div>
<script src="../assets/js/script.js"></script>
</body>
</html>