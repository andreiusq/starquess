<?php
define('BASEPATH', true);
require 'backend/config/db.php';

 if(isset($_POST['submit'])){  
        try {
            $dsn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
            $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


  
         $fname = $_POST['fname'];
         $lname = $_POST['lname'];
         $email = $_POST['email'];
         $pass = $_POST['pass'];

         $pass = password_hash($pass, PASSWORD_BCRYPT, array("cost" => 12));
          
         //Check if email exists
         $sql = "SELECT COUNT(email) AS num FROM users WHERE email =      :email";
         $stmt = $pdo->prepare($sql);

         $stmt->bindValue(':email', $email);
         $stmt->execute();
         $row = $stmt->fetch(PDO::FETCH_ASSOC);

         if($row['num'] > 0){
             echo '<script>Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Adresa de mail există deja!"
              })</script>';
        }
        
       else{

    $stmt = $dsn->prepare("INSERT INTO users (email, name, last_name, password) 
    VALUES (:email,:fname, :lname, :password)");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':fname', $fname);
    $stmt->bindParam(':lname', $lname);
    $stmt->bindParam(':password', $pass);
    
    

   if($stmt->execute()){
    echo '<script>let timerInterval
    Swal.fire({
      title: "Procesare...",
      timer: 2000,
      didOpen: () => {
        Swal.showLoading()
      }
    }).then((result) => {
      if (result.dismiss === Swal.DismissReason.timer) {
        Swal.fire(
        "Cont creat",
        "Îți mulțumim pentru înregistrare!",
        "succes"
        )
      }
    })
    });</script>';
    //redirect to another page
    echo '<script>window.location.replace("index.php")</script>';
     
   }else{
       echo '<script>Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Something went wrong!",
        footer: "<a href="">Why do I have this issue?</a>"
      })</script>';
   }
}
}catch(PDOException $e){
    $error = "Error: " . $e->getMessage();
    echo '<script type="text/javascript">alert("'.$error.'");</script>';
}
     }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styles/FontAwesome/css/all.css">
    <link rel="stylesheet" href="styles/register/all.css">
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
    <li class="nav-items"><a href="/join">Înregistrare</a></li>
  </ul>
</nav>
<section class="container">
  <div class="form-container">
    <div class="section-header">
      <h1 class="primary-heading">
        Înregistrează-te<span class="fullstop">.</span>
      </h1>
      <h2 class="secondary-heading">
        Deja ai un cont? <a class="login-link" href="/login">Logare</a>
      </h2>
    </div>
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" class="form">
      <div class="form-input">
        <div class="name-input">
          <input type="text" name="fname" id="fname" placeholder="Nume" required="required" />
          <input type="text" name="lname" id="lname" placeholder="Prenume" required="required" />
        </div>
        <input type="email" name="email" id="email" placeholder="Email" required="required" />
        <input type="password" name="pass" id="pass" placeholder="Parolă" required="required" />
        <div class="btn-input">
          <button type="submit" name="submit" class="primary-btn">Creează cont</button>
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