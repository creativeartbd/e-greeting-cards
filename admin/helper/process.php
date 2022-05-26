<?php 
require_once 'functions.php';

// Prepare the card and add the text on the card design
if( isset( $_POST['form']) && $_POST['form'] == 'edit_output_design' ) {
    
    $design = validate( $_POST['design'] );
    $design_id = isset( $_POST['design_id'] ) ? validate( $_POST['design_id'] ) : '';
    
    // Design settings
    $design_font_size = validate( $_POST['fontsize'] );
    $design_x = validate( $_POST['design_x'] );
    $design_y = validate( $_POST['design_y'] );
    $domain = validate( $_POST['domain'] );
    $color = validate( $_POST['color'] );
    // Domain settings
    $d_design_font_size = validate( $_POST['d_fontsize'] );
    $d_design_x = validate( $_POST['d_design_x'] );
    $d_design_y = validate( $_POST['d_design_y'] );
    $d_color = validate( $_POST['d_color'] );
    

    $domain_id = $domain;

    $get_desing = mysqli_query( $mysqli, "SELECT eg_design.design_img, eg_domains.domain_name FROM eg_design LEFT JOIN eg_domains ON eg_design.domain_id = eg_domains.domain_id WHERE eg_design.design_id = '$design_id' ");
    $found_design = mysqli_num_rows( $get_desing );

    $allowed_extension = [ 'jpg', 'jpeg', 'png' ];
    $allowed_file_size = 5000000; // 5 MB file size allowed
    $design_img = $file_name = $file_tmp_name = $file_size = $file_type = $extension = '';

    if( $found_design > 0 ) {
        $get_result = mysqli_fetch_array( $get_desing, MYSQLI_ASSOC );
        $design_img = $get_result['design_img'];
        $domain_name = $get_result['domain_name'];
        $explode = explode( '.', $design_img );
        $extension = $explode[1];
        $new_file_name = $design_img;

        $new_file_name = time().'.'.$extension;
        rename( "../assets/design/".$design_img, "../assets/design/".$new_file_name);
        $update = mysqli_query( $mysqli, "UPDATE eg_design SET design_img = '$new_file_name' WHERE design_id = '$design_id' ");
    }

    if( isset( $_FILES['design']['name'] ) && ! empty( $_FILES['design']['name']) ) {
        $file_name = validate( $_FILES['design']['name'] );
        $file_tmp_name = validate( $_FILES['design']['tmp_name'] );
        $file_size = validate( $_FILES['design']['size'] );
        $file_type = validate( $_FILES['design']['type'] );
        $explode = explode( '.', $file_name );
        $extension = end( $explode );
        $new_file_name = time().'.'.$extension;
    }

    if( $found_design == 0 ) {
        echo "<div class='alert alert-danger'>Design is not found.</div>";
    } elseif( empty( $design ) ) {
        echo "<div class='alert alert-danger'>Design title is reuqired.</div>";
    } elseif( ! in_array( $extension, $allowed_extension ) ) {
        echo "<div class='alert alert-danger'>Only JPG and PNG image is allowed.</div>";
    } else {
        // Store generate desing, we will delete it later
        $_SESSION['output_design'][] = $new_file_name;
        // Upload the file because we need to show the output right now
        if( isset( $_FILES['design']['name'] ) && ! empty( $_FILES['design']['name']) ) {
            move_uploaded_file($file_tmp_name, "../assets/design/sample/".$new_file_name);
        } else {
            copy("../assets/design/".$new_file_name, "../assets/design/sample/".$new_file_name);
        }
        // Start generating image
        if( isset( $_FILES['design']['name'] ) && ! empty( $_FILES['design']['name']) ) {
            if( in_array( $extension, [ 'jpg', 'jpeg'] ) ) {
                header("Content-type: image/jpeg, charset=utf-8");
                $jpg_image = imagecreatefromjpeg('../assets/design/sample/'.$new_file_name);
            } else{
                header("Content-type: image/png, charset=utf-8");
                $jpg_image = imagecreatefrompng('../assets/design/smaple/'.$new_file_name);
            }
        } else {
            if( in_array( $extension, [ 'jpg', 'jpeg'] ) ) {
                header("Content-type: image/jpeg, charset=utf-8");
                $jpg_image = imagecreatefromjpeg('../assets/design/sample/'.$new_file_name);
            } else{
                header("Content-type: image/png, charset=utf-8");
                $jpg_image = imagecreatefrompng('../assets/design/sample/'.$new_file_name);
            }
        }
        

        list($r, $g, $b) = sscanf($color, "#%02x%02x%02x");
        list($d_r, $d_g, $d_b) = sscanf($d_color, "#%02x%02x%02x");

        $black = imagecolorallocate($jpg_image, $r, $g, $b);
        $d_color = imagecolorallocate($jpg_image, $d_r, $d_g, $d_b);
        
        $font_path = '../Fonts/alfont_com_هلفيتيكا-عربي-.ttf';
        $text = $design;
        $text = mb_convert_encoding($text, "HTML-ENTITIES", "UTF-8");
        
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

        $d_font_size = 30;
        $d_x = $x;
        $d_y = $height - 200;

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

        if( !empty( $d_font_size ) ) {
            $d_font_size = $d_design_font_size;
        }
        if( !empty( $d_design_x ) ) {
            $d_x = $d_design_x;
        }
        if( !empty( $d_design_y ) ) {
            $d_y = $d_design_y;
        }

        imagettftext($jpg_image, $font_size, $angle, $x, $y, $black, $font_path, $text);
        imagettftext($jpg_image, $d_font_size, $angle, $d_x, $d_y, $d_color, $font_path, $domain_name);

        if( isset( $_FILES['design']['name'] ) && ! empty( $_FILES['design']['name']) ) {
            if( in_array( $extension, [ 'jpg', 'jpeg'] ) ) {
                imagejpeg($jpg_image, "../assets/design/sample/".$new_file_name);
            } else {
                imagepng($jpg_image, "../assets/design/sample/".$new_file_name);
            }
        } else {

           
            if( in_array( $extension, [ 'jpg', 'jpeg'] ) ) {
                imagejpeg($jpg_image, "../assets/design/sample/".$new_file_name);
            } else {
                imagepng($jpg_image, "../assets/design/sample/".$new_file_name);
            }
        }
        
        imagedestroy($jpg_image);
            
        $url = BASE_URL."admin/assets/design/sample/$new_file_name";
        echo "<img class='img-fluid' src='$url'>"; 
    }
}

