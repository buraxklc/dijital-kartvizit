<?php
require '../config/db.php';
$user = $db->query("SELECT * FROM settings WHERE id = 1")->fetch(PDO::FETCH_ASSOC);

// vCard formatı oluşturma
$vcard = "BEGIN:VCARD\r\n";
$vcard .= "VERSION:3.0\r\n";
$vcard .= "N:" . $user['full_name'] . "\r\n";
$vcard .= "FN:" . $user['full_name'] . "\r\n";
$vcard .= "TITLE:" . $user['title'] . "\r\n";
$vcard .= "TEL;TYPE=cell:" . $user['phone'] . "\r\n";
$vcard .= "EMAIL:" . $user['email'] . "\r\n";
$vcard .= "URL:" . $user['website'] . "\r\n";
$vcard .= "END:VCARD\r\n";

// Dosya olarak indirilmesini sağla
header('Content-Type: text/x-vcard');
header('Content-Disposition: attachment; filename="contact.vcf"');
echo $vcard;
?>