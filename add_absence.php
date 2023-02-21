<?php
define('BASEPATH', true);
require('./backend/config/db.php');
session_start();

// Retrieve the user email from the session variable
$user_email = $_SESSION['user'];

// Retrieve the teacher information based on the user email
$sql = "SELECT * FROM teachers WHERE teacher_email = (SELECT email FROM users WHERE email = :user_email)";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_email', $user_email);
$stmt->execute();
$teacher = $stmt->fetch(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['selected_student_id']) || !isset($_POST['absence_date'])) {
        header('HTTP/1.1 400 Bad Request');
        die('Missing parameter(s)');
    }

    $selected_student_id = $_POST['selected_student_id'];
    $absenceDate = $_POST['absence_date'];

    try {

        $query = 'INSERT INTO absences (user_id, date, teacher_id, subject) VALUES (:user_id, :date, :teacher_id, :subject)';
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':user_id', $selected_student_id, PDO::PARAM_INT);
        $stmt->bindParam(':date', $absenceDate, PDO::PARAM_STR);
        $stmt->bindParam(':teacher_id', $teacher['teacher_id'], PDO::PARAM_INT);
        $stmt->bindParam(':subject', $teacher['teacher_subject'], PDO::PARAM_STR);
        $stmt->execute();

        $response = array('status' => 'success');
        echo json_encode($response);
    } catch (PDOException $e) {
        header('HTTP/1.1 500 Internal Server Error');
        die('Database error: ' . $e->getMessage());
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    die('Method not allowed');
}
?>