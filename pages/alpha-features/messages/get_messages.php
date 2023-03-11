<?php
define('BASEPATH', true);
session_start();
require('../../../backend/config/db.php');

// retrieve the user ID parameter
$user_id = $_GET['with_user'];

// retrieve the latest messages for the user
$stmt = $db->prepare('SELECT * FROM messages WHERE (sender_id = :user_id OR receiver_id = :user_id) AND timestamp > DATE_SUB(NOW(), INTERVAL 30 SECOND)');
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// output the messages as JSON data
echo json_encode($messages);