<?php
session_start();
require_once 'auth.php';
require_once 'db.php';



// URL'den id al: guncelle.php?id=5
$id = (int)($_GET['id'] ?? 0); // (int) ile sayıya çevir → güvenlik

if ($id <= 0){
    header('Location: listele.php');
    exit;
}

// Önce mevcut kaydı çek (formu doldurmak için)
$stmt = $pdo->prepare("SELECT * FROM kullanicilar WHERE id = :id");
$stmt->execute([':id' => $id]);
$kullanici = $stmt->fetch();


// Kayıt bulunamadıysa listeye dön
if (!$kullanici){
    header('Location: listele.php');
    exit;
}
$id = $kullanici['id'];
$ad = $kullanici['ad'];
$soyad = $kullanici['soyad'];
$email = $kullanici['email'];
$telefon = $kullanici['telefon'];
// echo "Mevcut Bilgiler " . "<br>" . "ID ====> {$id} " . "<br>" . "AD ====> {$ad} " . "<br>" . "SOYAD ====> {$soyad} " . "<br>" . "EMAİL ====> {$email} " . "<br>" . "TELEFON ====> {$telefon} " . "<br>" ;
echo 
"<table border='2'> ".
    "<tr>".
        "<td>" 
            ."<b> ID </b>".
        "</td>" 

        . "<td>" 
            ."<b> AD </b>". 
        "</td>".

        "<td>"
            ."<b> SOYAD </b>". 
        "</td>".

        "<td>" 
            ."<b> EMAİL </b>". 
        "</td>".

        "<td>" 
            ."<b> TELEFON </b>". 
        "</td>".
    "</tr>".

    "<tr>" .
        "<td>" 
            . "{$id}" .
        "</td>" 

        . "<td>" 
            . "{$ad}" . 
        "</td>".

        "<td>"
            . "{$soyad}" . 
        "</td>".

        "<td>" 
            . "{$email}" . 
        "</td>".

        "<td>" 
            ."{$telefon}" . 
        "</td>".
    "</tr>".

    
"</table>";


$mesaj = '';
$hata = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $guncel_ad = trim($_POST['ad'] ?? '');
    $guncel_soyad = trim($_POST['soyad'] ?? '');
    $guncel_email = trim($_POST['email'] ?? '');
    $guncel_telefon = trim($_POST['telefon'] ?? '');
    
    // 2. E-posta Sistemde Kayıtlı mı Kontrolü
    $emailSorgu = $pdo->prepare("SELECT id FROM kullanicilar WHERE email = ?");
    $emailSorgu->execute([$guncel_email]);
    $emailSonuc = $emailSorgu->fetch();

    // 3. Telefon Numarası Sistemde Kayıtlı mı Kontrolü     
    $telefonSorgu = $pdo->prepare("SELECT id FROM kullanicilar WHERE telefon = ?");
    $telefonSorgu->execute([$guncel_telefon]);
    $telefonSonuc = $telefonSorgu->fetch();

 

    if(empty($guncel_ad) || empty($guncel_soyad) || empty($guncel_email)){
        $hata = 'Ad, soyad ve email zorunludur!';
    }

    elseif(!filter_var($guncel_email, FILTER_VALIDATE_EMAIL)){
        $hata = 'Geçersiz email formatı';
    }

    //Eğer bu email sistemde kayıtlı VE ID'si formdan gelen ID değilse
    elseif ($emailSorgu -> rowCount() > 0 && $emailSonuc['id'] != $id){
        $hata = 'Bu email sistemde zaten kayıtlı!';
    }

    //Eğer bu telefon sistemde kayıtlı VE id numarası formdan gelen ID değilse
    else if($telefonSorgu -> rowCount() > 0 && $telefonSonuc['id'] != $id){
        $hata = 'Bu telefon numarası sistemde zaten kayıtlı';
    }

    else{
        try {
            $stmt= $pdo ->prepare(
                "UPDATE kullanicilar 
                SET ad = :ad, soyad = :soyad, email = :email, telefon = :telefon 
                WHERE id = :id"
                );
            $stmt->execute([
                ':ad' => $guncel_ad,
                ':soyad' => $guncel_soyad,
                ':email' => $guncel_email,
                ':telefon' => $guncel_telefon,
                ':id' => $id
            ]);
            $mesaj = "Kullanıcı güncellendi!";
            //Güncel veriyi tekrar çek
            $kullanici = ['ad'=> $guncel_ad, 'soyad'=> $guncel_soyad, 'email'=>$guncel_email, 'telefon'=>$guncel_telefon ];
        } catch (PDOException $e) {
            $hata = 'Güncelleme hatası; Bu email başka bir kullanıcıda kayıtlı olabilir.';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Güncelle</title>
    <style>
        body { font-family: Arial; max-width: 500px; margin: 40px auto; padding: 0 20px; }
        input { width: 100%; padding: 10px; margin: 8px 0; 
                border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { padding: 10px 25px; background: #ffc107; color: black; 
                 border: none; border-radius: 4px; cursor: pointer; }
        .basari { color: green; } .hata { color: red; }
        .don { background: #28a745; color: white; padding: 10px 20px; text-decoration: none; 
        border-radius: 4px; display: inline-block;margin-bottom: 15px; }
    </style>
</head>
<body>
    <h2>Kullanıcı Güncelle (ID: <?= $id ?>)</h2>
    <a href="listele.php" class="don">← Listeye Dön</a>

    <?php if ($mesaj): ?><p class="basari"><?= htmlspecialchars($mesaj) ?></p><?php endif; ?>
    <?php if($hata): ?><p class="hata"><?= htmlspecialchars($hata) ?></p> <?php endif; ?>

    <form method="POST">
        <input type="text" name="ad" value="<?= htmlspecialchars($kullanici['ad']) ?>">
        <input type="text" name="soyad" value="<?= htmlspecialchars($kullanici['soyad']) ?>">
        <input type="text" name="email" value="<?= htmlspecialchars($kullanici['email']) ?>">
        <input type="text" name="telefon" value="<?= htmlspecialchars($kullanici['telefon']) ?>">
        <button type="submit">Güncelle</button>
    </form>
</body>
</html>