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
    <link rel="stylesheet" href="css/style.css">
    <title>Strona Edycji</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
<nav>
    Strona Edycji<br>
    <a href="index.php">strona z Tweetami</a>
    <a href="userPage.php">Twoja Tablica</a>
    <?php
    if(isset($_SESSION['userId'])){
        echo "<a href='logout.php'>logout</a>";
    }
    ?>
</nav>
<main>
    <section class="userCredentials">
        <form method="POST">
            <label>
                Twoje Imię : <?php echo $loggedUser->getName();?><br>
                <input type="text" name="name">
            </label>
            <br>
            <label>
                Twój E-mail: <?php echo $loggedUser->getEmail();?><br>
                <input type="text" name="email">
            </label><br>
            <label>
                Wpisz nowe hasło: <br>
                <input type="password" name='password'>
            </label>
            <br>
            <label>
               Powtórz hasło: <br>
                <input type='password' name="retyped_password"><br>
            <br>
            <input type="submit" value="zmień wartości">
        </form>
    </section>
</main>
<footer>
</footer>
</body>
</html>

