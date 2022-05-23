<?php require_once 'header.php'; ?>
<div class="container">
    <div class="row">
        <div class="col-sm-12 mt-5">
            <h2>Upload Design</h2>
        </div>
        <div class="col-md-12">
            <form id="form">
                <div class="mb-3">
                    <label for="design_title" class="form-label">Ttile of the design</label>
                    <input type="text" name="design" class="form-control" id="design_title">
                </div>
                <div class="mb-3">
                    <label for="design" class="form-label">Upload a new design</label>
                    <input type="file" name="design" class="form-control" id="design">
                </div>
                <div class="mb-3">
                    <label for="domain">Choose a domain</label>
                    <select name="domain" id="domain" class="form-control">
                        <option value="">--Choose--</option>
                        <?php 
                        $get_domain = mysqli_query( $mysqli, "SELECT * FROM eg_domains");
                        if( mysqli_num_rows( $get_domain ) > 0 ) {
                            while( $get_result = mysqli_fetch_array( $get_domain, MYSQLI_ASSOC ) ) {
                                $domain_name = $get_result['domain_name'];
                                $company = $get_result['company_name'];
                                $domain_id = $get_result['domain_id'];
                                echo "<option value='$domain_id'>$domain_name ($company)</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Create Design</button>
                <div class="mt-3">
                    <div class="result"></div>
                    <input type="hidden" name="form" value="create_design">
                </div>
            </form>
        </div>
    </div>
</div>
<?php require_once 'footer.php'; ?>