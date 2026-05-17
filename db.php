<!-- VERİTABANI BAĞLANTISI -->
<?php
$host = 'localhost';            //MYSQL Sunucu Adresi
$db_name = 'proje_deneme';      //Veritabanı Adı
$username = 'root';             //MYSQL Kullanıcı Adı
$password = '';                 //Veritabanı Parolası
$charset = 'utf8mb4';           //Veritabanı charset (Türkçe Karakterler için utf8mb şart!)


try {
    //PDO bağlantısı oluştur
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db_name;charset=$charset",
        $username,
        $password
    );
    //Hata modunu Exception yap, hata olursa try-catch yakalar
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Sonuçları otomatik PHP dizisi olarak al
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);



} catch (PDOException $e) {
    //Gerçek projede bu hatayı kullanıcıya gösterme!
    echo("Veritabanı bağlantı hatası! " . $e->getMessage());
}

?>