// update design 
if( isset( $_POST['form'] ) && ( $_POST['form'] == 'create_design' ||  $_POST['form'] == 'update_design' ) ) {


    $domain = isset( $_POST['domain'] ) ? validate( $_POST['domain'] ) : '';
    $design = isset( $_POST['domain'] ) ? validate( $_POST['design'] ) : '';
    // Title settings
    $design_font_size = isset( $_POST['domain'] ) ? validate( $_POST['fontsize'] )  : 30;
    $design_x = isset( $_POST['domain'] ) ? validate( $_POST['design_x'] ) : '';
    $design_y = isset( $_POST['domain'] ) ? validate( $_POST['design_y'] ) : '';
    $color = isset( $_POST['domain'] ) ? validate( $_POST['color'] ) : '#000000';
    // Domain settings
    $d_design_font_size = isset( $_POST['d_fontsize'] ) ? validate( $_POST['d_fontsize'] )  : 30;
    $d_design_x = isset( $_POST['d_design_x'] ) ? validate( $_POST['d_design_x'] ) : '';
    $d_design_y = isset( $_POST['d_design_y'] ) ? validate( $_POST['d_design_y'] ) : '';
    $d_color = isset( $_POST['d_color'] ) ? validate( $_POST['d_color'] ) : '#000000';

    $design_id = isset( $_POST['design_id'] ) ? validate( $_POST['design_id'] ) : 0;
    $form = isset( $_POST['domain'] ) ? validate( $_POST['form'] ) : '';

    // Hold all errors
    $output['message'] = [];
    $output['success'] = false;
    $output['redirect'] = "all-design.php";

    $check_design = mysqli_query( $mysqli, "SELECT domain_id FROM eg_design WHERE domain_id = '$domain' ");
    $found_design = mysqli_num_rows( $check_design );

    $file_name = $file_tmp_name = $file_size = $file_type = $extension = '';
    if( isset( $_FILES['design']['name'] ) ) {
        $file_name = validate( $_FILES['design']['name'] );
        $file_tmp_name = validate( $_FILES['design']['tmp_name'] );
        $file_size = validate( $_FILES['design']['size'] );
        $file_type = validate( $_FILES['design']['type'] );

        $allowed_extension = [ 'jpg', 'jpeg', 'png', 'gif' ];
        $explode = explode( '.', $file_name );
        $extension = end( $explode );
    }
    $allowed_file_size = 5000000; // 5 MB file size allowed
    $new_file_name = time().'.'.$extension;


    if( isset( $domain ) && isset( $file_name ) && isset( $design ) && isset( $design_font_size ) && isset( $design_x ) && isset( $design_y ) && isset( $color ) ) {
        if( empty( $domain ) && empty( $file_name ) && empty( $design ) && empty( $design_font_size ) && empty( $design_x ) && empty( $design_y ) && empty( $color )) {
            $output['message'][] = 'All field is required.';
        } else {

            if( empty( $design ) ) {
                $output['message'][] = 'Enter your design title.';
            } elseif( !preg_match('/^[a-zA-Z. ]+$/', $design) ) {
                $output['message'][] = 'Your design title should be contain only characters.';
            } elseif( strlen( $design) > 40 || strlen( $design ) < 2 ) {
                $output['message'][] = 'Your design title should be 2-40 characters long.';
            }

            if( 'update_design' == $form ) {
                if( !empty( $file_name ) ) {
                    if ( ! in_array( $extension, $allowed_extension ) ) {
                        $output['message'][] = 'Uploaded file type is not allowed. We are currently allowing ' . implode(', ', $allowed_extension )  .' filetype';
                    } elseif( $file_size > $allowed_file_size ) {
                        $output['message'][] = 'Your uploaded file size must be less than 5 MB';
                    }
                }
            } else {
                // Validate file name
                if( empty( $file_name ) ) {
                    $output['message'][] = 'Please upload your design file.';
                } elseif ( ! in_array( $extension, $allowed_extension ) ) {
                    $output['message'][] = 'Uploaded file type is not allowed. We are currently allowing ' . implode(', ', $allowed_extension )  .' filetype';
                } elseif( $file_size > $allowed_file_size ) {
                    $output['message'][] = 'Your uploaded file size must be less than 5 MB';
                }
            }

            // Title Settings validation
            if( empty( $design_font_size ) ) {
                $output['message'][] = 'Title font size is reuquired.';
            } elseif( !preg_match('/^[0-9]+$/', $design_font_size) ) {
                $output['message'][] = 'Font size should be contain only number.';
            } elseif( $design_font_size > 100 ) {
                $output['message'][] = 'Font size must be less than 100px';
            }

            if( empty( $design_x ) ) {
                $output['message'][] = 'Title X axios is required.';
            } elseif( !preg_match('/^[0-9]+$/', $design_x) ) {
                $output['message'][] = 'Title X axios value should be contain only number.';
            } elseif( $design_x > 1000 ) {
                $output['message'][] = 'Tille X axios value must be less than 1000px';
            } 
            
            if( empty( $design_y ) ) {
                $output['message'][] = 'Title Y axios is required.';
            }elseif( !preg_match('/^[0-9]+$/', $design_y) ) {
                $output['message'][] = 'Title Y axios value should be contain only number.';
            } elseif( $design_y > 2500 ) {
                $output['message'][] = 'Title Y axios value must be less than 2500px';
            }

            if( empty( $color ) ) {
                $output['message'][] = 'Title color is required';
            }

            // Domain Settings validation
            if( empty( $d_design_font_size ) ) {
                $output['message'][] = 'Domain font size is reuquired.';
            } elseif( !preg_match('/^[0-9]+$/', $d_design_font_size) ) {
                $output['message'][] = 'Domain size should be contain only number.';
            } elseif( $d_design_font_size > 100 ) {
                $output['message'][] = 'Domain size must be less than 100px';
            }

            if( empty( $d_design_x ) ) {
                $output['message'][] = 'Domain X axios is required.';
            } elseif( !preg_match('/^[0-9]+$/', $d_design_x) ) {
                $output['message'][] = 'Domain X axios value should be contain only number.';
            } elseif( $d_design_x > 1000 ) {
                $output['message'][] = 'Domain X axios value must be less than 1000px';
            } 
            
            if( empty( $d_design_y ) ) {
                $output['message'][] = 'Domain Y axios is required.';
            }elseif( !preg_match('/^[0-9]+$/', $d_design_y) ) {
                $output['message'][] = 'Domain Y axios value should be contain only number.';
            } elseif( $d_design_y > 2500 ) {
                $output['message'][] = 'Domain Y axios value must be less than 2500px';
            }

            if( empty( $d_color ) ) {
                $output['message'][] = 'Domain color is required';
            }

            if( empty( $domain ) ) {
                $output['message'][] = 'Enter your domain name.';
            } elseif( !preg_match('/^[0-9]+$/', $domain) ) {
                $output['message'][] = 'Your domain should be contain only number.';
            } 
            
            if( 'update_design' !== $form ) {
                if( $found_design > 0 ) {
                    $output['message'][] = 'Already added a design for this domain  .';
                }
            }
            
            
            if( empty( $output['message'] ) ) {
                if( 'create_design' == $form ) {
    
                    $columns = [
                        'design_img' => "'$new_file_name'",
                        'design_title' => "'$design'",
                        'domain_id' => "'$domain'",
                        
                        'color' => "'$color'",
                        'design_font_size' => "'$design_font_size'",
                        'design_x' => "'$design_x'",
                        'design_y' => "'$design_y'",

                        'd_color' => "'$d_color'",
                        'd_design_font_size' => "'$d_design_font_size'",
                        'd_design_x' => "'$d_design_x'",
                        'd_design_y' => "'$d_design_y'",
                    ];

                    $key = array_keys( $columns );
                    $value = array_values( $columns );
                    $key = implode( ', ', $key );
                    $value = implode( ', ', $value );
                    
                    $query = "INSERT INTO eg_design( $key ) VALUES( $value )";
                    $query = mysqli_query( $mysqli, $query );

                    $message = "Successfully created a new design.";

                } elseif ( 'update_design' == $form ) {

                    $columns = [
                        'design_title' => "'$design'",
                        'domain_id' => "'$domain'",
                        
                        'color' => "'$color'",
                        'design_font_size' => "'$design_font_size'",
                        'design_x' => "'$design_x'",
                        'design_y' => "'$design_y'",

                        'd_color' => "'$d_color'",
                        'd_design_font_size' => "'$d_design_font_size'",
                        'd_design_x' => "'$d_design_x'",
                        'd_design_y' => "'$d_design_y'",
                    ];

                    
                    if( !empty( $file_name ) ) {
                        $columns['design_img'] = "'$new_file_name'";
                    }

                    $prepared_sql = [];
                    foreach( $columns as $key => $value ) {
                        $prepared_sql[] = "$key = $value";
                    }

                    $implode = implode (', ', $prepared_sql );

                    
                    $sql = "UPDATE eg_design SET ";
                    $sql .= " $implode";
                    $sql .= " WHERE design_id = '$design_id' ";

                    // echo '<pre>';
                        
                    //      print_r( $sql );
                    // echo '</pre>';
                    // die();
                    
                    $query = mysqli_query( $mysqli, $sql);
                    $message = "Successfully updated the design.";
                }
                
                if( $query ) {
                    if( !empty( $file_tmp_name ) ) {
                        if( move_uploaded_file( $file_tmp_name, '../assets/design/'.$new_file_name ) ) {
                
                            $output['success'] = true;
                            $output['message'][] = $message;

                        } else {
                            $output['success'] = false;
                            $output['message'][] = "Opps! something wen't wrong. Design is not uploading...";
                        }
                    }
                    $output['success'] = true;
                    $output['message'][] = $message;
                } else {
                    $output['success'] = false;
                    $output['message'][] = "Opps! something wen't wrong." .  mysqli_error( $mysqli );
                }

                // Delete all generated design except the latest one
                // $total_output_desing = count( $_SESSION['output_design'] ); 
                // for( $x = 0; $x < $total_output_desing - 1; $x++) {
                //     @unlink("../assets/design/sample/".$_SESSION['output_design'][$x]);
                // } 
            }
        }
        echo json_encode($output);
    }
}

