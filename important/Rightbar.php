
<?php


require('backend/config/db.php');
$stmt = $pdo->prepare("SELECT name, last_name FROM users WHERE email=:email");
$stmt->bindParam(':email', $_SESSION['user']);   
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM user_activities WHERE user_id = :user_id");
$stmt->bindParam(":user_id", $user_id);

$user_id = $cont['id'];

$stmt->execute();
$activities = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<head>
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800;900&display=swap');
@import url("https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600&display=swap");


 *{
    font-family: 'Poppins', sans-serif;
    font-size: 800;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    position: relative;
 }
 body{
    min-height:100vh;
    background-color: #fff ;
 }

 .leftbar{
    position: relative;
    width: 300px;
    border-radius: 10px;
    box-sizing: initial;
    border: 15px solid white;
    height: 100%;
    padding: 50px;
    background-color : #fff;
  left:20px;
  margin-right:30px;
 } 

 .useravatar {
    position: absolute;
    top: -5px;
    left: -10px;
    width: 40px;
    height: 40px;
    border-radius: 10px;
 }

 .userName {
   top: 2px;
   left: 40px;
   display: inline-block;
    position: absolute;
    font-size: 1.2em;
    font-weight: 400;
    color: #000;
 }

 .leftbar .icon {
      position: absolute;
      top: -55px;
      right: 0px;
 }

 .leftbar__top__img {
      position: absolute;
      width: 200px;
      height: 60px;
      object-fit: cover;
      border: 15px solid #F0F7FF;
      border-radius: 10px;
      background-color: #F0F7FF;
 }


.activities_title {
   position: absolute;
   top: 150px;
   left: -40px;
   font-size: 1.2em;
   font-weight: 600;
   color: #000;
}

.activities_seeall {
   position: absolute;
   top: 155px;
   left: 150px;
   font-size: 0.8em;
   font-weight: 400;
   color: #0d80f2;
}

.activities_list_item {
   position: relative;
   top: 200px;
   left: -60px;
   font-size: 0.8em;
   font-weight: 400;
   color: #000;
   border: 15px solid #F0F7FF;
   background: #F0F7FF;
   border-radius: 40px;
   width: 280px;
   height: 80px;
}

.activities_list_item_icon {
   position: absolute;
   top: 10px;
   left: 10px;
   width: 40px;
   height: 40px;
   border-radius: 20px;
   border: 15px solid pink;
   background: pink;
}

.activities_list_item_text {
   position: absolute;
   top: 10px;
   left: 70px;
   font-size: 0.8em;
   font-weight: 400;
   color: #000;
}

.activities_list_item_text_date {
   position: absolute;
   top: 16px;
   font-size: 12px;
   font-weight: 400;
   color: #000;
}

.activities_list_item_date {
   position: relative;
   top: -10px;
   right: 10px;
   color: #fff;
   font-size: 24px;
}

.activities_list_item_text_description {
   position: absolute;
   top: 35px;
   font-size: 1.0em;
   font-weight: 400;
   color: #000;
}

.activities_list_item_text_time {
   position: absolute;
   font-size: 12px;
   font-weight: 400;
   color: #000;
   top: 16px;
   left: 100px;
}

.semieclipse-activities {
   position: absolute;
   top: 23px;
   right: 60px;
   border-radius: 50px;
   background-color: #0077FF;
   width: 5px;
   height: 5px;
}

.calendar_title {
      position: absolute;
      top: 100px;
      left: -40px;
      font-size: 1.2em;
      font-weight: 600;
      color: #000;
 }

</style>
</head>

<body class="light">

<div class="leftbar" style="position: fixed; margin-left: 1600px; border: 15px solid white; border-radius: 20px; height: 100%; width: 100%; background-color: #fff">
    <div class="leftbar__top">
        <span class="icon"><i class="fa-solid fa-bell"></i></span>
        <div class="leftbar__top__img">
            <h4 class="userName"><?php echo $user['name']; echo ' '; echo $user['last_name'] ?> </h4>
            <img class="useravatar" src="https://i.imgur.com/0y0t0Xy.jpg" alt="useravatar">
        </div>
    </div>
    
    <!--<div class="leftbar__calendar">
        <h5 class="calendar_title">Progresul meu</h5>

    </div>-->

    <div class="leftbar_activites">
        <h5 class="activities_title">Activitatile mele</h5>
        <h5 class="activities_seeall">Vezi toate</h5>
        <div class="activities_list">
         <?php foreach ($activities as $activity) { ?>
            <?php 
            
            // ora
            $activity_time = $activity["activity_time"];
            $timestamp = strtotime($activity_time);
            $formatted_time = date('H:i', $timestamp);
            //data cu luna
            $date = new DateTime($activity['activity_date']);
            $date_formatter = new IntlDateFormatter('ro_RO', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
            $date_formatter->getPattern();
            $date_formatter->setPattern('d MMMM');
            $formatted_date = $date_formatter->format($date);

            //doar data
            $dateString = $activity['activity_date'];
            $timestamp = strtotime($dateString);
            $dateNumber = date('j', $timestamp);

            // sterge data automat
            $activityDate = strtotime($activity['activity_date']);
            $currentDate = time();

            if ($currentDate > $activityDate) {
               $stmt = $pdo->prepare("DELETE FROM activities WHERE id = :id");
               $stmt->bindParam(':id', $activity['id']);
               $stmt->execute();
            }
            ?>
            <div class="activities_list_item">
                <div class="activities_list_item_icon">
                    <h2 class="activities_list_item_date"><?php echo $dateNumber ?></h2>
                </div>
                <div class="activities_list_item_text">
                    <h6 class="activities_list_item_text_title"><?php echo $activity["activity_name"] ?></h6>
                    <p class="activities_list_item_text_date"><?php echo $formatted_date ?></p> <span class="semieclipse-activities"></span><p class='activities_list_item_text_time'><?php echo $formatted_time ?></p>
                    <p class="activities_list_item_text_description"><?php echo $activity["activity_description"] ?> </p>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>

</div>



</body>