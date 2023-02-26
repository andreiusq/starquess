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

//  
function get_user_class($pdo, $user_id) {
    $stmt = $pdo->prepare('SELECT class_id FROM users WHERE id = :user_id');
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user_data['class_id'];
}



function get_class_average($pdo, $class_id) {
    $stmt = $pdo->prepare('SELECT AVG(grade) AS average_grade FROM grades WHERE class_id = :class_id GROUP BY class_id');
    $stmt->bindParam(':class_id', $class_id, PDO::PARAM_INT);
    $stmt->execute();
    $class_data = $stmt->fetch(PDO::FETCH_ASSOC);
    if (is_array($class_data)) {
        return $class_data['average_grade'];
    } else {
        return 0;
    }
}


// getting every student name & best
function get_best_students($pdo, $class_id) {
    $stmt = $pdo->prepare('SELECT g.user_id, g.grade FROM grades g WHERE g.class_id = :class_id ORDER BY g.grade DESC');
    $stmt->bindParam(':class_id', $class_id, PDO::PARAM_INT);
    $stmt->execute();
    $grades_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $best_students = array();
    $other_students = array();
    $max_grade = 0;

    foreach ($grades_data as $grade_data) {
        if ($grade_data['grade'] > $max_grade) {
            $max_grade = $grade_data['grade'];
            $best_students = array();
        }
        if ($grade_data['grade'] == $max_grade) {
            $user_id = $grade_data['user_id'];
            $stmt = $pdo->prepare('SELECT u.name, u.last_name FROM users u WHERE u.id = :user_id');
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
            $name = $user_data['name'] . ' ' . $user_data['last_name'];
            $initials = substr($user_data['name'], 0, 1) . substr($user_data['last_name'], 0, 1);
            $best_students[] = array('name' => $name, 'initials' => $initials, 'grade' => $grade_data['grade']);
        } else {
            $user_id = $grade_data['user_id'];
            $stmt = $pdo->prepare('SELECT u.name, u.last_name FROM users u WHERE u.id = :user_id');
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
            $name = $user_data['name'] . ' ' . $user_data['last_name'];
            $initials = substr($user_data['name'], 0, 1) . substr($user_data['last_name'], 0, 1);
            $other_students[] = array('name' => $name, 'initials' => $initials, 'grade' => $grade_data['grade']);
        }
    }
    return $best_students;
}



function get_class_data($dbh, $class_id) {
    $stmt = $dbh->prepare('SELECT class_name, AVG(grade) AS average_grade, COUNT(DISTINCT user_id) AS num_students FROM classes c INNER JOIN grades g ON c.class_id = g.class_id WHERE c.class_id = :class_id');
    $stmt->bindParam(':class_id', $class_id, PDO::PARAM_INT);
    $stmt->execute();
    $class_data = $stmt->fetch(PDO::FETCH_ASSOC);
    return $class_data;
}

$user_id = $cont['id'];
$class_id = get_user_class($pdo, $user_id);
$average_grade = get_class_average($pdo, $class_id);
$best_students = get_best_students($pdo, $class_id);

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
    <script src="https://code.highcharts.com/highcharts.js"></script>
</head>
<body>
    
    <?php include './important/Rightbar.php'; ?>
    <?php include './important/Sidebar.php'; ?>

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
                                <?php if($cont['rank'] == 2) echo ''?>
                            </div>
                            <?php } ?>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- performance box -->
        <div class="performance-box">
            <div class="performance-box-content">
                <h4 class="performance-box-title">Performanta clasei</h4>
                <div id="chart" style="width: 590px; height: 260px; top: 20px;"></div>
            </div>
        </div>

    <!-- best classmates -->
        <div class="best-classmates">
            <div class="best-classmates-content">
                <h4 class="best-classmates-title">Cei mai buni elevi</h4>
                <p class="best-classmates-attention">Tot timpul</p>
                <div class="best-classmates-ellipses">
                    <?php if(isset($best_students[0])) { ?>
                    <div class="best-classmates-first-box">
                        <div class="firstplace-initials">
                            <p class="firstplace-initials-text"><?php echo $best_students[0]['initials'] ?></p>
                        </div>
                        <p class="firstplace-student"> <?php echo $best_students[0]['name']; ?> </p>
                        <p class="firstplace-grade"> Media <?php echo $best_students[0]['grade'] ?> </p>
                        <img src="./styles/clasa-mea/img/first-place.png" class="firstplace-image">
                    </div>
                    <?php } ?>
                    <?php if(isset($best_students[1]))  { ?>
                    <div class="best-classmates-second-box">
                        <div class="secondplace-initials">
                            <p class="secondplace-initials-text"><?php echo $best_students[1]['initials'] ?></p>
                        </div>
                        <p class="secondplace-student"> <?php echo $best_students[1]['name']; ?> </p>
                        <p class="secondplace-grade"> Media <?php echo $best_students[1]['grade'] ?> </p>
                        <img src="./styles/clasa-mea/img/second-place.png" class="secondplace-image">
                    </div>
                    <?php } ?>
                    <?php if(isset($best_students[2])  && !empty($best_students[2]))  { ?>
                    <div class="best-classmates-third-box">
                        <div class="thirdplace-initials">
                            <p class="thirdplace-initials-text"><?php echo $best_students[2]['initials'] ?></p>
                        </div>
                        <p class="thirdplace-student"> <?php echo $best_students[2]['name']; ?> </p>
                        <p class="thirdplace-grade"> Media <?php echo $best_students[2]['grade'] ?> </p>
                        <img src="./styles/clasa-mea/img/third-place.png" class="thirdplace-image">
                    </div>
                    <?php } ?>
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

Highcharts.chart("chart", {
    chart: {
        type: "column"
    },
    title: {
        text: " "
    },
    xAxis: {
        categories: ["Clasa <?php echo $class_id; ?>"]
    },
    yAxis: {
        title: {
            text: "Medie"
        }
    },
    series: [{
        name: "Medie",
        data: [<?php echo $average_grade; ?>]
    }]
});


</script>