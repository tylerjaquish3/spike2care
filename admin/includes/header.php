<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Spike2Care Admin</title>

    <link rel="icon" href="images/vb-favicon.ico">
    <link rel="apple-touch-icon-precomposed" href="/images/favicon-152.png">

    <link href="css/full_sparkle.css" rel="stylesheet">
    <link href="css/sparkle.css" rel="stylesheet">
    <link href="css/app.css?<?php echo date('h:i:s'); ?>" rel="stylesheet">
    <link href="css/responsive.css?<?php echo date('h:i:s'); ?>" rel="stylesheet">
    <!-- <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script> -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    
</head>

<body class="nav-md">
<div class="container body">
    <div class="main_container">

        <?php
        include('datalogin.php');
        include('functions.php');
        include('sidebar.php');

        if (!isset($_SESSION["user_id"])) {
            header('location:'.URL);
        }
        ?>

        <div class="top_nav">
            <div class="nav_menu">
                <nav>
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="">
                            <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <?php echo getUser($_SESSION["user_id"]); ?>
                                <span class=" fa fa-angle-down"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-usermenu pull-right">
                                <li><a href="includes/logout.php">
                                        <i class="fa fa-sign-out pull-right"></i> Log Out
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
