<?php

require_once '../src/User.php';  // te .. kropki tutaj to jak w konsoli cd ..
require_once '../connection.php';

$user1 = new User();
$user1 -> setName('Adam');
$user1 -> setEmail('adam.mendak@gmail.com');
$user1 -> setPassword('trudnehaslo');

var_dump($user1 ->saveToDB($conn));

$conn -> close();
$conn = null;

?>
