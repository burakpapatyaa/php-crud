<?php
session_start();
require_once  '../db.php';

$stmt = $pdo -> query("SELECT * FROM yoneticiler");
$yoneticiler = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yöneticiler</title>
    <style>
        body { font-family: Arial; max-width: 900px; margin: 40px auto; padding: 0 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background: #f5f5f5; }
        tr:hover { background: #f9f9f9; }
        .git{
            background: #ffc107; 
            color: white; 
            padding: 10px 20px; 
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            margin-bottom: 15px; 
        
        }
    </style>
</head>

<body>
    <?php if(empty($yoneticiler)): ?>
        <p>Henüz kayıtlı yönetici yok!</p>
    <?php else: ?>
        <a href="../index.php" class="git">Ana Sayfa</a>
        <!-- <p>Kayıtlı yönetici var!</p> -->
         
        <table>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <?php foreach($yoneticiler as $y): ?>
            </tr>
            <tr>
                <td><?=  $y['id'] ?></td>
                <td><?=  htmlspecialchars($y['email']) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

</body>
</html>