<?php
define('BASEPATH', true);
session_start();
require('backend/config/db.php');

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
    <?php include './important/rightbar.php'; ?>
    <?php include './important/sidebar.php'; ?>


    <div class="documents-box">
        <div class="documents-box-content">
            <div class="documents-box-content-title">
                <h1>Documente Consiliul de Administrație</h1>
            </div>
            <div class="documents-box-content-documents">
            <?php foreach($documentsCA as $documentca) { ?>
                    <?php if($documentca['document_type'] == 1) { ?> 
                        <i class="fa-solid fa-file-pdf fa-xl"></i> 
                    <?php } else if($documentca['document_type'] == 2) { ?> 
                        <i class="fa-solid fa-file-excel fa-xl"></i>
                    <?php } else if($documentca['document_type'] == 3) { ?> 
                        <i class="fa-solid fa-file-word fa-xl"></i> <?php } ?>
                    <?php if($documentca['document_download'] == 1) { ?> <a href="' . $documentca['document_path'] . '"> <?php } ?> 
                        <h4 class="document-title"><?php echo $documentca['document_name'] ?></h4>
                    <h6 class="document-uploaded"><?php echo $documentca['document_uploaded'] ?></h6>
            </div>
        </div>
        <?php } ?>
            </div>

        <div class="contabilitate-box">
            <div class="contabilitate-box-content">
                <div class="contabilitate-box-content-title">
                    <h1>Documente Contabilitate</h1>
                </div>
                <div class="contabilitate-box-content-documents">
                <?php foreach($documentsCONTAB as $documentcontab) { ?>
                    <?php if($documentcontab['document_type'] == 1) { ?> 
                        <i class="fa-solid fa-file-pdf fa-xl"></i> 
                    <?php } else if($documentcontab['document_type'] == 2) { ?> 
                        <i class="fa-solid fa-file-excel fa-xl"></i>
                    <?php } else if($documentcontab['document_type'] == 3) { ?> 
                        <i class="fa-solid fa-file-word fa-xl"></i> <?php } ?>
                    <?php if($documentcontab['document_download'] == 1) { ?> <a href="' . $documentcontab['document_path'] . '"> <?php } ?> 
                    <h4 class="document-title"><?php echo $documentcontab['document_name'] ?></h4>
                    <h6 class="document-uploaded"><?php echo $documentcontab['document_uploaded'] ?></h6>
                </div>
                <?php } ?>
            </div>
        </div>


        <div class="consiliu-box">
            <div class="consiliu-box-content">
                <div class="consiliu-box-content-title">
                    <h1>Documente Consiliu Școlar</h1>
                </div>
                <div class="consiliu-box-content-documents">
                <?php foreach($documentsCSE as $documentcse) { ?>
                    <?php if($documentcse['document_type'] == 1) { ?> 
                        <i class="fa-solid fa-file-pdf fa-xl"></i> 
                    <?php } else if($documentcse['document_type'] == 2) { ?> 
                        <i class="fa-solid fa-file-excel fa-xl"></i>
                    <?php } else if($documentcse['document_type'] == 3) { ?> 
                        <i class="fa-solid fa-file-word fa-xl"></i> <?php } ?>
                    <?php if($documentcse['document_download'] == 1) { ?> <a href="' . $documentcse['document_path'] . '"> <?php } ?> 
                    <h4 class="document-title"><?php echo $documentcse['document_name'] ?></h4>
                    <h6 class="document-uploaded"><?php echo $documentcse['document_uploaded'] ?></h6>
                </div>
            </div>
            <?php } ?>
        </div>

    </div> 
</body>
</html>