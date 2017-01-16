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
        <?php include('headers.php');?>
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
        <div class="row">
            <section class="col-md-10 message"><br>
                Wiadomość:<br>
                <?php
                    $messageSender = User::loadUserById($conn, $message->getSenderId());
                    echo "otrzymana od: " . $messageSender->getName() . '<br>';
                    echo "otrzymana dnia: " . $message->getCreationDate() . '<br>';
                    echo $message->getMessage() . '<br>';

                ?>
            </section>
            <div class="col-md-2">
                <section class="allUsers class-fixed">
                    <h3>lista użytkowników:</h3>
                    <?php
                    $allUsers = User::loadAllUsers($conn);
                    foreach ($allUsers as $user) {
                        if ($user->getId() != $loggedUserId) {
                            echo '<div class="showUser" >' . $user->getName();
                            echo ' <a href="userPage.php?userId=' . $user->getId() . '">Wyślij wiadomość</a></div><br>';
                        }
                    }
                    ?>
            </section>
        </div>
    </main>
    <footer>
    </footer>
    </body>
</html>