// Prepare the card and add the text on the card design
if( isset( $_REQUEST['form']) && $_REQUEST['form'] == 'output_design' ) {

    $design = validate( $_POST['design'] );
    $domain = validate( $_POST['domain'] );

    $design_font_size = validate( $_POST['fontsize'] );
    $design_x = validate( $_POST['design_x'] );
    $design_y = validate( $_POST['design_y'] );
    $color = validate( $_POST['color'] );

    $d_design_font_size = validate( $_POST['d_fontsize'] );
    $d_design_x = validate( $_POST['d_design_x'] );
    $d_design_y = validate( $_POST['d_design_y'] );
    $d_color = validate( $_POST['d_color'] );

    $domain_id = $domain;
    $file_name = $file_tmp_name = $file_size = $file_type = $extension = '';

    if( isset( $_FILES['design']['name'] ) ) {
        $file_name = validate( $_FILES['design']['name'] );
        $file_tmp_name = validate( $_FILES['design']['tmp_name'] );
        $file_size = validate( $_FILES['design']['size'] );
        $file_type = validate( $_FILES['design']['type'] );

        $allowed_extension = [ 'jpg', 'jpeg', 'png' ];
        $explode = explode( '.', $file_name );
        $extension = end( $explode );
    }

    $get_domain_name = mysqli_query( $mysqli, "SELECT domain_name FROM eg_domains WHERE domain_id = '$domain_id' ");
    $found_domain = mysqli_num_rows( $get_domain_name );
    $domain_name = '';
    if( $found_domain > 0 ) {
        $get_domain_result = mysqli_fetch_array( $get_domain_name );
        $domain_name = $get_domain_result['domain_name'];
    }

    $allowed_file_size = 5000000; // 5 MB file size allowed
    $new_file_name = time().'.'.$extension;

    if( empty( $design ) ) {
        echo "<div class='alert alert-danger'>Design title is reuqired.</div>";
    } elseif( empty( $file_name ) ) {
        echo "<div class='alert alert-danger'>Please upload a design.</div>";
    } elseif( ! in_array( $extension, $allowed_extension ) ) {
        echo "<div class='alert alert-danger'>Only JPG and PNG image is allowed.</div>";
    } elseif( $found_domain == 0 ) {
        echo "<div class='alert alert-danger'>Domain name is not found. </div>";
    } else {
        // Store generate desing, we will delete it later
        $_SESSION['output_design'][] = $new_file_name;
        // Upload the file because we need to show the output right now
        move_uploaded_file($file_tmp_name, "../assets/design/sample/".$new_file_name);
        // Start generating image
        if( in_array( $extension, [ 'jpg', 'jpeg'] ) ) {
            header("Content-type: image/jpeg, charset=utf-8");
            $jpg_image = imagecreatefromjpeg('../assets/design/sample/'.$new_file_name);
        } else{
            header("Content-type: image/png, charset=utf-8");
            $jpg_image = imagecreatefrompng('../assets/design/sample/'.$new_file_name);
        }

        list($r, $g, $b) = sscanf($color, "#%02x%02x%02x");
        list($d_r, $d_g, $d_b) = sscanf($d_color, "#%02x%02x%02x");

        $black = imagecolorallocate($jpg_image, $r, $g, $b);
        $d_color = imagecolorallocate($jpg_image, $d_r, $d_g, $d_b);

        $font_path = '../Fonts/alfont_com_هلفيتيكا-عربي-.ttf';
        $text = $design;
        $text = mb_convert_encoding($text, "HTML-ENTITIES", "UTF-8");
        
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
        $x = $d_x = $centerX - $left_offset;
        $y = $d_y = $centerY + $top_offset;

        // Add text to image
        if( !empty( $design_font_size ) ) {
            $font_size = $design_font_size;
        }

        // Set horizonal position of title
        if( !empty( $design_x ) ) {
            $x = $design_x;
        }
        if( $x > $width ) {
            $x = $design_x;
        }
        // Set vettical position of title
        if( !empty( $design_y ) ) {
            $y = $design_y;
        }
        if( $y > $height ) {
            $y = $height - 20;
        }

        // Add text to image
        if( !empty( $d_design_font_size ) ) {
            $d_font_size = $d_design_font_size;
        }

        // Set horizonal position of domain name
        if( !empty( $d_design_x ) ) {
            $d_x = $d_design_x;
        }
        if( $d_x > $width ) {
            $d_x = $d_design_x;
        }
        // Set vettical position of domain name
        if( !empty( $d_design_y ) ) {
            $d_y = $d_design_y;
        }
        if( $d_y > $height ) {
            $d_y = $height - 20;
        }
        

        imagettftext($jpg_image, $font_size, $angle, $x, $y, $black, $font_path, $text);
        imagettftext($jpg_image, $d_font_size, $angle, $d_x, $d_y, $d_color, $font_path, $domain_name);

        //imagettftext($jpg_image, 25, 0, 655, 1200, $white, $font_path, $text);
        if( in_array( $extension, [ 'jpg', 'jpeg'] ) ) {
            imagejpeg($jpg_image, "../assets/design/sample/".$new_file_name);
        } else {
            imagepng($jpg_image, "../assets/design/sample/".$new_file_name);
        }
        
        imagedestroy($jpg_image);
            
        $url = BASE_URL."admin/assets/design/sample/".$new_file_name;
        echo "<img class='img-fluid' src='$url'>"; 
    }
}

