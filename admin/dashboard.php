<?php require_once 'header.php'; ?>
<style>.show_host, .show_auth_details{display: none;}</style>
<div class="container">
    <div class="row">
        <div class="col-sm-12 mt-5 mb-5">
            <h2>Welcome to Dashboard <?php echo $_SESSION['username']; ?></h2>
        </div>
        <div class="col-md-7">
            <h5>Email Settings</h5>
            <?php
            $get_settings = mysqli_query( $mysqli, "SELECT * FROM eg_email_settings");
            $get_result = mysqli_fetch_array( $get_settings, MYSQLI_ASSOC );

            $from_domain = isset( $get_result['from_domain'] ) ? $get_result['from_domain'] : '' ; 
            $from_name = isset( $get_result['from_name'] ) ? $get_result['from_name'] : '' ; 
            $email_subject = isset( $get_result['email_subject'] ) ?$get_result['email_subject'] : '' ; 
            
            $is_smtp = isset( $get_result['is_smtp'] ) ? $get_result['is_smtp'] : '' ; 
            $smtp_host = isset( $get_result['smtp_host'] ) ? $get_result['smtp_host'] : '' ; 
            $is_smtp_auth = isset( $get_result['is_smtp_auth'] ) ? $get_result['is_smtp_auth'] : '' ; 
            $smtp_username = isset( $get_result['smtp_username'] ) ? $get_result['smtp_username'] : '' ; 
            $smtp_password = isset( $get_result['smtp_password'] ) ? $get_result['smtp_password'] : '' ; 
            $smtp_mail_port = isset( $get_result['smtp_mail_port'] ) ? $get_result['smtp_mail_port'] : '' ; 
            ?>
            <form id="form">
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label for="from_domain" class="form-label">Enable SMTP</label> <br/>
                            <select name="is_smtp" id="is_smtp" class="form-control">
                                <option value="">--Select--</option>
                                <option value="yes" <?php if( $is_smtp == 'yes' ) echo 'selected'; ?>>Yes</option>
                                <option value="no" <?php if( $is_smtp == 'no' ) echo 'selected'; ?>>No</option>
                            </select>
                        </div>
                    </div>
                    <div class="col show_host">
                        <div class="mb-3">
                            <label for="smtp_host" class="form-label">SMTP Host</label>
                            <input type="text" name="smtp_host" value="<?php echo $smtp_host; ?>" class="form-control" id="smtp_host">
                        </div>
                    </div>
                </div>
                <div class="row show_auth_details">
                    <div class="col">
                        <div class="mb-3">
                            <label for="smtp_username" class="form-label">SMTP Username</label>
                            <input type="text" name="smtp_username" value="<?php echo $smtp_username; ?>" class="form-control" id="smtp_username">
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-3">
                            <label for="smtp_password" class="form-label">SMTP Password</label>
                            <input type="password" name="smtp_password" value="<?php echo $smtp_password; ?>" class="form-control" id="smtp_password">
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-3">
                            <label for="smtp_mail_port" class="form-label">SMTP Mail Port</label>
                            <input type="text" name="smtp_mail_port" value="<?php echo $smtp_mail_port; ?>" class="form-control" id="smtp_mail_port">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label for="from_domain" class="form-label">Email from domain address</label>
                            <input type="text" name="from_domain" value="<?php echo $from_domain; ?>" class="form-control" id="from_domain">
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-3">
                            <label for="from_name" class="form-label">Email from name</label>
                            <input type="text" name="from_name" value="<?php echo $from_name; ?>" class="form-control" id="from_name">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label for="email_subject" class="form-label">Email subject title</label>
                            <input type="text" name="email_subject" value="<?php echo $email_subject; ?>" class="form-control" id="email_subject">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-gradient-primary">Save Settings</button>
                <div class="mt-3">
                    <div class="result"></div>
                    <input type="hidden" name="form" value="email_settings">
                </div>
            </form>
        </div>
        <div class="col-md-5">
            <div class="result_design"></div>
        </div>
    </div>
</div>
<?php require_once 'footer.php'; ?> 