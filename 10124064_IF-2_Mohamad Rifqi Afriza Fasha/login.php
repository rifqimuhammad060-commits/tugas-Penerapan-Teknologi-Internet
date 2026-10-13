<?php
session_start();
require 'koneksi.php';

// Pengaturan Bahasa (Dual Language) via Session
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'id';

// Kamus Bahasa Sederhana
$text = [
    'id' => [
        'title' => 'Login Sistem - Manajemen Mahasiswa',
        'heading' => 'Silakan Login',
        'user' => 'Username',
        'pass' => 'Password',
        'btn' => 'Masuk',
        'error' => 'Username atau Password salah!',
        'switch' => 'English'
    ],
    'en' => [
        'title' => 'System Login - Student Management',
        'heading' => 'Please Login',
        'user' => 'Username',
        'pass' => 'Password',
        'btn' => 'Login',
        'error' => 'Invalid Username or Password!',
        'switch' => 'Indonesia'
    ]
];

$t = $text[$lang];

// Proses Login ketika tombol diklik
$error_msg = "";
if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Menggunakan Prepared Statement PDO untuk keamanan (mencegah SQL Injection)
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['login'] = true;
        $_SESSION['username'] = $user['username'];
        header("Location: index.php");
        exit;
    } else {
        $error_msg = $t['error'];
    }
}
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $t['title'] ?></title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0px 4px 10px rgba(0,0,0,0.1); width: 350px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        button { width: 100%; padding: 10px; background-color: #007BFF; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        .lang-switch { text-align: right; margin-bottom: 15px; font-size: 13px; }
        .lang-switch a { color: #007BFF; text-decoration: none; font-weight: bold; }
        .error { color: red; font-size: 13px; margin-bottom: 15px; text-align: center; }
    </style>
</head>
<body>

<div class="login-card">
    <div class="lang-switch">
        Language: 
        <?php if ($lang == 'id'): ?>
            <a href="login.php?lang=en">English</a>
        <?php else: ?>
            <a href="login.php?lang=id">Indonesia</a>
        <?php endif; ?>
    </div>

    <h2 style="text-align: center; margin-top: 0;"><?= $t['heading'] ?></h2>

    <?php if (!empty($error_msg)): ?>
        <div class="error"><?= $error_msg ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label><?= $t['user'] ?>:</label>
            <input type="text" name="username" required>
        </div>
        <div class="form-group">
            <label><?= $t['pass'] ?>:</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit" name="login"><?= $t['btn'] ?></button>
    </form>
</div>

</body>
</html>