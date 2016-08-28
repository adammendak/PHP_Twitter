<?php

session_start();

if(!isset($_SESSION['userId'])){
    header('Location: login.php');        // to funkcja ktora przekierowuje, link
}

?>

<html>
    <head>
        
    </head>
    <body>
        Strona Główna
    </body>
</html>