<?php 
// base url 
require_once 'connection.php';
define('BASE_URL', '' );

// Check if user logged in or not
function check_session() {
    if( ! isset( $_SESSION['username'] ) && empty( $_SESSION['username'] ) ) {
        header("location:index.php");
        exit();
    }
}

// Validate user input
function validate( $string ) {
    global $mysqli;
    $string = htmlspecialchars( $string );
    $string = trim( $string );
    $string = strip_tags( $string );
    $string = mysqli_real_escape_string( $mysqli, $string );
    return $string;
}
