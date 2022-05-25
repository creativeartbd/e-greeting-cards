<?php 
require_once 'functions.php';

// Prepare the card and add the text on the card design
if( isset( $_REQUEST['form']) && $_REQUEST['form'] == 'output_design' ) {
   
    echo '<pre>';
        //  print_r( $_REQUEST );
        //  print_r( $_FILES );
        // print_r( $_SESSION['output_design'] );
    echo '</pre>';

    $design = validate( $_POST['design'] );
    $design_font_size = validate( $_POST['design_font_size'] );
    $design_x = validate( $_POST['design_x'] );
    $design_y = validate( $_POST['design_y'] );
    $domain = validate( $_POST['domain'] );
    $color = validate( $_POST['color'] );

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
    $allowed_file_size = 5000000; // 5 MB file size allowed
    $new_file_name = time().'.'.$extension;

    if( empty( $file_name ) ) {
        echo "<div class='alert alert-warning'>Please upload a design.</div>";
    } else {
        // Store generate desing, we will delete it later
        $_SESSION['output_design'][] = $new_file_name;
        // Upload the file because we need to show the output right now
        move_uploaded_file($file_tmp_name, "../assets/design/".$new_file_name);
    
        // Start generating image
        if( in_array( $extension, [ 'jpg', 'jpeg'] ) ) {
            header("Content-type: image/jpeg");
            $jpg_image = imagecreatefromjpeg('../assets/design/'.$new_file_name);
        } else{
            header("Content-type: image/png");
            $jpg_image = imagecreatefrompng('../assets/design/'.$new_file_name);
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
            imagejpeg($jpg_image, "../assets/design/$new_file_name");
        } else {
            imagepng($jpg_image, "../assets/design/$new_file_name");
        }
        
        imagedestroy($jpg_image);
            
        $url = BASE_URL."admin/assets/design/$new_file_name";
        echo "<img class='img-fluid' src='$url'>"; 

        // Delete all generated design except the latest one
        $total_output_desing = count( $_SESSION['output_design'] ); 
        for( $x = 0; $x < $total_output_desing - 1; $x++) {
            @unlink("../assets/design/".$_SESSION['output_design'][$x]);
        }
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

// Create design 
if( isset( $_POST['form'] ) && ( $_POST['form'] == 'create_design' ||  $_POST['form'] == 'update_design' ) ) {

    $domain = validate( $_POST['domain'] );
    $design = validate( $_POST['design'] );
    $design_font_size = validate( $_POST['design_font_size'] );
    $design_x = validate( $_POST['design_x'] );
    $design_y = validate( $_POST['design_y'] );
    $design_id = isset( $_POST['design_id'] ) ? validate( $_POST['design_id'] ) : 0;
    $form = validate( $_POST['form'] );

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


    if( isset( $domain ) && isset( $file_name ) && isset( $design ) && isset( $design_font_size ) && isset( $design_x ) && isset( $design_y ) ) {
        if( empty( $domain ) && empty( $file_name ) && empty( $design ) && empty( $design_font_size ) && empty( $design_x ) && empty( $design_y ) ) {
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

            if( empty( $design_font_size ) ) {
                $output['message'][] = 'Enter the font size';
            } elseif( !preg_match('/^[0-9]+$/', $design_font_size) ) {
                $output['message'][] = 'Font size should be contain only number.';
            } elseif( $design_font_size > 100 ) {
                $output['message'][] = 'Font size must be less than 100px';
            }

            if( empty( $design_x ) ) {
                $output['message'][] = 'Enter the design X axios value';
            } elseif( !preg_match('/^[0-9]+$/', $design_x) ) {
                $output['message'][] = 'X axios value should be contain only number.';
            } elseif( $design_x > 1000 ) {
                $output['message'][] = 'X axios value must be less than 1000px';
            }

            if( empty( $design_y ) ) {
                $output['message'][] = 'Enter the design Y axios value';
            } elseif( !preg_match('/^[0-9]+$/', $design_y) ) {
                $output['message'][] = 'Y axios value should be contain only number.';
            } elseif( $design_y > 2500 ) {
                $output['message'][] = 'Y axios value must be less than 2500px';
            }
            
            if( empty( $domain ) ) {
                $output['message'][] = 'Enter your domain name.';
            } elseif( !preg_match('/^[0-9]+$/', $domain) ) {
                $output['message'][] = 'Your domain should be contain only number.';
            } 
            
            if( empty( $output['message'] ) ) {

                if( 'create_design' == $form ) {

                    $query = mysqli_query( $mysqli, "INSERT INTO eg_design( design_title, domain_id, design_img, design_font_size, design_x, design_y ) VALUES( '$design', '$domain', '$new_file_name', '$design_font_size', '$design_x', '$design_y' ) ");
                    $message = "Successfully created a new design.";

                } elseif ( 'update_design' == $form ) {

                    $sql = "SET design_title = '$design', domain_id = '$domain', design_font_size = '$design_font_size', design_x = '$design_x', design_y = '$design_y'  ";

                    if( !empty( $file_name ) ) {
                        $sql .= " ,design_img = '$new_file_name' ";
                    }

                    $sql .= " WHERE design_id = '$design_id' ";
                    $query = mysqli_query( $mysqli, "UPDATE eg_design $sql ");
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
            }
        }
        echo json_encode($output);
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