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

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addCommentForm']) && strlen(trim($_POST['addComment'])) > 0) {
    $comment = new Comment ();
    $comment->setText($_POST['addComment']);
    $comment->setId_usera($loggedUserId);
    $comment->setId_postu($_POST['tweetId']);
    $comment->setCreationDate(date('Y-m-d-h:i:s'));
    if($comment->saveToDB($conn)) {
        echo 'Dodano komentarz ' . $_POST['addComment'] . '<br>';
    } else {
        echo 'wystapil blad z dodawaniem komentarza';
    }
}
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['messageForm']) &&  strlen(trim($_POST['message'])) > 0) {
    $message = new Message ();
    $message->setMassage($_POST['message']);
    $message->setSenderId($loggedUserId);
    $message->setReceiverId($_POST['receiver']);
    $message->setCreationDate(date('Y-m-d-h:i:s'));
    if($message->saveToDB($conn)) {
        echo 'Wysłano wiadomość';
    } else {
        echo 'błąd wysyłania wiadomości';
    }

}
?>
<html lang="pl">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="css/style.css">
        <title>Strona Użytkownika</title>
    </head>
    <body>
        <nav>
            Strona Użytkownika<br>
            <a href="index.php">strona z Tweetami</a>
            <?php if ($_GET['userId'] == $loggedUserId) {
                echo '<a href="userEdit.php">edytuj swoje dane</a>';
            };
            ?>
            <?php
            if(isset($_SESSION['userId'])){
                echo "<a href='logout.php'>logout</a>";
            }
            ?>
        </nav>
        <main>
            <?php
            if ($_GET['userId'] == $loggedUserId) {
                echo '<section class="userTweetTable">
                <h3>Wszystkie twoje Tweety</h3>
               ';
                $tweets = Tweet::loadTweetByUserId($conn, $loggedUserId);
                if(count($tweets) > 0) {
                    foreach ($tweets as $tweet) {
                        $user = User::loadUserbyId($conn, $loggedUserId);
                        echo '<div class="TweetAuthor">Autor: ' . $user->getName() . '</div>';
                        echo '<div class="Tweetdate"> Czas dodania: ' . $tweet->getCreationDate() . '</div>';
                        echo '<div class="TweetText">' . $tweet->getText() . '</div>';
                        echo '<div class="TweetComment">';
                        echo '<div class="CommentCounter">';
                        $comments = Comment::loadCommentsByTweetID($conn, $tweet->getId());
                        $numberOfComments = count($comments);
                        if ($numberOfComments > 0) {
                            echo "Ilość komentarzy: " . $numberOfComments;
                        };
                        foreach ($comments as $comment) {
                            $commentAuthorId = $comment->getId_usera();
                            $commentAuthor = User::loadUserbyId($conn, $commentAuthorId);
                            echo '<div class="TweetAuthor">Autor komentarza: ' . $commentAuthor->getName() . '</div>';
                            echo '<div class="CommentDate"> Utworzony: ' . $comment->getCreationDate() . '</div>';
                            echo '<div class="CommentText">' . $comment->getText() . '</div>';
                        };
                        echo '</div>';
                        echo '<form method="POST" >
                            <input type="hidden" name="addCommentForm" value="addCommentForm">
                            <input type="text" name="addComment">
                            <input type="hidden" name="tweetId" value="' . $tweet->getID() . '"><br> 
                            <input type="submit" value="Dodaj komentarz">
                        </form>
                    </section>';
                    };
                } else {
                    echo 'nie masz jeszcze żadnych tweetów';
                }
            };
                ?>
            <section class="messages">
                <?php
                if ($_GET['userId'] != $loggedUserId) {
                    echo 'Wyślij użytkownikowi wiadomość :
                    <form method="POST" >
                        <input type="hidden" name="messageForm" value="messageForm">
                        <input type="hidden" name="receiver" value="' . $_GET['userId'] . '">
                        <input type="text" name="message">
                        <input type="submit" value="Wyslij wiadomość">
                    </form >
                    ';
                } else {
                    echo '<h3>Wszystkie Twoje Wiadomości:</h3>';
                    $messages = Message::loadAllMassagesReceivedByUser($conn, $loggedUserId);
                    var_dump($messages);
                }
                ?>
            </section>
        </main>
        <footer>
        </footer>
    </body>
</html>
