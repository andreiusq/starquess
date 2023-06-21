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
    <title>Librărie</title>
    <link rel="preload" href="../../styles/FontAwesome/css/all.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="stylesheet" href="../../styles/library/all.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.all.min.js"></script>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    
    <?php include '../../important/Rightbar-pages.php'; ?>
    <?php include '../../important/Sidebar-pages.php'; ?>


    <div id="bookList"></div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        // Fetch books from the server
        $.getJSON("/../../backend/queries/fetch_books.php", function(data) {
            var books = data;
            var bookList = $("#bookList");
            var html = "<div class='row'>"; // Start with a row

            // Loop through the books and generate HTML
            $.each(books, function(index, book) {
                if (index > 0 && index % 3 === 0) {
                    // If the current index is a multiple of 3, close the current row and start a new row
                    html += "</div><div class='row'>";
                }
                
                html += "<div class='book'>";
                html += "<a class='bookhref' href='" + book.link + "'>";
                html += "<img class='bookimage' src='" + book.image + "' alt='" + book.title + "'>";
                html += "</a>"
                html += "<h2 class='booktitle'>" + book.title + "</h2>";
                html += "</div>";
            });

            html += "</div>"; // Close the last row
            // Insert the HTML into the bookList element
            bookList.html(html);
        });
    });
</script>



</body>
</html>