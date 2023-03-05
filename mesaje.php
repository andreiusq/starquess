<?php
define('BASEPATH', true);
session_start();
require('backend/config/db.php');

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
$recipient_id = $cont['id'];

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
  $stmt = $pdo->prepare('SELECT messages.*, users.name AS sender_name FROM messages INNER JOIN users ON messages.sender_id = users.id WHERE (sender_id = :user_id AND recipient_id = :with_user_id) OR (sender_id = :with_user_id AND recipient_id = :user_id) ORDER BY timestamp');
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
  $content = $_POST['content'];
  
  // insert new message into database
  $stmt = $pdo->prepare('INSERT INTO messages (sender_id, recipient_id, message_content) VALUES (:sender_id, :recipient_id, :message_content)');
  $stmt->execute(['sender_id' => $user_id, 'recipient_id' => $with_user, 'message_content' => $content]);
  
  // redirect to current conversation to show new message
  header('Location: ?with_user=' . $with_user);
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preload" href="styles/FontAwesome/css/all.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="stylesheet" href="styles/dashboard/all.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.all.min.js"></script>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
</head>
<body>


    <?php include './important/Rightbar.php'; ?>
    <?php include './important/Sidebar.php'; ?>

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
      <li>
        <a href="?with_user=<?php echo $user['id']; ?>">
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
<?php if ($with_user) : ?>
  <div class="message-form">
    <h2>Conversation with <?php echo $with_user_name; ?></h2>
    <form method="post">
      <textarea name="content"></textarea>
      <button type="submit">Send</button>
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
                <div class="name"><?php echo $name; ?></div>
                <div class="content"><?php echo $message['message_content']; ?></div>
                <div class="timestamp"><?php echo date('Y-m-d H:i:s', strtotime($message['timestamp'])); ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>


</body>
</html>


</script>