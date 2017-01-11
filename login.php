<?php
require_once 'src/User.php';
require_once 'connection.php';
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
    <meta charset="utf-8">
    <title>Strona Główna</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
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
    <input type="submit" value="Login">
</form>
<a href="register.php">zarejestruj sie </a>
</body>

</html>