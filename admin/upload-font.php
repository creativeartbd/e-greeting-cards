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
            <h2>Upload a new Font file</h2>
        </div>
        <div class="col-md-7">
            <form id="form" enctype="multipart/form-data">
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label for="font_title" class="form-label">Font Title</label>
                            <input type="text" name="font_title" class="form-control" id="font_title">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label for="font_file" class="form-label">Upload font file</label>
                            <input type="file" name="font_file" class="form-control" id="font_file" onchange="getFileData(this);">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success ajax-btn">Upload Font</button>
                <div class="mt-3">
                    <div class="result"></div>
                    <input type="hidden" name="form" value="upload_font">
                </div>
            </form>
        </div>
    </div>
</div>
<?php require_once 'footer.php'; ?>