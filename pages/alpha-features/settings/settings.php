<?php

define('BASEPATH', true);
session_start();
require('../../../backend/config/db.php');
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

foreach($conturi as $cont);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preload" href="../../../styles/FontAwesome/css/all.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="stylesheet" href="../../../styles/settings/all.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.all.min.js"></script>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>

</head>
<body>
    <?php include '../../../important/Rightbar-pages.php'; ?>
    <?php include '../../../important/Sidebar-pages.php'; ?>
    

    <div class="settings-content">
        <div class="settings-content-header">
            <h1>Settings</h1>
        </div>
        <div class="settings-content-body">
            <div class="settings-content-body-left">
                <div class="settings-content-body-left-item">
                    <h1>General</h1>
                </div>
                <div class="settings-content-body-left-item">
                    <h1>Security</h1>
                </div>
                <div class="settings-content-body-left-item">
                    <h1>Notifications</h1>
                </div>
                <div class="settings-content-body-left-item">
                    <h1>Privacy</h1>
                </div>
                <div class="settings-content-body-left-item">
                    <h1>Account</h1>
                </div>
            </div>
            <div class="settings-content-body-right">
                <div class="settings-content-body-right-item">
                    <h1>General</h1>
                    <div class="settings-content-body-right-item-content">
                        <div class="settings-content-body-right-item-content-item">
                            <h1>Language</h1>
                            <select name="language" id="language">
                                <option value="English">English</option>
                                <option value="Romanian">Romanian</option>
                            </select>
                        </div>
                        <div class="settings-content-body-right-item-content-item">
                            <h1>Theme</h1>
                            <select name="theme" id="theme">
                                <option value="Light">Light</option>
                                <option value="Dark">Dark</option>
                            </select>
                        </div>
                        <div class="settings-content-body-right-item-content-item">
                            <h1>Timezone</h1>
                            <select name="timezone" id="timezone">
                                <option value="Europe/Bucharest">Europe/Bucharest</option>
                                <option value="Europe/London">Europe/London</option>
                            </select>
                        </div>
                        <div class="settings-content-body-right-item-content-item">
                            <h1>Country</h1>
                            <select name="country" id="country">
                                <option value="Romania">Romania</option>
                                <option value="United Kingdom">United Kingdom</option>
                            </select>
                        </div>
                        <div class="settings-content-body-right-item-content-item">
                            <h1>Language</h1>
                            <select name="language" id="language">
                                <option value="English">English</option>
                                <option value="Romanian">Romanian</option>
                            </select>
                        </div>
                        


    <p> <?php echo $cont['name']; ?> </p>

</body>
</html>