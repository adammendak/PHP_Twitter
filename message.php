<?php
session_start();
require_once 'init.php';

if (!isset($_SESSION['userId'])) {
    header('Location:login.php');
}

$loggedUserId = $_SESSION['userId'];
$loggedUser = User::loadUserById($conn, $loggedUserId);
Message::updateMessageRead($conn, $_GET['messageId']);
$message = Message::loadAMassageByMassageId($conn, $_GET['messageId']);


?>
<html lang="pl">
    <head>
        <meta charset="utf-8">
        <title>Strona Edycji</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
              integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
    <nav class="navbar navbar-fixed-top">
        <ul>
            <li>
                <?php echo '<a href="userPage.php?userId=' . $loggedUser->getId() . '">Twoja Tablica</a>' ?>
            </li>
            <li>
                <a href="index.php">strona z Tweetami</a>
            </li>
            <li>
                <?php
                if(isset($_SESSION['userId'])) {
                    echo "<a href='logout.php'>logout</a>";
                }
                ?>
            </li>
            <li style="float: right;">
                Witaj <?php echo $loggedUser->getName();?>
            </li>
            <div style="clear: both;"></div>
        </ul>
    </nav>
    <main>
        <section class="message"><br>
            Wiadomość:<br>
            <?php
                $messageSender = User::loadUserById($conn, $message->getSenderId());
                echo "otrzymana od: " . $messageSender->getName() . '<br>';
                echo "otrzymana dnia: " . $message->getCreationDate() . '<br>';
                echo $message->getMessage() . '<br>';

            ?>
        </section>
    </main>
    <footer>
    </footer>
    </body>
</html>

