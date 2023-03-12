<?php
define('BASEPATH', true);
session_start();
require('../config/db.php');
$is_administrator = 0;

$stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email"); 
$stmt -> bindParam(':email', $_SESSION['user']);

$stmt->execute(); 


$conturi = $stmt->fetchAll(); 

foreach($conturi as $cont);


// Check if the file was uploaded without errors
if(isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
    $userId = $cont['id'];
    $target_dir = "../../uploads/";
    $target_file = $target_dir . basename($_FILES["file"]["name"]);

    // Move the file to the uploads directory
    if(move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {

        // Insert the file information into the database
        $stmt = $pdo->prepare("INSERT INTO user_images (user, url, lastUpload) VALUES (:user, :url, NOW())");
        $stmt->bindParam(':user', $userId); // Replace $userId with the actual user ID
        $stmt->bindParam(':url', $target_file);
        $stmt->execute();

        echo "The file ". htmlspecialchars( basename( $_FILES["file"]["name"])). " has been uploaded and inserted into the database.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
} else {
    echo "Sorry, there was an error uploading your file.";
}