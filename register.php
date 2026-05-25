<?php
session_start();

require_once 'db.php';
$hata = '';
$mesaj = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = trim($_POST['email'] ?? '');
    $parola = trim($_POST['password'] ?? '');

    //Yönetici veritabanında kayıtlı mı kontrolü
    $stmt = $pdo->prepare("SELECT * FROM yoneticiler WHERE email = :email AND sifre = :sifre");
    $stmt->execute([
        ':email' => $email,
        ':sifre' => $parola
    ]);
    $yonetici = $stmt->fetch();
    if ($yonetici){
        $hata = "Bu hesap zaten kayıtlı!";
    }
    else{
        try {
            //code...
            $hash_sifre = password_hash($parola, PASSWORD_BCRYPT);

            $stmt = $pdo->prepare("INSERT INTO yoneticiler (email, sifre) VALUES (:email, :sifre)");
            $stmt -> execute([
                ':email' => $email,
                ':sifre' => $hash_sifre
            ]);
            echo "Başarılı";
            $mesaj = 'Kullanıcı başarıyla eklendi!';
            // header('Location: login.php');


            $mesaj = $hata;
        } 
        catch (PDOException $e) {
            $hata = 'Bilinmeyen bir hata oluştu!' . '<br>' . 'Hata:  ' . $e;
            // $hata = 'Bilinmeyen bir hata oluştu!';
        }
        
    }
}
else{
    $hata = 'Bağlantı başarısız';
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol</title>
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
        .wrap {
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body>  
    <div class="main">
        <h1>Aramıza Katıl!</h1>
        <h3>Kayıt Bilgilerini Giriniz</h3>
        <form method="POST">
            <h2 style="color: red;"><?= $mesaj ?></h2>
            <label for="first">
                Email Adresi:
            </label>
            <input type="email" name="email" 
                placeholder="Kullanıcı adı giriniz" required>

            <label for="password">
                Parola:
            </label>
            <input type="password" name="password" 
                placeholder="Parola giriniz" required>

            <div class="wrap">
                <button type="submit">
                    Kayıt Ol
                </button>
            </div>
        </form>
        
        <p>Hesabınız Var mı?
            <a href="login.php" style="text-decoration: none;">
                Giriş Yapın
            </a>
        </p>
    </div>
</body>
</html>