// Prepare the card and add the text on the card design
if( isset( $_POST['form']) && $_POST['form'] == 'check_design' ) {

    $name = validate( $_POST['name'] );
    $email = validate( $_POST['email'] );
    $domain = validate( $_POST['domain'] );

    $given_fontsize = validate( $_POST['fontsize'] );
    $given_position_x = validate( $_POST['position_x'] );
    $given_position_y = validate( $_POST['position_y'] );

    $domain_id = $domain;
    $get_desing = mysqli_query( $mysqli, "SELECT design_img FROM eg_design WHERE domain_id = '$domain_id' ");

    $errors = [];

    if( empty( $name ) ) {
        $errors[] = 'Your name is required';
    } elseif( strlen( $name ) > 50 || strlen( $name ) < 2 ) {
        $errors[] = 'Your name must be between 2-50 characters long';
    }

    if( empty( $email ) ) {
        $errors[] = 'Your email address is required';
    }

    if( empty( $domain ) ) {
        $errors[] = 'Please select a domain';
    } elseif( mysqli_num_rows( $get_desing ) == 0 ) {
        $errors[] = 'No design found';
    }

    if( !empty( $errors ) ) {
        foreach( $errors as $error ) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    } else {
        $get_result = mysqli_fetch_array( $get_desing, MYSQLI_ASSOC );
        $design_img = $get_result['design_img'];

        header('Content-type: image/jpeg');
        $jpg_image = imagecreatefromjpeg("../assets/design/$design_img");
        $black = imagecolorallocate($jpg_image, 0, 0, 0);
        $font_path = '../Fonts/HelveticaNeue-BoldItalic.otf';
        $text = $name;
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
        if( !empty( $given_fontsize ) ) {
            $font_size = $given_fontsize;
        }
        if( !empty( $given_position_x ) ) {
            $x = $given_position_x;
        }
        if( !empty( $given_position_y ) ) {
            $y = $given_position_y;
        }

        imagettftext($jpg_image, $font_size, $angle, $x, $y, $black, $font_path, $text);
        $time = time();
        //imagettftext($jpg_image, 25, 0, 655, 1200, $white, $font_path, $text);
        imagejpeg($jpg_image, "../assets/design/$time.jpg");
        imagedestroy($jpg_image);
            
        //echo "<img class='img-fluid' src='assets/design/$design_img'>";
        $url = BASE_URL."admin/assets/design/$time.jpg";
        echo "<img class='img-fluid' src='$url'>";   
    }
}

