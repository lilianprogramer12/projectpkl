<?php
require '../config/database.php';

if (isset($_SESSION['admin_logged_in'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']);

    $cek = mysqli_query($conn, "SELECT * FROM admin WHERE username='$username' AND password='$password'");
    if (mysqli_num_rows($cek) > 0) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Username atau Password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - SMK Batik Perbaik Purworejo</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="display:flex; justify-content:center; align-items:center; padding:20px;">

<div class="landing-card" style="background:#fff;">
    <img src="../assets/img/logo-smk.png" alt="Logo" class="school-logo">
    <h2 class="school-name" style="margin-bottom:20px;">SMK<br>Batik Perbaik Purworejo</h2>

    <?php if ($error): ?>
        <div style="background:#FEE2E2; color:#991B1B; padding:10px; border-radius:10px; margin-bottom:14px; font-size:0.85rem;">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <form method="POST" style="text-align:left;">
        <div class="form-group">
            <label>Username :</label>
            <input type="text" name="username" class="form-control" placeholder="Masukkan Username" required>
        </div>
        <div class="form-group">
            <label>Password :</label>
            <input type="password" name="password" class="form-control" placeholder="Masukkan Password" required>
        </div>
        <div style="text-align:center; margin-top:22px;">
            <button type="submit" name="login" class="btn-login-admin" style="border:none; cursor:pointer;">LOGIN</button>
        </div>
    </form>

    <div style="margin-top:18px;">
        <a href="../index.php" style="font-size:0.85rem; color:#64748B; text-decoration:none;">← Kembali ke Halaman Awal</a>
    </div>
</div>

</body>
</html>