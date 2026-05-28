<?php 
session_start();
require_once '../auth.php';
require_once '../db.php';


$id = (int)($_GET['id'] ?? 0);
if ($id <= 0){
    header('Location: listele.php');
    exit;
}

$mesaj = '';
$hata ='';

$stmt = $pdo->prepare("SELECT * FROM yoneticiler WHERE id = :id");
$stmt -> execute([
    ':id' => $id
]);

$yoneticiler = $stmt->fetchAll();
foreach ($yoneticiler as $yonetici ) {
    # code...
}

$email = $yonetici['email']; //Mevcut Email Adresi
$password = $yonetici['sifre'] ; //Mevcut Parola
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    
    // echo "Yönetici Email: ===> " . $email . "<br>" . "Yonetici Parola ===> " . $password;

    $guncelEmail = trim($_POST['email'] ?? ''); //Güncellenecek mail adresi
    $guncelParola1 = trim($_POST['parola1'] ?? ''); //Güncellenecek parola1
    $guncelParola2 = trim($_POST['parola2'] ?? ''); //Güncellenecek parola2

    //İşlem kontrolü
    if ($_POST['action'] === 'update_password'){
        //Alanların doluluk kontrolü
        if(empty($guncelParola1) || empty($guncelParola2)){
            $hata = 'Parola alanlarını doldurunuz!';
        }
        else if ($guncelParola1 != $guncelParola2){
            $hata  = 'Parolalar aynı olmak zorundadır!';
        }
        else{
            try {
                //code...
                $sifre = password_hash($guncelParola1, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare("UPDATE yoneticiler SET sifre = :sifre WHERE id = :id");
                $stmt->execute([
                    ':sifre' => $sifre,
                    ':id' => $id
                ]);
                $mesaj = 'Parola başarıyla güncellendi!';

            } catch (PDOException $e) {
                //throw $th;
                // $hata = 'Bilinmeyen bir hata oluştu! <br>';
                $hata = 'Bir hata oluştu: <br>' . $e;
            }
        }
    }
    else if(empty($email)){
        $hata = 'Email alanını doldurunuz!';
    }
    else if ($guncelParola1 != $guncelParola2){
        $hata = 'Parolalar aynı olmak zorundadır.';
    }
    else{
        
        $mesaj = 'Parolalar Eşleşdi!';

        $emailSorgu = $pdo->prepare("SELECT id FROM yoneticiler WHERE email = ?");
        $emailSorgu->execute([$guncelEmail]);
        $emailSonuc = $emailSorgu->fetch();

        if ($emailSorgu -> rowCount() > 0 && $emailSonuc['id'] != $id){
            $hata = 'Bu email sistemde zaten kayıtlı!';
        }
        else{
            try {
                //code...
                $stmt = $pdo->prepare(
                    "UPDATE yoneticiler SET email = :email WHERE id = :id"
                );
                $stmt->execute([
                    ':email' => $guncelEmail,
                    ':id' => $id
                ]);
                $mesaj = 'Yönetici Bilgisi Güncellendi';
                $email = $guncelEmail;
            } catch (PDOException $e) {
                //throw $th;
                // $hata = 'Bilinmeyen bir hata oluştu! <br>';
                $hata = 'Bir hata oluştu: <br>' . $e;
            }
        }
    }
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yönetici Güncelleme</title>
</head>
<style>
    body{
        max-width: 500px;
        margin: 40px auto;
        background-color: #1995;
        padding: 0 20px;
    }
    input{
        width: 100%;
        padding: 10px;
        margin: 8px 0;
        border-radius: 10px;
    }
    button{ 
        padding: 11px 85px; 
        background: #ffc107; 
        color: black; 
        border: none; 
        border-radius: 10px; 
        cursor: pointer; 
    }
    button:hover{
        background-color: #9dff00;
    }
    .don { 
        background: #28a745; 
        color: white; 
        padding: 10px 20px; 
        text-decoration: none; 
        border-radius: 4px; 
        display: inline-block;
        margin-bottom: 15px; 
    }
    .basari {
        color: green; 
    } 
    .hata { 
        color: red;
    }
</style>
<body>
    <h2>Yönetici Güncelle (ID: <?= $id ?>)</h2>
    <a href="listele.php" class="don">Listeye Dön</a>
    <h3><?= $hata ?></h3>
    <h3><?= $mesaj ?></h3>
    <form method="POST">
        <input type="text" placeholder="Email Adresi Giriniz" name="email" value="<?= $email ?>">
        <button type="submit" name="action" value="update_data">Güncelle</button>
        <input type="password" placeholder="Yeni Parolayı Giriniz" name="parola1">
        <input type="password" placeholder="Tekrar Yeni Parolayı Giriniz" name="parola2">
        <button type="submit" name="action" value="update_password">Şifreyi Değiştir</button>
    </form>
</body>
</html>