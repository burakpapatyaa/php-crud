<!-- GİRİŞ SAYFASI -->
<?php
session_start(); //PHP oturumunu başlat (her sayfada olmalı)

if (isset($_SESSION['giris_yapildi'])){
    header('Location: index.php');
    exit;
}

require_once 'db.php'; //Veritabanı bağlantısını dahil et

$hata = '';  //Hata mesajı için boş değişken

//FORM GÖNDERİLDİYSE
if($_SERVER['REQUEST_METHOD'] == "POST"){

    //Kullanıcıdan gelen veriyi al ve temizle
    //trim() başındaki/sonundaki boşlukları sil

    $email = trim($_POST['email'] ?? '');
    $parola = trim($_POST['password'] ?? '');

    if(empty($email) || empty($parola)){
        $hata = 'Kullanıcı adı ve şifre boş bırakılamaz!';
    }
    else{
        // Prepared Statement → SQL Injection'a karşı güvenli sorgu
        // :email → placeholder, gerçek değer aşağıda bağlanır
        //Yönetici veritabanında var mı?
        $stmt = $pdo->prepare("SELECT * FROM yoneticiler WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $yonetici = $stmt->fetch();


        //Kullanıcı bulunduysa parolayı kontrol et
        //password_verify() -> hashlenmiş şifre ile girilen şifreyi karşılaştırır

        if ($yonetici && password_verify($parola, $yonetici['sifre'])){
            //Oturum Değişkenklerini Ayarla

            $_SESSION['giris_yapildi'] = true;
            $_SESSION['yonetici_adi'] = $yonetici['email'];

            header('Location: index.php'); // Ana sayfaya yönlendir
            exit;
        }
        else{
            $hata = "Kullanıcı adı veya parola yanlış!";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>HTML Login Form</title>
    <style>

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: sans-serif;
            line-height: 1.5;
            min-height: 100vh;
            background: #f3f3f3;
            flex-direction: column;
            margin: 0;
        }   
        .main {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            padding: 10px 20px;
            transition: transform 0.2s;
            width: 500px;
            text-align: center;
        }
        h1 {
            color: #4CAF50;
        }
        label {
            display: block;
            width: 100%;
            margin-top: 10px;
            margin-bottom: 5px;
            text-align: left;
            color: #555;
            font-weight: bold;
        }
        input {
            display: block;
            width: 100%;
            margin-bottom: 15px;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            padding: 15px;
            border-radius: 10px;
            margin-top: 15px;
            margin-bottom: 15px;
            border: none;
            color: white;
            cursor: pointer;
            background-color: #4CAF50;
            width: 100%;
            font-size: 16px;
        }
        .hata{
            color: #ff0000;
        }
        .wrap {
            display: flex;
            justify-content: center;
            align-items: center;
        }
</style>
</head>

<body>
    <div class="main">
        <h1>Hoş geldiniz</h1>
        <h3>Kullanıcı Bilgilerini Giriniz</h3>
        <h2 class="hata"><?= $hata ?></h2>

        <form method="POST">
            <label for="first">
                Email Adresi:
            </label>
            <input type="email" name="email" 
                placeholder="Email Adresi giriniz" required>

            <label for="password">
                Parola:
            </label>
            <input type="password" name="password" 
                placeholder="Parola giriniz" required>

            <div class="wrap">
                <button type="submit">
                    Giriş Yap
                </button>
            </div>
        </form>
        
        <p>Hesabınız Yok mu?
            <a href="register.php" style="text-decoration: none;">
                Hesap Oluşturun
            </a>
        </p>
    </div>
</body>

</html>