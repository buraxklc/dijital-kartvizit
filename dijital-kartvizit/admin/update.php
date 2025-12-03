<?php
session_start();

// GÜVENLİK: Giriş yapmayan işlem yapamaz
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

require '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 1. Metin verilerini al
    $full_name = $_POST['full_name'];
    $title     = $_POST['title'];
    $bio       = $_POST['bio'];
    $phone     = $_POST['phone'];
    $email     = $_POST['email'];
    $whatsapp  = $_POST['whatsapp'];
    $instagram = $_POST['instagram'];
    $linkedin  = $_POST['linkedin'];

    // 2. Önce bilgileri güncelle
    $sql = "UPDATE settings SET full_name=?, title=?, bio=?, phone=?, email=?, whatsapp=?, instagram=?, linkedin=? WHERE id=1";
    $stmt = $db->prepare($sql);
    $update = $stmt->execute([$full_name, $title, $bio, $phone, $email, $whatsapp, $instagram, $linkedin]);

    if (!$update) {
        die("Veritabanı güncelleme hatası!");
    }

    // 3. Fotoğraf Yükleme İşlemi
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['photo']['name'];
        $filetmp = $_FILES['photo']['tmp_name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            // Benzersiz isim oluştur
            $new_name = uniqid() . '.' . $ext;
            $upload_path = '../assets/images/' . $new_name;

            if (move_uploaded_file($filetmp, $upload_path)) {
                // Veritabanındaki resim adını güncelle
                $imgSql = "UPDATE settings SET photo_path=? WHERE id=1";
                $db->prepare($imgSql)->execute([$new_name]);
            }
        }
    }

    // İşlem bitince ana sayfaya geri dön (başarılı parametresiyle)
    header("Location: index.php?status=ok");
    exit;
} else {
    // Post olmadan gelindiyse
    header("Location: index.php");
    exit;
}
?>