<?php
session_start();

include('includes/header.php');

if (!isset($_SESSION["user_id"])) {
    header('location:'.URL);
}

$title = $checked = $registrationChecked = $eventDate = $checkinTime = $meetingTime = $playTime = $location = $price = $address = $city = $format = $fbLink = $additionalInfo = $description = $maxTeams = $eventId = $imagePath = $active = $registrationDeadline = '';
$isNew = true;
$specialEvent = false;

if (isset($_GET) && !empty($_GET)) {
    $isNew = false;
    $maxTeams = $teamPlayers = 0;
    $eventId = $_GET['eventId'];    

    $event = mysqli_query($conn,"SELECT * FROM events WHERE id = ".$eventId);
    if (mysqli_num_rows($event) > 0) {
        while($row = mysqli_fetch_array($event)) {
            $title = $row['title'];
            ($row['special_event'] == 1 ? $specialEvent = true : $specialEvent = false);
            ($row['registration_open'] == 1 ? $registrationOpen = true : $registrationOpen = false);
            $eventDate = $row['event_date'];
            $checkinTime = $row['checkin_time'];
            $meetingTime = $row['meeting_time'];
            $playTime = $row['play_time'];
            $location = $row['location'];
            $price = $row['price'];
            $address = $row['address'];
            $city = $row['city'];
            $format = $row['format'];
            $description = $row['description'];
            $maxTeams = $row['max_teams'];
            $teamPlayers = $row['team_players'];
            $imagePath = $row['image_path'];
            $fbLink = $row['fb_link'];
            $additionalInfo = $row['additional_info'];
            $registrationDeadline = $row['registration_deadline'];
            $active = $row['is_active'];
        }
    }

    $event = mysqli_query($conn,"SELECT * FROM event_divisions WHERE event_id = ".$eventId);
    if (mysqli_num_rows($event) > 0) {
        while($row = mysqli_fetch_array($event)) {
            $existingDivisions[] = $row['division_id'];
        }
    }

    if ($specialEvent) {
        $checked = 'checked';
    }

    if ($registrationOpen) {
        $registrationChecked = 'checked';
    }
}
?>

