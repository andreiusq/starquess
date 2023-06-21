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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>
<body>
    

    <?php include '../../important/Rightbar.php'; ?>
    <?php include '../../important/Sidebar.php'; ?>

    <!--<div class="stats-box">
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
    </div> -->


    <div class="card text-bg-dark" style="width: 25em; position: absolute; left: 60%; top: 20px;">
        <img src="https://www.adservio.ro/rimages/5c3e46e33620b579b27b..png" class="card-img" alt="..." stlye="filter: blur(2px);">
        <div class="card-img-overlay">
            <h5 class="card-title">Recomandă Starquess</h5>
            <p class="card-text">Recomandă Starquess unei unități de învățământ și contribuie la transformarea digitală!</p>
        </div>
        </div>


    <div class="card" style="width: 50em; position: relative; top: 60px; left: 17%;">
        <div class="card-body">
            <h5 class="card-title"><?php echo $result['name'] ?></h5>
            <p class="card-text"> <?php echo $result['location'] ?> </p>
           <a href="school_edit.php?school_id=<?php echo $result['school_id'] ?>" class="btn btn-primary">Setări școală</a>
        </div>
    </div>

    <div class="asistenta-stuff">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"> Asistență </h5>
                <p class="card-text"> Întâmpini probleme? Contactează-ne! </p>
                <a href="#" class="btn btn-primary"> Contactează-ne </a>
            </div>
        </div>
    </div>

    <div class="actiuni">
        <h5 class="actiuni-title">Acțiuni</h5>
        <div class="row">
        <div class="col-sm-3">
            <div class="card" style="width: 20rem;">
            <div class="card-body">
                <h5 class="card-title">Special title treatment</h5>
                <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                <a href="#" class="btn btn-primary">Go somewhere</a>
            </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card" style="width: 20rem;">
            <div class="card-body">
                <h5 class="card-title">Special title treatment</h5>
                <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                <a href="#" class="btn btn-primary">Go somewhere</a>
            </div>
            </div>
        </div>
        </div>

    </div>


    <!-- script -->

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>

</body>
</html>