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

?>
            <script src="https://cdn.jsdelivr.net/npm/peerjs@1.2.0/dist/peerjs.min.js"></script>
<script>
                var peer = new Peer('<?php echo $user_id ?>', {
                    host: '127.0.0.1',
                    port: '9000',
                    path: '/starquess',
                    debug: 3,
                });

</script>
<html>
    <head>
        <title>Video Call</title>
    </head>
    <body>
        <?php
            // Check if the user is authenticated
            if (!isset($_SESSION['user'])) {
                header("Location: login.php");
                exit();
            }
            // Check if the user is trying to join a call
            if (isset($_GET['call_id'])) {
                // Get the call ID from the GET parameters
                $call_id = $_GET['call_id'];
                // Prepare a SELECT statement to check if the call exists
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM calls WHERE id = ?");
                $stmt->execute([$call_id]);
                $count = $stmt->fetchColumn();
                if($count == 1) {
                    // Render the user's video stream on the page
                    echo '<video id="local_video" autoplay></video>';
                    // Render the remote video stream on the page
                    echo '<video id="remote_video" autoplay></video>';
                } else {
                    // Redirect the user to the call page
                    header("Location: videocall.php");
                    exit();
                }
        ?>
            <br>
            Call ID: <?php echo $call_id ?><br>
            Your ID: <?php echo $user_id ?><br>

            <script>
                // Initialize the PeerJS object
                // Call the remote user with the specified call ID
                navigator.mediaDevices.getUserMedia({audio: true, video: true})
                .then(function(stream) {
                    var media_connection = peer.call('<?php echo $call_id ?>', stream);
                    // Add the user's media stream to the MediaConnection object and render it on the page
                    media_connection.on('stream', function(remote_stream) {
                        document.getElementById('remote_video').srcObject = remote_stream;
                    });
                    document.getElementById('local_video').srcObject = stream;
                })
                .catch(function(error) {
                    console.log(error);
                });

                // Answer incoming call
                peer.on('call', function(media_connection) {
                    navigator.mediaDevices.getUserMedia({audio: true, video: true})
                    .then(function(stream) {
                        // Answer the incoming call and add the user's media stream to the MediaConnection object
                        media_connection.answer(stream);
                        // Render the remote user's media stream on the page
                        media_connection.on('stream', function(remote_stream) {
                            document.getElementById('remote_video').srcObject = remote_stream;
                        });
                        document.getElementById('local_video').srcObject = stream;
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
                });
            </script>
        <?php
            } else {
                // Render the list of available calls for the user to join
                echo "<h1>Available Calls:</h1>";
                // Prepare a SELECT statement to fetch the list of available calls
                $stmt = $pdo->prepare("SELECT * FROM calls");
                $stmt->execute();
                $calls = $stmt->fetchAll();
                // If there are no available calls, display a message to the user
                if (count($calls) == 0) {
                echo "<p>No calls available at the moment.</p>";
                } else {
                // Render the list of available calls
                echo "<ul>";
                foreach ($calls as $call) {
                echo "<li><a href='?call_id=" . $call['id'] . "'>Call ID: " . $call['id'] . "</a></li>";
                }
                echo "</ul>";
                }
                }
                ?>
                </body>
                
                </html>