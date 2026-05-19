<?php
session_start();
require_once 'auth.php';
require_once 'db.php';



$mesaj = '';
$hata = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $sorgu = $pdo->query("SELECT * FROM kullanicilar");
    $kullanici = $sorgu->fetchAll();
    $ad = trim($_POST['ad'] ?? '');
    $soyad = trim($_POST['soyad'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefon = trim($_POST['telefon'] ?? '');

    //Boşluk Kontrolu
    if(empty($ad) || empty($soyad) || empty($email)){
        $hata = 'Ad, soyad ve email zorunludur!';
    }
    else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        //PHP Yerleşik Email Doğrulama Fonksiyonu
        $hata = 'Geçersiz email formatı!';
    }
    
    else{
        // 2. E-posta Sistemde Kayıtlı mı Kontrolü
        $emailSorgu = $pdo->prepare("SELECT id FROM kullanicilar WHERE email = ?");
        $emailSorgu->execute([$email]);

        // 3. Telefon Numarası Sistemde Kayıtlı mı Kontrolü
        $telefonSorgu = $pdo->prepare("SELECT id FROM kullanicilar WHERE telefon = ?");
        $telefonSorgu->execute([$telefon]);

        

        else{
            try {
                //code...
                $stmt = $pdo->prepare("INSERT INTO kullanicilar (ad, soyad, email, telefon) VALUES (:ad, :soyad, :email, :telefon)");
                $stmt->execute([
                    ':ad' => $ad,
                    ':soyad' => $soyad,
                    ':email' => $email,
                    ':telefon' => $telefon
                ]);
                $mesaj = 'Kullanıcı başarıyla eklendi!';
    
            } catch (PDOException $e) {
                // Duplicate email gibi veritabanı hataları
                $hata = 'Bu email zaten kayıtlı veya bir hata oluştu!' . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcı Ekleme</title>

    <style>
        body { font-family: Arial; max-width: 500px; margin: 40px auto; padding: 0 20px; }
        input { width: 100%; padding: 10px; margin: 8px 0; 
                border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { padding: 10px 25px; background: #28a745; color: white; 
                 border: none; border-radius: 4px; cursor: pointer; }
        .basari { color: green; } .hata { color: red; }
        h2{color: #648343;}
        .ekle { background: #28a745; color: white; padding: 10px 20px; text-decoration: none; 
        border-radius: 4px; display: inline-block;margin-bottom: 15px; }
        .git{background: #ffc107; color: white; padding: 10px 20px; text-decoration: none; 
        
        border-radius: 4px; display: inline-block; margin-bottom: 15px; }
    </style>
</head>
<body>
    <h2>+ Yeni Kullanıcı Ekle</h2>
    <a href="listele.php" class="ekle">Listele</a> |
    <a href="index.php" class="git">Ana Sayfa</a>

    <?php if ($mesaj): ?> 
        <p class="basari"> <?= htmlspecialchars($mesaj) ?>  </p>
    <?php endif; ?>

    <?php if ($hata): ?>
        <p class="hata"> <?= htmlspecialchars($hata) ?> </p>
    <?php endif; ?>

    <form method="POST">

        <input type="text" name= "ad" placeholder="Ad" required>
        <input type="text" name= "soyad" placeholder="Soyad" required>
        <input type="email" name= "email" placeholder="Email" required>
        <input type="text" name= "telefon" placeholder="Telefon" required>
        <button type="submit">Kaydet</button>
    </form>

</body>
</html>