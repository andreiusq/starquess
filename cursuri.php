<?php
define('BASEPATH', true);
session_start();
require('backend/config/db.php');
$is_administrator = 0;

if(!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

// cursuri count
$query = "SELECT COUNT(*) AS row_count FROM courses";
$stmt = $pdo->query($query);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$courses_count = $result['row_count'];

// teste count
$query = "SELECT COUNT(*) AS row_count FROM tests";
$stmt = $pdo->query($query);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$tests_count = $result['row_count'];


// get all courses from the database
$query = "SELECT id, course_name, course_author, course_link, course_image FROM courses LIMIT 5";
$stmt = $pdo->query($query);
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cursuri</title>
    <link rel="preload" href="styles/FontAwesome/css/all.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="stylesheet" href="styles/cursuri/all.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.all.min.js"></script>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
</head>
<body>

        <?php include './important/Rightbar.php'; ?>
        <?php include './important/Sidebar.php'; ?>

        <!-- stats -->

        <div class="stats">
            <div class="all-courses">
                <div class="rounded-circle">
                    <i class="fa-solid fa-book bookk fa-2xl"></i>
                </div>
                <div class="all-courses-text">
                    <h3 class="all-courses-title"><?php echo $courses_count ?></h3>
                    <p class="all-courses-desc">Cursuri</p>
                </div>
            </div>

            <div class="all-tests">
                <div class="rounded-circle">
                    <i class="fa-solid fa-book-circle-arrow-up bookk fa-2xl"></i>
                </div>
                <div class="all-tests-text">
                    <h3 class="all-tests-title"><?php echo $tests_count ?></h3>
                    <p class="all-tests-desc">Teste</p>
                </div>
            </div>

            <div class="all-favorite">
                <div class="rounded-circle">
                    <i class="fa-solid fa-book-circle-arrow-right bookk fa-2xl"></i>
                </div>
                <div class="all-favorite-text">
                    <h3 class="all-favorite-title">0</h3>
                    <p class="all-favorite-desc">Cursuri favorite</p>
                </div>
            </div>
        </div>
        <!-- end of stats -->
    
        <div class="content">
        <!-- cursurile tale -->
            <div class="cursuri-box">
                <div class="cursuri-box-head">
                    <h3 class="cursuri-box-title">Cursurile tale</h3>
                    <a href="#" class="cursuri-box-link">Vezi toate cursurile</a> <i class="fa-regular fa-arrow-right sagetuta"></i>
                </div>
                <div class="cursuri-box-content">
                    <div class="curs">
                        <img class="curs-image" src="https://via.placeholder.com/144x146" />
                        <div class="curs-box"></div>
                        <div class="curs-box-title">Titlu</div>
                    </div>
                </div>
            </div>

            <div class="cursuri-recomandate-box">
                <div class="cursuri-recomandate-box-head">
                    <h3 class="cursuri-box-title">Cursuri recomandate pentru tine</h3>
                    <a href="#" class="cursuri-box-link">Vezi toate cursurile</a> <i class="fa-regular fa-arrow-right sagetuta"></i>
                </div>
                <div class="cursuri-recomandate-box-content">
                    <?php foreach ($courses as $course): ?>
                        <div class="curs">
                            <img class="curs-image" src="<?php echo $course['course_image']; ?>" />
                            <div class="curs-box"></div>
                            <div class="curs-box-title"><?php echo $course['course_name']; ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

</body>
</html>