<?php
//logout.php çıkış işlemi

session_start();

//Tüm oturum verilerini sil

session_destroy();

//Login sayfasına yönlendir
header('Location: login.php');
exit;

?>