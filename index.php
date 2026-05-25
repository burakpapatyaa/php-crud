<!-- ANA SAYFA -->
<?php 
session_start(); //Oturum kontrolü için şart!
require_once 'auth.php';
require_once 'db.php';
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yönetim Paneli</title>
    <style>
        body { font-family: Arial; max-width: 800px; margin: 40px auto; padding: 0 20px; }
        .nav a { display: inline-block; padding: 10px 20px; margin: 5px;
                 background: #007bff; color: white; text-decoration: none; border-radius: 4px; }
        .nav a:hover { background: #0056b3; }
        .cikis { background: #dc3545 !important; }
    </style>
</head>
<body>
    <h1>👋 Hoşgeldin, <?= htmlspecialchars($_SESSION['yonetici_adi']) ?>!</h1>
    <p>Kullanıcı yönetim panelindesin.</p>
    
    <div class="nav">

        <a href="admin/listele.php">📋 Yöneticileri Listele</a>
        <a href="listele.php">📋 Kullanıcıları Listele</a>
        <a href="kaydet.php">➕ Yeni Kullanıcı Ekle</a>
        <a href="logout.php" class="cikis">🚪 Çıkış Yap</a>
    </div>
</body>
</html>
