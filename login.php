<?php
require_once 'init.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email']) && strlen(trim($_POST['email'])) >= 5
        && isset($_POST['password']) && strlen(trim($_POST['password'])) >= 6
    ) {

        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $user = User::login($conn, $email, $password);

        if ($user) {
            $_SESSION['userId'] = $user->getId();
            header('Location: index.php');
        } else {
            echo "niepoprawne dane logowania";

        }

    }
}

?>


<html lang="pl">
    <head>
        <?php include('headers.php');?>
    </head>
    <body>
    <nav>
    </nav>
    <main>
        <section class="jumbotron">
            <div class="container loginForm">
            <form method="POST">
                <label>
                    E-mail:<br>
                    <input type="text" name="email">
                </label>
                <br>
                <label>
                    Password:<br>
                    <input type="password" name="password">
                </label>
                <br>
                <input class="btn btn-default" type="submit" value="Login">
            </form>
                <a class="btn btn-primary" href="register.php" role="button">zarejestruj sie </a>
            </div>
        </section>
    </main>
    <footer>
    </footer>
    </body>
</html>