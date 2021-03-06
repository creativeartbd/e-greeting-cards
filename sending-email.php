<?php 
require_once 'header.php'; 
require_once 'admin/word2uni-main/word2uni.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once 'admin/vendor/autoload.php';
?>
<div class="container">
    <div class="row">
        <div class="col-sm-4 mx-auto mt-5">
            <h2>Sent Card</h2>
            <?php
            // Get data from the url;
            $domain_id = isset( $_GET['domain'] ) ? (int) $_GET['domain'] : 0;
            $name = isset( $_GET['name'] ) ? (string) $_GET['name'] : '';
            $email = isset( $_GET['email'] ) ? (string) $_GET['email'] : '';

            // $get_domain = mysqli_query( $mysqli, "SELECT edg.*, ed.domain_name FROM eg_design AS edg LEFT JOIN eg_domains AS ed ON edg.domain_id = ed.domain_id WHERE edg.domain_id = '$domain_id' ");

            $get_domain = mysqli_query( $mysqli, "SELECT edg.*, ed.domain_name, egf.font_name AS design_font_name , egf2.font_name AS domain_font_name FROM eg_design AS edg 
            LEFT JOIN eg_domains AS ed ON edg.domain_id = ed.domain_id 
            LEFT JOIN eg_fonts AS egf ON egf.font_id = edg.design_font 
            LEFT JOIN eg_fonts AS egf2 ON egf2.font_id = edg.domain_font 
            WHERE edg.domain_id = '$domain_id' ");

            if( mysqli_num_rows( $get_domain ) > 0 ) {

                // get email settings
                $get_settings = mysqli_query( $mysqli, "SELECT * FROM eg_email_settings");
                $get_result = mysqli_fetch_array( $get_settings, MYSQLI_ASSOC );

                $from_domain = !empty( $get_result['from_domain'] ) ? $get_result['from_domain'] : '' ; 
                $from_name = !empty( $get_result['from_name'] ) ? $get_result['from_name'] : '' ; 
                $email_subject = !empty( $get_result['email_subject'] ) ?$get_result['email_subject'] : '' ; 
                $email_body = !empty( $get_result['email_body'] ) ? $get_result['email_body'] : '' ;
                $is_smtp = ! empty( $get_result['is_smtp'] ) ? validate( $get_result['is_smtp'] ) : '';
                $smtp_host = ! empty( $get_result['smtp_host'] ) ? validate( $get_result['smtp_host'] ) : '';
            
                $smtp_username = ! empty( $get_result['smtp_username'] ) ? validate( $get_result['smtp_username'] ) : '';
                $smtp_password = ! empty( $get_result['smtp_password'] ) ? validate( $get_result['smtp_password'] ) : '';
                $smtp_mail_port = ! empty( $get_result['smtp_mail_port'] ) ? validate( $get_result['smtp_mail_port'] ) : '';

                $show_preview = ! empty( $get_result['show_preview'] ) ? validate( $get_result['show_preview'] ) : '';
    

                // Result data
                $get_result = mysqli_fetch_array( $get_domain, MYSQLI_ASSOC );
                $design_img = $get_result['design_img'];
                
                $design_font_size = $get_result['design_font_size'];
                $design_x = $get_result['design_x'];
                $design_y = $get_result['design_y'];
                $color = $get_result['color'];
                $design_font = $get_result['design_font_name'];

                $d_design_font_size = $get_result['d_design_font_size'];
                $d_design_x = $get_result['d_design_x'];
                $d_design_y = $get_result['d_design_y'];
                $d_color = $get_result['d_color'];
                $domain_font = $get_result['domain_font_name'];

                $domain_name = $get_result['domain_name'];
                $email = $email.'@'.$domain_name;
                $domain_name = text2uni($domain_name);

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

                $font_path = 'admin/assets/fonts/'.$design_font;
                $domain_font_path = 'admin/assets/fonts/'.$domain_font;
                $text = $name;
                $text = text2uni($name);
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
                imagettftext( $image, $d_design_font_size, $angle, $d_design_x, $d_design_y, $domain_color, $domain_font_path, $domain_name);
                //imagettftext($jpg_image, 25, 0, 655, 1200, $white, $font_path, $text);
                $time = time().'.'.$extension;
                if( $extension == 'jpg' || $extension == 'jpeg' ) {
                    imagejpeg( $image,"admin/assets/design/design.".$extension );
                } else {
                    imagepng( $image,"admin/assets/design/design.".$extension );
                }

                imagedestroy( $image );
                
                $url = "admin/assets/design/design.".$extension;
                if( 1 == $show_preview ) {
                    echo "<img class='img-fluid' src='$url'>"; 
                }
        
                //Create an instance; passing `true` enables exceptions
                $mail = new PHPMailer(true);
                try {  
                    //Recipients
                    
                    $mail->isSMTP();      
                    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                    $mail->Host       = $smtp_host;
                    $mail->Port       = $smtp_mail_port;;        
                    $mail->SMTPAuth   = true;                                  
                    $mail->Username   = $smtp_username;              
                    $mail->Password   = $smtp_password;  
    
                    //Recipients
                    $mail->setFrom( $from_domain, $from_name );
                    $mail->addAddress( $email, $name );
                   
                    //Content
                    $mail->isHTML(true);//Set email format to HTML
                    $mail->Subject = $email_subject;
                    $mail->Body = "<p>$email_body</p>";
                    $mail->AddEmbeddedImage($url,'greetingcard','greetingcard.'.$extension);
                    $mail->Body .= '<img src="cid:greetingcard">';
                    $mail->send();

                    echo "<div class='alert alert-success mt-5'>Congratulation! Your greeting card has been sent.";
                    
                } catch (Exception $e) {
                    echo "<div class='alert alert-danger mt-5'>Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
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