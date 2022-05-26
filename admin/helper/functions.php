<?php 
// base url 
require_once 'connection.php';
define('BASE_URL', 'http://localhost:8888/freelancer/e-greeting-cards/' );

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

function generate_image() {
    // Start generating image
    if( in_array( $extension, [ 'jpg', 'jpeg'] ) ) {
        header("Content-type: image/jpeg");
        $jpg_image = imagecreatefromjpeg('../assets/design/sample/'.$new_file_name);
    } else{
        header("Content-type: image/png");
        $jpg_image = imagecreatefrompng('../assets/design/sample/'.$new_file_name);
    }

    list($r, $g, $b) = sscanf($color, "#%02x%02x%02x");

    $black = imagecolorallocate($jpg_image, $r, $g, $b);
    $font_path = '../Fonts/HelveticaNeue-BoldItalic.otf';
    $text = $design;
    $font_size = 30;
    $angle = 0;

    // Get image dimensions
    $width = imagesx($jpg_image);
    $height = imagesy($jpg_image);
    // Get center coordinates of image
    $centerX = $width / 2;
    $centerY = $height / 2;
    // Get size of text
    list($left, $bottom, $right, , , $top) = imageftbbox($font_size, $angle, $font_path, $text);
    // Determine offset of text
    $left_offset = ($right - $left) / 2;
    $top_offset = ($bottom - $top) / 2;
    // Generate coordinates
    $x = $centerX - $left_offset;
    $y = $centerY + $top_offset;

    // Add text to image
    if( !empty( $design_font_size ) ) {
        $font_size = $design_font_size;
    }
    if( !empty( $design_x ) ) {
        $x = $design_x;
    }
    if( !empty( $design_y ) ) {
        $y = $design_y;
    }

    imagettftext($jpg_image, $font_size, $angle, $x, $y, $black, $font_path, $text);
    $time = time();
    //imagettftext($jpg_image, 25, 0, 655, 1200, $white, $font_path, $text);
    if( in_array( $extension, [ 'jpg', 'jpeg'] ) ) {
        imagejpeg($jpg_image, "../assets/design/sample/$new_file_name");
    } else {
        imagepng($jpg_image, "../assets/design/sample/$new_file_name");
    }
    
    imagedestroy($jpg_image);
    
}
