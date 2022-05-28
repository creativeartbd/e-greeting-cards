<?php require_once 'header.php'; ?>
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
            $email_body = isset( $get_result['email_body'] ) ? $get_result['email_body'] : '' ; 
            ?>
            <form id="form">
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
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label for="email_body" class="form-label">Email subject title <span class='text-danger'>(Optional)</span></label>
                            <textarea name="email_body" id="email_body" cols="30" rows="5" class="form-control"><?php echo $email_body; ?></textarea>
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