<?php require_once 'header.php'; 
$username = validate($_SESSION['username']);
$sql = mysqli_query( $mysqli, "SELECT * FROM eg_users WHERE username = '$username' ");
$found = false;
if( mysqli_num_rows( $sql ) === 1 ) {
    $found = true;
}
?>
<div class="container">
    <div class="row">
        <div class="col-sm-12 mt-5">
            <h2>Your Profile</h2>
        </div>
        <?php 
        if( $found ) :
            $get_result = mysqli_fetch_array( $sql, MYSQLI_ASSOC );
            $full_name = $get_result['full_name'];
            $username = $get_result['username'];
            $email = $get_result['email'];
        ?>
        <div class="col-md-7">
            <form id="form" enctype="multipart/form-data" autocomplete="off">
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Full Name</label>
                            <input type="text" name="full_name" class="form-control" id="full_name" value="<?php echo $full_name; ?>">
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="text" name="email" class="form-control" id="email" value="<?php echo $email; ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="test" name="username" class="form-control" id="username" value="<?php echo $username; ?>">
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-3">
                            <label for="password" class="form-label">Update Password</label>
                            <input type="password" name="password" class="form-control" id="password" readonly onfocus="this.removeAttribute('readonly');">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success ajax-btn">Update Profile</button>
                <div class="mt-3">
                    <div class="result"></div>
                    <input type="hidden" name="form" value="update_profile">
                </div>
            </form>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php require_once 'footer.php'; ?>