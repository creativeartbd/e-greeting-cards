<?php require_once 'header.php'; ?>
<div class="container">
    <div class="row">
        <div class="col-sm-12 mt-5">
            <h2>All Fonts</h2>
        </div>
        <div class="col-md-12">
            <div class="result"></div>
            <table class="table table-bordered">
                <tr>
                    <th>S.l</th>
                    <th>Font Title</th>
                    <th>Font File</th>
                    <th>Action</th>
                </tr>
                <?php 
                $get_fonts = mysqli_query( $mysqli, "SELECT * FROM eg_fonts");
                if( mysqli_num_rows( $get_fonts) > 0 ) {
                    $count = 1;
                    while ( $get_result = mysqli_fetch_array( $get_fonts, MYSQLI_ASSOC ) ) {
                        $font_id = $get_result['font_id'];
                        $font_title = $get_result['font_title'];
                        $font_name = $get_result['font_name'];
                        echo "<tr>";
                            echo "<td>$font_id</td>";
                            echo "<td>$font_title</td>";
                            echo "<td>$font_name</td>";
                            echo "<td><a class='btn btn-success btn-sm' href='edit-font.php?font_id=$font_id'>Edit</a> <a class='btn btn-danger btn-sm delete-btn' data-delete-id='$font_id' data-form='delete_font'>Delete</a> </td>";
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