// delete domain 
if( isset( $_POST['form']) && $_POST['form'] == 'delete_domain' ) {

    $domain_id = (int) validate( $_POST['delete_id'] );
    // Hold all errors
    $output['message'] = [];
    $output['success'] = false;
    $output['redirect'] = "all-domain.php";

    if( empty( $domain_id ) ) {
        $output['message'][] = 'Your domain id missing';
    }

    if( empty( $output['message'] ) ) {
        $delete = mysqli_query( $mysqli, "DELETE FROM eg_domains WHERE domain_id = '$domain_id' ");
        if( $delete ) {
            $output['success'] = true;
            $output['message'][] = "Successfully deleted the domain.";
        } else {
            $output['success'] = false;
            $output['message'][] = "Opps! something wen't wrong.";
        }
    }
    echo json_encode($output);
}

// delete design 
if( isset( $_POST['form']) && $_POST['form'] == 'delete_design' ) {

    $design_id = (int) validate( $_POST['delete_id'] );
    // Hold all errors
    $output['message'] = [];
    $output['success'] = false;
    $output['redirect'] = "all-design.php";

    if( empty( $design_id ) ) {
        $output['message'][] = 'Your design id missing';
    }

    if( empty( $output['message'] ) ) {
        $delete = mysqli_query( $mysqli, "DELETE FROM eg_design WHERE design_id = '$design_id' ");
        if( $delete ) {
            $output['success'] = true;
            $output['message'][] = "Successfully deleted the design.";
        } else {
            $output['success'] = false;
            $output['message'][] = "Opps! something wen't wrong.";
        }
    }
    echo json_encode($output);
}

