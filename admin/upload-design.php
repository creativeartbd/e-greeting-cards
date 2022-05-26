<?php require_once 'header.php'; ?>
<div class="container">
    <div class="row">
        <div class="col-sm-12 mt-5">
            <h2>Upload Design</h2>
        </div>
        <div class="col-md-7">
            <form id="form" enctype="multipart/form-data">
                <div class="alert alert-warning">Note: If you leave the X and Y axios then the text will be on the center position.</div>
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label for="design_title" class="form-label">Ttile of the design</label>
                            <input type="text" name="design" class="form-control" id="design_title">
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-3">
                            <label for="design" class="form-label">Upload a new design</label>
                            <input type="file" name="design" class="form-control" id="design">
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="row">
                        <div class="col">
                            <label for="fontsize" class="form-label">Font size</label>
                            <input type="number" name="fontsize" class="form-control" id="fontsize">
                        </div>
                        <div class="col">
                            <label for="position_x" class="form-label">Position X</label>
                            <input type="number" name="design_x" class="form-control" id="position_x">
                        </div>
                        <div class="col">
                            <label for="position_y" class="form-label">Position Y</label>
                            <input type="number" name="design_y" class="form-control" id="position_y">
                        </div>
                        <div class="col">
                            <label for="color" class="form-label">Font Color</label>
                            <input type="color" name="color" class="form-control" id="color">
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="domain">Choose a domain</label>
                    <select name="domain" id="domain" class="form-control" data-form="design_output">
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
                <button type="button" class="btn btn-primary ajax-btn output-desing" data-form="output_design">Output Design</button>
                <button type="submit" class="btn btn-success ajax-btn">Save Design</button>
                <div class="mt-3">
                    <div class="result"></div>
                    <input type="hidden" name="form" value="create_design">
                </div>
            </form>
        </div>
        <div class="col-md-5">
            <h4>Output Design</h4>
            <div class="output-design"></div>
        </div>
    </div>
</div>
<?php require_once 'footer.php'; ?>