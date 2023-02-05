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


// class logic
$stmt = $pdo->prepare("SELECT * FROM classes WHERE class_id = $cont[class_id]"); 
$stmt->execute(); 

$classes = $stmt->fetchAll(); 

foreach($classes as $class)  

// elev logic
$stmt = $pdo->prepare("SELECT * FROM users WHERE class_id = $cont[class_id]"); 
$stmt->execute(); 

$classmates = $stmt->fetchAll(); 


//best classmate logic
$stmt = $pdo->prepare("SELECT name, last_name, MAX(grade) as max_grade from grades JOIN users on grades.class_id = users.id WHERE users.class_id = $cont[class_id] ORDER BY max_grade DESC LIMIT 3");
$stmt -> execute();
$best_classmate = $stmt -> fetch();

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
    <title>Dashboard</title>
    <link rel="preload" href="styles/FontAwesome/css/all.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="stylesheet" href="styles/clasa-mea/all.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.all.min.js"></script>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
</head>
<body>
    
    <?php include './important/rightbar.php'; ?>
    <?php include './important/sidebar.php'; ?>

    <div class="students-box">
        <div class="students-box-content">
            <h1 class="students-title"><?php if($cont['class_id'] == $class['class_id'] && $cont['school_id'] == $class['school_id']) { 
                echo $class['class_name'];
            }
                ?>
                </h1>
            <p class="students-text">Aici poți vedea elevii din clasa ta.</p>
            <div class="students-box-list">
                <div class="students-box-list-item">
                    <div class="students-box-list-item-content">
                        <div class="students-box-list-item-content-left">
                            <div class="students-box-list-item-content-left-img">
                                <img src="https://cdn.discordapp.com/attachments/881100000000000000/881100000000000000/unknown.png" alt="">
                            </div>
                            <?php if(count($classmates) > 0) {
                                foreach($classmates as $classmate) { ?>
                            <div class="students-box-list-item-content-left-text">
                                <h1 class="students-box-list-item-content-left-text-name"><?php echo $classmate['name']; echo ' ';  echo $classmate['last_name']; ?></h1>
                                <p class="students-box-list-item-content-left-text-email"><?php echo $classmate['email'] ?></p>
                                <?php if($cont['rank'] > 2) { ?>
                                <div class="students-box-list-item-actions">
                                    <div class="students-box-list-item-actions-1">
                                        <i class="fa-solid fa-pen-to-square" alt="Modifică elev" onclick="modificaelev(event)"></i>
                                    </div>
                                    <div class="students-box-list-item-actions-2" onclick="estisigurstergeelev(event)">
                                        <i class="fa-solid fa-trash" alt="Șterge elev"></i>
                                    </div>
                                    <div class="students-box-list-item-actions-3">
                                        <i class="fa-solid fa-notes" alt="Modifică notiță (pt. profesori)" onclick="modificanotitaelev(event)"></i>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <?php } } } else { ?> <h3>You don't have any classmates ;( </h3> <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- performance box -->
        <div class="performance-box">
            <div class="performance-box-content">
                <h4 class="performance-box-title">Performanta clasei</h4>
                <i class="fa-solid fa-circle-xmark fa-4x" style="color: red; position: relative; top: 80px; left: 260px;"></i>
                <h3 style="position: relative; top: 100px; left: 60px;">Oopsie! Looks like we can't calculate the performance</h3>

            <!--<figure class="highcharts-figure">
                <div id="container-perf"></div>
            </figure>-->
            </div>
        </div>

    <!-- best classmates -->
        <div class="best-classmates">
            <div class="best-classmates-content">
                <h4 class="best-classmates-title">Cei mai buni elevi</h4>
                <p class="best-classmates-attention">Pe clasa</p>
                <div class="best-classmates-ellipses">
                    <div class="best-classmates-first-box">
                        <div class="firstplace-initials">
                            <p class="firstplace-initials-text">SG</p>
                        </div>
                        <p class="firstplace-student"> <?php echo $best_classmate['name']; echo ' '; echo $best_classmate['last_name'] ?> </p>
                        <p class="firstplace-grade">MEDIA <?php echo $best_classmate['max_grade']; ?> </p>
                        <img src="./styles/clasa-mea/img/first-place.png" class="firstplace-image">
                    </div>
                    <div class="best-classmates-second-box">
                        <div class="secondplace-initials">
                            <p class="secondplace-initials-text">SG</p>
                        </div>
                        <p class="secondplace-student">  <?php echo $best_classmate['name']; echo ' '; echo $best_classmate['last_name'] ?> </p>
                        <p class="secondplace-grade">MEDIA <?php echo $best_classmate['max_grade']; ?> </p>
                        <img src="./styles/clasa-mea/img/second-place.png" class="secondplace-image">
                    </div>
                    <div class="best-classmates-third-box">
                        <div class="thirdplace-initials">
                            <p class="thirdplace-initials-text">SG</p>
                        </div>
                        <p class="thirdplace-student"> Ștefan Ghețu </p>
                        <p class="thirdplace-grade">MEDIA 9.99 </p>
                        <img src="./styles/clasa-mea/img/third-place.png" class="thirdplace-image">
                    </div>
                    <div class="best-classmates-noplace-box">
                        <div class="noplace-initials">
                            <p class="noplace-initials-text">SG</p>
                        </div>
                        <p class="noplace-student"> Ștefan Ghețu </p>
                        <p class="noplace-grade">MEDIA 9.99 </p>
                    </div>
                </div>
            </div>
        </div>


</body>


<script>
function estisigurstergeelev(e) {
    e.preventDefault();
        Swal.fire({
    title: 'Ești sigur?',
    text: "Ești sigur că vrei să ștergi elevul? Acțiunea nu poate fi anulată!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Da, șterge-l!'
    }).then((result) => {
    if (result.isConfirmed) {
        Swal.fire(
            'Șters!',
            'Elevul a fost șters cu succes.',
            'success'
            )
        }
    })
}


async function modificanotitaelev(e) {
    e.preventDefault();
    const { value: text } = await Swal.fire({
    input: 'textarea',
    inputLabel: 'Modifică notiță',
    inputPlaceholder: 'Introdu notița ta aici...',
    inputAttributes: {
        'aria-label': 'Introdu notița aici'
    },
    showCancelButton: true
    })

    if (text) {
    Swal.fire(`Notiță adăugată: ${text}`)
    }
}

async function modificaelev(e) {
    const { value: selection } = await Swal.fire({
  title: 'Te rog să alegi ceea ce dorești să modifici',
  input: 'select',
  inputOptions: {
    'General': {
      note: 'Note',
      informatii: 'Informatii'
    },
    'Administrativ': {
      maicaas: 'Maica-sa',
      taicasu: 'Taica-su'
    },
    'Altele': 'Altele'
  },
  inputPlaceholder: 'Alege o opțiune',
  showCancelButton: true,
  inputValidator: (value) => {
    return new Promise((resolve) => {
      if (value === 'note') {
        resolve()
      } else {
        resolve('You need to select something :)')
      }
    })
  }
})

if (fruit) {
  Swal.fire(`You selected: ${fruit}`)
}
}

</script>