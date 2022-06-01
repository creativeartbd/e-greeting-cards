<?php require_once 'header.php'; ?>
<div class="container">
    <div class="row">
        <div class="col-sm-12 mt-5">
            <h2>All Domain</h2>
        </div>
        <div class="col-md-12">
            <div class="result"></div>
            <table class="table table-bordered">
                <tr>
                    <th>S.l</th>
                    <th>Domain Name</th>
                    <th>Company Name</th>
                    <th>Action</th>
                </tr>
                <?php 
                $get_domain = mysqli_query( $mysqli, "SELECT * FROM eg_domains");
                if( mysqli_num_rows( $get_domain) > 0 ) {

                    $count = 1;
                    while ( $get_result = mysqli_fetch_array( $get_domain, MYSQLI_ASSOC ) ) {
                        $domain_id = $get_result['domain_id'];
                        $domain = $get_result['domain_name'];
                        $company = $get_result['company_name'];
                        echo "<tr>";
                            echo "<td>$count</td>";
                            echo "<td>$domain</td>";
                            echo "<td>$company</td>";
                            echo "<td><a class='btn btn-success btn-sm' href='edit-domain.php?domain_id=$domain_id'>Edit</a> <a class='btn btn-danger btn-sm delete-btn' data-delete-id='$domain_id' data-form='delete_domain'>Delete</a> </td>";
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