<?php
session_start();
require_once '../db.php';

$id = (int) ($_GET['id'] ?? '');
if ($id <= 0){
    header('Location: listele.php');
    exit;
}
else{
    try {
        //code...
        $stmt = $pdo->prepare("DELETE FROM yoneticiler WHERE id = :id");
        $stmt->execute([
            ':id' => $id
        ]);
        $mesaj = 'Yönetici başarıyla silindi!';
    } catch (PDOException $e) {
        //throw $th;
        $hata = 'Bilinmeyen bir hata oluştu!';
        // $hata = 'Bir hata oluştu: <br>' . $e;
    }
    header('Location: listele.php');
    exit;
}
?>