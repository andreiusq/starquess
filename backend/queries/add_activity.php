<?php

define('BASEPATH', true);
require('../config/db.php');
include("./important/Rightbar.php");

$stmt = $pdo->prepare("SELECT id FROM users WHERE email=:email"); 
$stmt -> bindParam(':email', $_SESSION['user']);

$stmt->execute(); 


$conturi = $stmt->fetchAll(); 

// get the POST data
$name = $_POST['name'];
$desc = $_POST['desc'];
$date = $_POST['date'];
$time = $_POST['time'];
$user_id = $_POST['user_id'];

// validate the data
if (!$name || !$desc || !$date || !$time || !$user_id) {
  $response = array(
    'success' => false,
    'message' => 'Please provide all the required data.'
  );
  echo json_encode($response);
  exit();
}


// prepare the SQL statement
$stmt = $pdo->prepare('INSERT INTO user_activities (user_id, activity_name, activity_description, activity_date, activity_time) VALUES (:user_id, :name, :desc, :date, :time)');
$stmt->bindParam(':name', $name);
$stmt->bindParam(':desc', $desc);
$stmt->bindParam(':date', $date);
$stmt->bindParam(':time', $time);
$stmt->bindParam(':user_id', $user_id);

// execute the statement
if ($stmt->execute()) {
  $response = array(
    'success' => true,
    'message' => 'Activitate adăugată cu succes.'
  );
} else {
  $response = array(
    'success' => false,
    'message' => 'Failed to add activity.'
  );
}

// return the response as JSON
echo json_encode($response);