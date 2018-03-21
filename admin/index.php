<?php
session_start();

include('includes/header.php');

if (!isset($_SESSION["user_id"])) {
    header('location:'.URL);
}

$newMessages = getDashboardActivity('messages');
$newApplications = getDashboardActivity('applications');
$recaps = getDashboardActivity('events');

?>

<!-- page content -->
<div class="right_col" role="main">
                    
    <div class="page-title">
        <div class="title_left">
            <h1>Dashboard</h1>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Last 14 Days</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="col-md-12 col-sm-12">
                        <p>New messages from Spike2Care.org: <?php echo $newMessages; ?></p>
                        <p>New applications submitted: <?php echo $newApplications; ?></p>
                        <p>Past events that need a recap entered: <?php echo $recaps; ?></p>
                        <p>Outstanding orders for merchandise: 0</p>
                        <p><a href="includes/handleForm.php?export-emails" class="btn btn-info">Export Email List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Web Traffic Stats coming soon!</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="col-md-12 col-sm-12">
                        <p>This section will contain charts/graphs, statistics, and helpful analytic data for traffic on Spike2Care.org</p> 
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>

<?php
include('includes/footer.php');
?>

    