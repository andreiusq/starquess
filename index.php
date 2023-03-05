
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
  
$user_id = $cont['id'];

// chart
$query = "SELECT module_id, grade FROM grades WHERE user_id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

$semesters = array_unique(array_column($data, 'module_id'));
$averages = array();
foreach ($semesters as $semester) {
    $grades = array_filter($data, function($row) use ($semester) {
        return $row['module_id'] == $semester;
    });
    $average = array_sum(array_column($grades, 'grade')) / count($grades);
    $averages[] = round($average, 5);
}


$data_json = json_encode(array(
    'module' => $semesters,
    'grades' => array_column($data, 'grade'),
    'averages' => $averages
));



// calcul procentaj
$today = date('Y-m-d');
$last_week = date('Y-m-d', strtotime('-1 week'));
$query = "SELECT * FROM grades WHERE date >= :last_week AND date <= :today AND user_id = $user_id";
$stmt = $pdo->prepare($query);
$stmt->bindValue(':last_week', $last_week);
$stmt->bindValue(':today', $today);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

$grades_this_week = [];
$grades_last_week = [];

foreach ($data as $row) {
    if ($row['date'] >= $today) {
        $grades_this_week[] = $row['grade'];
    } else {
        $grades_last_week[] = $row['grade'];
    }
}

$average_this_week = count($grades_this_week) > 0 ? array_sum($grades_this_week) / count($grades_this_week) : 0;
$average_last_week = count($grades_last_week) > 0 ? array_sum($grades_last_week) / count($grades_last_week) : 0;

// Calculate the percentage improvement
$percent_improvement = $average_last_week > 0 ? (($average_this_week - $average_last_week) / $average_last_week) * 100 : 0;
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
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <style>
      .pencil {
    display: block;
    width: 20em;
    height: 20em;
  }
  
  .pencil__body1,
  .pencil__body2,
  .pencil__body3,
  .pencil__eraser,
  .pencil__eraser-skew,
  .pencil__point,
  .pencil__rotate,
  .pencil__stroke {
    animation-duration: 3s;
    animation-timing-function: linear;
    animation-iteration-count: infinite;
  }
  
  .pencil__body1,
  .pencil__body2,
  .pencil__body3 {
    transform: rotate(-90deg);
  }
  
  .pencil__body1 {
    animation-name: pencilBody1;
  }
  
  .pencil__body2 {
    animation-name: pencilBody2;
  }
  
  .pencil__body3 {
    animation-name: pencilBody3;
  }
  
  .pencil__eraser {
    animation-name: pencilEraser;
    transform: rotate(-90deg) translate(49px,0);
  }
  
  .pencil__eraser-skew {
    animation-name: pencilEraserSkew;
    animation-timing-function: ease-in-out;
  }
  
  .pencil__point {
    animation-name: pencilPoint;
    transform: rotate(-90deg) translate(49px,-30px);
  }
  
  .pencil__rotate {
    animation-name: pencilRotate;
  }
  
  .pencil__stroke {
    animation-name: pencilStroke;
    transform: translate(100px,100px) rotate(-113deg);
  }
  
  /* Animations */
  @keyframes pencilBody1 {
    from,
      to {
      stroke-dashoffset: 351.86;
      transform: rotate(-90deg);
    }
  
    50% {
      stroke-dashoffset: 150.8;
   /* 3/8 of diameter */
      transform: rotate(-225deg);
    }
  }
  
  @keyframes pencilBody2 {
    from,
      to {
      stroke-dashoffset: 406.84;
      transform: rotate(-90deg);
    }
  
    50% {
      stroke-dashoffset: 174.36;
      transform: rotate(-225deg);
    }
  }
  
  @keyframes pencilBody3 {
    from,
      to {
      stroke-dashoffset: 296.88;
      transform: rotate(-90deg);
    }
  
    50% {
      stroke-dashoffset: 127.23;
      transform: rotate(-225deg);
    }
  }
  
  @keyframes pencilEraser {
    from,
      to {
      transform: rotate(-45deg) translate(49px,0);
    }
  
    50% {
      transform: rotate(0deg) translate(49px,0);
    }
  }
  
  @keyframes pencilEraserSkew {
    from,
      32.5%,
      67.5%,
      to {
      transform: skewX(0);
    }
  
    35%,
      65% {
      transform: skewX(-4deg);
    }
  
    37.5%, 
      62.5% {
      transform: skewX(8deg);
    }
  
    40%,
      45%,
      50%,
      55%,
      60% {
      transform: skewX(-15deg);
    }
  
    42.5%,
      47.5%,
      52.5%,
      57.5% {
      transform: skewX(15deg);
    }
  }
  
  @keyframes pencilPoint {
    from,
      to {
      transform: rotate(-90deg) translate(49px,-30px);
    }
  
    50% {
      transform: rotate(-225deg) translate(49px,-30px);
    }
  }
  
  @keyframes pencilRotate {
    from {
      transform: translate(100px,100px) rotate(0);
    }
  
    to {
      transform: translate(100px,100px) rotate(720deg);
    }
  }
  
  @keyframes pencilStroke {
    from {
      stroke-dashoffset: 439.82;
      transform: translate(100px,100px) rotate(-113deg);
    }
  
    50% {
      stroke-dashoffset: 164.93;
      transform: translate(100px,100px) rotate(-113deg);
    }
  
    75%,
      to {
      stroke-dashoffset: 439.82;
      transform: translate(100px,100px) rotate(112deg);
    }
  }

    
    </style>
</head>


