<?php
require '../config/database.php';
if (!isset($_SESSION['admin_logged_in'])) { header("Location: login.php"); exit; }

if (isset($_POST['ubah_status'])) {
    $kid = (int)$_POST['kostum_id'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    mysqli_query($conn, "UPDATE kostum SET status='$status' WHERE id=$kid");
    header("Location: konfirmasi_kostum.php?saved=1");
    exit;
}

$kostum = mysqli_query($conn, "SELECT * FROM kostum ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Kostum - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="app-container" style="max-width: 800px;">
    <a href="dashboard.php" class="btn-back">← Kembali</a>
    <div style="margin-bottom: 18px;">
        <span class="pill-header">Konfirmasi & Ubah Status Kostum</span>
    </div>

    <?php if (isset($_GET['saved'])): ?>
        <div style="background:#DCFCE7; color:#166534; padding:12px; border-radius:12px; margin-bottom:16px; font-weight:600;">
            ✅ Status kostum berhasil diperbarui dan langsung tampil di katalog customer!
        </div>
    <?php endif; ?>

    <div class="catalog-grid">
        <?php while ($k = mysqli_fetch_assoc($kostum)): ?>
        <form method="POST" class="card-panel" style="text-align:center;">
            <input type="hidden" name="kostum_id" value="<?= $k['id'] ?>">
            <img src="../assets/uploads/<?= htmlspecialchars($k['gambar']) ?>" onerror="this.src='../assets/img/bg-maskot.jpg'" style="width:100%; height:170px; object-fit:cover; border-radius:14px; margin-bottom:10px;">
            <h4><?= htmlspecialchars($k['nama_kostum']) ?></h4>
            
            <div class="form-group" style="text-align:left; margin-top:10px;">
                <label>Status Ketersediaan:</label>
                <select name="status" class="form-control">
                    <option value="Tersedia" <?= $k['status']=='Tersedia'?'selected':'' ?>>Tersedia</option>
                    <option value="Sedang Disewa" <?= $k['status']=='Sedang Disewa'?'selected':'' ?>>Sedang Disewa</option>
                    <option value="Dalam Antrian" <?= $k['status']=='Dalam Antrian'?'selected':'' ?>>Dalam Antrian</option>
                </select>
            </div>

            <button type="submit" name="ubah_status" class="btn-primary" style="width:100%;">SIMPAN</button>
        </form>
        <?php endwhile; ?>
    </div>
</div>
</body>
</html>