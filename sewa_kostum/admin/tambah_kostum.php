<?php
require '../config/database.php';
if (!isset($_SESSION['admin_logged_in'])) { header("Location: login.php"); exit; }

$sukses = false;
if (isset($_POST['simpan_kostum'])) {
    $nama  = mysqli_real_escape_string($conn, $_POST['nama_kostum']);
    $harga = (int)$_POST['harga'];
    $desk  = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    $nama_file = "default.jpg";
    if (!empty($_FILES['gambar']['name'])) {
        $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $nama_file = "kostum_" . time() . "." . $ext;
        move_uploaded_file($_FILES['gambar']['tmp_name'], "../assets/uploads/" . $nama_file);
    }

    mysqli_query($conn, "INSERT INTO kostum (nama_kostum, harga, gambar, deskripsi, status) VALUES ('$nama', $harga, '$nama_file', '$desk', 'Tersedia')");
    $sukses = true;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kostum - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="app-container" style="max-width: 550px;">
    <a href="dashboard.php" class="btn-back">← Kembali</a>

    <form method="POST" enctype="multipart/form-data" class="card-panel">
        <div style="background:#CBD5E1; border-radius:18px; padding:30px; text-align:center; margin-bottom:18px; position:relative;">
            <img id="imgPreview" style="max-height:200px; margin:0 auto 12px; display:none; border-radius:12px;">
            <label for="fileInput" style="cursor:pointer; font-weight:700; color:#1E293B;">
                🖼️ Klik untuk Unggah Foto Kostum (+)
            </label>
            <input type="file" id="fileInput" name="gambar" accept="image/*" onchange="previewImage(event)" style="display:none;" required>
        </div>

        <div class="form-group">
            <label>Nama / Nomor Kostum</label>
            <input type="text" name="nama_kostum" class="form-control" placeholder="Masukkan Nomor / Nama Kostum" required>
        </div>

        <div class="form-group">
            <label>Harga Kostum (Rp)</label>
            <input type="number" name="harga" class="form-control" placeholder="Masukkan Harga Kostum (contoh: 150000)" required>
        </div>

        <div class="form-group">
            <label>Deskripsi Singkat Kostum</label>
            <textarea name="deskripsi" class="form-control" rows="2" placeholder="Keterangan kelengkapan aksesoris kostum..."></textarea>
        </div>

        <div style="display:flex; gap:12px; margin-top:20px;">
            <a href="dashboard.php" class="btn-primary" style="background:#94A3B8; text-decoration:none; text-align:center; flex:1;">KEMBALI</a>
            <button type="submit" name="simpan_kostum" class="btn-primary" style="flex:1;">SIMPAN</button>
        </div>
    </form>
</div>

<?php if ($sukses): ?>
<div class="modal-overlay">
    <div class="modal-box">
        <div style="width:65px; height:65px; background:#22C55E; color:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:2rem; margin:0 auto 14px;">✓</div>
        <h2 style="font-size:1.25rem; margin-bottom:18px;">KOSTUM BERHASIL DITAMBAHKAN</h2>
        <a href="dashboard.php" class="btn-primary" style="text-decoration:none; display:inline-block;">Kembali ke Dashboard</a>
    </div>
</div>
<?php endif; ?>

<script src="../assets/js/script.js"></script>
</body>
</html>