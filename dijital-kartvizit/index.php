<?php
require 'config/db.php';

// Veri tabanından bilgileri çekelim (Mevcut yapıyı koruyoruz)
try {
    $stmt = $db->query("SELECT * FROM settings WHERE id = 1");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $row = [];
}

// Sosyal medya kontrolü
$has_insta = !empty($row['instagram']);
$has_linkedin = !empty($row['linkedin']);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($row['full_name'] ?? 'Neo Kart'); ?></title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="bento-container">
        
        <div class="card profile-card span-2">
            <div class="avatar">
                <?php if(!empty($row['photo_path'])): ?>
                    <img src="assets/images/<?php echo $row['photo_path']; ?>" alt="Profil">
                <?php else: ?>
                    <img src="https://via.placeholder.com/150" alt="Profil">
                <?php endif; ?>
            </div>
            <div class="profile-info">
                <h1><?php echo htmlspecialchars($row['full_name']); ?></h1>
                <span class="badge"><?php echo htmlspecialchars($row['title']); ?></span>
            </div>
        </div>

        <div class="card bio-card span-2">
            <p><?php echo nl2br(htmlspecialchars($row['bio'])); ?></p>
        </div>

        <?php if(!empty($row['whatsapp'])): ?>
        <a href="https://wa.me/<?php echo $row['whatsapp']; ?>" class="card action-card whatsapp">
            <div class="icon-box"><i class="fab fa-whatsapp"></i></div>
            <span>WhatsApp</span>
        </a>
        <?php endif; ?>

        <?php if(!empty($row['email'])): ?>
        <a href="mailto:<?php echo $row['email']; ?>" class="card action-card mail">
            <div class="icon-box"><i class="fas fa-envelope"></i></div>
            <span>E-Posta</span>
        </a>
        <?php endif; ?>

        <?php if(!empty($row['phone'])): ?>
        <a href="tel:<?php echo $row['phone']; ?>" class="card action-card phone">
            <div class="icon-box"><i class="fas fa-phone"></i></div>
            <span>Ara</span>
        </a>
        <?php endif; ?>

        <?php if(!empty($row['website'])): ?>
        <a href="<?php echo $row['website']; ?>" target="_blank" class="card action-card website">
            <div class="icon-box"><i class="fas fa-globe"></i></div>
            <span>Website</span>
        </a>
        <?php endif; ?>

        <?php if($has_insta || $has_linkedin): ?>
        <div class="card social-card span-2">
            <span class="label">Takip Et</span>
            <div class="social-icons">
                <?php if($has_insta): ?>
                    <a href="<?php echo $row['instagram']; ?>" target="_blank" class="soc-btn"><i class="fab fa-instagram"></i></a>
                <?php endif; ?>
                <?php if($has_linkedin): ?>
                    <a href="<?php echo $row['linkedin']; ?>" target="_blank" class="soc-btn"><i class="fab fa-linkedin-in"></i></a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <a href="functions/vcard.php" class="card cta-card span-2">
            <span>Rehbere Kaydet</span>
            <i class="fas fa-save"></i>
        </a>

    </div>

</body>
</html>