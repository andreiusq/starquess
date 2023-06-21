<?php

define('BASEPATH', true);
session_start();
require('../../backend/config/db.php');
$is_administrator = 0;



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preload" href="../../styles/FontAwesome/css/all.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="stylesheet" href="../../styles/videoconference/all.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.all.min.js"></script>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    
    <?php include '../../important/Rightbar-pages.php'; ?>
    <?php include '../../important/Sidebar-pages.php'; ?>

    <div class="students-box">
        <div class="students-box-content">
            <h1 class="students-title">Videoconferințe</h1>
            <p class="students-text">Întâlniri video premium, acum gratuit pentru toată lumea!</p>
            <div class="students-box-list">
                <div class="students-box-list-item">
                    <div class="students-box-list-item-content">
                        <div class="students-box-list-item-content-left">
                            <div class="students-box-list-item-content-left-img">
                                <img src="https://cdn.discordapp.com/attachments/881100000000000000/881100000000000000/unknown.png" alt="">
                            </div>
                                <img src="https://www.gstatic.com/meet/user_edu_get_a_link_light_90698cd7b4ca04d3005c962a3756c42d.svg" alt="" style="position: absolute; left: 600px; top: -150px;">
                                
                                
                                <button class="normal" id="normal" onClick="event.preventDefault(); makeConference()"> Întâlnire nouă </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="videoconferences-box">
        <div class="videoconferences-box-content">
            <h1 class="students-title">Videoconferința <b>ta</b></h1>
            <p class="students-text">Gestionează vieoconferința actuală</p>
            <div class="videoconferences-box-list">
                <div class="videoconferences-box-list-item">
                    <div class="videoconferences-box-list-item-content">
                        <div class="videoconferences-box-list-item-content-left">                         
                            <button class="red" id="red" onClick="event.preventDefault(); closeConference()"> Închide conferință </button>
                            <button class="normal" onClick="event.preventDefault(); closeConference()"> Vizualizează înregistrări </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script>

function makeConference() {
    const { value: type } = Swal.fire({
        title: 'Alege tipul de întâlnire',
        input: 'select',
        inputOptions: {
            '1': 'Creează o întâlnire instantanee',
            '2': 'Programează în Starquess Calendar'
        },
        inputPlaceholder: 'Selectează tipul',
        showCancelButton: true,
        inputValidator: (value) => {
            return new Promise((resolve) => {
                if (value === '1') {
                    const { value: name } = Swal.fire({
                        title: 'Introdu numele conferinței',
                        input: 'text',
                        inputLabel: '',
                        inputPlaceholder: 'Numele conferinței',
                        inputAttributes: {
                            maxlength: 10,
                            autocapitalize: 'off',
                            autocorrect: 'off'
                        }
                    }).then((result) => {
                        if (result.value) {
                            Swal.showLoading();
                            // Redirect the user
                            window.location.href = 'https://meet.starquess.ro/arm-rom-stq';
                        }
                    });
                } else {
                    resolve('Funcționalitatea încă nu merge, momentan! :)')
                }
            })
        }
    });

    if (type) {
        Swal.fire(`Ai ales: ${type}`);
    }
}



function closeConference() { 
    Swal.fire({
    title: 'Oopsie...',
    text: "Această funcționalitate încă nu este implementată, dar poți să o folosești în curând!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Ok'
    }).then((result) => {
    if (result.isConfirmed) {
        Swal.fire(
        'Închis!',
        'Conferința ta a fost închisă.',
        'success'
        )
    }
    })
}

</script>

</body>
</html>