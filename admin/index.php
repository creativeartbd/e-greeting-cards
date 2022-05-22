<?php 
require_once 'helper/connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <title>Admin Panel</title>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-sm-4 mx-auto border pt-5 pb-2 mt-5">
            <h2>Admin Panel</h2>
            <p>Please enter your username and password to access the admin panel.</p>
            <form id="form">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" id="username" aria-describedby="username">
                    <div id="username" class="form-text">Enter your username</div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="password"  aria-describedby="password">
                    <div id="password" class="form-text">Enter your password</div>
                </div>
                <button type="submit" class="btn btn-success">Login</button>
                <div class="mt-3">
                    <div class="result"></div>
                    <input type="hidden" name="form" value="login">
                    <label>Forgot Password? Click <a href="forgot-password.php">here</a></label>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>