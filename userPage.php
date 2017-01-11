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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addCommentForm']) && strlen(trim($_POST['addComment'])) > 0) {
    $comment = new Comment ();
    $comment->setText($_POST['addComment']);
    $comment->setId_usera($loggedUserId);
    $comment->setId_postu($_POST['tweetId']);
    $comment->setCreationDate(date('Y-m-d-h:i:s'));
    if ($comment->saveToDB($conn)) {
        echo 'Dodano komentarz ' . $_POST['addComment'] . '<br>';
    } else {
        echo 'wystapil blad z dodawaniem komentarza';
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['messageForm']) && strlen(trim($_POST['message'])) > 0) {
    $message = new Message ();
    $message->setMessage($_POST['message']);
    $message->setSenderId($loggedUserId);
    $message->setReceiverId($_POST['receiver']);
    $message->setCreationDate(date('Y-m-d-h:i:s'));
    if ($message->saveToDB($conn)) {
        echo 'Wysłano wiadomość';
    } else {
        echo 'błąd wysyłania wiadomości';
    }

}
?>
<html lang="pl">
    <head>
        <meta charset="utf-8">
        <title>Strona Użytkownika</title>
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
                <a href="index.php">strona z Tweetami</a>
            </li>
            <li>
                <?php echo '<a href="userPage.php?userId=' . $loggedUser->getId() . '">Twoja Tablica</a>' ?>
            </li>
            <?php if ($_GET['userId'] == $loggedUserId) {
                echo '<li><a href="userEdit.php">edytuj swoje dane</a></li>';
            };
            ?>
            <li>
                <?php
                if (isset($_SESSION['userId'])) {
                    echo "<a href='logout.php'>logout</a>";
                }
                ?>
            </li>
            <li style="float: right;">
                Witaj <?php echo $loggedUser->getName(); ?>
            </li>
            <div style="clear: both;"></div>
        </ul>
    </nav>
    <main>
        <div class="row">
            <div class="col-md-6 bg-success columnSection">
                <?php
                if ($_GET['userId'] == $loggedUserId) {
                    echo '<section class="userTweetTable"><h3>Wszystkie twoje Tweety</h3>';
                    $tweets = Tweet::loadTweetByUserId($conn, $loggedUserId);
                    if (count($tweets) > 0) {
                        foreach ($tweets as $tweet) {
                            $user = User::loadUserbyId($conn, $loggedUserId);
                            echo '<div class="tweetAuthor">Autor: ' . $user->getName() . '</div>';
                            echo '<div class="tweetDate"> Czas dodania: ' . $tweet->getCreationDate() . '</div>';
                            echo '<div class="tweetText">' . $tweet->getText() . '</div>';
                            echo '<div class="tweetComment">';
                            echo '<div class="commentCounter">';
                            $comments = Comment::loadCommentsByTweetID($conn, $tweet->getId());
                            $numberOfComments = count($comments);
                            if ($numberOfComments > 0) {
                                echo 'Ilość komentarzy: '. $numberOfComments . '</div>';
                            };
                            foreach ($comments as $comment) {
                                $commentAuthorId = $comment->getId_usera();
                                $commentAuthor = User::loadUserbyId($conn, $commentAuthorId);
                                echo '<div class="tweetAuthor">Autor komentarza: ' . $commentAuthor->getName() . '</div>';
                                echo '<div class="commentDate"> Utworzony: ' . $comment->getCreationDate() . '</div>';
                                echo '<div class="commentText">' . $comment->getText() . '</div>';
                            };

                            echo '<form method="POST" >
                                            <input type="hidden" name="addCommentForm" value="addCommentForm">
                                            <input type="text" name="addComment">
                                            <input type="hidden" name="tweetId" value="' . $tweet->getID() . '"><br> 
                                            <input role="button" class="btn btn-warning" type="submit" value="Dodaj komentarz">
                                        </form>
                                        </div>
                                    ';
                        };
                        echo "</section>";
                    } else {
                        echo 'nie masz jeszcze żadnych tweetów';
                    }
                };
                ?>
            </div>
            <div class="col-md-4 bg-success columnSection">
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
                        echo '<h3>Otrzymane Wiadomości:</h3>';
                        $messages = Message::loadAllMassagesToReceiver($conn, $loggedUserId);
                        foreach ($messages as $message) {
                            $messageAuthorId = $message->getSenderId();
                            $messageAuthor = User::loadUserbyId($conn, $messageAuthorId);
                            echo '<div class="messageAuthor">wiadomośc otrzymana od: ' . $messageAuthor->getName() .
                                '</div>';
                            if ($message->getMessageRead() == 0) {
                                echo "Wiadomość nieprzeczytana";
                            }
                            echo '<div class="messageDate"> otrzymana dnia: ' . $message->getCreationDate() . '</div>';
                            echo '<div class="messageText"> ' . substr($message->getMessage(), 0, 29) . '</div>';
                            echo '<a href="message.php?messageId=' . $message->getId() . '">przeczytaj wiadomość</a>';
                        };
                        echo '<h3>Wysłane Wiadomości:</h3>';
                        $messages = Message::loadAllMassagesSentByUser($conn, $loggedUserId);
                        foreach ($messages as $message) {
                            $messageReceiverId = $message->getReceiverId();
                            $messageReceiver = User::loadUserbyId($conn, $messageReceiverId);
                            echo '<div class="messageAuthor">wiadomośc wysłana do: ' . $messageReceiver->getName() .
                                '</div>';
                            echo '<div class="messageDate"> wysłana dnia: ' . $message->getCreationDate() . '</div>';
                            echo '<div class="messageText"> ' . $message->getMessage() . '</div><br>';
                        };
                    }
                    ?>
                </section>
            </div>
            <div class="col-md-2">
                <section class="allUsers">
                    <h3>lista użytkowników:</h3>
                    <?php
                    $allUsers = User::loadAllUsers($conn);
                    foreach ($allUsers as $user) {
                        if ($user->getId() != $loggedUserId) {
                            echo '<div class="showUser">' . $user->getName();
                            echo ' <a href="userPage.php?userId=' . $user->getId() . '">Wyślij wiadomość</a></div><br>';
                        }
                    }
                    ?>
                </section>
            </div>
        </div>
    </main>
    <footer>
    </footer>
    </body>
</html>
