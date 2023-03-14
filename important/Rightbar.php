
<?php


$stmt = $pdo->prepare("SELECT name, last_name, id, rank FROM users WHERE email=:email");
$stmt->bindParam(':email', $_SESSION['user']);   
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM user_activities WHERE user_id = :user_id");
$stmt->bindParam(":user_id", $user_id);

$user_id = $user['id'];
$stmt->execute();
$activities = $stmt->fetchAll(PDO::FETCH_ASSOC);


// get user image
// Retrieve the file URLs from the database using the user ID
$userId = $user['id']; // Replace with the actual user ID
$stmt = $pdo->prepare("SELECT url FROM user_images WHERE user = :user");
$stmt->bindParam(':user', $userId);
$stmt->execute();
$urls = $stmt->fetchAll(PDO::FETCH_COLUMN);

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
    left: 20px;
    margin-right: 30px;
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
   top: -2px;
   left: 40px;
   display: inline;
   position: absolute;
   font-size: 1.2em;
   font-weight: 400;
   color: #000;
   white-space: nowrap;
 }

 .userRank {
   top: 19px;
   left: 40px;
   display: inline;
   position: absolute;
   font-size: 0.7em;
   font-weight: 400;
   color: #000;
   white-space: nowrap;
 }

 .leftbar .icon {
      position: absolute;
      top: -55px;
      right: 0px;
 }

 .leftbar__top__img {
      position: absolute;
      width: 215px;
      height: 60px;
      object-fit: cover;
      border: 15px solid #F0F7FF;
      border-radius: 10px;
      background-color: #F0F7FF;
      left: -50px;
 }


.activities_title {
   position: absoplute;
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
   top: 5px;
   left: 70px;
   font-size: 0.8em;
   font-weight: 400;
   color: #000;
}

.activities_list_item_text_date {
   position: absolute;
   top: 15px;
   font-size: 12px;
   font-weight: 400;
   color: #000;
   display: inline;
   white-space: nowrap;
}

.activities_list_item_date {
   position: relative;
   top: -10px;
   left: -10px;
   justify-content: center;
   color: #fff;
   font-size: 24px;
}

.activities_list_item_text_description {
   position: absolute;
   top: 35px;
   width: 150px;
   font-size: 1.0em;
   font-weight: 400;
   color: #000;
   display: inline;
   white-space: nowrap;
   overflow: hidden;
   text-overflow: ellipsis;
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


 .activities-top-buttons-add {
    background: transparent;
	position: relative;
	left: -22px;
	bottom: -0.2px;
    border: transparent;
}

.activities-top-buttons-add:hover {
    background: #F0F7FF;
    border-radius: 10px;
}

.activities-top-buttons-delete {
    background: transparent;
    border: transparent;
}

.activities-top-buttons-delete:hover {
    background: #F0F7FF;
    border-radius: 10px;
}

.activities_list_item_text_title {
   position: absolute;
   top: -10px;
   color: #000;
   display: inline;
   white-space: nowrap;
   overflow: hidden;
   text-overflow: ellipsis;
   width: 120px;
}


.activities-button {
   background: transparent;
   border: transparent;
}


</style>
<?php
foreach($urls as $url) {
   $imgTag = "<img src='$url' alt='user image' class='useravatar'>";
   $imageTags[] = $imgTag;
}
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.all.min.js"></script>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
</head>

<body class="light">

<div class="leftbar" style="position: fixed; margin-left: 1600px; border: 15px solid white; border-radius: 20px; height: 100%; width: 100%; background-color: #fff">
    <div class="leftbar__top">
        <div class="leftbar__top__img">
            <h4 class="userName"><?php echo $user['name']; echo ' '; echo $user['last_name'] ?> </h4>
            <?php if($user['rank'] == 11) { ?> <h5 class="userRank">Starquess TEAM </h5> <?php } ?>
            <?php foreach($imageTags as $img) { echo $img; } ?>
        </div>
    </div>
    
    <!--<div class="leftbar__calendar">
        <h5 class="calendar_title">Progresul meu</h5>

    </div>-->

    <div class="leftbar_activites">
        <h5 class="activities_title">Activitatile mele</h5>
        <h5 class="activities_seeall">
         <!-- adauga -->
            <button class="activities-top-buttons-add" onclick="adauganou(event)"><i class="fas fa-plus fa-xl"></i></button>
        </h5>
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
            $date_formatter->setPattern('d  MMMM');
            $formatted_date = $date_formatter->format($date);

            //doar data
            $dateString = $activity['activity_date'];
            $timestamp = strtotime($dateString);
            $dateNumber = date('j', $timestamp);

            // sterge data automat
            $activityDate = strtotime($activity['activity_date']);
            $currentDate = time();

            if ($currentDate > $activityDate) {
               $stmt = $pdo->prepare("DELETE FROM user_activities WHERE id = :id");
               $stmt->bindParam(':id', $activity['id']);
               $stmt->execute();
            }
            ?>
            <br>
            <div class="activities_list_item">
                <div class="activities_list_item_icon">
                    <h2 class="activities_list_item_date"><?php echo $dateNumber ?></h2>
                </div>
                <div class="activities_list_item_text">
                    <h6 class="activities_list_item_text_title"><?php echo $activity["activity_name"] ?></h6>
                    <p class="activities_list_item_text_date"><?php echo $formatted_date ?></p><p class='activities_list_item_text_time'><?php echo $formatted_time ?></p>
                    <p class="activities_list_item_text_description"><?php echo $activity["activity_description"] ?> </p>
                </div>
            </div>
            <?php } ?> <br>
        </div>
    </div>

