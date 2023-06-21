<?php
define('BASEPATH', true);
require("../config/db.php");

$input = $_GET['input'];
$action = $_GET['action'];

try {
    if ($action == 'delete') {
        $statement = $pdo->prepare("SELECT * FROM news ORDER BY id DESC LIMIT 1");
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $id = $result['id'];
        $statement = $pdo->prepare("DELETE FROM news WHERE id = :id");
        $statement->execute(array(':id' => $id));
        echo "Response successfully deleted";
    } elseif($action == 'edit') { 
        $statement = $pdo->prepare("SELECT * FROM news ORDER BY id DESC LIMIT 1");
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $id = $result['id'];
        $statement = $pdo->prepare("UPDATE news SET text = :input WHERE id = :id");
        $statement->execute(array(':input' => $input, ':id' => $id));
        echo htmlspecialchars("Response successfully edited: $input", ENT_QUOTES, 'UTF-8');
    } else {
        $statement = $pdo->prepare("INSERT INTO news (text) VALUES (:text)");
        $statement->execute(array(':text' => $input));
        echo htmlspecialchars("Response successfully edited: $input", ENT_QUOTES, 'UTF-8');
    }
} catch (PDOException $e) {
    echo $e->getMessage();
}