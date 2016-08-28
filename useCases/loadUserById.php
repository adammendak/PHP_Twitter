<?php

require_once'../src/User.php';
require_once'../connection.php';

$user = User::loadUserById($conn, 1);
var_dump($user);

$conn -> close();
$conn = null;




?>