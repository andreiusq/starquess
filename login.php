<?php
define('BASEPATH', true);
require 'backend/config/db.php';
session_start();

if(isset($_POST['submit'])){  
            $dsn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
            $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $email = !empty($_POST['email']) ? trim($_POST['email']) : null;
    $passwordAttempt = !empty($_POST['pass']) ? trim($_POST['pass']) : null;
    
    $sql = "SELECT id, email, name, last_name, rank, school_id, status, interests, password FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    
    $stmt->bindValue(':email', $email);
    
    $stmt->execute();
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
  
    if($user === false){
      echo '<script>Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Something went wrong!",
        footer: "<a href="">Why do I have this issue?</a>"
      })</script>';
    } else{
         
        $validPassword = password_verify($passwordAttempt, $user['password']);
        
        if($validPassword){
             
            $_SESSION['user'] = $email;
            if($user['interests_set'] == 1) {
              $_SESSION['interests_set'] = true;
              echo '<script>window.location.replace("index.php");</script>';
            }
            else {
              $_SESSION['interests_set'] = false;
              echo '<script>window.location.replace("index.php");</script>';
            }
            exit;
            
        } else{
          echo '<script>Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Something went wrong!",
            footer: "<a href="">Why do I have this issue?</a>"
          })</script>';
        }
    }
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.all.min.js"></script>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
<!-- Matomo -->
<script>
  var _paq = window._paq = window._paq || [];
  /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u="//team.starquess.ro/metrics/";
    _paq.push(['setTrackerUrl', u+'matomo.php']);
    _paq.push(['setSiteId', '1']);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<!-- End Matomo Code -->

</head>
<body>
<nav class="nav-container">
  <div class="logo">
    <img src="https://cdn.discordapp.com/attachments/987759466495746159/1074133800050774107/Asset_1.png" style="width: 40px; height: 50px;" class="imagine" />
    <h2>Starquess<span class="fullstop">.</span></h2>
  </div>

  <ul class="nav-list">
    <li class="nav-items"><a href="/">Acasă</a></li>
    <li class="nav-items"><a href="/login.php">Logare</a></li>
  </ul>
</nav>
<section class="container">
  <div class="form-container">
    <div class="section-header">
      <h1 class="primary-heading">
        Logare<span class="fullstop">.</span>
      </h1>
      <h2 class="secondary-heading">
        Nu ai un cont? <a class="login-link" href="/register.php">Înregistrare</a>
      </h2>
    </div>
    <form action="" method="post" class="form">
      <div class="form-input">
        <input type="email" name="email" id="email" placeholder="Email" required="required" />
        <input type="password" name="pass" id="pass" placeholder="Parolă" required="required" />
        <div class="btn-input">
          <button type="submit" name="submit" class="primary-btn">Logare</button>
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