<?php 
require_once '../admin/helper/functions.php';

// Send email 
if( isset( $_POST['form']) && $_POST['form'] == 'send_email' ) {

    $name = validate( $_POST['name'] );
    $email = validate( $_POST['email'] );
    $domain = validate( $_POST['domain'] );

    $generate_email = $email;

    // Hold all errors
    $output['message'] = [];
    $output['success'] = false;
    $output['redirect'] = "sending-email.php?email=$generate_email&name=$name&domain=$domain";

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
            // validate email
            if( empty( $email ) ) {
                $output['message'][] = 'Email address is required.';
            } elseif( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
                $output['message'][] = 'Email address is not correct';
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