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

// Get the user's rank
$rank_query = "SELECT rank FROM users WHERE id = $cont[id]";
$rank_stmt = $pdo->prepare($rank_query);
$rank_stmt->execute();
$rank = $rank_stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preload" href="styles/FontAwesome/css/all.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="stylesheet" href="styles/clasa-mea/all.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.all.min.js"></script>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
</head>
<body>

<?php include './important/rightbar.php'; ?>
    <?php include './important/sidebar.php'; ?>

<?php   $classes_query = "SELECT class_name FROM classes WHERE teacher_id = $cont[id]";
                $classes_stmt = $pdo->prepare($classes_query);
                $classes_stmt->execute();
                $classes = $classes_stmt->fetchAll(); ?>
                <?php foreach($classes as $class) { ?>

                <div class="class-box">
                    <div class="class-box-content">
                        <div class="class-box-content-left">
                            <div class="class-box-content-left-title">
                                <h3 class="class-box-content-left-title-text"><?php echo $class['class_name'] ?></h3>
                            </div>
                        </div>
                        <div class="class-box-content-right">
                            <div class="class-box-content-right-button">
                                <a href="./clase/<?php echo $class['class_id'] ?>" class="class-box-content-right-button-text">Accesează</a>
                            </div>
                        </div>
                    </div>
                </div>
    <?php } ?>    

</body>
</html>