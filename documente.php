<?php
define('BASEPATH', true);
session_start();
require('backend/config/db.php');
$is_administrator = 0;

if(!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
  }


// documents logic
$sql = "SELECT document_id, document_name, document_type, document_status, document_uploaded, document_path, document_download FROM documents WHERE document_show = 0";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$documentsCA = $stmt->fetchAll();

// documents contab logic
$sql = "SELECT document_id, document_name, document_type, document_status, document_uploaded, document_path, document_download FROM documents WHERE document_show = 1";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$documentsCONTAB = $stmt->fetchAll();

// documents logic
$sql = "SELECT document_id, document_name, document_type, document_status, document_uploaded, document_path, document_download FROM documents WHERE document_show = 2";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$documentsCSE = $stmt->fetchAll();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documente</title>
    <link rel="preload" href="styles/FontAwesome/css/all.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="stylesheet" href="styles/documente/all.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.all.min.js"></script>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
</head>
<body>
    <?php include './important/Rightbar.php'; ?>
    <?php include './important/Sidebar.php'; ?>


    <div class="documents-box">
        <div class="documents-box-content">
            <div class="documents-box-content-title">
                <h1>Documente Consiliul de Administrație</h1>
            </div>
            <div class="documents-box-content-documents">
                    <p>Încă nu a fost adăugat nimic aici!</p>
            </div>
        </div>

            </div>

        <div class="contabilitate-box">
            <div class="contabilitate-box-content">
                <div class="contabilitate-box-content-title">
                    <h1>Documente Contabilitate</h1>
                </div>
                <div class="contabilitate-box-content-documents">
                    <p>Încă nu a fost adăugat nimic aici!</p>
                </div>

            </div>
        </div>


        <div class="consiliu-box">
            <div class="consiliu-box-content">
                <div class="consiliu-box-content-title">
                    <h1>Documente Consiliul Școlar</h1>
                </div>
                <div class="consiliu-box-content-documents">
                    <p>Încă nu a fost adăugat nimic aici!</p>
                </div>
            </div>
        </div>

    </div> 
</body>
</html>