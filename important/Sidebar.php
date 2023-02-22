
<?php
require('backend/config/db.php');
$stmt = $pdo->prepare("SELECT rank FROM users WHERE email=:email");
$stmt->bindParam(':email', $_SESSION['user']);   
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800;900&display=swap');

 *{
    font-family: 'Poppins', sans-serif;
    font-size: 800;
    margin:0;
    padding:0;
    box-sizing:border-box;
 }
 body{
    min-height:100vh;
    background-color: #fff ;
    left: 30px;
 }

 .sidebar{
    position:fixed;
    bottom: 40px;
    width: 300px;
    border-radius: 10px;
    box-sizing:initial;
    background-color : #fff ;
   
 } 

 .sidebar ul{
    position:absolute;
    top: 400px;
    left:0;
    width:100%;
    padding-left:1px;
    padding-top:20px;
    
 }

 .sidebar ul li{
  position:relative;
    list-style:none;
    width:100%;
    transition:0.5s;
    font-size: 1.2em;

 }
 .sidebar ul li.active{

background: #F0F7FF;
border-left:5px solid #0d80f2 ;
font-size: 1.4em;
font-weight: 600;
color: #0d80f2;
 
 }

 
 .sidebar ul li a{
    position:relative;
    display:block;
    width:100%;
    display:flex;
    text-decoration:none;
    color:#8A8A8A;
 }
 .sidebar ul li.active a{
color:#0d80f2 ;

 
 }
 
 .sidebar ul li a .icon{
    position:relative;
    display:block;
    min-width:60px;
    height:60px;
    line-height: 61px;
    text-align:center;
    
 }
 .sidebar ul li a .icon.active{
   color:#8A8A8A;
 }
 .sidebar ul li a .icon{
   color:#8A8A8A;
font-size:1.5em;

 }
 .sidebar ul li a .title{
    position:relative;
    display:block;
    padding-left:30px;
    height:60px;
    line-height:60px;
    white-space:normal; 
    color:#8A8A8A;
 }

.sidebar ul title.active {
   color: #0d80f2;

}

