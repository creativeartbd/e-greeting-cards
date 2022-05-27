<?php require_once 'header.php'; ?>
<div class="container">
    <div class="row">
        <div class="col-sm-6 mt-5 mx-auto">
            <h2>Send Greeting Card</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mx-auto">
            <form id="form">
                <div class="mb-3">
                    <label for="name" class="form-label">Please write your full name</label>
                    <input type="text" name="name" class="form-control" id="name">
                </div>
                <div class="mb-3">
                    <div class="row">
                        <div class="col">
                            <label for="name" class="form-label">Enter your email address</label>
                            <input type="text" name="email" class="form-control" id="email">
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Choose a domain <span class='finding-design'></span></label>
                            <select name="domain" id="" class="form-control choose-domain" data-form="choose_domain">
                                <option value="">--Choose--</option>
                                <?php 
                                $get_domain = mysqli_query( $mysqli, "SELECT edo.*, edes.design_img FROM eg_design AS edes LEFT JOIN  eg_domains AS edo ON edo.domain_id = edes.domain_id");
                                if( mysqli_num_rows( $get_domain) > 0 ) {
                                    while ( $get_result = mysqli_fetch_array( $get_domain, MYSQLI_ASSOC ) ) {
                                        $domain_id = $get_result['domain_id'];
                                        $domain_name = $get_result['domain_name'];
                                        $company = $get_result['company_name'];
                                        echo "<option value='$domain_id'>$domain_name ($company)</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-gradient-primary ajax-btn">Send Email</button>
                <div class="mt-3">
                    <div class="result"></div>
                    <input type="hidden" name="form" value="send_email">
                </div>
            </form>
        </div>
    </div>
</div>
<?php require_once 'footer.php'; ?>