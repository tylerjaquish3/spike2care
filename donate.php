
<?php
$currentPage = '';
include('header.php');
?>

    <section class="title">
        <div class="container">
            <div class="row-fluid">
                <div class="span6">
                    <h1>Donate</h1>
                </div>
            </div>
        </div>
    </section>  

    <section id="about-us" class="container main">
        
        <h1 class="center">Secure Donation Form</h1>
        <p>
            All donations are tax deductible, and the tax ID for Spike2Care is 47-4545145. Spike2Care thanks you for your donation!
        </p>

        <hr>

        <div class="row">
            <div class="col-xs-12">
                <form name="assistance_application" id="assistance_application" action="#" method="POST" enctype="multipart/form-data">

                    <div class="row">
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <label for="full_name">Name</label>
                                <input type="text" class="form-control" required name="full_name" placeholder="Full Name">
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <label for="phone">Phone #</label>
                                <input type="text" class="form-control" required name="phone" placeholder="xxx-xxx-xxxx">
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" required name="email" aria-describedby="emailHelp" placeholder="Enter email">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-3">
                            <div class="form-group">
                                <label for="nominee_address">Mailing Address</label>
                                <input type="text" class="form-control" name="nominee_address" placeholder="Full Name">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-3">
                            <div class="form-group">
                                <label for="nominee_city">City</label>
                                <input type="text" class="form-control" name="nominee_city" placeholder="Full Name">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-3">
                            <div class="form-group">
                                <label for="nominee_state">State</label>
                                <select class="form-control" name="nominee_state">
                                    <?php loadStates(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-3">
                            <div class="form-group">
                                <label for="nominee_zip">Zip</label>
                                <input type="text" class="form-control" name="nominee_zip" placeholder="Full Name">
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-xs-12">
                            <button type="submit" name="assistance-application" class="btn btn-primary">Submit</button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </section>  

<?php
include('footer.php');
?>

</body>
</html>