.sidebar ul li:hover{
   color:#0d80f2;
   background:#fff;
   
 }
 .sidebar ul li a:hover{
   color:#0d80f2;
}
 .sidebar ul li:hover .title , .sidebar ul li:hover .icon
 
 { color:#0d80f2; }
 .sidebar ul li.active.title {
   color:#0d80f2; 
 }
.sidebar ul li:nth-child(7) {
color:red;
}

.logo {
   position: relative;
   left: 50px;
   top: 40px;
   color: #0d80f2;
}

.fa-solid{
   scale:0.8;
}


</style>

<body>


<div class="sidebar" style="left: 0px; height: 100%; top: 0px; border-radius: 4 0 px;">
    <div class="logo">
        <h2> <img src='https://starquess.kodikas.ro/upload/logo.png' width='220px' height='55px' alt='logo' style="position: relative; top: 15px;"/> </h2>
    </div>

    <ul style="top: 190px;">
    <?php if($is_administrator == 0) { ?>
        <li class="list active" style="left: 30px;">
            <a href="../dashboard.php">
            <span class="icon"><i class="fa-solid fa-house"></i></span>
            <span class="title">Acasa</span>
            </a>
        </li>
        <?php } ?>

        <?php if($user['rank'] == 1 || $user['rank'] == 11 && $is_administrator == 0) { ?> <li class="list" style="left: 30px">
            <a href="../clasa-mea.php">
            <span class="icon"><i class="fa-solid fa-users"></i></span>
            <span class="title">Clasa mea</span>
            </a>
        </li>

       <!-- <li class="list" style="left: 30px">
            <a href="#">
            <span class="icon"><i class="fa-solid fa-messages"></i></span>
            <span class="title">Mesaje</span>
            </a>
        </li> -->

        <li class="list" style="left: 30px">
            <a href="../situatie-scolara.php">
            <span class="icon"><i class="fa-solid fa-bookmark"></i></span>
            <span class="title">Situatie scolara</span>
            </a>
        </li>

        <li class="list" style="left: 30px">
            <a href="../documente.php">
            <span class="icon"><i class="fa-solid fa-file"></i></span>
            <span class="title">Documente</span>
            </a>
        </li>

        <li class="list" style="left: 30px">
            <a href="../setari.php">
            <span class="icon"><i class="fa-solid fa-gear"></i></span>
            <span class="title">Setari</span>
            </a>
        </li>
         <?php } else if($user["rank"] == 2) { ?>
         <li class="list" style="left: 30px">
            <a href="../clase.php">
            <span class="icon"><i class="fa-solid fa-users"></i></span>
            <span class="title">Clase</span>
            </a>
         </li>
         
         <li class="list" style="left: 30px">
            <a href="../documente.php">
            <span class="icon"><i class="fa-regular fa-user-shield"></i></span>
            <span class="title">Elevi</span>
            </a>
         </li>
         
         <?php } ?>
         <?php if($user["rank"] == 11 && $is_administrator == 0) { ?>
         <li class="list" style="left: 30px">
               <a href="../admin.php">
               <span class="icon"><i class="fa-solid fa-hammer"></i></span>
               <span class="title">Administrativ</span>
               </a>
         </li>
         <?php } else if($user["rank"] == 11 && $is_administrator == 1) { ?>
            <li class="list" style="left: 30px; top: 450px;">
               <a href="../dashboard.php">
               <span class="icon"><i class="fa-solid fa-home"></i></span>
               <span class="title">Înapoi acasă</span>
               </a>
         </li>
         <?php } ?>

         <?php if($is_administrator == 1) { ?>
         
         
         <li class="list active" style="left: 30px">
            <a href="../administrator.php">
            <span class="icon"><i class="fa-solid fa-home"></i></span>
            <span class="title">Dashboard</span>
            </a>
         </li>

         <li class="list" style="left: 30px">
            <a href="../cadre-didactice.php">
            <span class="icon"><i class="fa-solid fa-users"></i></span>
            <span class="title">Cadre didactice</span>
            </a>
         </li>

         <li class="list" style="left: 30px">
            <a href="../clase.php">
            <span class="icon"><i class="fa-solid fa-chalkboard-user"></i></span>
            <span class="title">Clase</span>
            </a>
         </li>

         <li class="list" style="left: 30px">
            <a href="../elevi.php">
            <span class="icon"><i class="fa-solid fa-graduation-cap"></i></span>
            <span class="title">Elevi</span>
            </a>
         </li>

         <li class="list" style="left: 30px">
            <a href="../orar.php">
            <span class="icon"><i class="fa-solid fa-clock"></i></span>
            <span class="title">Orar</span>
            </a>
         </li>


         <li class="list" style="left: 30px" data-bs-toggle="dropdown" aria-expanded="false">
            <a href="../administrator.php">
            <span class="icon"><i class="fa-solid fa-hammer"></i></span>
            <span class="title">Acțiuni admin</span>
            </a>
            <ul class="dropdown-menu">
               <li><a class="dropdown-item" href="../administrator.php">Administrativ</a></li>
               <li><a class="dropdown-item" href="../administrator.php">Cadre didactice</a></li>
               <li><a class="dropdown-item" href="../administrator.php">Clase</a></li>
               <li><a class="dropdown-item" href="../administrator.php">Elevi</a></li>
               <li><a class="dropdown-item" href="../administrator.php">Orar</a></li>
            </ul>
         </li>

         
            
         <?php } ?>

        <li class="list" style="left: 30px; top: 190px; color: gray;">
            <a href="../signout.php">
            <span class="icon"  style="color:gray;"><i class="fa-solid fa-door-open"></i></span>
            <span class="title" style="color:gray;">Deconectare</span>
            </a>
        </li>
    </ul>
</div>


</body>