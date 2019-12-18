<?php
$currentPage = '404';
include('header.php');
?>

    <section class="title">
        <div class="container">
            <div class="row-fluid">
                <div class="span6">
                    <h1>Well, this is embarrassing</h1>
                </div>
            </div>
        </div>
    </section>

    <!-- 404 error -->
    <section id="error" class="container">
        <h1>404, Page not found</h1>
        <p>The page you are looking for doesn't exist or another error occurred.</p>
        <p>Help us out and <a href="mailto:itResources@spike2care.org">send a note to IT</a> <br /><br />
        <a class="btn btn-success" href="index.php">GO BACK TO THE HOMEPAGE</a>
    </section>
    <!-- /404 error -->

<?php
include('footer.php');
?>

