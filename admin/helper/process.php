<?php 
require_once 'functions.php';

// Create domain 
if( isset( $_POST['form']) && $_POST['form'] == 'delete_domain' ) {

    $domain_id = (int) validate( $_POST['domain_id'] );
    // Hold all errors
    $output['message'] = [];
    $output['success'] = false;
    $output['redirect'] = "all-domain.php";

    if( empty( $domain_id ) ) {
        $output['message'][] = 'Your domain id missing';
    }

    if( empty( $output['message'] ) ) {
        $domain = '@'.$domain;
        $update = mysqli_query( $mysqli, "DELETE FROM sg_domains WHERE domain_id = '$domain_id' ");
        if( $update ) {
            $output['success'] = true;
            $output['message'][] = "Successfully deleted the domain.";
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
            $update = mysqli_query( $mysqli, "UPDATE sg_domains SET domain_name = '$domain', company_name = '$company' WHERE domain_id = '$domain_id' ");
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

        if( empty( $output['message'] ) ) {
            $domain = '@'.$domain;
            $insert = mysqli_query( $mysqli, "INSERT INTO sg_domains( domain_name, company_name ) VALUES( '$domain', '$company' ) ");
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
if( isset( $_POST['form']) && $_POST['form'] == 'send_email' ) {

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