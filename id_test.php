<?php
require_once 'db.php';

    $ad = "Burak";
    $soyad = "Papatya";
    $email = "burakpapatyaa@gmail.com";
    $telefon = "+905061948485";
    // $ad = 'Burak';
    // $soyad = 'Papatya';
    // $email = 'burakpapatyaa@gmail.com';
    // $telefon = '+905061948485';

    try {
        //code...
        // $idSorgu = $pdo->prepare("SELECT id FROM kullanicilar WHERE ad='{$ad}' AND soyad='{$soyad}' AND email='{$email}' AND telefon='{$telefon}'");
        // $idSorgu = $pdo->prepare("SELECT id FROM kullanicilar WHERE ad = :ad AND soyad = :soyad AND email = :email AND telefon = :telefon");
        
        // $idSorgu->execute([
        //     ':ad' => $ad,
        //     ':soyad' => $soyad,
        //     ':email' => $email,
        //     ':telefon' => $telefon
        //     ]);
        // $sonuc = $idSorgu->fetch();
        // if ($sonuc){
        //     echo "Sonuç: {$sonuc['id']}" ;
        // }
        // else{
        //     echo "Bilinmeyen bir hata";
        // }




        // $emailSorgu = $pdo->prepare("SELECT id FROM kullanicilar WHERE email = ?");
        // $emailSorgu->execute([$email]);
        // $emailSonuc = $emailSorgu->fetch();

        // echo "ID Numara => " . $emailSonuc['id'];


        $emailSorgu = $pdo->prepare("SELECT id FROM kullanicilar WHERE email = ?");
        $emailSorgu->execute([$email]);
        $emailSonuc = $emailSorgu->fetch();

        // 3. Telefon Numarası Sistemde Kayıtlı mı Kontrolü
        $telefonSorgu = $pdo->prepare("SELECT id FROM kullanicilar WHERE telefon = ?");
        $telefonSorgu->execute([$telefon]);
        $telefonSonuc = $telefonSorgu->fetch();

        //Bilgileri (formdan) gelen kullanıcının ID sorgusu
        $idSorgu = $pdo->prepare("SELECT id FROM kullanicilar WHERE ad = :ad AND soyad = :soyad AND email = :email AND telefon = :telefon");
        $idSorgu->execute([
            ':ad' => $ad,
            ':soyad' => $soyad,
            ':email' => $email,
            ':telefon' => $telefon
            ]);
        $idSonuc = $idSorgu->fetch();



        echo "Email Sonuç:   " . $emailSonuc['id'] . "<br>";
        echo "Telefon Sonuç:   " . $telefonSonuc['id'] . "<br>";
        echo "ID Sonuç:   " . $idSonuc['id'];
    } 
    catch (PDOException $e) {
        echo "Hata var => " . $e->getMessage(); 
    }