<?php require_once 'header.php'; ?>
<div class="container">
    <div class="row">
        <div class="col-sm-12 mt-5">
            <h2>Send Email</h2>
        </div>
        <div class="col-md-12">
            <form id="form">
                <div class="mb-3">
                    <label for="name" class="form-label">Please write your full name</label>
                    <input type="text" name="name" class="form-control" id="name">
                </div>
                <div class="mb-3">
                    <div class="row">
                        <div class="col">
                            <label for="name" class="form-label">Enter your email address associated with the company</label>
                            <input type="text" name="email" class="form-control" id="email">
                        </div>
                        <div class="col">
                            <label for="">Choose a domain</label>
                            <select name="domain" id="" class="form-control">
                                <option value="">--Choose--</option>
                                <option value="@alfozan.com">@alfozan.com</option>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Next</button>
                <div class="mt-3">
                    <div class="result"></div>
                    <input type="hidden" name="form" value="send_email">
                </div>
            </form>
        </div>
    </div>
</div>
<?php require_once 'footer.php'; ?>