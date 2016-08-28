<?php
require_once '../src/User.php';
require_once '../connection.php';

$user = User::loadUserByEmail($conn, 'adam.mendak@gmail.com');
var_dump($user);


$conn -> close ();
$conn = null;