</div>



</body>

<script>
    async function adauganou(e) {
        e.preventDefault();
         Swal.fire({
      title: 'Adaugă activitate',
      html:
        '<form id="add-activity-form">' +
          '<div class="form-group">' +
            '<label for="activity-name">Nume activitate:</label>' +
            '<input type="text" id="activity-name" name="activity-name" class="swal2-input" required>' +
          '</div>' +
          '<div class="form-group">' +
            '<label for="activity-desc">Descriere activitate:</label>' +
            '<textarea id="activity-desc" name="activity-desc" class="swal2-textarea" required></textarea>' +
          '</div>' +
          '<div class="form-group">' +
            '<label for="activity-date">Dată:</label>' +
            '<input type="date" id="activity-date" name="activity-date" class="swal2-input" required>' +
          '</div>' +
          '<div class="form-group">' +
            '<label for="activity-time">Oră:</label>' +
            '<input type="time" id="activity-time" name="activity-time" class="swal2-input" required>' +
          '</div>' +
          '<input type="hidden" id="user-id" name="user-id" value="<?php echo $user['id']; ?>">' +
        '</form>',
      showCancelButton: true,
      confirmButtonText: 'Adaugă',
      focusConfirm: false,
      inputAttributes: {
         maxlength: 250
      },
      preConfirm: () => {
        const name = document.getElementById('activity-name').value
        const desc = document.getElementById('activity-desc').value
        const date = document.getElementById('activity-date').value
        const time = document.getElementById('activity-time').value
        const user_id = document.getElementById('user-id').value

        return { name: name, desc: desc, date: date, time: time, user_id: user_id }
      }
    }).then((result) => {
      if (result.isConfirmed) {
        const formData = new FormData();
        formData.append('name', result.value.name);
        formData.append('desc', result.value.desc);
        formData.append('date', result.value.date);
        formData.append('time', result.value.time);
        formData.append('user_id', result.value.user_id);

        fetch('../../backend/queries/add_activity.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          console.log(data);
          if (data.success) {
            Swal.fire('Success!', data.message, 'success');
          } else {
            Swal.fire('Error!', data.message, 'error');
          }
        })
        .catch((error) => {
         Swal.fire('Success!', data.message, 'success');
        });
      }
    });
  }
</script>