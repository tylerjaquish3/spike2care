<?php

session_start();

include('includes/header.php');

if (!isset($_SESSION["user_id"])) {
    header('location:'.URL);
}

?>

<!-- page content -->
<div class="right_col" role="main">
                    
    <div class="page-title">
        <div class="title_left">
            <h1>Meeting Minutes</h1>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Add Meeting Minutes</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="col-md-6 col-sm-12">

                        <form action="includes/handleForm.php" method="POST" enctype="multipart/form-data">

                            <div class="form-group">
                                <label for="event_date">Meeting Date <span class="required">*</span></label>
                                <div class='input-group date' id='datetimepicker1'>
                                    <input type='text' name="event_date" class="form-control" data-placeholder="Pick a date" placeholder="Pick a date" />
                                    <span class="input-group-addon">
                                        <span class="fa fa-calendar"></span>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="image_path">Minutes</label>
                                <input type="file" class="form-control-file" name="minutes" aria-describedby="fileHelp">
                                <small id="fileHelp" class="form-text text-muted">Only pdf format is acceptable.</small>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-xs-12 center">
                            <br /><br />
                            <button type="submit" name="save-minutes" class="btn btn-info">Save</button>
                        </div>

                        </form>

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

    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!
    var yyyy = today.getFullYear();

    if (dd<10) {
        dd='0'+dd;
    } 

    if (mm<10) {
        mm='0'+mm;
    } 

    today = mm+'/'+dd+'/'+yyyy;

    $('#datetimepicker1').datetimepicker({
        format: 'MM/DD/YYYY',
        defaultDate: today
    });


</script>
    