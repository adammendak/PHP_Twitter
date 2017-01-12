<?php
require_once 'connection.php';
spl_autoload_register(function($class) {
    require_once "src/{$class}.php";
});
?>

