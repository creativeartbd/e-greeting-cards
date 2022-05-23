<?php require_once 'header.php'; ?>
<div class="container">
    <div class="row">
        <div class="col-sm-12 mt-5">
            <h2>Send Card</h2>
            <?php
            header('Content-type: image/jpeg');
            $jpg_image = imagecreatefromjpeg('assets/design/design.jpeg');
            $white = imagecolorallocate($jpg_image, 0, 0, 0);
            $font_path = 'Fonts/HelveticaNeue-BoldItalic.otf';
            $text = "محمد سيبر أحمد";
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
            imagettftext($jpg_image, $font_size, $angle, $x, 1200, $white, $font_path, $text);


            //imagettftext($jpg_image, 25, 0, 655, 1200, $white, $font_path, $text);
            imagejpeg($jpg_image,"iimage.jpg");
            imagedestroy($jpg_image);
            
            // header('Content-type: image/jpeg');

            // // Create Image From Existing File
            // $jpg_image = imagecreatefromjpeg('assets/design/design.jpeg');
            // //$jpg_image=imagecreatetruecolor(100,100);

            // // Allocate A Color For The Text
            // $white = imagecolorallocate($jpg_image, 0, 0, 0);


            // // Set Path to Font File
            // $font_path = 'Fonts/HelveticaNeue-BoldItalic.otf';

            // // Set Text to Be Printed On Image
            // $text = "New Name";

            // // Print Text On Image
            // // $x=20;
            // // for($i=0;$i<=strlen($text);$i++){
            // //     $print_text=substr($text,$i,1);
            // //     $x+=30;
                

            // // }
            // imagettftext($jpg_image, 30, 0, $x, 1200, $white, $font_path, $print_text);

            // // Send Image to Browser
            // imagejpeg($jpg_image,'name.jpg');

            // // Clear Memory
            // imagedestroy($jpg_image);
            ?>
        </div>
        <div class="col-md-12">
        </div>
    </div>
</div>
<?php require_once 'footer.php'; ?>