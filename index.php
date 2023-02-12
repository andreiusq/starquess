
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

foreach($conturi as $cont);

// news logic
$statement = $pdo->prepare("SELECT * FROM news ORDER BY posted_at DESC");
$statement->execute();
$news = $statement ->fetchAll(PDO::FETCH_ASSOC);

// timetable logic
$day = date("l");

if ($day == "Saturday") {
    $timetable_day = "Monday";
} elseif ($day == "Sunday") {
    $timetable_day = "Monday";
} else {
    $timetable_day = $day;
}

$query = "SELECT * FROM timetables WHERE day = :day AND class_id = $cont[class_id]";

$stmt = $pdo->prepare($query);
$stmt->bindParam(':day', $timetable_day);
$stmt->execute();
$timetable = $stmt->fetchAll();



if($cont["rank"] == 2) {
    header("Location: teacher.php");
    exit();
  }
  

?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="preload" href="styles/FontAwesome/css/all.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="stylesheet" href="styles/dashboard/all.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.all.min.js"></script>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    
    <?php include './important/rightbar.php'; ?>
    <?php include './important/sidebar.php'; ?>


    <?php if($cont["rank"] == 11) : ?>
      <div class="attention-box">
        <div class="attention-box-content">
          <h1>Atenție!</h1>
          <p class="attention-text">Ești logat pe un cont cu permisiuni de <b>Administrator</b>. Te rugăm să nu le folosești în mod ilicit.</p>
        </div>
      </div>  
    <?php endif; ?>
    <!-- welcome box -->

   
    <div class="welcome-box">
        <div class="welcome-box-content">
            <h1>Bine ai revenit, <?php echo $cont["name"] ?> !<img class="mana" src="https://em-content.zobj.net/thumbs/120/apple/325/waving-hand_1f44b.png" alt="Salut!" style="position: absolute; width: 50px; top: -13px;"/></h1>
            <p class="congrats-text">Eșt mai bun cu <b>70%</b> în această săptămână. Felicitări!</p>
            
        </div>
        <div className='welcome-box-image'> 
            <img src="https://i.imgur.com/aFNE7od.png" alt="Salut!" height="200px" style="position: relative; top: -80px; left: 900px" />
        </div>
    </div>

    <!-- performance box -->
    <div class="performance-box">
        <div class="performance-box-content">
            <h4 class="performance-box-title">Performanta</h4>
            <i class="fa-solid fa-circle-xmark fa-4x" style="color: red; position: relative; top: 80px; left: 260px;"></i>
            <h5 style="position: relative; top: 100px; left: 30px;">Oopsie! Looks like we can't calculate your performance</h5>

            <!--<figure class="highcharts-figure">
                <div id="container-perf"></div>
            </figure>-->
        </div>
    </div>
