<?php
require("./config/db.php");

try {

    $value = $_POST['value'];
    $stmt = $pdo -> prepare("INSERT INTO news (text) VALUES (:value)");
    $stmt -> bindParam(":value", $value);
    $stmt -> execute();

} catch (PDOException $e) {
    echo $e->getMessage();
}

