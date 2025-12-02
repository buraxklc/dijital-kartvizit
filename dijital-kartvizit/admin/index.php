<?php
session_start();

// GÜVENLİK KONTROLÜ
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

require '../config/db.php';

// Mevcut ayarları çek
$stmt = $db->query("SELECT * FROM settings WHERE id = 1");
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// Site adresini dinamik olarak bulalım (QR Kod için)
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$site_url = $protocol . "://" . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['PHP_SELF']));
// QR Kod Servisi (API kullanarak hızlıca üretiyoruz)
$qr_api_url = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($site_url);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartvizit Yönetim Paneli</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; margin: 0; padding: 20px; }
        .main-wrapper { display: flex; flex-wrap: wrap; gap: 20px; max-width: 1000px; margin: 0 auto; }
        
        /* Sol taraf: Form */
        .form-container { flex: 2; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
        
        /* Sağ taraf: QR ve Önizleme */
        .preview-container { flex: 1; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); height: fit-content; text-align: center; }

        h2 { margin-top: 0; color: #333; border-bottom: 2px solid #f0f2f5; padding-bottom: 15px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #555; }
        input[type="text"], input[type="email"], textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; transition: 0.3s; }
        input:focus, textarea:focus { border-color: #007bff; outline: none; }
        
        .btn-save { background: #28a745; color: white; padding: 12px; border: none; border-radius: 8px; cursor: pointer; width: 100%; font-size: 16px; font-weight: bold; }
        .btn-save:hover { background: #218838; }
        
        .qr-img { margin: 15px 0; border: 10px solid #f8f9fa; border-radius: 10px; }
        .btn-view { display: block; background: #007bff; color: white; text-decoration: none; padding: 10px; border-radius: 8px; margin-bottom: 10px; }
        .btn-logout { display: block; background: #dc3545; color: white; text-decoration: none; padding: 10px; border-radius: 8px; }
        
        /* Profil resmi önizleme */
        .current-img { width: 80px; height: 80px; object-fit: cover; border-radius: 50%; margin-top: 10px; border: 3px solid #eee; }

        @media (max-width: 768px) {
            .main-wrapper { flex-direction: column-reverse; }
        }
    </style>
</head>
<body>

<div class="main-wrapper">
    
    <div class="form-container">
        <h2><i class="fas fa-edit"></i> Bilgileri Düzenle</h2>
        
        <?php if (isset($_GET['status']) && $_GET['status'] == 'ok'): ?>
            <div style="background: #d4edda; color: #155724; padding: 15px; margin-bottom: 20px; border-radius: 8px;">
                <i class="fas fa-check-circle"></i> Bilgiler güncellendi!
            </div>
        <?php endif; ?>

        <form action="update.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Ad Soyad</label>
                <input type="text" name="full_name" value="<?php echo htmlspecialchars($row['full_name']); ?>" required>
            </div>

            <div class="form-group">
                <label>Ünvan</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($row['title']); ?>">
            </div>

            <div class="form-group">
                <label>Biyografi</label>
                <textarea name="bio" rows="4"><?php echo htmlspecialchars($row['bio']); ?></textarea>
            </div>

            <div class="form-group">
                <label>Profil Fotoğrafı</label>
                <input type="file" name="photo" accept="image/*">
                <?php if($row['photo_path']): ?>
                    <img src="../assets/images/<?php echo $row['photo_path']; ?>" class="current-img">
                <?php endif; ?>
            </div>

            <h3 style="margin-top: 30px; color: #666;">İletişim</h3>
            
            <div class="form-group">
                <label><i class="fas fa-phone"></i> Telefon</label>
                <input type="text" name="phone" value="<?php echo htmlspecialchars($row['phone']); ?>">
            </div>
            
            <div class="form-group">
                <label><i class="fas fa-envelope"></i> Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>">
            </div>

            <div class="form-group">
                <label><i class="fab fa-whatsapp"></i> WhatsApp (Örn: 90555...)</label>
                <input type="text" name="whatsapp" value="<?php echo htmlspecialchars($row['whatsapp']); ?>">
            </div>

            <div class="form-group">
                <label><i class="fab fa-instagram"></i> Instagram Linki</label>
                <input type="text" name="instagram" value="<?php echo htmlspecialchars($row['instagram']); ?>">
            </div>

            <div class="form-group">
                <label><i class="fab fa-linkedin"></i> LinkedIn Linki</label>
                <input type="text" name="linkedin" value="<?php echo htmlspecialchars($row['linkedin']); ?>">
            </div>

            <button type="submit" class="btn-save">Kaydet</button>
        </form>
    </div>

    <div class="preview-container">
        <h3>Senin Kartın</h3>
        <p style="font-size: 14px; color: #666;">Telefonuna okutarak test et</p>
        
        <img src="<?php echo $qr_api_url; ?>" class="qr-img" alt="Kartvizit QR">
        
        <br>
        <a href="<?php echo $site_url; ?>" target="_blank" class="btn-view">
            <i class="fas fa-external-link-alt"></i> Siteyi Görüntüle
        </a>
        
        <a href="logout.php" class="btn-logout">
            <i class="fas fa-sign-out-alt"></i> Çıkış Yap
        </a>
    </div>

</div>

</body>
</html>