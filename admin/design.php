<?php require_once 'header.php'; ?>
<div class="container">
    <div class="row">
        <div class="col-sm-12 mt-5">
            <h2>Upload Design</h2>
        </div>
        <div class="col-md-12">
            <form id="form">
                <div class="mb-3">
                    <label for="file" class="form-label">Upload a new design</label>
                    <input type="file" name="file" class="form-control" id="file">
                </div>
                <div class="mb-3">
                    <label for="">Choose a domain</label>
                    <select name="domain" id="" class="form-control">
                        <option value="">--Choose--</option>
                        <option value="@alfozan.com">@alfozan.com</option>
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