// Create domain 
if( isset( $_POST['form']) && $_POST['form'] == 'update_domain' ) {

    $domain = validate( $_POST['domain'] );
    $company = validate( $_POST['company'] );
    $domain_id = (int) validate( $_POST['domain_id'] );

    // Hold all errors
    $output['message'] = [];
    $output['success'] = false;
    $output['redirect'] = "all-domain.php";

    if( isset( $domain) ) {

        if( empty( $domain ) ) {
            $output['message'][] = 'Enter your domain name.';
        } elseif( !preg_match('/^[a-zA-Z]+$/', $domain) ) {
            $output['message'][] = 'Your domain should be contain only characters.';
        } elseif( strlen( $domain) > 40 || strlen( $domain ) < 2 ) {
            $output['message'][] = 'Your domain should be 2-40 characters long.';
        }

        if( empty( $company ) ) {
            $output['message'][] = 'Enter your company name.';
        } elseif( !preg_match('/^[a-zA-Z]+$/', $company) ) {
            $output['message'][] = 'Your company name should be contain only characters.';
        } elseif( strlen( $company) > 40 || strlen( $company ) < 2 ) {
            $output['message'][] = 'Your company should be 2-40 characters long.';
        }

        if( empty( $domain_id ) ) {
            $output['message'][] = 'Your domain id missing';
        }

        if( empty( $output['message'] ) ) {
            $domain = '@'.$domain;
            $update = mysqli_query( $mysqli, "UPDATE eg_domains SET domain_name = '$domain', company_name = '$company' WHERE domain_id = '$domain_id' ");
            if( $update ) {
                $output['success'] = true;
                $output['message'][] = "Successfully udpate the domain.";
            } else {
                $output['success'] = false;
                $output['message'][] = "Opps! something wen't wrong.";
            }
        }
        echo json_encode($output);
    }
}

