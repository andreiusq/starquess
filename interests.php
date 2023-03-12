<?php
session_start();
define('BASEPATH', true);

if(!isset($_SESSION["user"])) {
  header("Location: login.php");
  exit();
}

require('backend/config/db.php');
$stmt = $pdo->prepare("SELECT id, name FROM users WHERE email=:email");
$stmt->bindParam(':email', $_SESSION['user']);   
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

// interessts logic
$stmt = $pdo->prepare("SELECT id, name FROM interests");
$stmt->execute();

$interests = $stmt->fetchAll(PDO::FETCH_ASSOC);



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interests</title>
    <link rel="stylesheet" href="styles/FontAwesome/css/all.css">
    <link rel="stylesheet" href="styles/register/all.css">
    <link rel="stylesheet" href="styles/register/interests.css">
    <link rel="stylesheet" href="styles/register/radio.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.all.min.js"></script>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
</head>
<body>
<nav class="nav-container">
  <div class="logo">
    <div class="circle"></div>
    <h2>Starquess<span class="fullstop">.</span></h2>
  </div>
</nav>

<section class="container">
  <div class="form-container">
    <div class="section-header">
      <h1 class="primary-heading">
        Bună, <?php echo $user['name'] ?><span class="fullstop">.</span>
      </h1>
      <h2 class="secondary-heading">
        Pentru că la <span class="fullstop">Starquess</span> ne pasă de elev, trebuie să îți alegi niște interese.
      </h2>
    </div>
    <?php
    if (isset($_POST['submit'])) {
      $selectedInterests = $_POST['interests'];
      $userId = $user['id'];  
      if (count($selectedInterests) > 5) {
        echo '<script>';
        echo 'Swal.fire({
                icon: "error",
                title: "Eroare!",
                text: "Poți alege maxim 5 interese.",
              });';
        echo '</script>';
      } else {
        try {
          
          $pdo->beginTransaction();
          
          $stmt = $pdo->prepare("DELETE FROM user_interests WHERE user_id = :user_id");
          $stmt->bindParam(':user_id', $userId);
          $stmt->execute();
          
          $stmt = $pdo->prepare("INSERT INTO user_interests (user_id, interest_id) VALUES (:user_id, :interest_id)");
          foreach ($selectedInterests as $interest) {
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':interest_id', $interest);
            $stmt->execute();
          }
          
          $pdo->commit();
          
          echo '<script>';
          echo 'Swal.fire({
                  icon: "success",
                  title: "Success!",
                  text: "Interesele tale au fost adăugate cu succes.",
                });';
          echo '</script>';
        } catch (PDOException $e) {
          $pdo->rollback();
          echo "Error: " . $e->getMessage();
        }
      }
    }
    ?>
        <form method="post">
          <?php foreach($interests as $interest) { ?>
            <div>
            <label class="checkbox">
                <input type="checkbox" id="cbx" name="interests[]" value="<?php echo htmlspecialchars($interest['id'], ENT_QUOTES) ?>">
                <span class="interest-name"><?php echo htmlspecialchars($interest['name'], ENT_QUOTES) ?></span>
            </label> <br>
          </div>
          <?php } ?>
          <button type="submit" name="submit" class="primary-btn">Mai departe</button>
        <h3>Hei! Să știi că suntem încă în perioada <b>alpha</b>. Așa că poți să <a href="/index.php">sari peste partea asta.</a></h3>
      </div>
    </form>
  </div>
  <div class="side-panel">
    <div class="background"></div>
  </div>
</section>
</body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</html>