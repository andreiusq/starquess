<?php
define('BASEPATH', true);
session_start();
require('backend/config/db.php');

if(!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
  }

if(isset($_SESSION['user'])) {
  unset($_SESSION['user']);
  unset($_SESSION['interests_set']);
  session_destroy();
  echo '<script>window.location.replace("index.php");</script>';
}
else {
  echo '<script>window.location.replace("index.php");</script>';
}
?>