// Create domain 
if( isset( $_POST['form']) && $_POST['form'] == 'create_domain' ) {

    $domain = validate( $_POST['domain'] );
    $company = validate( $_POST['company'] );

    // Hold all errors
    $output['message'] = [];
    $output['success'] = false;
    $output['redirect'] = "all-domain.php";

    if( isset( $domain ) && isset( $company ) ) {

        if( empty( $domain ) ) {
            $output['message'][] = 'Enter your domain name.';
        } elseif( !preg_match('/^[@a-zA-Z. ]+$/', $domain) ) {
            $output['message'][] = 'Your domain should be contain only characters.';
        } elseif( strlen( $domain) > 40 || strlen( $domain ) < 2 ) {
            $output['message'][] = 'Your domain should be 2-40 characters long.';
        }

        if( empty( $company ) ) {
            $output['message'][] = 'Enter your company name.';
        } elseif( !preg_match('/^[a-zA-Z]+$/', $company) ) {
            $output['message'][] = 'Your company name should be contain only characters.';
        } elseif( strlen( $company) > 40 || strlen( $company ) < 2 ) {
            $output['message'][] = 'Your company should be 2-40 characters long.';
        }

        if( empty( $output['message'] ) ) {
            $domain = '@'.$domain;
            $insert = mysqli_query( $mysqli, "INSERT INTO eg_domains( domain_name, company_name ) VALUES( '$domain', '$company' ) ");
            if( $insert ) {
                $output['success'] = true;
                $output['message'][] = "Successfully created a new domain.";
            } else {
                $output['success'] = false;
                $output['message'][] = "Opps! something wen't wrong.";
            }
        }
        echo json_encode($output);
    }
}

