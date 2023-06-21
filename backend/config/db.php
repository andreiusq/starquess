<?php

defined('BASEPATH') OR exit('No direct script access allowed');
$startTime = microtime(true);


$host = '146.70.56.165';
$user = 'kodikasr_starquessuser';
$password = 'HDNuuEo!F5oQ';
$dbname = 'kodikasr_starquess';
try{
    $dsn = 'mysql:host='.$host. ';dbname='.$dbname;

    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    echo 'Connection Error: '.$e->getMessage();
}

?>