<?php
define('BASEPATH', true);
session_start();
require('backend/config/db.php');
$is_administrator = 0;



if(isset($_GET['class'])) {
    $class_id = $_GET['class'];

    $statement = $pdo->prepare("SELECT * FROM users WHERE class_id = :class_id");
    $statement->execute(array(':class_id' => $class_id));
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard - Classes</title>
    <link rel="preload" href="styles/FontAwesome/css/all.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="stylesheet" href="styles/classes/all.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.all.min.js"></script>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body>
    
    <?php include './important/Rightbar.php'; ?>
    <?php include './important/Sidebar.php'; ?>

 <div class="students-box">
        <div class="students-box-content">
            <h1 class="students-title">Studenti</h1>
            <p class="students-text">Aici poți vedea elevii din clasa ta.</p>
            <div class="students-box-list">
                <div class="students-box-list-item">
                    <div class="students-box-list-item-content">
                        <div class="students-box-list-item-content-left">
                            <div class="students-box-list-item-content-left-img">
                                <img src="https://cdn.discordapp.com/attachments/881100000000000000/881100000000000000/unknown.png" alt="">
                            </div>
                            <?php if (count($results) > 0) {
                                foreach ($results as $row) { ?>
                            <div class="students-box-list-item-content-left-text">
                                <h1 class="students-box-list-item-content-left-text-name"><?php echo $row['name']; echo ' ';  echo $row['last_name']; ?></h1>
                                <p class="students-box-list-item-content-left-text-email"><?php echo $row['email'] ?></p>
                            </div>
                            <div class="students-box-list-item-content-right">
                                <form method="POST" onsubmit="event.preventDefault(); addGradeToStudent(<?php echo $row['id']; ?>);">
                                    <input type="hidden" name="selected_student_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="add-grade"><i class="fas fa-plus"></i></button>
                                </form>
                                <form method="POST" onsubmit="event.preventDefault(); addAbsence(<?php echo $row['id']; ?>);">
                                    <input type="hidden" name="selected_student_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="add-absence"><i class="fas fa-minus"></i></button>
                                </form>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>

<!-- SweetAlert2 modal -->
<div id="absenceModal" class="d-none">
  <form id="absenceForm">
    <div class="form-group">
      <label for="absenceDate">Absence Date:</label>
      <input type="date" class="form-control" id="absenceDate" name="absenceDate" required>
    </div>
  </form>
</div>
    
</body>
</html>

<script>
function addGradeToStudent(selected_student_id) {
    Swal.fire({
        title: 'Adaugă notă',
        input: 'number',
        inputLabel: 'Introdu nota:',
        inputAttributes: {
            min: 0,
            max: 10,
            step: 1
        },
        showCancelButton: true,
        confirmButtonText: 'Submit',
        showLoaderOnConfirm: true,
        preConfirm: (grade) => {
            const urlParams = new URLSearchParams(window.location.search);
            const class_id = urlParams.get('class');
            return fetch('/backend/queries/add_grade.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'selected_student_id=' + selected_student_id + '&grade=' + grade + '&class_id=' + class_id
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(response.statusText)
                }
                return response.json()
            })
            .catch(error => {
                Swal.showValidationMessage(
                    `Request failed: ${error}`
                )
            })
        },
        allowOutsideClick: () => !Swal.isLoading()
    })
    .then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Notă adăugată!',
                icon: 'success',
                text: 'Nota a fost adăugată cu succes!',
            });
        }
    });
}

function addAbsence(selected_student_id) {
  Swal.fire({
    title: 'Adaugă absență',
    html: `<form id="absence-form">
            <div class="form-group">
              <label for="absence-date">Dată:</label>
              <input type="date" id="absence-date" name="absence-date" class="form-control" required>
            </div>
          </form>`,
    showCancelButton: true,
    confirmButtonText: 'Confirmă',
    cancelButtonText: 'Anulează',
    focusConfirm: false,
    preConfirm: () => {
      const absenceDate = Swal.getPopup().querySelector('#absence-date').value
      if (!absenceDate) {
        Swal.showValidationMessage(`Te rugăm să selectezi o dată.`)
      }
      return { absenceDate: absenceDate }
    }
  }).then((result) => {
    if (result.isConfirmed) {
      const absenceDate = result.value.absenceDate;
      $.ajax({
        type: 'POST',
        url: 'backend/queries/add_absence.php',
        data: { selected_student_id: selected_student_id, absence_date: absenceDate},
        success: function(response) {
          if (response.status === 'success') {
            Swal.fire('Success', 'Absența a fost adăugată.', 'success').then(() => {
              location.reload();
            });
          } else {
            Swal.fire('Success', 'Absența a fost adăugată.', 'success').then(() => {
              location.reload();
            });
          }
        },
        error: function(xhr, status, error) {
          Swal.fire('Error', 'Failed to set absence.', 'error');
        }
      });
    }
  })
}
</script>