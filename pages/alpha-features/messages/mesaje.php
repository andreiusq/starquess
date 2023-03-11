<?php
define('BASEPATH', true);
session_start();
require('../../../backend/config/db.php');

$is_administrator = 0;

if(!isset($_SESSION["user"])) {
  header("Location: login.php");
  exit();
}


// users logic 2
$stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email"); 
$stmt -> bindParam(':email', $_SESSION['user']);

$stmt->execute(); 


$conturi = $stmt->fetchAll(); 

foreach($conturi as $cont);

$user_id = $cont['id'];
$recipient_id = $_GET['with_user'];

// Get messages for the selected recipient and sender (if specified)
if (isset($_GET['sender_id'])) {
    $sender_id = $_GET['sender_id'];
    $messages_query = $pdo->prepare("
      SELECT messages.*, users.name AS sender_name
      FROM messages
      JOIN users ON messages.sender_id = users.id
      WHERE messages.recipient_id = :recipient_id
      AND messages.sender_id = :sender_id
      ORDER BY messages.timestamp ASC
    ");
    $messages_query->execute(['recipient_id' => $recipient_id, 'sender_id' => $sender_id]);
  } else {
    $messages_query = $pdo->prepare("
      SELECT messages.*, users.name AS sender_name
      FROM messages
      JOIN users ON messages.sender_id = users.id
      WHERE messages.recipient_id = :recipient_id
      ORDER BY messages.timestamp ASC
    ");
    $messages_query->execute(['recipient_id' => $recipient_id]);
  }


// retrieve all conversations involving the current user
$stmt = $pdo->prepare('SELECT DISTINCT users.id, users.name, users.last_name FROM messages INNER JOIN users ON (messages.sender_id = users.id OR messages.recipient_id = users.id) WHERE messages.sender_id = :user_id OR messages.recipient_id = :user_id ORDER BY users.name');
$stmt->execute(['user_id' => $user_id]);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// retrieve conversation with selected user if provided
$conversation = null;
if (isset($_GET['with_user'])) {
  $with_user_id = intval($_GET['with_user']);
  $stmt = $pdo->prepare('SELECT messages.*, users.name AS sender_name FROM messages INNER JOIN users ON messages.sender_id = users.id WHERE (sender_id = :user_id AND recipient_id = :with_user_id) OR (sender_id = :with_user_id AND recipient_id = :user_id) ORDER BY timestamp DESC');
  $stmt->execute(['user_id' => $user_id, 'with_user_id' => $with_user_id]);
  $conversation = $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// check if a user has been selected from the conversation list
$with_user = isset($_GET['with_user']) ? $_GET['with_user'] : null;

// retrieve the selected user's name
if ($with_user) {
  $stmt = $pdo->prepare('SELECT name FROM users WHERE id = :with_user_id');
  $stmt->execute(['with_user_id' => $with_user]);
  $with_user_name = $stmt->fetchColumn();
}

// handle message form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the message text from the form input
    $message = $_POST['message_content'];
    
    // Insert the message into the messages table
    $stmt = $pdo->prepare("INSERT INTO messages (sender_id, recipient_id, message_content) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $recipient_id, $message]);
  
  // redirect to current conversation to show new message
  header('Location: ?with_user=' . urlencode($with_user));
  exit;
}

// Retrieve the profile photo path or URL for the selected user
$stmt = $pdo->prepare("SELECT url FROM user_images WHERE user = ?");
$stmt->execute([$with_user]);
$user_image_path = $stmt->fetchColumn();



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preload" href="../../../styles/FontAwesome/css/all.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="stylesheet" href="../../../styles/messages/all.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.all.min.js"></script>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
</head>
<body>


    <?php include '../../../important/Rightbar-pages.php'; ?>
    <?php include '../../../important/Sidebar-pages.php'; ?>

    <div class="content" style="position: relative; left: 300px;">
        <!-- display conversation list -->
<div class="conversation-list">
  <ul>
    <?php foreach ($users as $user) : ?>
      <?php
        // determine the last message in the conversation with this user
        $stmt = $pdo->prepare('SELECT messages.*, users.name AS sender_name FROM messages INNER JOIN users ON messages.sender_id = users.id WHERE (sender_id = :user_id AND recipient_id = :with_user_id) OR (sender_id = :with_user_id AND recipient_id = :user_id) ORDER BY timestamp DESC LIMIT 1');
        $stmt->execute(['user_id' => $user_id, 'with_user_id' => $user['id']]);
        $last_message = $stmt->fetch(PDO::FETCH_ASSOC);
      ?>
      <li> <br> <br>
        <a class="redirect-user" href="?with_user=<?php echo $user['id']; ?>">
          <?php echo $user['name'] . ' ' . $user['last_name']; ?>
          <?php if ($last_message) : ?>
            <span class="last-message"><?php echo $last_message['message_content']; ?></span>
          <?php endif; ?>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
</div>


<!-- display message form if a user has been selected from the conversation list -->
<div class="messages-content">
    <?php if ($with_user) : ?>
    <div class="message-form">
        
        <h2> <?php echo $with_user_name; ?></h2>
        <form class="form" method="post">
    <button type="submit">
        <svg width="17" height="16" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" aria-labelledby="search">
            <path d="M7.667 12.667A5.333 5.333 0 107.667 2a5.333 5.333 0 000 10.667zM14.334 14l-2.9-2.9" stroke="currentColor" stroke-width="1.333" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>
    </button>
    <input class="input" placeholder="Type your text" required="" type="text" name="message_content">
    <button class="reset" type="reset">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>
</form>
    </div>
    <?php endif; ?>
            <!-- display conversation -->
            <?php if ($conversation) : ?>
            <div class="conversation">
                <?php foreach ($conversation as $message) : ?>
                <?php $is_sender = ($message['sender_id'] == $user_id); ?>
                <?php $message_class = ($is_sender ? 'sender' : 'recipient'); ?>
                <?php $name = ($is_sender ? 'Tu' : $message['sender_name']); ?>
                <div class="message <?php echo $message_class; ?>">
                    <b><div class="name"><?php echo $name; ?></div></b>
                    <div class="content"><?php echo $message['message_content']; ?></div>
                    <div class="timestamp"><?php echo date('H:i', strtotime($message['timestamp'])); ?></div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>


</body>
</html>


</script>