<!-- page content -->
<div class="right_col" role="main">
                    
    <div class="page-title">
        <div class="title_left">
            <h1>Events</h1>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Create Event</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="col-md-6 col-sm-12">

                        <form action="includes/handleForm.php" method="POST" enctype="multipart/form-data">
                            
                            <input type="hidden" name="event_id" value="<?php echo $eventId; ?>">
                            <input type="hidden" name="is-new" value="<?php echo ($title == '' ? 'true' : 'false'); ?>">

                            <div class="form-group">
                                <label for="title">Title <span class="required">*</span></label>
                                <input type="text" class="form-control" name="title" required placeholder="Title" value="<?php echo $title; ?>">
                            </div>

                            <div class="form-group">
                                <label for="special_event">Special Event?</label>
                                <input type="checkbox" name="special_event" id="specialEvent" <?php echo $checked; ?>>
                            </div>

                            <div class="form-group">
                                <label for="registration_open">Registration Open?</label>
                                <input type="checkbox" name="registration_open" id="registrationOpen" <?php echo $registrationChecked; ?>>
                            </div>

                            <div class="form-group">
                                <label for="event_date">Registration Deadline <span class="required">*</span></label>
                                <div class='input-group date' id='datetimepicker2'>
                                    <input type='text' name="registration_deadline" required class="form-control" data-placeholder="Pick a date" placeholder="Pick a date">
                                    <span class="input-group-addon">
                                        <span class="fa fa-calendar"></span>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="event_date">Date <span class="required">*</span></label>
                                <div class='input-group date' id='datetimepicker1'>
                                    <input type='text' name="event_date" required class="form-control" data-placeholder="Pick a date" placeholder="Pick a date">
                                    <span class="input-group-addon">
                                        <span class="fa fa-calendar"></span>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="price">Price per person <span class="required">*</span></label>
                                <input type="text" class="form-control" name="price" placeholder="Dollar Amount" required value="<?php echo $price; ?>">
                            </div>

                            <div class="regularEvent">
                                <div class="form-group">
                                    <label for="checkin_time">Check In Time</label>
                                    <input type="text" class="form-control" name="checkin_time" placeholder="Time" value="<?php echo $checkinTime; ?>">
                                </div>

                                <div class="form-group">
                                    <label for="meeting_time">Captain's Meeting</label>
                                    <input type="text" class="form-control" name="meeting_time" placeholder="Time" value="<?php echo $meetingTime; ?>">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="play_time">Start <span class="required">*</span></label>
                                <input type="text" class="form-control" required name="play_time" placeholder="Time" value="<?php echo $playTime; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="location">Location <span class="required">*</span></label>
                                <input type="text" class="form-control" name="location" required placeholder="Location" value="<?php echo $location; ?>">
                            </div>

                            <div class="form-group">
                                <label for="address">Address</label>
                                <input type="text" class="form-control" name="address" placeholder="Address" value="<?php echo $address; ?>">
                            </div>

                            <div class="form-group">
                                <label for="city">City</label>
                                <input type="text" class="form-control" name="city" placeholder="City" value="<?php echo $city; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="image_path">Event Image</label>
                                <input type="file" class="form-control-file" name="image_path" aria-describedby="fileHelp">
                                <small id="fileHelp" class="form-text text-muted">Only jpg, gif, and png formats are acceptable.</small>
                            </div>

                        </div>
                        <div class="col-md-6 col-sm-12">

                            <div class="regularEvent">

                                <div class="form-group">
                                    <label for="divisions[]">Divisions</label><br />
                                    <select id="selectDivisions" name="divisions[]" class="form-control" multiple="multiple">
                                    <?php
                                    $divisions = mysqli_query($conn,"SELECT * FROM divisions");
                                    if (mysqli_num_rows($divisions) > 0) {
                                        while($div = mysqli_fetch_array($divisions)) {
                                            if (in_array($div['id'], $existingDivisions)) { ?>
                                                <option selected="selected" value="<?php echo $div['id']; ?>"><?php echo $div['division_label']; ?></option>
                                            <?php } else { ?>
                                                <option value="<?php echo $div['id']; ?>"><?php echo $div['division_label']; ?></option>
                                            <?php }
                                        }
                                    } ?>

                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="team_players">Players per team</label>
                                    <select class="form-control" name="team_players">
                                        <option disabled>Select</option>
                                        <?php for ($x = 1; $x < 7; $x++) {
                                            if ($x == $teamPlayers) {
                                                echo '<option selected>'.$x.'</option>';
                                            } else {
                                                echo '<option>'.$x.'</option>';
                                            }
                                        } ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="price">Max. number of teams</label>
                                    <input type="text" class="form-control" name="max_teams" required placeholder="16, 20, etc" value="<?php echo $maxTeams; ?>">
                                </div>

                                <div class="form-group">
                                    <label for="format">Event Format</label>
                                    <textarea class="form-control" name="format" rows="3"><?php echo $format; ?></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="description">Event Description <span class="required">*</span></label>
                                <textarea class="form-control ckeditor" id="ckeditor" required name="description" rows="6"><?php echo $description; ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="fb_link">Facebook Link</label>
                                <input type="text" class="form-control" name="fb_link" placeholder="Link" value="<?php echo $fbLink; ?>">
                            </div>

                            <div class="form-group">
                                <label for="additional_info">Additional Info </label>
                                <textarea class="form-control ckeditor" id="ckeditor1" required name="additional_info" rows="6"><?php echo $additionalInfo; ?></textarea>
                            </div>

                            <?php if ($imagePath) { ?>
                            <img src="../images/events/<?php echo $imagePath; ?>" width="100%">
                            <?php } ?>

                            
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-xs-12 center">
                            <br /><br />
                            <?php if (!$isNew) { ?>
                                <button id="cancel-event" name="cancel-event" class="btn btn-warning">Cancel Event</button>
                            <?php } ?>
                            <button type="submit" name="save-event" class="btn btn-info">Save</button>
                        </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="modal fade" id="cancel-confirm" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Confirm Cancel</h4>
            </div>
            <div class="modal-body">
                This will refund every paid entrant. Are you sure this is what you want to do?
            </div>
            <div class="modal-buttons">
                <button type="button" class="btn btn-warning" class="close" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-success" id="cancel-event-btn">Yes</button>
            </div>
        </div>
    </div>
</div>

<?php
include('includes/footer.php');
?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<script>

    $('#selectDivisions').select2({
        placeholder: 'Select multiple'
    });

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

    var eventDate = "<?php echo $eventDate; ?>"; 
    var registrationDeadline = "<?php echo $registrationDeadline; ?>"; 
    var defaultEventDate = "";
    var defaultRegistrationDeadline = "";

    if (eventDate != '') {
        defaultEventDate = "<?php echo date('m/d/Y', strtotime($eventDate)); ?>";
    }
    if (registrationDeadline != '') {
        defaultRegistrationDeadline = "<?php echo date('m/d/Y', strtotime($registrationDeadline)); ?>";
    }

    $('#datetimepicker1').datetimepicker({
        format: 'MM/DD/YYYY',
        defaultDate: defaultEventDate
    });

    $('#datetimepicker2').datetimepicker({
        format: 'MM/DD/YYYY',
        defaultDate: defaultRegistrationDeadline
    });

    regularEvent = false;
    $('#specialEvent').click(function () {
        $('.regularEvent').toggle();

        if (regularEvent) {
            $('[name="max_teams"]').prop('required', true);
            regularEvent = false;
        } else {
            $('[name="max_teams"]').prop('required', false);
            regularEvent = true;
        }
    });

    var specialEvent = "<?php echo $specialEvent ?>";
    if (specialEvent) {
        $('.regularEvent').hide();
    }

    $('#cancel-event').click(function(e) {
        e.preventDefault();
        $('#cancel-confirm').modal('show');
    });

    $('#cancel-event-btn').click(function(e) {
        // ajax request to handleForm for cancel and refund
    });

</script>
    