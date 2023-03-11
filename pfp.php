<?php
session_start();
define('BASEPATH', true);

if(!isset($_SESSION["user"])) {
  header("Location: login.php");
  exit();
}

require('backend/config/db.php');

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
    <title>Login</title>
    <link rel="stylesheet" href="styles/FontAwesome/css/all.css">
    <link rel="stylesheet" href="styles/register/all.css">
    <link rel="stylesheet" href="styles/register/interests.css">
    <link rel="stylesheet" href="styles/register/radio.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.all.min.js"></script>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
</head>
<body>
<nav class="nav-container">
  <div class="logo">
    <div class="circle"></div>
    <h2>Starquess<span class="fullstop">.</span></h2>
  </div>
 
  <ul class="nav-list">
    <li class="nav-items"><a href="/">Acasă</a></li>
  </ul>
</nav>

<section class="container">
  <div class="form-container">
    <div class="section-header">
      <h1 class="primary-heading">
        Bună, <?php echo $cont['name'] ?><span class="fullstop">.</span>
      </h1>
      <h2 class="secondary-heading">
        Pentru că la <span class="fullstop">Starquess</span> ne pasă de elev și de cum consideră el că își vrea viața online, îți oferim șansa de a avea o poză de profil.
      </h2>
    </div>
    <form action="backend/queries/upload-profilep.php" class="dropzone" id="my-dropzone">
    <button id="next-button" class="primary-btn" style="display: none;">Mai departe</button>
    </form>
  </div>
  <div class="side-panel">
    <div class="background"></div>
  </div>
</section>
</body>
</html>

<script>
// Initialize Dropzone.js
Dropzone.autoDiscover = false;
var myDropzone = new Dropzone("#my-dropzone", { url: "backend/queries/upload-profilep.php" });

// Listen for the "success" event
myDropzone.on("success", function(file, response) {
    // Show the "Next" button if the upload was successful
    if (response == "success") {
        $("#next-button").show();
    }
});

// Listen for the "complete" event
myDropzone.on("complete", function() {
    // Redirect to the next page when the upload is complete
    window.location.href = "index.php";
});
</script>