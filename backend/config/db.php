<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'starquess';

try{
    $dsn = 'mysql:host='.$host. ';dbname='.$dbname;

    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    echo 'Connection Error: '.$e->getMessage();
}

?>