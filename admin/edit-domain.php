<?php require_once 'header.php'; ?>
<div class="container">
    <div class="row">
        <div class="col-sm-12 mt-5">
            <h2>Update Domain</h2>
        </div>
        <div class="col-md-12">
            <?php
            $domain_id = (int) $_GET['domain_id'];
            $get_domain = mysqli_query( $mysqli, "SELECT * FROM sg_domains WHERE domain_id = '$domain_id' ");
            if( mysqli_num_rows( $get_domain ) > 0 ) :
                $get_result = mysqli_fetch_array( $get_domain );
                $domain_name = $get_result['domain_name'];
                $company = $get_result['company_name'];
                $explode = explode( '@', $domain_name );
                $domain_name = isset( $explode[1] ) ? $explode[1] : $domain_name;
            ?>
            <form id="form">
                <div class="mb-3">
                    <label for="domain">Enter your domain name</label>
                    <input type="text" id="domain" name="domain" class="form-control" value="<?php echo $domain_name; ?>">
                </div>
                <div class="mb-3">
                    <label for="company">Enter the company name</label>
                    <input type="text" id="company" name="company" class="form-control" value="<?php echo $company; ?>">
                </div>
                <button type="submit" class="btn btn-success">Update Domain </button>
                <div class="mt-3">
                    <div class="result"></div>
                    <input type="hidden" name="domain_id" value="<?php echo $domain_id; ?>">
                    <input type="hidden" name="form" value="update_domain">
                </div>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php require_once 'footer.php'; ?>