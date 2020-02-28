<?php
session_start();

include('includes/header.php');

if (!isset($_SESSION["user_id"])) {
    header('location:'.URL);
}

$message = '';

if (isset($_GET) && !empty($_GET)) {
    $eventId = $_GET['eventId'];  

    $result = mysqli_query($conn,"SELECT * FROM events WHERE id = $eventId");
    while($event = mysqli_fetch_array($result)) 
    {
        $eventName = $event['title'];
    }

    if (isset($_GET['success']) && $_GET['success'] == 'true') {
        $message = "Team spot has been reserved and captain email was sent.";
    } elseif ($_GET['success'] == 'false') {
        $message = "There was a problem adding the captain. Please contact admin.";
    }
}
?>

<!-- page content -->
<div class="right_col" role="main">
                    
    <div class="page-title">
        <div class="title_left">
            <h1>Add Team</h1>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-xs-12">
            <div class="x_panel table_panel">
                <div class="x_content">
                    <div class="col-xs-12">
                        <p>Enter captain name and it will search for that user in the database to find a valid email, otherwise add a new email.</p>

                        <form id="add-captain-form" action="includes/handleForm.php" method="POST" enctype="multipart/form-data">

                            <input type="hidden" name="action" value="add-team">
                            <input type="hidden" name="event-name" value="<?php echo $eventName; ?>">
                            <input type="hidden" name="event-id" value="<?php echo $eventId; ?>">
                            <div class="row">
                                <div class="col-xs-12 col-md-3">
                                    <label for="name">Captain</label>
                                </div>
                                <div class="col-xs-12 col-md-3">
                                    <label for="division">Division</label>
                                </div>
                                <div class="col-xs-12 col-md-6">
                                    <label for="email">Email</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 col-md-3">
                                    <input type="input" class="form-control" name="name" placeholder="Name" id="name1" required>
                                </div>
                                <div class="col-xs-12 col-md-3">
                                    <select id="division" class="form-control" name="division">
                                        <?php 
                                        $divisions = mysqli_query($conn,"SELECT divisions.id, divisions.division_label, event_divisions.max_teams FROM divisions JOIN event_divisions ON event_divisions.division_id = divisions.id JOIN events ON events.id = event_divisions.event_id WHERE events.id = ".$eventId);
                                        while($division = mysqli_fetch_array($divisions)) 
                                        {
                                            $sql2 = mysqli_query($conn,"SELECT * FROM teams WHERE is_active = 1 AND event_id = ".$eventId." AND division_id = ".$division['id']);
                                            $teamCount = mysqli_num_rows($sql2);

                                            if ($teamCount >= $division['max_teams']) {
                                                echo '<option disabled value="'.$division['id'].'">'.$division['division_label'].' is FULL</option>';
                                            } else {
                                                echo '<option value="'.$division['id'].'">'.$division['division_label'].'</option>';
                                            }
                                
                                        } ?>
                                    </select>
                                </div>
                                <input type="hidden" id="selectedDivision" name="selected-division">
                                <div class="col-xs-12 col-md-3">
                                    <select id="email1" class="form-control" name="email1">
                                        <option disabled>Add name first</option>
                                    </select>
                                </div>
                                <input type="hidden" id="selectedEmail" name="selected-email">
                                <div class="col-xs-12 col-md-3">
                                    <input type="input" class="form-control" name="new-email" placeholder="New Email">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 center">
                                    <br /><br />
                                    <button name="add-team" class="btn btn-info" id="save">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="x_panel table_panel">
                <div class="x_content">
                    <div class="col-xs-12">
                        <p>Clean up emails for existing players</p>

                        <form id="remove-email-form" action="includes/handleForm.php" method="POST" enctype="multipart/form-data">

                            <input type="hidden" name="action" value="remove-email">
                            
                            <div class="row">
                                <div class="col-xs-12">
                                    <input type="input" class="form-control" name="name" placeholder="Player Name" id="player-lookup" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12" id="found-emails">
                                    
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add-team-confirm-modal" role="dialog" aria-hidden="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            </div>
            <div class="modal-body">

                This will reserve a spot for this captain and send a registration email. Are you sure the email is accurate?
                <div class="modal-buttons">
                    <button type="button" class="btn btn-warning" class="close" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-success" id="add-team-btn">Yes</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('includes/footer.php');
?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<script type="text/javascript">

    var message = "<?php echo $message;?>";
    if (message != "") {
        addAlertToPage('success', 'Success', message, 0);
    }
    
    var options = [];
    $('#email1').select2({
        data: options
    });
    $('#name1').focusout(function () {

        $.ajax({
            url: 'includes/handleForm.php',
            type: "GET",
            data: {
                action: 'findEmail',
                name: $('#name1').val()
            },
            dataType: 'json',
            complete: function (response) {
                options = JSON.parse(response.responseText);

                if ($('#email1').hasClass("select2-hidden-accessible")) {
                    $('#email1').select2('destroy').empty().select2({data: options});
                }
            }
        });
    });
                
    $('#save').click(function (e) {
        e.preventDefault();
        if ($('#division').val() == "" || $('#division').val() == null) {
            addAlertToPage('Error', 'error', "You must select a division.");
        } else {
            $('#add-team-confirm-modal').modal('show');
        }
    });  

    $('#add-team-btn').click(function (e) {
        e.preventDefault();
        $('#add-team-confirm-modal').modal('hide');
        $('#selectedEmail').val($('#email1 :selected').text());
        $('#selectedDivision').val($('#division').val());

        $('#add-captain-form').submit();
    });

    $('#player-lookup').focusout(function () {
        $.ajax({
            url: 'includes/handleForm.php',
            type: "GET",
            data: {
                action: 'findEmail',
                name: $('#player-lookup').val()
            },
            dataType: 'json',
            complete: function (response) {
                var emails = JSON.parse(response.responseText);
                $('#found-emails').html('');

                $.each(emails, function (key, value) {
                    var removeFunction = "remove('"+value.text+"')";
                    var stuff = value.text + ' <a class="btn btn-warning" href="#" onclick='+removeFunction+'>Remove</a><br />';
                    $('#found-emails').append(stuff);
                });
            }
        });
    });

    function remove(email) 
    {
        $.ajax({
            url: 'includes/handleForm.php',
            type: "POST",
            data: {
                action: 'invalidate-email',
                email: email
            },
            dataType: 'json',
            complete: function (response) {
                location.reload();
            }
        });
    }

</script>