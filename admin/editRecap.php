<?php
session_start();

include('includes/header.php');

if (!isset($_SESSION["user_id"])) {
    header('location:'.URL);
}

if (isset($_GET)) {
	$eventId = $_GET['eventId'];
    $active = '';

	$event = mysqli_query($conn,"SELECT * FROM events WHERE id = ".$eventId);
    while($row = mysqli_fetch_array($event)) 
    {
    	$eventName = $row['title'];
	}

	$recapText = '';
	$recap = mysqli_query($conn,"SELECT * FROM recaps WHERE event_id = ".$eventId);
	if (mysqli_num_rows($recap) > 0) {
	    while($row = mysqli_fetch_array($recap)) 
	    {
	    	$recapText = $row['recap_text'];
            ($row['is_active'] == 1 ? $active = ' checked' : $active = '');
	    }
	}
}
?>

<!-- page content -->
<div class="right_col" role="main">
                    
    <div class="page-title">
        <div class="title_left">
            <h1>Edit Recap</h1>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Event: <?php echo $eventName; ?></h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form action="includes/handleForm.php" id="save-recap" method="POST" enctype="multipart/form-data">
                        
                        <div class="row">
                            <div class="col-xs-12">

                                <input type="hidden" name="save-recap" value="1">
                            	<input type="hidden" name="event-id" value="<?php echo $eventId; ?>">
                            	<input type="hidden" name="is-new" value="<?php echo $recapText == '' ? true : false; ?>">

                                <div class="form-group">
                                    <label for="recap_text">Recap Text</label>
                                    <textarea  class="form-control ckeditor recap-text" id="ckeditor" required name="recap-text"><?php echo $recapText; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="testimonial_text">Active on site?</label>
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" value="1" <?php echo $active; ?>>
                                </div>

                                <div class="form-group">
                                    <label for="image_path">Event Images</label>
                                    <br />
                                    <small id="fileHelp" class="form-text text-muted">Only jpg, gif, and png formats are acceptable.</small>
                                    <input type="file" class="form-control-file" name="image1" id="fileToUpload1"><br />
    								<input type="file" class="form-control-file" name="image2" id="fileToUpload2"><br />
    								<input type="file" class="form-control-file" name="image3" id="fileToUpload3"><br />
    								<input type="file" class="form-control-file" name="image4" id="fileToUpload4"><br />
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-xs-12 center">
                                <br /><br />
                                <button type="submit" class="btn btn-info">Save</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('includes/footer.php');
?>
    