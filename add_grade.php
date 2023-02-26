<?php
define('BASEPATH', true);
require('./backend/config/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected_student_id = $_POST['selected_student_id'];
    $grade = $_POST['grade'];
    $class_id = $_POST['class_id'];


    // module
    $current_date = date("Y-m-d");

    // Primul Modul
    $start_date_module_1 = date("Y") . "-09-05";
    $end_date_module_1 = date("Y") . "-10-30";

    // Al doilea modul
    $start_date_module_2 = date("Y") . "-10-31";
    $end_date_module_2 = date("Y") . "-01-08";

    // Al treilea modul
    $start_date_module_3 = date("Y") . "-01-09";
    $end_date_module_3 = date("Y") . "-02-26";

    // Al patrulea modul
    $start_date_module_4 = date("Y") . "-02-27";
    $end_date_module_4 = date("Y") . "-04-18";

    // Al cincilea modul
    $start_date_module_5 = date("Y") . "-04-19";
    $end_date_module_5 = date("Y") . "-06-16";

    if ($current_date >= $start_date_module_1 && $current_date <= $end_date_module_1) {
      $module_id = 1;
    } elseif ($current_date >= $start_date_module_2 && $current_date <= $end_date_module_2) {
      $module_id = 2;
    } elseif ($current_date >= $start_date_module_3 && $current_date <= $end_date_module_3) {
      $module_id = 3;
    } elseif ($current_date >= $start_date_module_4 && $current_date <= $end_date_module_4) {
      $module_id = 4;
    } elseif ($current_date >= $start_date_module_5 && $current_date <= $end_date_module_5) {
      $module_id = 5;
    } else {
      $module_id = 0;
    }

    $statement = $pdo->prepare("SELECT c.school_id, t.teacher_subject FROM classes c JOIN teachers t ON c.class_id = t.class_id WHERE c.class_id = :class_id");
    $statement->execute(array(':class_id' => $class_id));
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    if ($result) {
      $school_id = $result['school_id'];
      if($result['teacher_subject'] == "Matematica") {
          $subject_id = 1;
        }
      if($result['teacher_subject'] == "Romana") {
          $subject_id = 2;
        }
      if($result['teacher_subject'] == "Istorie") {
          $subject_id = 3;
        }
      if($result['teacher_subject'] == "Geografie") {
          $subject_id = 4;
        }
      if($result['teacher_subject'] == "Engleza") {
          $subject_id = 5;
        }
      if($result['teacher_subject'] == "Franceza") {
          $subject_id = 6;
        }
      if($result['teacher_subject'] == "Germana") {
          $subject_id = 7;
        }
      if($result['teacher_subject'] == "Biologie") {
          $subject_id = 8;
        }
      if($result['teacher_subject'] == "Fizica") {
          $subject_id = 9;
        }
      if($result['teacher_subject'] == "Chimie") {
          $subject_id = 10;
        }
      if($result['teacher_subject'] == "Informatica") {
          $subject_id = 11;
        }
      if($result['teacher_subject'] == "Religie") {
          $subject_id = 12;
        }
      if($result['teacher_subject'] == "Muzica") {
          $subject_id = 13;
        }
      if($result['teacher_subject'] == "Arta") {
          $subject_id = 14;
        }
      if($result['teacher_subject'] == "Sport") {
          $subject_id = 15;
        }
      if($result['teacher_subject'] == "Educatie Fizica") {
          $subject_id = 16;
        }
      if($result['teacher_subject'] == "Educatie Civica") {
          $subject_id = 17;
        }
      if($result['teacher_subject'] == "Psihologie") {
          $subject_id = 18;
        }
      if($result['teacher_subject'] == "Filosofie") {
          $subject_id = 19;
        }
      if($result['teacher_subject'] == "Economie") {
          $subject_id = 20;
        }
      if($result['teacher_subject'] == "Ed. Antreprenoriala") {
          $subject_id = 21;
      }  

      try {
          $statement = $pdo->prepare("INSERT INTO grades (user_id, class_id, school_id, grade, subject_id, module_id, date) VALUES (:user_id, :class_id, :school_id, :grade, :subject_id, :module_id, NOW())");
          $statement->execute(array(':user_id' => $selected_student_id, ':class_id' => $class_id, ':school_id' => $school_id, ':grade' => $grade, ':subject_id' => $subject_id, ':module_id' => $module_id));

          echo json_encode(array('status' => 'success', 'message' => 'Notă adăugată cu succes!'));
          } catch(PDOException $e) {
            echo json_encode(array('status' => 'error', 'message' => 'Error adding grade: ' . $e->getMessage()));
          }
        } else {
          echo json_encode(array('status' => 'error', 'message' => 'Invalid class_id'));
      }
} else {
echo json_encode(array('status' => 'error', 'message' => 'Invalid request method'));
}
