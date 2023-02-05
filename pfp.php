<?php
session_start();
define('BASEPATH', true);

if(!isset($_SESSION["user"])) {
  header("Location: login.php");
  exit();
}

require('backend/config/db.php');
$stmt = $pdo->prepare("SELECT name FROM users WHERE email=:email");
$stmt->bindParam(':email', $_SESSION['user']);   
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);


// upload logic
$stmt = $pdo->prepare("SELECT * from user_images WHERE user=:email");
$stmt->bindParam(':email', $_SESSION['user']);
$stmt->execute();

$images = $stmt->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['submit'])) {
  $countfiles = count($_FILES['files']['name']);
  $query = "INSERT INTO user_images (user,url) VALUES(?,?)";
  $statement = $conn->prepare($query);

  for($i = 0; $i < $countfiles; $i++) {

      $filename = $_FILES['files']['name'][$i];
      $target_file = './uploads/'.$filename;

      $file_extension = pathinfo(
          $target_file, PATHINFO_EXTENSION);
           
      $file_extension = strtolower($file_extension);

      $valid_extension = array("png","jpeg","jpg");
    
      if(in_array($file_extension, $valid_extension)) {

          if(move_uploaded_file(
              $_FILES['files']['tmp_name'][$i],
              $target_file)
          ) {

              $statement->execute(
                  array($filename,$target_file));
          }
      }
  }
   
  echo "File upload successfully";
}
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
        Bună, <?php echo $user['name'] ?><span class="fullstop">.</span>
      </h1>
      <h2 class="secondary-heading">
        Pentru că la <span class="fullstop">Starquess</span> ne pasă de elev și de cum consideră el că își vrea viața online, îți oferim șansa de a avea o poză de profil.
      </h2>
    </div>
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" class="form" enctype=”multipart/form-data”>
      <div class="form-input">
          <input type="file" id="file" name="files[]">
          
        <div class="btn-input">
          <button type="submit" name="submit" class="primary-btn">Gata</button>
        </div>
      </div>
    </form>
  </div>
  <div class="side-panel">
    <div class="background"></div>
  </div>
</section>
</body>
</html>