<?php
define('BASEPATH', true);
require('backend/config/db.php');

session_start();

$stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email"); 
$stmt -> bindParam(':email', $_SESSION['user']);
$stmt->execute();
$conturi = $stmt->fetchAll(); 
foreach($conturi as $cont);
$user_id = $cont['id'];

// Check if the user is authenticated
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Handle the creation of a new call
if (isset($_POST['create_call'])) {
    // Generate a unique ID for the call
    $call_id = uniqid();
    // Insert the new call into the database
    $stmt = $pdo->prepare("INSERT INTO calls (id, user_id) VALUES (:id, :user_id)");
    $stmt->bindParam(':id', $call_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    // Redirect the user to the new call
    header("Location: videocall.php?call_id=$call_id");
    exit();
}

// Get the list of available calls
$stmt = $pdo->prepare("SELECT calls.id, users.email FROM calls JOIN users ON calls.user_id = users.id");
$stmt->execute();
$available_calls = $stmt->fetchAll();

?>

<html>
    <head>
        <title>Video Call</title>
    </head>
    <body>
        <h1>Create a new call:</h1>
        <form method="post">
            <input type="hidden" name="create_call" value="1">
            <input type="submit" value="Create Call">
        </form>

        <h1>Join an existing call:</h1>
        <ul>
            <?php foreach ($available_calls as $call): ?>
                <li><a href="videocall.php?call_id=<?php echo $call['id'] ?>"><?php echo $call['email'] ?></a></li>
            <?php endforeach ?>
        </ul>
    </body>
</html>