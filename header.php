<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Spike2Care | <?php echo $currentPage; ?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width">

    <meta property="og:title" content="Spike2Care" />
    <meta property="og:description" content="Doing what we love, helping those we love" />
    <meta property="og:url" content="https://spike2care.org" />
    <meta property="og:image" content="https://spike2care.org/images/basic-banner-blue-min.jpg" />

    <!-- Latest compiled and minified CSS -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://use.fontawesome.com/d09139796f.js"></script> -->
    <!-- <link rel="stylesheet" href="css/full_sparkle.css"> -->


    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="images/vb-favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="images/ico/apple-touch-icon-57-precomposed.png">

    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
</head>

<body>

    <?php include('admin/includes/functions.php'); ?>
    <?php include('admin/includes/datalogin.php'); ?>

    <!--Header-->
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                </button>
                <a id="logo" class="navbar-brand" href="index.php"></a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

                <ul class="nav navbar-nav navbar-right">
                <?PHP 
                    $active = ' class="active"';
                ?>
                    
                    <li <?PHP if($currentPage == 'Home'){echo $active;} ?>><a href="index.php">Home</a></li>
                    <li <?PHP if($currentPage == 'Events'){echo $active;} ?>><a href="events.php">Events</a></li>
                    <li <?PHP if($currentPage == 'About'){echo $active;} ?>><a href="about.php">About</a></li>
                    <li <?PHP if($currentPage == 'Assistance'){echo $active;} ?>><a href="application.php">Assistance</a></li>
                    <li <?PHP if($currentPage == 'Photos'){echo $active;} ?>><a href="photos.php">Photos</a></li>
                    <!-- <li <?PHP if($currentPage == 'Shop'){echo $active;} ?>><a href="shop.php">Shop</a></li> -->
                    <li <?PHP if($currentPage == 'Contact'){echo $active;} ?>><a href="contact.php">Contact</a></li>
                    <li class="login">
                        <a data-toggle="modal" href="#loginForm"><i class="fa fa-lock"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!--  Login form -->
    <div class="modal fade" id="loginForm" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Admin Login</h4>
                </div>
                <div class="modal-body">
                    <form class="form-inline" method="post" id="form-login">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <input type="text" name="myusername" placeholder="Email">
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <input type="password" name="mypassword" id="mypassword" placeholder="Password">
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 text-center">
                                <button type="submit" class="btn btn-primary">Sign in</button>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-xs-12">
                            <span id="error-message" style="display: none;">Incorrect email/password combination. Please try again.</span><br />
                            <a id="forgot-password">Forgot your password?</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--  Forgot Password form -->
    <div class="modal fade" id="emailForm" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Forgot Password</h4>
                    <p>Enter your email, and a link to change your password will be sent.</p>
                </div>
                <div class="modal-body">
                    <form class="form-inline" method="post" id="form-email">
                        <input type="hidden" name="form-email">
                        <div class="row">
                            <div class="col-xs-12 col-sm-8">
                                <input type="text" name="user_email" id="userEmail" required placeholder="Email">
                            </div>
                            <div class="col-xs-12 col-sm-4">
                                <button type="submit" class="btn btn-primary">Send Email</button>
                            </div>
                        </div>
                    </form>
                    <span id="error-message2" style="display: none;">Email does not exist. Please try again or contact an administrator.</span><br />
                    <span id="success-message" style="display: none;">Email was sent!</span><br />
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">

        $('#form-login').submit(function (e) {
            e.preventDefault();

            var formData = $('#form-login').serialize();
            $.ajax({
                url: 'admin/includes/checkLogin.php',
                type: "POST",
                data: formData,
                async: false,
                dataType: 'json',
                complete: function (response) {
                    data = $.parseJSON(response.responseText);
                    if (data.type == 'error') {
                        $('#mypassword').val('');
                        $('#error-message').show();
                    } else if (data.type == 'success') {
                        window.location.replace("admin/index.php");
                    }
                }
            })
        });

        $('#forgot-password').click(function (e) {
            $('#loginForm').modal('hide');
            $('#emailForm').modal('show');
        });

        $('#form-email').submit(function (e) {
            e.preventDefault();

            var formData = $('#form-email').serialize();
            $.ajax({
                url: 'admin/includes/handleForm.php',
                type: "POST",
                data: formData,
                async: false,
                dataType: 'json',
                complete: function (response) {
                    data = $.parseJSON(response.responseText);
                    if (data.type == 'error') {
                        $('#userEmail').val('');
                        $('#success-message').hide();
                        $('#error-message2').show();
                    } else if (data.type == 'success') {
                        $('#error-message2').hide();
                        $('#success-message').show();
                    }
                }
            })
        });

    </script>
