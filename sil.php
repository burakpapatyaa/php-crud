<?php 
session_start();

require_once 'auth.php';
require_once 'db.php';

// URL'den id al: guncelle.php?id=5
$id = (int)($_GET['id'] ?? 0); // (int) ile sayıya çevir → güvenlik

if ($id > 0){
    $stmt = $pdo->prepare("DELETE FROM kullanicilar WHERE id = :id");
    $stmt->execute([
        ':id' => $id
    ]);
}

//İşlem sonrası listeleye dön
header('Location: listele.php');
exit;
  
?>
