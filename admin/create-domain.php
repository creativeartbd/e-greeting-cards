<?php require_once 'header.php'; ?>
<div class="container">
    <div class="row">
        <div class="col-sm-12 mt-5">
            <h2>Add a new domain</h2>
        </div>
        <div class="col-md-12">
            <form id="form">
                <div class="mb-3">
                    <label for="domain">Enter the domain name</label>
                    <input type="text" id="domain" name="domain" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="company">Enter the company name</label>
                    <input type="text" id="company" name="company" class="form-control">
                </div>
                <button type="submit" class="btn btn-success">Create Domain </button>
                <div class="mt-3">
                    <div class="result"></div>
                    <input type="hidden" name="form" value="create_domain">
                </div>
            </form>
        </div>
    </div>
</div>
<?php require_once 'footer.php'; ?>