<?php
define('BASEPATH', true);
session_start();
require('../../backend/config/db.php');

if(!isset($_SESSION["user"])) {
  header("Location: login.php");
  exit();
}

$is_administrator = 1;

// users logic 2
$stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email"); 
$stmt -> bindParam(':email', $_SESSION['user']);

$stmt->execute(); 


$conturi = $stmt->fetchAll(); 

foreach($conturi as $cont);

// count users
$sql = "SELECT COUNT(*) FROM users";
$stmt = $pdo->query($sql);
$userscount = $stmt->fetchColumn();

// count messages
$sql = "SELECT COUNT(*) FROM messages";
$stmt = $pdo->query($sql);
$messagescount = $stmt->fetchColumn();

// count activities
$sql = "SELECT COUNT(*) FROM user_activities";
$stmt = $pdo -> query($sql);
$activitiescount = $stmt->fetchColumn();


// uptime
$uptimeSeconds = time() - $startTime;

// Convert the uptime to a human-readable format
$uptime = sprintf("%d days, %02d:%02d:%02d",
                  $uptimeSeconds / 86400,
                  ($uptimeSeconds % 86400) / 3600,
                  ($uptimeSeconds % 3600) / 60,
                  $uptimeSeconds % 60);


// get school information
// User ID (change this to the actual user ID)
$user_id = $cont['id'];

// SQL query to retrieve user and school information
$sql = "SELECT u.*, s.school_name AS name, s.school_location AS location
        FROM users u
        JOIN schools s ON u.school_id = s.school_id
        WHERE u.id = :user_id";

// Prepare and execute the query
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

// Fetch the result
$result = $stmt->fetch();



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preload" href="../../styles/FontAwesome/css/all.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="stylesheet" href="../../styles/administrator/all.css">
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
    <script src="https://unpkg.com/@andreasremdt/simple-translator@latest/dist/umd/translator.min.js" defer></script>
</head>
<body>
    

    <?php include '../../important/Rightbar.php'; ?>
    <?php include '../../important/Sidebar.php'; ?>

    <div class="stats-box">
        <div class="stats-box-item">
            <i class="fa-solid fa-users fa-xl" style="top: -5px;"></i>
            <h1 class="stats-box-item-title" style="position: absolute; left: 40px;">Users</h1>
            <h2 class="stats-box-item-number"><?php echo $userscount ?></h2>
        </div>
        <div class="stats-box-item">
            <i class="fa-solid fa-messages fa-xl" style="top: -5px;"></i>
            <h1 class="stats-box-item-title" style="position: absolute; left: 40px;">Messages (across all instances)</h1>
            <h2  class="stats-box-item-number"><?php echo $messagescount ?></h2>
        </div>
        <div class="stats-box-item">
        <i class="fa-solid fa-square-person-confined fa-xl" style="top: -5px;"></i>
            <h1 class="stats-box-item-title" style="position: absolute; left: 40px;">Activities</h1>
            <h2  class="stats-box-item-number"><?php echo $activitiescount ?></h2>
        </div>
        <div class="stats-box-item">
            <i class="fa-solid fa-heart fa-beat fa-xl" style="--fa-animation-duration: 0.5s; top: -5px;" ></i>
            <h1 class="stats-box-item-title" style="position: absolute; left: 40px;">Platform uptime (this instance)</h1>
            <h2  class="stats-box-item-number"><?php echo $uptime ?></h2>
        </div>
    </div>

    <div class="school-information-box">
        <h2 class="school-name"> <?php echo $result['name'] ?> </h2>
        <h4 class="school-location"> <?php echo $result['location'] ?> </h4>
        <h4 class="school-type"> Public </h4>
        <a class=""><i class="fa-solid fa-gear fa-2x" style="top: -150px; left: 230px;"></i></a>
    </div>

</body>
</html>