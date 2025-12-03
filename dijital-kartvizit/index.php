<?php
require 'config/db.php';

// Veritabanı işlemleri
try {
    $stmt = $db->query("SELECT * FROM settings WHERE id = 1");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $row = [];
}

$has_insta = !empty($row['instagram']);
$has_linkedin = !empty($row['linkedin']);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($row['full_name'] ?? 'Soft UI Profil'); ?></title>
    
    <!-- Font: Poppins (Yuvarlak hatlı ve modern) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- İkonlar -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Stil Dosyası -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <main class="main-container">
        
        <!-- Ana Neumorphic Kart -->
        <div class="neu-card">
            
            <!-- Profil Fotoğrafı (Dışa çıkık çerçeve içinde) -->
            <div class="profile-section">
                <div class="avatar-wrapper">
                    <?php if(!empty($row['photo_path'])): ?>
                        <img src="assets/images/<?php echo $row['photo_path']; ?>" alt="Avatar" class="avatar">
                    <?php else: ?>
                        <div class="avatar-placeholder"><?php echo substr($row['full_name'], 0, 1); ?></div>
                    <?php endif; ?>
                </div>
                
                <h1 class="name"><?php echo htmlspecialchars($row['full_name']); ?></h1>
                <p class="title"><?php echo htmlspecialchars($row['title']); ?></p>
                
                <div class="divider"></div>
                
                <p class="bio"><?php echo nl2br(htmlspecialchars($row['bio'])); ?></p>
            </div>

            <!-- Aksiyon Butonları (Grid Yapı) -->
            <div class="actions-grid">
                
                <?php if(!empty($row['whatsapp'])): ?>
                <a href="https://wa.me/<?php echo $row['whatsapp']; ?>" class="neu-btn">
                    <i class="fab fa-whatsapp icon"></i>
                    <span>WhatsApp</span>
                </a>
                <?php endif; ?>

                <?php if(!empty($row['phone'])): ?>
                <a href="tel:<?php echo $row['phone']; ?>" class="neu-btn">
                    <i class="fas fa-phone icon"></i>
                    <span>Ara</span>
                </a>
                <?php endif; ?>

                <?php if(!empty($row['email'])): ?>
                <a href="mailto:<?php echo $row['email']; ?>" class="neu-btn">
                    <i class="fas fa-envelope icon"></i>
                    <span>E-Posta</span>
                </a>
                <?php endif; ?>

                <?php if(!empty($row['website'])): ?>
                <a href="<?php echo $row['website']; ?>" target="_blank" class="neu-btn">
                    <i class="fas fa-globe icon"></i>
                    <span>Website</span>
                </a>
                <?php endif; ?>

            </div>

            <!-- Sosyal Medya (Yuvarlak Butonlar) -->
            <?php if($has_insta || $has_linkedin): ?>
            <div class="social-row">
                <?php if($has_insta): ?>
                    <a href="<?php echo $row['instagram']; ?>" target="_blank" class="neu-btn-circle">
                        <i class="fab fa-instagram"></i>
                    </a>
                <?php endif; ?>
                
                <?php if($has_linkedin): ?>
                    <a href="<?php echo $row['linkedin']; ?>" target="_blank" class="neu-btn-circle">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- Alt Buton (VCard) - Daha belirgin -->
            <a href="functions/vcard.php" class="neu-btn-primary">
                <i class="fas fa-download"></i> Rehbere Kaydet
            </a>

        </div>

    </main>

</body>
</html>