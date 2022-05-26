<?php require_once 'header.php'; ?>
<div class="container">
    <div class="row">
        <div class="col-sm-12 mt-5">
            <h2>All Design</h2>
        </div>
        <div class="col-md-12">
            <div class="result"></div>
            <table class="table table-bordered">
                <tr>
                    <th>S.l</th>
                    <th>Design Title</th>
                    <th>Domain Name</th>
                    <th>Design</th>
                    <th>Uploaded On</th>
                    <th>Action</th>
                </tr>
                <?php 
                $get_design = mysqli_query( $mysqli, "SELECT ed.*, edo.domain_name FROM eg_design AS ed LEFT JOIN eg_domains AS edo ON ed.domain_id = edo.domain_id ");
                if( mysqli_num_rows( $get_design) > 0 ) {

                    $count = 1;
                    while ( $get_result = mysqli_fetch_array( $get_design, MYSQLI_ASSOC ) ) {
                        
                        $design_id = $get_result['design_id'];
                        $design_title = $get_result['design_title'];
                        $domain_name = $get_result['domain_name'];
                        $desing_img = $get_result['design_img'];
                        $desing_created = $get_result['design_created'];

                        echo "<tr>";
                            echo "<td>$count</td>";
                            echo "<td>$design_title</td>";
                            echo "<td>$domain_name</td>";
                            echo "<td><img src='assets/design/$desing_img' width='100' class='img-fluid'/></td>";
                            echo "<td>$desing_created</td>";
                            echo "<td><a class='btn btn-success btn-sm' href='edit-design.php?design_id=$design_id'>Edit</a> <a class='btn btn-danger btn-sm delete-btn' data-delete-id='$design_id' data-form='delete_design'>Delete</a> </td>";
                        echo "</tr>";
                        $count++;
                    }
                }
                ?>
            </table>
        </div>
    </div>
</div>
<?php require_once 'footer.php'; ?>