// Send email 
if( isset( $_POST['form']) && $_POST['form'] == 'send_email___' ) {

    $name = validate( $_POST['name'] );
    $email = validate( $_POST['email'] );
    $domain = validate( $_POST['domain'] );

    $generate_email = $email.$domain;

    // Hold all errors
    $output['message'] = [];
    $output['success'] = false;
    $output['redirect'] = "sending-email.php?email=$generate_email&name=$name";

    if( isset( $name) && isset( $email ) && isset( $domain ) ) {
        if( empty( $name ) && empty( $email ) && empty( $domain ) ) {
            $output['message'][] = 'All fields is required';
        } else {
            // validate name
            if( empty( $name ) ) {
                $output['message'][] = 'Your name is required.';
            } elseif( strlen( $name) > 40 || strlen( $name ) < 2 ) {
                $output['message'][] = 'Your name should be between 2-40 characters long.';
            } elseif( !preg_match('/^[a-zA-Z \d]+$/', $name) ) {
                $output['message'][] = 'Your name should be contain only characters.';
            } 
            // validate email but not actual email
            if( empty( $email ) ) {
                $output['message'][] = 'Your email address';
            } elseif( strlen( $email) > 40 || strlen( $email ) < 2 ) {
                $output['message'][] = 'Your email address should be between 2-40 characters long.';
            } elseif( !preg_match('/^[a-zA-Z \d]+$/', $email) ) {
                $output['message'][] = 'Your email address should be contain only characters.';
            }
            // validate domain
            if( empty( $domain ) ) {
                $output['message'][] = 'Please select a domain';
            }
             
        }

        if( empty( $output['message'] ) ) {
            $output['success'] = true;
            $output['message'][] = "Generating card....";
        }
        echo json_encode($output);
    }
}

// Process login form 
if( isset( $_POST['form']) && $_POST['form'] == 'login' ) {
    // get all form field value
    $username = validate( $_POST['username'] );
    $password = validate( $_POST['password'] );
    $hash_password = hash( 'sha512', $password );
    $status = '';

    // Hold all errors
    $output['message'] = [];
    $output['success'] = false;
    $output['redirect'] = 'dashboard.php';

    // Check username and password
    $check_user = "SELECT username, password, user_status FROM eg_users WHERE username = '$username' AND password = '$hash_password' ";
    $user_query = mysqli_query( $mysqli, $check_user );
    $found_user = mysqli_num_rows( $user_query );

    $status = 1;
    if( $found_user ) {
        $result = mysqli_fetch_array( $user_query, MYSQLI_ASSOC );
        $status = $result['user_status'];
    }
    
    if( isset( $username) && isset( $password ) ) {
        if( empty( $username ) && empty( $password ) ) {
            $output['message'][] = 'All fields is required';
        } else {
            // validate username
            if( empty( $username ) ) {
                $output['message'][] = 'Username is required';
            } elseif( empty( $password ) ) {
                $output['message'][] = 'Password is required';
            } elseif( $found_user == 0 ) {
                $output['message'][] = 'Username or password is incorrect';
            } elseif( $status == 0 ) {
                $output['message'][] = 'Your account is not active, Please contact administrative';
            } 
        }

        if( empty( $output['message'] ) ) {
            if( $found_user == 1 ) {
                $output['success'] = true;
                $output['message'][] = "Successfully Logged, Now you are redirecting....";
                $_SESSION['username'] = $username;
            } else {
                $output['success'] = false;
                $output['message'][] = "Opps! Something wen't wrong! Please contact administrator";
            }
        }
        echo json_encode($output);
    }
}