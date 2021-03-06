<?php 
require_once 'helper/connection.php';
require_once 'helper/functions.php';
check_session();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Admin Panel</title>
</head>
<body>

<div class="container-fluid">
    <nav class="navbar navbar-expand-lg bg-light pt-3 pb-3">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="dashboard.php">eGreeting Cards</a>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="../index.php">Send Email</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="design" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Design
                        </a>
                            <ul class="dropdown-menu" aria-labelledby="design">
                            <li><a class="dropdown-item" href="all-design.php">All Design</a></li>
                            <li><a class="dropdown-item" href="upload-design.php">Upload Design</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="domain" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Domain
                        </a>
                            <ul class="dropdown-menu" aria-labelledby="domain">
                            <li><a class="dropdown-item" href="all-domain.php">All Domain</a></li>
                            <li><a class="dropdown-item" href="create-domain.php">Create domain</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="font" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Fonts
                        </a>
                            <ul class="dropdown-menu" aria-labelledby="font">
                            <li><a class="dropdown-item" href="all-fonts.php">All Font</a></li>
                            <li><a class="dropdown-item" href="upload-font.php">Upload Font</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Email Settings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-gradient-danger btn-sm text-white px-3" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>