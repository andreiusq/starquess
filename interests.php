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
        Pentru că la <span class="fullstop">Starquess</span> ne pasă de elev, trebuie să îți alegi niște interese.
      </h2>
    </div>
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" class="form">
      <div class="form-input">
        <div class="wrapper">
          <div class="rand-3">
          <div class="interes">
             <img src="styles/register/img/chimie.png" id="chimie" class="image"/>
               <span class="nume">Chimie</span>
              </div>


             <div class="interes">
             <img src="styles/register/img/robotica.png" id="chimie" class="image"/>
             <span class="nume">Robotica</span>
               </div>


             <div class="interes">
             <img src="styles/register/img/kodikas.png" id="cod" class="image"/>
              <span class="nume">Programare</span>
               </div>
          </div>
             <div class="rand-2">
             <div class="interes">
             <img src="styles/register/img/debate.jpg" id="debate" class="image"/>
               <span class="nume">Debate</span>
              </div>


             <div class="interes">
             <img src="styles/register/img/sport.png" id="sport" class="image"/>
              <span class="nume">Sport</span>
               </div>
             </div>

             <div class="rand-3">
          <div class="interes">
             <img src="styles/register/img/Biologie.png" id="Biologie" class="image"/>
               <span class="nume">Biologie</span>
              </div>


             <div class="interes">
             <img src="styles/register/img/romana.png" id="romana" class="image"/>
             <span class="nume">Romana</span>
               </div>


             <div class="interes">
             <img src="styles/register/img/matematica.png" id="mate" class="image"/>
              <span class="nume">Matematica</span>
               </div>
          </div>
             
          <div class="rand-2">
             <div class="interes">
             <img src="styles/register/img/geografie.png" id="Geografie" class="image"/>
               <span class="nume">Geografie</span>
              </div>


             <div class="interes">
             <img src="styles/register/img/engleza.png" id="engleza" class="image"/>
              <span class="nume">Engleza</span>
               </div>
             </div>
   

        </div>
        <div class="btn-input">
          <div type="submit" name="submit" class="primary-btn">Gata</div>
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