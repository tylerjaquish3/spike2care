<?php
session_start();

include('includes/header.php');

if (!isset($_SESSION["user_id"])) {
    header('location:'.URL);
}

if (isset($_GET)) {
    $appId = $_GET['appId'];

    $applications = mysqli_query($conn,"SELECT * FROM applications JOIN people on applications.nominator_id = people.id WHERE applications.id = $appId");
    while($app = mysqli_fetch_array($applications)) 
    { 
        $nominator = $app['full_name'];
        $nominator_phone = $app['phone'];
        $nominator_email = $app['email'];
    }

    $applications = mysqli_query($conn,"SELECT * FROM applications JOIN people on applications.nominee_id = people.id WHERE applications.id = $appId");
    while($app = mysqli_fetch_array($applications)) 
    { 
        $nominee = $app['full_name'];
        $nominee_phone = $app['phone'];
        $nominee_email = $app['email'];
        $nominee_address = $app['address'];
        $nominee_city = $app['city'];
        $nominee_state = $app['state'];
        $nominee_zip = $app['zip'];

        $volleyball_association = $app['volleyball_association'];
        $circumstances = $app['circumstances'];
        $amount_requested = $app['amount_requested'];
        $requested_date = $app['requested_date'];
        $attachment_path = $app['attachment_path'];
        $signature_path = $app['signature_path'];
        $signed_date = $app['signed_date'];
        $submitted_at = $app['submitted_at'];
        $status = $app['status'];
    }
}
?>

<!-- page content -->
<div class="right_col" role="main">
                    
    <div class="page-title">
        <div class="title_left">
            <h1>Assistance Application</h1>
        </div>
    </div>

    <div class="clearfix"></div>
        <div class="row">
            <div class="col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <div class="inline">
                            Status: 
                            <select name="status" class="form-control" id="statusSelect">
                                <option value="submitted" <?php echo ($status == 'submitted' ? 'selected' : ''); ?>>Submitted</option>
                                <option value="reviewed" <?php echo ($status == 'reviewed' ? 'selected' : ''); ?>>Reviewed</option>
                                <option value="granted" <?php echo ($status == 'granted' ? 'selected' : ''); ?>>Granted</option>
                                <option value="denied" <?php echo ($status == 'denied' ? 'selected' : ''); ?>>Denied</option>
                            </select>
                        </div>
                            
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                            <li>
                                <div class="pull-right">
                                    <a href="exportApplication.php?id=<?php echo $appId; ?>" class="btn btn-info">Export</a>
                                </div>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    
                    <div class="x_content">
                        
                        <div class="row">
                            <div class="col-xs-12 col-md-6">
                   
                                <label class="control-label col-xs-6">Nominee</label>
                                <div class="no-input col-xs-6">
                                    <p><?php echo $nominee; ?></p>
                                </div>
                            
                                <label class="control-label col-xs-6">Phone</label>
                                <div class="no-input col-xs-6">
                                    <p><?php echo $nominee_phone; ?></p>
                                </div>
                            
                                <label class="control-label col-xs-6">Email</label>
                                <div class="no-input col-xs-6">
                                    <p><?php echo $nominee_email; ?></p>
                                </div>
                            
                                <label class="control-label col-xs-6">Address</label>
                                <div class="no-input col-xs-6">
                                    <p><?php echo $nominee_address; ?></p>
                                </div>
                            
                                <label class="control-label col-xs-6">City</label>
                                <div class="no-input col-xs-6">
                                    <p><?php echo $nominee_city; ?></p>
                                </div>
                            
                                <label class="control-label col-xs-6">State</label>
                                <div class="no-input col-xs-6">
                                    <p><?php echo $nominee_state; ?></p>
                                </div>
                            
                                <label class="control-label col-xs-6">Zip</label>
                                <div class="no-input col-xs-6">
                                    <p><?php echo $nominee_zip; ?></p>
                                </div>
                                
                            </div>
                            <div class="col-xs-12 col-md-6">
                   
                                <label class="control-label col-xs-6">Nominator</label>
                                <div class="no-input col-xs-6">
                                    <p><?php echo $nominator; ?></p>
                                </div>
                            
                                <label class="control-label col-xs-6">Phone</label>
                                <div class="no-input col-xs-6">
                                    <p><?php echo $nominator_phone; ?></p>
                                </div>
                            
                                <label class="control-label col-xs-6">Email</label>
                                <div class="no-input col-xs-6">
                                    <p><?php echo $nominator_email; ?></p>
                                </div>
                            </div>
                        </div>

                        <hr />

                        <div class="row">
                            <div class="col-xs-12">
                                
                                <label class="control-label col-xs-12">How the nominee is associated with the volleyball community</label>
                                <div class="no-input col-xs-12">
                                    <p><?php echo $volleyball_association; ?></p>
                                </div>

                                <label class="control-label col-xs-12">The nominee's circumstances and specific financial need</label>
                                <div class="no-input col-xs-12">
                                    <p><?php echo $circumstances; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <label class="control-label col-xs-6">Amount Requested</label>
                                <div class="no-input col-xs-6">
                                    <p>$ <?php echo $amount_requested; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <label class="control-label col-xs-6">Requested date to have funds</label>
                                <div class="no-input col-xs-6">
                                    <p><?php echo $requested_date; ?></p>
                                </div>
                            </div>
                        </div>

                        <hr />

                        <div class="row">
                            <div class="col-xs-12">
                                <label class="control-label col-xs-6">Attachment</label>
                                <div class="no-input col-xs-6">
                                    <p><a href="../attachments/<?php echo $attachment_path; ?>"><?php echo $attachment_path; ?></a></p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <label class="control-label col-xs-6">Signature</label>
                                <div class="no-input col-xs-6">
                                    <p><img src="<?php echo '../'.$signature_path; ?>"></p>
                                </div>
                            </div>
                        </div>  
                        <div class="row">
                            <div class="col-xs-12">
                                <label class="control-label col-xs-6">Submitted</label>
                                <div class="no-input col-xs-6">
                                    <p><?php echo $submitted_at; ?></p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('includes/footer.php');
?>
    
<script>

    $('#statusSelect').change(function () {

        var status = $('#statusSelect option:selected').val();
        var appId = "<?php echo $appId; ?>";

        $.ajax({
            url: 'includes/handleForm.php',
            type: 'post',
            cache: false,
            data: {
                action: 'updateStatus',
                appId: appId, 
                status: status
            },
            success: function () {
                addAlertToPage('success', 'Success', 'Status was successfully changed.', 10);
            }
            // error: function () {
            //     addAlertToPage('error', 'Error', 'Error canceling item.', 10);
            // }
        });
    });

</script>