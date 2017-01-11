<?php
session_start();

if(!isset($_SESSION['userId'])){
    header('Location: login.php');
}

require_once 'src/Comment.php';
require_once 'src/Tweet.php';
require_once 'src/User.php';
require_once 'connection.php';

$loggedUserId = $_SESSION['userId'];
$loggedUser = User::loadUserById($conn, $loggedUserId);

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addTweetForm']) && strlen(trim($_POST['addTweet'])) > 0) {
    $tweet = new Tweet();
    $tweet->setText($_POST['addTweet']);
    $tweet->setUserId($loggedUserId);
    $tweet->setCreationDate(date('Y-m-d-h:i:s'));
    if($tweet->saveToDB($conn)) {
        echo 'Dodano Tweeta ' . $_POST['addTweet'] . "<br>";
    } else {
        echo 'wystapil problem z dodawaniem tweeta';
    }
}

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




?>

<html lang="pl">
    <head>
        <meta charset="utf-8">
        <title>Strona Główna</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <nav>
        Strona Główna, Witaj <?php echo $loggedUser->getName();?><br>
            <?php echo '<a href="userPage.php?userId=' . $loggedUser->getId() . '">Twoja Tablica</a>' ?>
        <?php
            if(isset($_SESSION['userId'])) {
                echo "<a href='logout.php'>logout</a>";
            }
        ?>

        </nav>
    <main>
        <section class="allUsers">
            <h3>lista użytkowników:</h3>
            <?php
            $allUsers = User::loadAllUsers($conn);
            foreach ($allUsers as $user) {
                if($user->getId() != $loggedUserId) {
                    echo '<div class="showUser" >' . $user->getName();
                    echo ' <a href="userPage.php?userId=' . $user->getId() . '">Wyślij wiadomość użytkownikowi</a></div><br>';
                }
            }
            ?>
        </section>
        <section class="addTweetForm">
            <h3>Dodaj Tweeta:</h3><br>
            <form action="#" method="POST">
            <input type="text" name="addTweet">
            <input type="hidden" name="addTweetForm" value="addTweetForm">
            <input type="submit" value="Dodaj Tweeta">
        </form>
        </section>
        <section class="tweetTable">
            <?php
            $tweets = Tweet::loadAllTweets($conn);
            foreach ($tweets as $tweet) {
                $authorId = $tweet->getUserId();
                $author = User::loadUserbyId($conn, $authorId);
                echo '<div class="TweetAuthor">Autor: ' . $author->getName() . '</div>';
                echo '<div class="Tweetdate"> Czas dodania: ' . $tweet->getCreationDate() . '</div>';
                echo '<div class="TweetText">' . $tweet->getText() . '</div>';
                echo '<div class="TweetComment">';
                $comments = Comment::loadCommentsByTweetID($conn, $tweet->getId());
                    foreach ($comments as $comment) {
                        $commentAuthorId = $comment->getId_usera();
                        $commentAuthor = User::loadUserbyId($conn, $commentAuthorId);
                        echo '<div class="TweetAuthor">Autor komentarza: ' . $commentAuthor->getName() . '</div>';
                        echo '<div class="CommentDate"> Utworzony: ' . $comment->getCreationDate() . '</div>';
                        echo'<div class="CommentText">' . $comment->getText() . '</div>';
                    };
                echo '</div>';
                echo '<form method="POST" >
                    <input type="hidden" name="addCommentForm" value="addCommentForm">
                    <input type="text" name="addComment">
                    <input type="hidden" name="tweetId" value="' . $tweet->getID() . '"><br> 
                    <input type="submit" value="Dodaj komentarz">
                </form>';
                };
                ?>
        </section>
    </main>
    </body>
</html>