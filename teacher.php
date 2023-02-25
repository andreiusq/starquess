<?php
define('BASEPATH', true);
session_start();
require('backend/config/db.php');
$is_administrator = 0;


//rank logic
// users logic 2
$stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email"); 
$stmt -> bindParam(':email', $_SESSION['user']);

$stmt->execute(); 


$conturi = $stmt->fetchAll(); 

foreach($conturi as $cont);

if($cont['rank'] < 2) {
    header("Location: index.php");
    exit();
}


if(!isset($_SESSION["user"])) {
  header("Location: login.php");
  exit();
}


//teacher
$teacher_email = $_SESSION['user'];
$statement = $pdo->prepare("SELECT * FROM teachers WHERE teacher_email = :teacher_email");
$statement->execute(array(':teacher_email' => $teacher_email));

$classes = $statement->fetchAll(PDO::FETCH_ASSOC);


// news logic
$statement = $pdo->prepare("SELECT * FROM news ORDER BY posted_at DESC");
$statement->execute();
$news = $statement ->fetchAll(PDO::FETCH_ASSOC);


// 
// Prepare the SQL query to count the number of users
$sql = "SELECT COUNT(*) FROM users";

// Execute the SQL query
$stmt = $pdo->query($sql);

// Fetch the result and display it
$count = $stmt->fetchColumn();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <link rel="preload" href="styles/FontAwesome/css/all.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="stylesheet" href="styles/dashboard/teacher.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.all.min.js"></script>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
    <script src="https://unpkg.com/@andreasremdt/simple-translator@latest/dist/umd/translator.min.js" defer></script>
</head>
<body>
    
    <?php include './important/Rightbar.php'; ?>
    <?php include './important/Sidebar.php'; ?>

    <!-- stats -->

    <div class="all-students-stats">
        <div class="all-students-stats-content">
            <div class="all-students-stats-circle">
                <div class="all-students-stats-circle-content">
                    <div class="all-students-stats-content-icon">
                        <i class="fas fa-user-graduate fa-2xl"></i>
                    </div>
                </div>
                    <div class="all-students-stats-content-text">
                        <h1 class="students-title">Elevi</h1>
                        <h2 class="students-number"><?php echo $count; ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="news-box">
        <div class="news-top">
            <h1 class="news-title">Noutati</h1>
            <?php if($user["rank"] == 11 || $user["rank"] == 4): ?>
            <div class="news-top-buttons">
                <button class="news-top-buttons-add" onclick="adaugaanunt(event)"><i class="fas fa-plus"></i></button>
                <button class="news-top-buttons-delete" onclick="deleteResponse(id)"><i class="fas fa-trash"></i></button>
            </div>
            <?php endif; ?>
            <?php   foreach ($news as $row) { ?>
            <div class="news-box-news-content">
                <div class="news-box-news-content-news-ellipse">
                    <p class="news-box-news-content-news-ellipse-text"><?php echo $row['text'];?></p>
            </div>
            </div>
            <?php } ?>
        </div>
    </div>


    <div class="classes-box">
      <div class="classes-top">
        <h1 class="classes-title">Clase</h1> <br>
        <?php if(count($classes) > 0) { ?>
          <?php foreach($classes as $row) { ?>
            <div class="classes-box-content">
              <div class="classes-box-content-classes-ellipse">
                <p class="classes-box-content-classes-ellipse-text"><a class="classes-box-content-classes-ellipse-text-href" href="https://starquess.kodikas.ro/class.php?class=<?php echo $row['class_id'] ?>"><?php echo $row['class_name'];?></a></p>
              </div>
            </div>
        <?php } 
          }
        ?>
      </div>
    </div>

</body>
</html>

<script>
const regexPattern = /\b(ass|asshole|bastard|bitch|crap|damn|fuck|shit|muie)\b/i;
const inputText = document.getElementsByClassName('adauga-anunt');
if (regexPattern.test(inputText)) {
  Swal.fire('Oops...', 'Please refrain from using inappropriate language.', 'error');
} else {
  // send announcement to database
}



    async function adaugaanunt(e) {
        e.preventDefault();
        Swal.fire({
  title: 'Introdu anunțul',
  input: 'textarea',
  showCancelButton: true,
  confirmButtonText: 'Submit',
  showLoaderOnConfirm: true,
  customClass: 'adauga-anunt',
  preConfirm: (inputValue) => {
    return fetch(`submit.php?action&input=${inputValue}`)
      .then(response => {
        if (!response.ok) {
          throw new Error(response.statusText)
        }
        return response.text()
      })
      .catch(error => {
        Swal.showValidationMessage(
          `Request failed: ${error}`
        )
      })
  },
  allowOutsideClick: () => !Swal.isLoading()
}).then((result) => {
  if (result.value) {
    Swal.fire({
      title: `Succes!`,
      text: result.value,
      icon: success
      
    })
    document.location.reload();
  }
})
}

    function deleteResponse(id) {
  Swal.fire({
    title: 'Ești sigur?',
    text: "Ești sigur că vrei să ștergi anunțul? Datele nu mai pot fi recuperate!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Da, șterge!'
  }).then((result) => {
    if (result.value) {
      fetch(`submit.php?action=delete&input=${id}`)
        .then(response => {
          if (!response.ok) {
            throw new Error(response.statusText)
          }
          return response.text()
        })
        .then(result => {
          Swal.fire({
            title: 'Șters!',
            text: "Anunțul a fost șters cu succes!",
            icon: 'success'
          })
          document.location.reload();
        })
        .catch(error => {
          Swal.fire({
            title: 'Eroare',
            text: error.message,
            icon: 'error'
          })
        })
    }
  })
}



</script>