<?php if($timetable) { ?>
    <!-- timetable box -->
    <div class="timetable-box">
        <div class="timetable-box-content">
            <h1 class="timetable-title" id="timetable-title"></h1>
            <p class="timetable-subtitle" id="date"></p>
            <?php foreach($timetable as $row) { ?>
    </div>
        <div class="timetable-box-hours">
            <div class="timetable-box-hours-content" id="timetable-box-hours-content">
                <div class="timetable-box-hours-content-hour-circle">
                    <p class="timetable-box-hours-content-hour-circle-text">08:00</p>
                </div>
                <p class="timetable-box-hours-content-hour-text"><?php echo $row['hour1']?>
                <div class="timetable-box-hours-content-hour-circle">
                    <p class="timetable-box-hours-content-hour-circle-text">09:00</p>
                </div>
                <p class="timetable-box-hours-content-hour-text"><?php echo $row['hour2']?>
               </p>
               <div class="timetable-box-hours-content-hour-circle">
                    <p class="timetable-box-hours-content-hour-circle-text">10:00</p>
                </div>
                <p class="timetable-box-hours-content-hour-text"><?php echo $row['hour3']?>
                <div class="timetable-box-hours-content-hour-circle">
                    <p class="timetable-box-hours-content-hour-circle-text">11:00</p>
                </div>
                <p class="timetable-box-hours-content-hour-text"><?php echo $row['hour4']?>
                <div class="timetable-box-hours-content-hour-circle">
                    <p class="timetable-box-hours-content-hour-circle-text">12:00</p>
                </div>
                <p class="timetable-box-hours-content-hour-text"><?php echo $row['hour5']?>
                <div class="timetable-box-hours-content-hour-circle">
                    <p class="timetable-box-hours-content-hour-circle-text">13:00</p>
                </div>
                <p class="timetable-box-hours-content-hour-text"><?php echo $row['hour6']?>
                <div class="timetable-box-hours-content-hour-circle">
                    <p class="timetable-box-hours-content-hour-circle-text">14:00</p>
                </div>
                <p class="timetable-box-hours-content-hour-text"><?php echo $row['hour7']?>
            </div>
            <?php } ?>
        </div>
    </div>
    <?php } else {
        echo "No timetable found for" . $timetable_day;
    } ?>
    <!-- messages box -->
    <div class="message-box">
        <div class="message-box-content">
            <h1 class="message-title">?????</h1>
            <p class="message-subtitle"></h1>
        </div>
        <div class="message-box-messages">
            <div class="message-box-messages-content">
                <!--<div class="message-box-messages-content-message-circle">
                    <p class="message-box-messages-content-message-circle-text">IA</p>
                </div>
                <p class="message-box-messages-content-message-text">Andrei Iasar</p>
                <div class="message-box-messages-content-message-description">
                    <p class="message-box-messages-content-message-description-text">Salut frate, ce faci?</p>
                </div>
                <div class="message-box-messages-content-message-time">
                    <p class="message-box-messages-content-message-time-text">10:00</p>
                </div>-->
                <i class="fa-solid fa-block-question fa-4x" style="position: relative; left: 260px; top: 80px"></i>
                <h5 style="position: relative; top: 100px; left: 100px;">Woah! Who knows what is going to be here</h5>
            </div>
        </div>
    </div>

    <!-- news box -->
    <div class="news-box">
        <div class="news-box-content">
            <h1 class="news-title">Noutati</h1>
            <p class="news-subtitle">Vezi tot</h1>
        </div>
        <div class="news-box-news">
        <?php foreach($news as $row) { ?>
            <div class="news-box-news-content">
                <div class="news-box-news-content-news-ellipse">
                    <p class="news-box-news-content-news-ellipse-text"><?php echo $row['text'];?></p>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
    </div>
</body>

<script>

$(document).ready(function() {
    Swal.fire({
        position: 'top',
        toast: true,
        timer: 3500,
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        showConfirmButton: false,
        text: '‎ ‎ Avem un flux mare de utilizatori, te rugăm să aștepți cât timp mărim capacitatea serverului.',
        icon: 'warning'
    });
});

    var date = new Date();
    var day = date.getDate();
    var month = date.getMonth() + 1;
    var year = date.getFullYear();
    var dayOfWeek = date.getDay();
    var dayOfWeekText = "";
    switch (dayOfWeek) {
        case 0:
            dayOfWeekText = "Duminica";
            break;
        case 1:
            dayOfWeekText = "Luni";
            break;
        case 2:
            dayOfWeekText = "Marti";
            break;
        case 3:
            dayOfWeekText = "Miercuri";
            break;
        case 4:
            dayOfWeekText = "Joi";
            break;
        case 5:
            dayOfWeekText = "Vineri";
            break;
        case 6:
            dayOfWeekText = "Sambata";
            break;
    }
    if (day < 10) {
        day = "0" + day;
    }
    if (month < 10) {
        month = "0" + month;
    }
    var dateText = dayOfWeekText + ", " + day + "." + month + "." + year;
    document.getElementById("date").innerHTML = dateText;

    if(dayOfWeek == 0 || dayOfWeek == 6) {
        document.getElementById("timetable-title").innerHTML = "Orar";
    } else {
        document.getElementById("timetable-title").innerHTML = "Orar";
    }

    var hour = date.getHours();
    var minute = date.getMinutes();
    if (hour < 10) {
        hour = "0" + hour;
    }
    if (minute < 10) {
        minute = "0" + minute;
    }

    if(hour > 15) {
        document.getElementById("timetable-title").innerHTML = "Orar";
    }

</script>
</html>
