<?php
require '../config/database.php';

if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}

// Hapus 1 item
if (isset($_GET['hapus'])) {
    $hapus_id = (int)$_GET['hapus'];
    $_SESSION['keranjang'] = array_diff($_SESSION['keranjang'], [$hapus_id]);
    header("Location: keranjang.php");
    exit;
}

// Hapus semua
if (isset($_GET['hapus_semua'])) {
    $_SESSION['keranjang'] = [];
    header("Location: keranjang.php");
    exit;
}

$items = [];
$total_harga = 0;

if (!empty($_SESSION['keranjang'])) {
    $ids = implode(',', array_map('intval', $_SESSION['keranjang']));
    $res = mysqli_query($conn, "SELECT * FROM kostum WHERE id IN ($ids)");
    while ($row = mysqli_fetch_assoc($res)) {
        $items[] = $row;
        $total_harga += $row['harga'];
    }
}

$pesanan_sukses = false;
$jumlah_dipesan = 0;

// Proses Checkout
if (isset($_POST['pesan_semua']) && !empty($items)) {
    $nama   = mysqli_real_escape_string($conn, $_POST['nama_pemesan']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $no_hp  = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $tgl_pakai = $_POST['tanggal_pemakaian'];
    $tgl_ambil = $_POST['tanggal_pengambilan'];

    $insert = mysqli_query($conn, "INSERT INTO pesanan (nama_pemesan, alamat, no_hp, tanggal_pemakaian, tanggal_pengambilan, total_harga) 
                                   VALUES ('$nama', '$alamat', '$no_hp', '$tgl_pakai', '$tgl_ambil', '$total_harga')");
    
    if ($insert) {
        $pesanan_id = mysqli_insert_id($conn);
        foreach ($items as $item) {
            $kid = $item['id'];
            $hrg = $item['harga'];
            mysqli_query($conn, "INSERT INTO detail_pesanan (pesanan_id, kostum_id, harga_satuan) VALUES ($pesanan_id, $kid, $hrg)");
            // Otomatis ubah status kostum menjadi Dalam Antrian agar tidak dipesan ganda
            mysqli_query($conn, "UPDATE kostum SET status = 'Dalam Antrian' WHERE id = $kid");
        }
        $jumlah_dipesan = count($items);
        $_SESSION['keranjang'] = [];
        $pesanan_sukses = true;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Saya - Sewa Kostum</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="app-container" style="max-width: 600px;">
    <a href="katalog.php" class="btn-back">← keranjang saya</a>

    <div class="card-panel">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">
            <h3>Daftar Kostum</h3>
            <?php if (!empty($items)): ?>
                <a href="keranjang.php?hapus_semua=1" style="color:#BA1833; font-size:0.85rem; text-decoration:none; font-weight:600;">🗑️ Hapus Semua</a>
            <?php endif; ?>
        </div>

        <?php if (empty($items) && !$pesanan_sukses): ?>
            <p style="text-align:center; color:#64748B; padding:20px 0;">Keranjang masih kosong. Silakan pilih kostum di katalog.</p>
        <?php else: ?>
            <?php foreach ($items as $item): ?>
            <div style="display:flex; align-items:center; justify-content:space-between; background:#F8FAFC; padding:10px; border-radius:14px; margin-bottom:10px;">
                <div style="display:flex; align-items:center; gap:12px;">
                    <img src="../assets/uploads/<?= htmlspecialchars($item['gambar']) ?>" onerror="this.src='../assets/img/bg-maskot.jpg'" style="width:55px; height:55px; border-radius:10px; object-fit:cover;">
                    <div>
                        <div style="font-weight:700;"><?= htmlspecialchars($item['nama_kostum']) ?></div>
                        <div style="color:var(--teal-dark); font-weight:700; font-size:0.9rem;"><?= formatRupiah($item['harga']) ?></div>
                    </div>
                </div>
                <a href="keranjang.php?hapus=<?= $item['id'] ?>" style="text-decoration:none; font-size:1.2rem;">🗑️</a>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php if (!empty($items)): ?>
    <form method="POST" class="card-panel">
        <h3 style="margin-bottom: 14px;">👤 Data Pemesan</h3>
        <div class="form-group">
            <label>Nama Pemesan</label>
            <input type="text" name="nama_pemesan" class="form-control" placeholder="Masukkan nama lengkap" required>
        </div>
        <div class="form-group">
            <label>Alamat Pemesan</label>
            <input type="text" name="alamat" class="form-control" placeholder="Masukkan alamat lengkap" required>
        </div>
        <div class="form-group">
            <label>No. Telp / WhatsApp</label>
            <input type="text" name="no_hp" class="form-control" placeholder="Contoh: 081234567890" required>
        </div>
        <div class="form-group">
            <label>Tanggal Pengambilan</label>
            <input type="date" name="tanggal_pengambilan" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Tanggal Pemakaian</label>
            <input type="date" name="tanggal_pemakaian" class="form-control" required>
        </div>

        <div style="display:flex; justify-content:space-between; align-items:center; background:#E2E8F0; padding:12px 16px; border-radius:12px; margin:16px 0; font-weight:800;">
            <span>TOTAL HARGA</span>
            <span><?= formatRupiah($total_harga) ?></span>
        </div>

        <button type="submit" name="pesan_semua" class="btn-primary" style="width:100%; font-size:1rem;">
            PESAN SEMUA
        </button>
    </form>
    <?php endif; ?>
</div>

<?php if ($pesanan_sukses): ?>
<div class="modal-overlay">
    <div class="modal-box">
        <div style="width:65px; height:65px; background:#22C55E; color:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:2rem; margin:0 auto 14px;">✓</div>
        <h2 style="font-size:1.3rem; margin-bottom:6px;">PESANAN BERHASIL</h2>
        <h3 style="font-size:1.1rem; color:#1E293B; margin-bottom:12px;"><?= $jumlah_dipesan ?> KOSTUM DI PESAN</h3>
        <p style="font-size:0.82rem; color:#475569; margin-bottom:10px;">
            UNTUK MEMPERCEPAT PROSES KONFIRMASI SILAHKAN HUBUNGI NOMOR DI BAWAH INI
        </p>
        <div style="font-size:1.25rem; font-weight:800; color:#64748B; letter-spacing:1px; margin-bottom:20px;">
            085348535839758
        </div>
        <a href="riwayat.php" class="btn-primary" style="display:inline-block; text-decoration:none;">Lihat Pesanan Saya</a>
    </div>
</div>
<?php endif; ?>

</body>
</html>