<?php
session_start();
require '../config/db.php';

// Zaten giriş yapmışsa panele yönlendir
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: index.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']); // Formdan gelen şifre

    if (empty($username) || empty($password)) {
        $error = "Lütfen alanları doldur.";
    } else {
        $stmt = $db->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin) {
            // --- KRİTİK NOKTA BURASI ---
            // Hem normal şifrelemeyi kontrol et, HEM DE düz metin '123456'yı kabul et.
            if (password_verify($password, $admin['password']) || $password === $admin['password']) {
                
                $_SESSION['admin_logged_in'] = true;
                header("Location: index.php");
                exit;
                
            } else {
                $error = "Şifre Yanlış! (Veritabanındaki: " . $admin['password'] . ")";
            }
        } else {
            $error = "Kullanıcı bulunamadı!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Giriş</title>
    <style>
        body { font-family: sans-serif; background: #eee; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .box { background: #fff; padding: 30px; border-radius: 5px; width: 300px; text-align: center; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #333; color: #fff; border: none; cursor: pointer; }
        .err { color: red; font-size: 14px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Yönetici Girişi</h2>
        <?php if($error): ?><div class="err"><?php echo $error; ?></div><?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Kullanıcı Adı" required>
            <input type="password" name="password" placeholder="Şifre" required>
            <button type="submit">Giriş Yap</button>
        </form>
    </div>
</body>
</html>