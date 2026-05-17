<?php
session_start();
require_once 'auth.php';
require_once 'db.php';


// Tüm kullanıcıları çek — fetchAll() tümünü dizi olarak döndürür
$stmt = $pdo->query("SELECT * FROM kullanicilar");
$kullanicilar = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcı Listesi</title>
    <style>
        
        body { font-family: Arial; max-width: 900px; margin: 40px auto; padding: 0 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background: #f5f5f5; }
        tr:hover { background: #f9f9f9; }
        .btn { padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 0.85rem; }
        .duzenle { background: #ffc107; color: black; }
        .sil { background: #dc3545; color: white; }
        .ekle { background: #28a745; color: white; padding: 10px 20px; 
                text-decoration: none; border-radius: 4px; display: inline-block; margin-bottom: 15px; }
    
        .kaydetme { 
            background: #28a745; 
            color: white; 
            padding: 10px 20px; 
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            margin-bottom: 15px; 
        
        }
        .git{
            background: #ffc107; 
            color: white; 
            padding: 10px 20px; 
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            margin-bottom: 15px; 
        
        }
    </style>
</head>
<body>
    <h2>Kullanıcı Listesi</h2>

    <a href="kaydet.php" class="kaydetme">+ Yeni Ekle</a> 
    <a href="index.php" class="git">Ana Sayfa</a>


    
    <?php if (empty($kullanicilar)): ?>
        <p>Henüz kayıtlı kullanıcı yok!</p>

    <?php else: ?>
        <p>Kayıtlı kullanıcı var!</p>
        <table>
            <tr>    
                <th>ID</th><th>Ad</th><th>Soyad</th>
                <th>Email</th><th>Telefon</th><th>İşlemler</th>
                <?php foreach ($kullanicilar as $k): ?>
            </tr>
            <tr>
                <td><?= $k['id'] ?></td>
                <!-- htmlspecialchars() → XSS saldırısına karşı koruma -->
                <td><?= htmlspecialchars($k['ad']) ?></td>
                <td><?= htmlspecialchars($k['soyad']) ?></td>
                <td><?= htmlspecialchars($k['email']) ?></td>
                <td><?= htmlspecialchars($k['telefon']) ?></td>
                <td>
                    <!-- URL'e id parametresi geçiyoruz -->
                    <a href="guncelle.php?id=<?= $k['id'] ?>" class="btn duzenle">Düzenle</a>
                    <a href="sil.php?id=<?= $k['id'] ?>" class="btn sil" 
                        onclick="return confirm('Emin misiniz?')">🗑️ Sil</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>     
</body>
</html>