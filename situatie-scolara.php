<?php
define('BASEPATH', true);
session_start();
require('backend/config/db.php');
$is_administrator = 0;

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

// absences logic
$sql = "SELECT * FROM absences WHERE user_id = $cont[id]";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$absences = $stmt->fetchAll();


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

    <?php include './important/Rightbar.php'; ?>
    <?php include './important/Sidebar.php'; ?>

    <div class="my-grades-box">
        <div class="my-grades-box-content">
            <h1 class="my-grades-box-title">Situatie scolara</h1>
            <br>
            <?php foreach ($grades as $grade) { ?> 
            <div class="my-grades-box-content-grades">
            <div class="my-grades-box-content-grades-content">
                    <div class="my-grades-box-content-grades-content-subject">
                        <h5 class="my-grades-box-subject"><?php if($grade['subject_id'] == 1) { 
                                echo 'Matematică'; }
                                else if($grade['subject_id'] == 2) {
                                    echo 'Limba Română';
                                } else if($grade['subject_id'] == 3) {
                                    echo 'Istorie';
                                } else if ($grade['subject_id'] == 4) {
                                    echo 'Geografie';
                                } else if($grade['subject_id'] == 5) {
                                    echo 'Limba Engleză'; 
                                } else if($grade['subject_id'] == 6) {
                                    echo 'Limba Franceză';
                                } else if($grade['subject_id'] == 7) {
                                    echo 'Limba Germană';
                                } else if($grade['subject_id'] == 8) {
                                    echo 'Biologie';
                                } else if($grade['subject_id'] == 9) {
                                    echo 'Fizică';
                                } else if($grade['subject_id'] == 10) {
                                    echo 'Chimie';
                                } else if($grade['subject_id'] == 11) {
                                    echo 'Informatică';
                                } else if($grade['subject_id'] == 12) {
                                    echo 'Religie';
                                } else if($grade['subject_id'] == 13) {
                                    echo 'Muzică';
                                } else if($grade['subject_id'] == 14) {
                                    echo 'Artă';
                                } else if($grade['subject_id'] == 15) {
                                    echo 'Educație Fizică';
                                } else if($grade['subject_id'] == 16) {
                                    echo 'Psihologie';
                                } else if($grade['subject_id'] == 17) {
                                    echo 'Filosofie';
                                } else if($grade['subject_id'] == 20) {
                                    echo 'Economie';
                                } else if($grade['subject_id'] == 21) {
                                    echo 'Ed. Antreprenoriala';
                                }?></h5>
                        <div class="grades-box">
                            <h3 class="grades"><?php echo $grade['grade'] ?></h5>
                        </div>

                    </div>
    </div>
    <?php } ?>
    <div class="absente-box">
        <h1 class="absente-title">Absențe</h1>
        <?php if($absences) { ?> 
            <?php foreach($absences as $absence) { ?> <br> 
                <?php if($absence['absence_motivat'] == 0) { ?>
                <div class="absences-content-box"> 
                    <p class="absences-date"> <?php echo $absence['date'] ?> </p>
                    <p class="absences-subject"> <?php echo $absence['subject'] ?> </p>
                </div>
                    <?php } ?>
                    <?php if($absence['absence_motivat'] == 1) { ?>
                                            <div class="absences-motivat-content-box"> 
                    <p class="absences-date"> <?php echo $absence['date'] ?> </p>
                    <p class="absences-subject"> <?php echo $absence['subject'] ?> </p>
                </div>
                    <?php } ?>
                <?php } ?>  
        <?php } ?>
        
    </div>
    
</body>
</html>