<body>
<div style="position: absolute; margin-left: 50%; margin-right: 50%; margin-top: 10%; z-index: 9999;" class="pencil-loader" id="pencil-loader">
    <svg xmlns="http://www.w3.org/2000/svg" height="200px" width="200px" viewBox="0 0 200 200" class="pencil">
        <defs>
            <clipPath id="pencil-eraser">
                <rect height="30" width="30" ry="5" rx="5"></rect>
            </clipPath>
        </defs>
        <circle transform="rotate(-113,100,100)" stroke-linecap="round" stroke-dashoffset="439.82" stroke-dasharray="439.82 439.82" stroke-width="2" stroke="currentColor" fill="none" r="70" class="pencil__stroke"></circle>
        <g transform="translate(100,100)" class="pencil__rotate">
            <g fill="none">
                <circle transform="rotate(-90)" stroke-dashoffset="402" stroke-dasharray="402.12 402.12" stroke-width="30" stroke="hsl(223,90%,50%)" r="64" class="pencil__body1"></circle>
                <circle transform="rotate(-90)" stroke-dashoffset="465" stroke-dasharray="464.96 464.96" stroke-width="10" stroke="hsl(223,90%,60%)" r="74" class="pencil__body2"></circle>
                <circle transform="rotate(-90)" stroke-dashoffset="339" stroke-dasharray="339.29 339.29" stroke-width="10" stroke="hsl(223,90%,40%)" r="54" class="pencil__body3"></circle>
            </g>
            <g transform="rotate(-90) translate(49,0)" class="pencil__eraser">
                <g class="pencil__eraser-skew">
                    <rect height="30" width="30" ry="5" rx="5" fill="hsl(223,90%,70%)"></rect>
                    <rect clip-path="url(#pencil-eraser)" height="30" width="5" fill="hsl(223,90%,60%)"></rect>
                    <rect height="20" width="30" fill="hsl(223,10%,90%)"></rect>
                    <rect height="20" width="15" fill="hsl(223,10%,70%)"></rect>
                    <rect height="20" width="5" fill="hsl(223,10%,80%)"></rect>
                    <rect height="2" width="30" y="6" fill="hsla(223,10%,10%,0.2)"></rect>
                    <rect height="2" width="30" y="13" fill="hsla(223,10%,10%,0.2)"></rect>
                </g>
            </g>
            <g transform="rotate(-90) translate(49,-30)" class="pencil__point">
                <polygon points="15 0,30 30,0 30" fill="hsl(33,90%,70%)"></polygon>
                <polygon points="15 0,6 30,0 30" fill="hsl(33,90%,50%)"></polygon>
                <polygon points="15 0,20 10,10 10" fill="hsl(223,10%,10%)"></polygon>
            </g>
        </g>
    </svg>
</div>    


    <?php include './important/Rightbar.php'; ?>
    <?php include './important/Sidebar.php'; ?>
    <!-- welcome box -->

    <div class="content" id="content">
        <div class="welcome-box">
            <div class="welcome-box-content">
                <h1>Bine ai revenit, <?php echo $cont["name"]; echo "!" ?></h1>
                <?php if($percent_improvement > 0) : ?>
                    <p class="congrats-text">Ești mai bun cu <b><?php echo number_format($percent_improvement, 2) ?> %</b> în această săptămână. Felicitări!</p>
                <?php else : ?>
                    <p class="congrats-text">Nu ai avut nicio notă în această săptămână.</p>
                <?php endif; ?>

            </div>
            <div className='welcome-box-image'> 
                <img src="https://i.imgur.com/YCBEE6D.png" alt="Salut!" height="200px" style="position: relative; top: -110px; left: 900px" />
            </div>
        </div>

        <!-- performance box -->
        <div class="performance-box">
            <div class="performance-box-content">
                <h4 class="performance-box-title">Performanță</h4>
    <!--            <i class="fa-solid fa-circle-xmark fa-4x" style="color: red; position: relative; top: 80px; left: 260px;"></i>
                <h5 style="position: relative; top: 100px; left: 30px;">Oopsie! Looks like we can't calculate your performance</h5>
        -->

                    <div id="container" style="width: 600px; height: 240px; top: 20px;"></div>
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
                
                    <i class="fa-solid fa-block-question fa-4x" style="position: relative; left: 260px; top: 80px"></i>
                    <h5 style="position: relative; top: 100px; left: 100px;">Cine știe ce o să fie aici?</h5>
                </div>
            </div>
        </div>

    <!-- news box -->
    <div class="news-box">
        <div class="news-box-content">
            <h1 class="news-title">Noutăți</h1>
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
        </div>
</body>

<script>
    var loader = document.getElementById("pencil-loader");
    var content = document.getElementById("content");

    function showLoader() {
      loader.style.display = "block";
      content.style.display = "none";
    }

    function hideLoader() {
      loader.style.display = "none";
      content.style.display = "block";
    }

    window.onload = function() {
      showLoader();
      setTimeout(function() {
        hideLoader();
      }, 2000); // hide the loader after 2 seconds
    };
  </script>


<script>

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


        var data = <?php echo $data_json; ?>;
        Highcharts.chart('container', {
            title: {
                text: ''
            },
            xAxis: {
                categories: data.module
            },
            yAxis: {
                title: {
                    text: 'Medie'
                }
            },
            series: [{
                name: 'Note',
                data: data.grades,
                dataLabels: {
                enabled: true,
                formatter: function() {
                    return 'Media ' + this.y;
                }
            }
            }, {
                name: 'Medie generală',
                data: data.averages
            }]
        });

</script>
</body>
</html>