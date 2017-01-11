<?php
if($_SERVER['REQUEST_METHOD'] == 'POST'){
     if(isset($_POST['name']) && strlen(trim($_POST['name'])) > 0 
    && isset($_POST['email']) && strlen(trim($_POST['email'])) >= 5
    && isset($_POST['password']) && strlen(trim($_POST['password'])) >= 6
    && isset($_POST['retyped_password']) 
    && trim($_POST['password']) == trim($_POST['retyped_password'])){
         require_once 'src/User.php';
         require_once 'connection.php';
         
         $user = new User();
         $user -> setName(trim($_POST['name']));
         $user -> setEmail(trim($_POST['email']));
         $user ->setPassword(trim($_POST['password']));
         
         if($user ->saveToDB($conn)){
             echo "Udalo sie zarejestrowac uzytkownika";
             header('Location: index.php');
         }else{
             echo "nie udalo sie zarejestrowac uzytkownika";
         }
     }else{
         echo "bledne dane w formularzu";
     }
}
?>
<html>
    <head>
        <meta charset="utf-8">
        <title>Strona Główna</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
              integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
    <nav>
    </nav>
    <main>
        <section class="jumbotron">
            <div class="container loginForm">
                <form method ='POST'>
                        <label>
                            Imię: <br>
                            <input type='text' name='name'>
                        </label>
                        <br>
                        <label>
                            E-mail:<br>
                            <input type="text" name="email">
                        </label>
                        <br>
                        <label>
                            Hasło: <br>
                            <input type="password" name='password'>
                        </label>
                        <br>
                        <label>
                            Powtórz hasło: <br>
                            <input type='password' name="retyped_password"><br>
                            <input class="btn btn-default" type="submit" value="register">
                            <a href="login.php">strona logowania</a>
                        </label>
                </form>
            </div>
    </main>
    </body>
</html>
