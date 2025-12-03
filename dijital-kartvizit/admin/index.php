<?php
session_start();

// GÜVENLİK
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

require '../config/db.php';

// Verileri Çek
$stmt = $db->query("SELECT * FROM settings WHERE id = 1");
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// QR Linki
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$site_url = $protocol . "://" . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['PHP_SELF']));
$qr_api_url = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($site_url);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Paneli | Minimal</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        gray: {
                            50: '#F9FAFB',
                            100: '#F3F4F6',
                            200: '#E5E7EB',
                            900: '#111827',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased">

    <div id="mobile-menu-overlay" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-20 hidden" onclick="toggleSidebar()"></div>

    <div class="flex h-screen overflow-hidden">

        <aside id="sidebar" class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r border-gray-200 transform -translate-x-full lg:translate-x-0 transition-transform duration-200 ease-in-out flex flex-col">
            <div class="h-16 flex items-center px-6 border-b border-gray-100">
                <i class="fas fa-cube text-gray-900 text-lg mr-3"></i>
                <span class="text-lg font-semibold tracking-tight">AdminPanel</span>
            </div>

            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                <a href="#" class="flex items-center px-3 py-2 text-sm font-medium bg-gray-100 text-gray-900 rounded-md group">
                    <i class="fas fa-chart-line w-5 h-5 text-gray-500 group-hover:text-gray-900 mr-3"></i>
                    Genel Bakış
                </a>
                <a href="<?php echo $site_url; ?>" target="_blank" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-md hover:bg-gray-50 hover:text-gray-900 group">
                    <i class="fas fa-external-link-alt w-5 h-5 text-gray-400 group-hover:text-gray-500 mr-3"></i>
                    Siteyi Görüntüle
                </a>
                <div class="pt-4 mt-4 border-t border-gray-100">
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Hesap</p>
                    <a href="logout.php" class="flex items-center px-3 py-2 text-sm font-medium text-red-600 rounded-md hover:bg-red-50 group">
                        <i class="fas fa-sign-out-alt w-5 h-5 text-red-400 group-hover:text-red-500 mr-3"></i>
                        Çıkış Yap
                    </a>
                </div>
            </nav>

            <div class="border-t border-gray-100 p-4">
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded bg-gray-200 flex items-center justify-center text-gray-500 font-bold text-xs">A</div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Yönetici</p>
                        <p class="text-xs text-gray-500">admin@panel.com</p>
                    </div>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col lg:pl-64 w-0 min-w-0">
            
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 sm:px-6 lg:px-8">
                <button class="lg:hidden text-gray-500 hover:text-gray-700 focus:outline-none" onclick="toggleSidebar()">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div class="flex-1 flex justify-between items-center">
                    <h1 class="text-lg font-medium text-gray-900 ml-4 lg:ml-0">Dashboard</h1>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-500 hidden sm:inline-block"><?php echo date("d M Y"); ?></span>
                        <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                            <i class="far fa-bell"></i>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto bg-gray-50 p-4 sm:p-8">
                <div class="max-w-6xl mx-auto space-y-6">

                    <?php if (isset($_GET['status']) && $_GET['status'] == 'ok'): ?>
                        <div class="rounded-md bg-green-50 p-4 border border-green-200">
                            <div class="flex">
                                <div class="flex-shrink-0"><i class="fas fa-check-circle text-green-400"></i></div>
                                <div class="ml-3"><p class="text-sm font-medium text-green-800">Başarılı: Bilgiler güncellendi.</p></div>
                            </div>
                        </div>
                    <?php endif; ?>

                    

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        
                        <div class="lg:col-span-2">
                            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                                <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Kart Bilgilerini Düzenle</h3>
                                    <p class="mt-1 text-sm text-gray-500">Lütfen linkleri "https://" ile başlayacak şekilde giriniz.</p>
                                </div>
                                <div class="px-4 py-5 sm:p-6">
                                    <form action="update.php" method="POST" enctype="multipart/form-data">
                                        
                                        <div class="mb-6 flex items-center space-x-6">
                                            <div class="flex-shrink-0">
                                                <?php if($row['photo_path']): ?>
                                                    <img class="h-16 w-16 rounded-full object-cover border border-gray-200" src="../assets/images/<?php echo $row['photo_path']; ?>" alt="Profil">
                                                <?php else: ?>
                                                    <span class="h-16 w-16 rounded-full bg-gray-100 flex items-center justify-center text-gray-400"><i class="fas fa-camera fa-lg"></i></span>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Fotoğraf Yükle</label>
                                                <input type="file" name="photo" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-6 gap-6">
                                            <div class="col-span-6 sm:col-span-3">
                                                <label class="block text-sm font-medium text-gray-700">Ad Soyad</label>
                                                <input type="text" name="full_name" value="<?php echo htmlspecialchars($row['full_name']); ?>" class="mt-1 focus:ring-black focus:border-black block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            </div>
                                            <div class="col-span-6 sm:col-span-3">
                                                <label class="block text-sm font-medium text-gray-700">Ünvan</label>
                                                <input type="text" name="title" value="<?php echo htmlspecialchars($row['title']); ?>" class="mt-1 focus:ring-black focus:border-black block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            </div>
                                            <div class="col-span-6">
                                                <label class="block text-sm font-medium text-gray-700">Biyografi</label>
                                                <textarea name="bio" rows="3" class="mt-1 focus:ring-black focus:border-black block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"><?php echo htmlspecialchars($row['bio']); ?></textarea>
                                            </div>

                                            <div class="col-span-6"><div class="border-t border-gray-100 my-2"></div></div>
                                            
                                            <div class="col-span-6 sm:col-span-3">
                                                <label class="block text-sm font-medium text-gray-700">Telefon</label>
                                                <div class="mt-1 relative rounded-md shadow-sm">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <i class="fas fa-phone text-gray-400"></i>
                                                    </div>
                                                    <input type="text" name="phone" value="<?php echo htmlspecialchars($row['phone']); ?>" class="focus:ring-black focus:border-black block w-full pl-10 sm:text-sm border-gray-300 rounded-md" placeholder="0555...">
                                                </div>
                                            </div>

                                            <div class="col-span-6 sm:col-span-3">
                                                <label class="block text-sm font-medium text-gray-700">E-Posta</label>
                                                <div class="mt-1 relative rounded-md shadow-sm">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <i class="fas fa-envelope text-gray-400"></i>
                                                    </div>
                                                    <input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" class="focus:ring-black focus:border-black block w-full pl-10 sm:text-sm border-gray-300 rounded-md">
                                                </div>
                                            </div>

                                            <div class="col-span-6 sm:col-span-3">
                                                <label class="block text-sm font-medium text-gray-700">WhatsApp</label>
                                                <div class="mt-1 relative rounded-md shadow-sm">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <i class="fab fa-whatsapp text-gray-400"></i>
                                                    </div>
                                                    <input type="text" name="whatsapp" value="<?php echo htmlspecialchars($row['whatsapp']); ?>" class="focus:ring-black focus:border-black block w-full pl-10 sm:text-sm border-gray-300 rounded-md" placeholder="905xxxxxxxxx">
                                                </div>
                                            </div>
                                            
                                            <div class="col-span-6 sm:col-span-3">
                                                <label class="block text-sm font-medium text-gray-700">Instagram Linki</label>
                                                <div class="mt-1 relative rounded-md shadow-sm">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <i class="fab fa-instagram text-gray-400"></i>
                                                    </div>
                                                    <input type="text" name="instagram" value="<?php echo htmlspecialchars($row['instagram']); ?>" class="focus:ring-black focus:border-black block w-full pl-10 sm:text-sm border-gray-300 rounded-md" placeholder="https://instagram.com/kullaniciadi">
                                                </div>
                                                <p class="mt-1 text-xs text-gray-400">Tam link giriniz (https:// ile başlayan).</p>
                                            </div>

                                            <div class="col-span-6 sm:col-span-3">
                                                <label class="block text-sm font-medium text-gray-700">LinkedIn Linki</label>
                                                <div class="mt-1 relative rounded-md shadow-sm">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <i class="fab fa-linkedin text-gray-400"></i>
                                                    </div>
                                                    <input type="text" name="linkedin" value="<?php echo htmlspecialchars($row['linkedin']); ?>" class="focus:ring-black focus:border-black block w-full pl-10 sm:text-sm border-gray-300 rounded-md" placeholder="https://linkedin.com/in/kullanici">
                                                </div>
                                                 <p class="mt-1 text-xs text-gray-400">Tam link giriniz.</p>
                                            </div>
                                        </div>

                                        <div class="mt-6 flex justify-end">
                                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-colors">
                                                Değişiklikleri Kaydet
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-1">
                            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                                <div class="px-4 py-5 sm:p-6 text-center">
                                    <h3 class="text-base font-medium text-gray-900 mb-4">QR Kodunuz</h3>
                                    <div class="bg-gray-50 p-2 rounded-lg inline-block border border-gray-100 mb-4">
                                        <img src="<?php echo $qr_api_url; ?>" alt="QR" class="w-32 h-32">
                                    </div>
                                    <a href="<?php echo $qr_api_url; ?>" download="qr-code.png" class="block w-full py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 text-center">
                                        İndir
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-menu-overlay');
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        }
    </script>
</body>
</html>