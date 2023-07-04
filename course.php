<?php
define('BASEPATH', true);
session_start();
require('backend/config/db.php');
$is_administrator = 0;

if(!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

$query = "SELECT * FROM courses WHERE id = :id";
$statement = $pdo->prepare($query);
$statement->bindValue(':id', $id, PDO::PARAM_INT);
$statement->execute();

 $course = $statement->fetch(PDO::FETCH_ASSOC);
 if (!$course) {
     echo '<h2 class="titlu-oopsie">Oopsie.. cursul este de negăsit! Dar nu te îngrijora, încearcă din nou!</h2>';
     exit;
 }

  $pdfPath = $course['pdf_path'];

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preload" href="styles/FontAwesome/css/all.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="stylesheet" href="styles/cursuri/all.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.all.min.js"></script>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
</head>
<body>
<?php include './important/Rightbar.php'; ?>
        <?php include './important/Sidebar.php'; ?>
      
        <iframe src="<?php echo $pdfPath; ?>" width="65%" height="900px" style="position: relative; left: 300px;"></iframe>
</body>
</html>