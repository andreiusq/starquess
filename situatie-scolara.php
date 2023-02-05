<?php
define('BASEPATH', true);
session_start();
require('backend/config/db.php');

if(!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
  }

// users logic 2
$stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email"); 
$stmt -> bindParam(':email', $_SESSION['user']);

$stmt->execute(); 


$conturi = $stmt->fetchAll(); 

foreach($conturi as $cont)  

//grades logic

$sql = "SELECT subject_id, grade FROM grades WHERE user_id = $cont[id]";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$grades = $stmt->fetchAll();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preload" href="styles/FontAwesome/css/all.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="stylesheet" href="styles/situatie/all.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.all.min.js"></script>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
</head>
<body>

    <?php include './important/rightbar.php'; ?>
    <?php include './important/sidebar.php'; ?>

    <div class="my-grades-box">
        <div class="my-grades-box-content">
            <h1>Situatie scolara</h1>
            <?php  
            if($grades) {
            foreach ($grades as $grade) { ?>
            <div class="my-grades-box-content-grades">
                <div class="my-grades-box-content-grades-content">
                    <div class="my-grades-box-content-grades-content-subject">
                        <h1><?php if($grade['subject_id'] == 1) { 
                                echo 'Limba Romana'; }
                                else if($grade['subject_id'] == 2) {
                                    echo 'Matematica';
                                } else if($grade['subject_id'] == 3) {
                                    echo 'Limba Incepătoare (1)';
                                } else if ($grade['subject_id'] == 4) {
                                    echo 'Limba Latină';
                                } else if($grade['subject_id'] == 5) {
                                    echo 'Psihologie'; 
                                }?></h1>
                        <h1><?php echo $grade['grade'] ?></h1>
                    </div>
    </div>
    <?php } } else {?> <h1>Nu ai note. </h1> <?php }?>
    <div class="absente-box">
        
    </div>
    
</body>
</html>