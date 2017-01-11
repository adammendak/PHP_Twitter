<?php
session_start();
require_once 'src/Tweet.php';
require_once 'src/Message.php';
require_once 'src/User.php';
require_once 'connection.php';
require_once 'src/Comment.php';

if (!isset($_SESSION['userId'])) {
    header('Location:index.php');
}

$loggedUserId = $_SESSION['userId'];
$loggedUser = User::loadUserById($conn, $loggedUserId);

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['name'] ) && strlen(trim($_POST['name'])) > 1
        && isset($_POST['email'] ) && strlen(trim($_POST['email'])) > 5
//         && isset($_POST['password']) && strlen($_POST['password'] > 5)
//        && isset($_POST['retyped_password'])
    ){
        if(trim($_POST['retyped_password']) == trim($_POST['password']) ){
            $loggedUser->setName(trim($_POST['name']));
            $loggedUser->setEmail(trim($_POST['email']));
            $loggedUser->setPassword(trim($_POST['password']));
            $loggedUser->saveToDB($conn);
            echo 'edycja przebiegła prawidłowo'.$_POST['name'];
        }  else {
            echo 'Podane hasla nie sa identyczne nie udalo sie rarestwoac';
        }

    }  else {
        echo 'podano nieprawidlowe dane';
    }
}
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
        <div class="row">
            <div class="col-md-offset-2 col-md-8 bg-success columnSection"
            <section class="userCredentials">
                <form method="POST">
                    <label>
                        Twoje Imię : <?php echo $loggedUser->getName();?><br>
                        <input type="text" name="name" placeholder="podaj nowe imię ">
                    </label>
                    <br>
                    <label>
                        Twój E-mail: <?php echo $loggedUser->getEmail();?><br>
                        <input type="text" name="email" placeholder="podaj nowy e-mail">
                    </label><br>
                    <label>
                        Wpisz nowe hasło: <br>
                        <input type="password" name='password' placeholder="podaj nowe hasło">
                    </label>
                    <br>
                    <label>
                       Powtórz hasło: <br>
                        <input type='password' name="retyped_password" placeholder="powtorz hasło"><br>
                    <br>
                    <input role="button" class="btn btn-primary" type="submit" value="zmień wartości">
                </form>
            </section>
        </div>
            <div class="col-md-2">
                <section class="allUsers">
                    <h3>lista użytkowników:</h3>
                    <?php
                    $allUsers = User::loadAllUsers($conn);
                    foreach ($allUsers as $user) {
                        if($user->getId() != $loggedUserId) {
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

