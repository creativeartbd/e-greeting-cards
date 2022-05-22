<?php
session_start();
ini_set('display_errors', 1);
// Report simple running errors
error_reporting(E_ERROR | E_WARNING | E_PARSE);
// Reporting E_NOTICE can be good too (to report uninitialized
// variables or catch variable name misspellings ...)
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
// Report all errors except E_NOTICE
error_reporting(E_ALL & ~E_NOTICE);
// Report all PHP errors (see changelog)
error_reporting(E_ALL);
// Report all PHP errors
error_reporting(-1);
// Same as error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

$host = 'localhost';
$user = 'root';
$password = 'root';
$db = 'e-greeting-cards';

$mysqli = new mysqli( $host, $user, $password, $db );
// Check connection
if ($mysqli -> connect_errno) {
    die(" Failed to connect to MySQL: " . $mysqli -> connect_error);
    exit();
}