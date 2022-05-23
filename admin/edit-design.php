<?php require_once 'header.php'; ?>
<div class="container">
    <div class="row">
        <div class="col-sm-12 mt-5">
            <h2>Edit Design</h2>
        </div>
        <?php 
        $design_id = (int) $_GET['design_id'];
        $get_design = mysqli_query( $mysqli, "SELECT ds.*, do.domain_name FROM eg_design AS ds LEFT JOIN eg_domains AS do ON ds.domain_id = do.domain_id WHERE ds.design_id = '$design_id' ");
        if( mysqli_num_rows( $get_design) > 0 ) :
            $get_result = mysqli_fetch_array( $get_design, MYSQLI_ASSOC );
            $design_id = $get_result['design_id'];
            $design_title = $get_result['design_title'];
            $domain_name = $get_result['domain_name'];
            $ex_domain_id = $get_result['domain_id'];
            $design_img = $get_result['design_img'];
            $design_created = $get_result['design_created'];
        ?>
        <div class="col-md-8">
            <form id="form">
                <div class="mb-3">
                    <label for="design_title" class="form-label">Ttile of the design</label>
                    <input type="text" name="design" value="<?php echo $design_title; ?>" class="form-control" id="design_title">
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
                                $selected = '';
                                if( $ex_domain_id == $domain_id ) {
                                    $selected = 'selected';
                                }
                                echo "<option $selected value='$domain_id'>$domain_name ($company)</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update Design</button>
                <div class="mt-3">
                    <div class="result"></div>
                    <input type="hidden" name="design_id" value="<?php echo $design_id; ?>">
                    <input type="hidden" name="form" value="update_design">
                </div>
            </form>
        </div>
        <div class="col-md-4">
            <label>Design File</label>
            <img class="img-fluid" src="assets/design/<?php echo $design_img; ?>" alt="">
        </div>
        <?php endif; ?>
    </div>
</div>
<?php require_once 'footer.php'; ?>