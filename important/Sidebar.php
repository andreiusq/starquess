
<?php
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

/* Dropdown container */
.dropdown {
  position: relative;
  display: inline-block;
}

/* Dropdown button */
.dropbtn {
  background-color: #4CAF50;
  color: white;
  padding: 10px;
  font-size: 16px;
  border: none;
  cursor: pointer;
}

/* Dropdown content */
.dropdown-content {
  display: none;
  position: absolute;
  z-index: 1;
}

/* Dropdown links */
.dropdown-content a {
  color: black;
  padding: 10px;
  text-decoration: none;
  display: block;
}

/* Show dropdown on hover */
.dropdown:hover .dropdown-content {
  display: block;
}

/* Change background color of dropdown links on hover */
.dropdown-content a:hover {
  background-color: #f1f1f1;
}


</style>

<body>


<div class="sidebar" style="left: 0px; height: 100%; top: 0px; border-radius: 4 0 px;">
    <div class="logo">
        <h2> <a href="https://starquess.ro"><img src='http://cdn.starquess.ro/logo.png' width='220px' height='55px' alt='logo' style="position: relative; top: 15px;"/> </a></h2>
    </div>

    <ul style="top: 190px;">
    <?php if($is_administrator == 0) { ?>
        <li class="list active" style="left: 30px;">
            <a href="../index.php">
               <span class="icon"><i class="fa-solid fa-house"></i></span>
               <span class="title">Acasa</span>
            </a>
        </li>
        <?php } ?>

        <?php if($user['rank'] == 1 || $user['rank'] == 11 && $is_administrator == 0 || $user['rank'] == 2) { ?> <li class="list" style="left: 30px">
            <a href="../clasa-mea.php">
               <span class="icon"><i class="fa-solid fa-users"></i></span>
               <span class="title">Clasa mea</span>
            </a>
        </li>

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

        <?php } if($user["rank"] == 2) { ?>
        <li class="list" style="left: 30px">
            <a href="../teacher.php">
               <span class="icon"><i class="fa-solid fa-gear"></i></span>
               <span class="title">Profesor</span>
            </a>
        </li>
        <?php } ?>

         <?php if($is_administrator == 1) { ?>
         <li class="list" style="left: 30px">
            <a href="./users.php">
               <span class="icon"><i class="fa-solid fa-users"></i></span>
               <span class="title">Elevi</span>
            </a>
         </li>

         <li class="list" style="left: 30px">
            <a href="./teachers.php">
               <span class="icon"><i class="fa-solid fa-user-tie"></i></span>
               <span class="title">Profesori</span>
            </a>
         </li>

         <li class="list" style="left: 30px">
            <a href="./classes.php">
               <span class="icon"><i class="fa-solid fa-users"></i></span>
               <span class="title">Clase</span>
            </a>
         </li>

         

         <li class="list" style="left: 30px">
         <div class="dropdown">
            <a href="./settings">
            <span class="icon"><i class="fa-solid fa-gear"></i></span>
            <span class="title">Setări platformă</span>
            <div class="dropdown-content">
               <a href="">Optiune 1</a>
               <a href="">Optiune 2</a>
               <a href="">Optiune 3</a>
            </div>
         </div>
         </li>
         <?php } ?>


        

        <li class="list" style="left: 30px; top: 190px; color: red;">
            <a href="../signout.php">
            <span class="icon"  style="color:red;"><i class="fa-solid fa-door-open"></i></span>
            <span class="title" style="color:red;">Deconectare</span>
            </a>
        </li>
    </ul>
</div>


</body>