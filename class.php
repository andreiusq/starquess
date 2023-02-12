<?php
define('BASEPATH', true);
session_start();
require('backend/config/db.php');
$is_administrator = 0;



if(isset($_GET['class'])) {
    $class_id = $_GET['class'];

    $statement = $pdo->prepare("SELECT * FROM users WHERE class_id = :class_id");
    $statement->execute(array(':class_id' => $class_id));
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard - Classes</title>
    <link rel="preload" href="styles/FontAwesome/css/all.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="stylesheet" href="styles/classes/all.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.all.min.js"></script>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
</head>
<body>
    
    <?php include './important/Rightbar.php'; ?>
    <?php include './important/Sidebar.php'; ?>

 <div class="students-box">
        <div class="students-box-content">
            <h1 class="students-title">Studenti</h1>
            <p class="students-text">Aici poți vedea elevii din clasa ta.</p>
            <div class="students-box-list">
                <div class="students-box-list-item">
                    <div class="students-box-list-item-content">
                        <div class="students-box-list-item-content-left">
                            <div class="students-box-list-item-content-left-img">
                                <img src="https://cdn.discordapp.com/attachments/881100000000000000/881100000000000000/unknown.png" alt="">
                            </div>
                            <?php if (count($results) > 0) {
                                foreach ($results as $row) { { ?>
                            <div class="students-box-list-item-content-left-text">
                                <h1 class="students-box-list-item-content-left-text-name"><?php echo $row['name']; echo ' ';  echo $row['last_name']; ?></h1>
                                <p class="students-box-list-item-content-left-text-email"><?php echo $row['email'] ?></p>
                            </div>
                        </div>
                        <?php } ?>
                        <?php } ?>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>

</body>
</html>