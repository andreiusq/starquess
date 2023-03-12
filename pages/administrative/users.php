<?php
define('BASEPATH', true);
session_start();
require('../../backend/config/db.php');

if(!isset($_SESSION["user"])) {
  header("Location: login.php");
  exit();
}

$is_administrator = 1;

// users logic 2
$stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email"); 
$stmt -> bindParam(':email', $_SESSION['user']);

$stmt->execute(); 


$conturi = $stmt->fetchAll(); 

foreach($conturi as $cont);

$school_id = $cont['school_id'];

// search users
$statement = $pdo->prepare("SELECT * FROM users WHERE school_id = :school_id");
$statement->execute(array(':school_id' => $school_id));
$results = $statement->fetchAll(PDO::FETCH_ASSOC);


// count users
$sql = "SELECT COUNT(*) FROM users";
$stmt = $pdo->query($sql);
$userscount = $stmt->fetchColumn();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preload" href="../../styles/FontAwesome/css/all.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="stylesheet" href="../../styles/administrator/students.css">
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
    <script src="https://unpkg.com/@andreasremdt/simple-translator@latest/dist/umd/translator.min.js" defer></script>
</head>
<body>
    

    <?php include '../../important/Rightbar.php'; ?>
    <?php include '../../important/Sidebar.php'; ?>

    <div class="stats-box">
        <div class="stats-box-item">
                <span class="rounded-circle"><i class="fa-solid fa-users fa-2xl" style="top: 20px; left: 15px; color: #0077ff;"></i></span>
                <h1 class="stats-box-item-title" style="position: absolute; left: 40px;">Users</h1>
                <h2 class="stats-box-item-number"><?php echo $userscount ?></h2>
            </div>
    </div>

    <div class="students-box">
        <div class="students-box-content">
            <h1 class="students-title">Listă elevi</h1>
            <div class="students-box-list">
                <div class="students-box-list-item">
                    <div class="students-box-list-item-content">
                        <div class="students-box-list-item-content-center">
                            <div class="students-box-list-item-content-center-text">
                                <form onsubmit="event.preventDefault(); addStudent();">
                                    <a href=""> <p> Adaugă elevi </p> </a>
                                </form>
                                <form onsubmit="event.preventDefault(); importStudents();">
                                    <button type="submit" class="import-stud">Importă elevi</button>
                                </form>
                            </div>
                            <?php if (count($results) > 0) {
                                foreach ($results as $row) { ?>
                            <div class="students-box-list-item-content-left-text">
                                <h1 class="students-box-list-item-content-left-text-name"><?php echo $row['name']; echo ' ';  echo $row['last_name']; ?></h1>
                                <p class="students-box-list-item-content-left-text-email"><?php echo $row['email'] ?></p>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>


</body>
</html>

<script>
    function importStudents() {
        const { value: fruit } = Swal.fire({
            title: 'Te rog să alegi tipul de import',
            input: 'select',
            inputOptions: {
                'Tip': {
                automat: 'Import automat',
                siir: 'Import din SIIR',
                manual: 'Import manual'
                },
            },
            inputPlaceholder: 'Selectează tip import',
            showCancelButton: true,
            inputValidator: (value) => {
                return new Promise((resolve) => {
                if (value === 'automat') {
                    const { value: file } = Swal.fire({
                        title: 'Selectează excel',
                        input: 'file',
                        inputAttributes: {
                            'accept': 'xslx/*',
                            'aria-label': 'Încarcă fișier'
                        }
                        })

                        if (file) {
                        const reader = new FileReader()
                        reader.onload = (e) => {
                            Swal.fire({
                            title: 'Fișier încărcat'
                            })
                        }
                        reader.readAsDataURL(file)
                        }
                } else if(value === 'siir') {
                    const { value: file } = Swal.fire({
                        title: 'Selectează excel / csv',
                        input: 'file',
                        inputAttributes: {
                            'accept': 'xslx/csv/*',
                            'aria-label': 'Încarcă fișier'
                        }
                        })

                        if (file) {
                        const reader = new FileReader()
                        reader.onload = (e) => {
                            Swal.fire({
                            title: 'Fișier încărcat'
                            })
                        }
                        reader.readAsDataURL(file)
                        }
                } else if(value === 'manual') {
                   resolve('Funcționalitatea nu este disponibilă momentan :(');
                } else {
                    resolve('Nu poți selecta altceva :)')
                }
                })
            }
            })
    }
</script>