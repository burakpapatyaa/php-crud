<?php
require_once 'db.php';

if(empty($_SESSION['giris_yapildi'])){
    header('Location: login.php');
    exit;
}
?>