<?php
define('BASEPATH', true);
session_start();
require('backend/config/db.php');
$is_administrator = 0;

if(!isset($_SESSION["user"])) {
  header("Location: login.php");
  exit();
}

// Get the username from the URL
$acronim = $_GET['acronim'];

// Prepare a SQL statement to select the user's information from the database
$stmt = $pdo->prepare('SELECT * FROM users WHERE acronim = :acronim');
$stmt->execute(array('acronim' => $acronim));
$users = $stmt->fetch();

foreach($users as $user)
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preload" href="styles/FontAwesome/css/all.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="stylesheet" href="styles/profil/all.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.all.min.js"></script>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
</head>
<body>

<?php require './important/Rightbar.php'; ?>
    <?php require './important/Sidebar.php'; ?>
    <?php if($user) { ?>
        <?php     $name = $user['name']; ?>
        Numele: <?php echo $name; ?>
        Prenumele: <?php echo $user['last_name']; ?>
    <?php } else { ?>
        <p>Acest utilizator nu exista.</p>
    <?php } ?>
</body>
</html>