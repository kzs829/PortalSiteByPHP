<?php
session_start();

$_SESSION = array();
@session_destroy();

session_start();
$_SESSION['access'] = 1;

header('Location: index.php');
?>