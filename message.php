<?php
session_start();
require_once 'src/Tweet.php';
require_once 'src/Message.php';
require_once 'src/User.php';
require_once 'connection.php';
require_once 'src/Comment.php';

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
    <link rel="stylesheet" href="css/style.css">
    <title>Strona Wiadomości</title>
</head>
<body>
<nav>
    Wiadomość numer <?php echo $_GET['messageId'];?> <br>
    <a href="index.php">strona z Tweetami</a>
    <?php
    echo '<a href="userPage.php?userId=' . $loggedUser->getId() . '">Twoja Tablica</a> ';
    if(isset($_SESSION['userId'])){
        echo "<a href='logout.php'>logout</a>";
    }
    ?>
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

