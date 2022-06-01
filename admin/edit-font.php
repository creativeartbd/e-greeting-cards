<?php require_once 'header.php'; ?>
<script>
function getFileData(myFile) {
    var file = myFile.files[0];
    var filename = file.name;
    filename = filename.split(".");
    filename = filename[0];
    filename = filename.replace("/[^a-zA-Z ]/g", "");
    document.querySelector("#font_title").value = filename;
}
</script>
<div class="container">
    <div class="row">
        <div class="col-sm-12 mt-5">
            <h2>Edit Font</h2>
        </div>
        <?php 
        $font_id = (int) $_GET['font_id'];
        $get_font = mysqli_query( $mysqli, "SELECT * FROM eg_fonts WHERE font_id = '$font_id' ");

        if( mysqli_num_rows( $get_font) > 0 ) :
            $get_result = mysqli_fetch_array( $get_font, MYSQLI_ASSOC );
            $font_title = $get_result['font_title'];
            $font_name = $get_result['font_name'];
            $font_file = $get_result['font_file'];
        ?>
        <div class="col-md-7">
            <form id="form">
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label for="font_title" class="form-label">Font Title</label>
                            <input type="text" name="font_title" value="<?php echo $font_title; ?>" class="form-control" id="font_title">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label for="font_file" class="form-label">Upload font file (<a download="new" href="download.php?file=<?php echo $font_name; ?>">Download Old Font</a>)</label>
                            <input type="file" name="font_file" class="form-control" id="font_file">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Update Font</button>
                <div class="mt-3">
                    <div class="result"></div>
                    <input type="hidden" name="font_id" value="<?php echo $font_id; ?>">
                    <input type="hidden" name="form" value="update_font">
                </div>
            </form>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php require_once 'footer.php'; ?>