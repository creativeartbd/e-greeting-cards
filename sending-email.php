<?php 
require_once 'header.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'admin/vendor/autoload.php';
?>
<div class="container">
    <div class="row">
        <div class="col-sm-6 mx-auto mt-5">
            <h2>Sent Card</h2>
            <?php
            // Get data from the url;
            $domain_id = isset( $_GET['domain'] ) ? (int) $_GET['domain'] : 0;
            $name = isset( $_GET['name'] ) ? (string) $_GET['name'] : '';
            $email = isset( $_GET['email'] ) ? (string) $_GET['email'] : '';

            $get_domain = mysqli_query( $mysqli, "SELECT edg.*, ed.domain_name FROM eg_design AS edg LEFT JOIN eg_domains AS ed ON edg.domain_id = ed.domain_id WHERE edg.domain_id = '$domain_id' ");
            if( mysqli_num_rows( $get_domain ) > 0 ) {
                // Result data
                $get_result = mysqli_fetch_array( $get_domain, MYSQLI_ASSOC );
                $design_img = $get_result['design_img'];
                
                $design_font_size = $get_result['design_font_size'];
                $design_x = $get_result['design_x'];
                $design_y = $get_result['design_y'];
                $color = $get_result['color'];

                $d_design_font_size = $get_result['d_design_font_size'];
                $d_design_x = $get_result['d_design_x'];
                $d_design_y = $get_result['d_design_y'];
                $d_color = $get_result['d_color'];

                $domain_name = $get_result['domain_name'];

                $explode = explode( '.', $design_img );
                $extension = $explode[1];

                $allowed_extension = [ 'jpg', 'jpeg', 'png' ];

                //header('Content-type: image/jpeg');
                if( $extension == 'jpg' || $extension == 'jpeg' ) {
                    $image = imagecreatefromjpeg('admin/assets/design/'.$design_img);
                } else {
                    $image = imagecreatefrompng('admin/assets/design/'.$design_img);
                }

                list($r, $g, $b) = sscanf( $color, "#%02x%02x%02x" );
                list($d_r, $d_g, $d_b) = sscanf( $d_color, "#%02x%02x%02x" );
                
                $title_color = imagecolorallocate($image, $r, $g, $b);
                $domain_color = imagecolorallocate($image, $d_r, $d_g, $d_b);

                $font_path = 'admin/Fonts/alfont_com_هلفيتيكا-عربي-.ttf';
                $text = $name;
                $font_size = $design_font_size;
                $angle = 0;

                // Get image dimensions
                $width = imagesx($image);
                $height = imagesy($image);
                // Get center coordinates of image
                $centerX = $width / 2;
                $centerY = $height / 2;
                // Get size of text
                list($left, $bottom, $right, , , $top) = imageftbbox( $design_font_size, $angle, $font_path, $text );
                // Determine offset of text
                $left_offset = ($right - $left) / 2;
                $top_offset = ($bottom - $top) / 2;
                // Generate coordinates
                $x = $centerX - $left_offset;
                $y = $centerY + $top_offset;
                // Add text to image
                imagettftext( $image, $design_font_size, $angle, $design_x, $design_y, $title_color, $font_path, $text);
                imagettftext( $image, $d_design_font_size, $angle, $d_design_x, $d_design_y, $domain_color, $font_path, $domain_name);
                //imagettftext($jpg_image, 25, 0, 655, 1200, $white, $font_path, $text);
                $time = time().'.'.$extension;
                if( $extension == 'jpg' || $extension == 'jpeg' ) {
                    imagejpeg( $image,"admin/assets/design/design.".$extension );
                } else {
                    imagepng( $image,"admin/assets/design/design.".$extension );
                }

                imagedestroy( $image );
                
                $url = "admin/assets/design/design.".$extension;
                echo "<img class='img-fluid' src='$url'>"; 
                
                //Create an instance; passing `true` enables exceptions
                $mail = new PHPMailer(true);

                try {
                    
                    //Recipients
                    $mail->setFrom('support@shibbir.dev', 'Shibbir Dev');
                    $mail->addAddress($email, 'Shibbir Ahmed');     //Add a recipient

                    //Content
                    $mail->isHTML(true);                                  //Set email format to HTML
                    $mail->Subject = 'Greeting Card';
                    $mail->Body    = "<img src='$url'>";
                    $mail->AddEmbeddedImage($url,'testImage','test.jpg');
                    $mail->Body = '<img src="cid:testImage">';

                    $mail->send();
                    echo "<div class='alert alert-success mt-5'>Message has been sent";
                    
                } catch (Exception $e) {
                    echo "<div class='alert alert-success mt-5'>Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }       

            } else {
                echo "<div class='alert alert-danger  mt-5'>Sorry, No design is found!</div>";
            }
            ?>
        </div>
        <div class="col-md-12">
        </div>
    </div>
</div>
<?php require_once 'footer.php'; ?>