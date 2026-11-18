<?php
require '../config/database.php';
if (!isset($_SESSION['admin_logged_in'])) { header("Location: login.php"); exit; }

if (isset($_POST['simpan_harga'])) {
    foreach ($_POST['harga'] as $id => $harga_baru) {
        $id = (int)$id;
        $harga_baru = (int)$harga_baru;
        mysqli_query($conn, "UPDATE kostum SET harga=$harga_baru WHERE id=$id");
    }
    header("Location: pengaturan_harga.php?updated=1");
    exit;
}

$kostum = mysqli_query($conn, "SELECT * FROM kostum ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Harga - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="app-container" style="max-width: 650px;">
    <a href="dashboard.php" class="btn-back">← Pengaturan Harga</a>

    <?php if (isset($_GET['updated'])): ?>
        <div style="background:#DCFCE7; color:#166534; padding:12px; border-radius:12px; margin-bottom:16px; font-weight:600;">
            ✅ Semua perubahan harga berhasil disimpan!
        </div>
    <?php endif; ?>

    <form method="POST">
        <?php while ($k = mysqli_fetch_assoc($kostum)): ?>
        <div class="card-panel" style="display:flex; align-items:center; gap:16px; padding:14px;">
            <img src="../assets/uploads/<?= htmlspecialchars($k['gambar']) ?>" onerror="this.src='../assets/img/bg-maskot.jpg'" style="width:75px; height:75px; border-radius:14px; object-fit:cover;">
            <div style="flex:1;">
                <label style="font-weight:700; display:block; margin-bottom:6px;"><?= htmlspecialchars($k['nama_kostum']) ?></label>
                <input type="number" name="harga[<?= $k['id'] ?>]" class="form-control" value="<?= $k['harga'] ?>" required>
            </div>
        </div>
        <?php endwhile; ?>

        <div style="text-align:center; margin-top:20px;">
            <button type="submit" name="simpan_harga" class="btn-primary" style="padding:14px 36px;">Simpan Perubahan</button>
        </div>
    </form>
</div>
</body>
</html>