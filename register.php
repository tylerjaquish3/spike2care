<?php
$currentPage = 'Events';
include('header.php');

if (isset($_GET)) {
    $eventId = $_GET['id'];
} else {
    die('<script type="text/javascript">window.location.href="404.php";</script>');
}
$eventFull = $specialEvent = false;

$sql = $conn->prepare("SELECT * FROM events WHERE id = ?");
$sql->bind_param('i', $eventId);
$sql->execute();

$result = $sql->get_result();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        if ($row['special_event']) {
            $specialEvent = true;
        } else {
            $teams = mysqli_query($conn,"SELECT *, count(*) as num_teams FROM teams WHERE is_active = 1 AND event_id = ".$eventId." GROUP BY id");
            if (mysqli_num_rows($teams) > 0) {
                while($team = mysqli_fetch_array($teams)) {
                    if ($team['num_teams'] == $row['max_teams']) {
                        $eventFull = true;
                    }
                } 
            } 
        }
    
?>

        <section class="title">
            <div class="container">
                <div class="row-fluid">
                    <div class="span6">
                        <h1>Register</h1>
                    </div>
                </div>
            </div>
        </section>

        <section id="contact-page" class="container">
            <div class="row">

                <div class="col-xs-12">
                    <h2><?php echo $row['title']; ?></h2>
                    <h4><small><?php echo date('m.d.Y', strtotime($row['event_date'])); ?>, <?php echo $row['location']; ?></small></h4>
                    <div class="status alert alert-success" style="display: none"></div>
                </div>
            </div>

            <hr>

            <form id="registration-form" name="registration-form" method="post" action="includes/handleForm.php">  
                <input type="hidden" name="event_id" value="<?php echo $row['id']; ?>">
                <div class="row-fluid">   
                
                    <div class="col-xs-12 col-md-6">
                        
                        <div class="row">
                            
                            <?php if ($specialEvent) { ?>
                                <div class="desc">
                                    In order to reserve your spot in this special event, fill in the required fields below and submit your payment on the next step.   
                                </div>
                            <?php } else { ?>
                                <div class="desc">
                                    If you are registering a team, you must create a passcode and make a payment today. If you are jumping on a team already created, you will need to enter a passcode to register on that team. Team captains hold the passcode.
                                </div>
                            <?php } ?>
                        </div>
                        <div class="row">
                            <div class="col-xs-4">
                                <label>Full Name</label>
                            </div>
                            <div class="col-xs-8">
                                <input type="text" class="input-block-level" required name="full_name" id="full_name">
                                <span style="display:none;" class="full" id="invalidNameError"><br />Please enter a name.</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4">
                                <label>Email Address</label>
                            </div>
                            <div class="col-xs-8">
                                <input type="text" class="input-block-level" required name="email" id="email">
                                <span style="display:none;" class="full" id="invalidEmailError">Invalid email, please enter a valid email address.</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4">
                                <label>Phone Number</label>
                            </div>
                            <div class="col-xs-8">
                                <input type="text" class="input-block-level" required name="phone" id="phone">
                                <span style="display:none;" class="full" id="invalidPhoneError"><br />Invalid phone number, please enter 10 digits.</span>
                            </div>
                        </div>
                        <div class="row">
                            <?php if (!$specialEvent) { ?>
                                <label>Please choose one of the following: </label><br />
                                <input type="radio" name="type" required value="new" <?php if ($eventFull) { echo 'disabled'; } ?>> 
                                    <label>Registering new team as captain</label><?php if ($eventFull) { echo '<span class="full">Event full!</span>'; } ?><br />
                                <input type="radio" name="type" required value="freeAgent"> <label>Registering as free agent</label><br />
                                <input type="radio" name="type" required value="existing"> <label>Joining an existing team</label>
                                <br><label for="type" class="error" style="display: none;"></label>
                            <?php } ?>
                            
                        </div>   
                    </div>

                    <div class="col-xs-12 col-md-6">
                        <div id="new-team" style="display:none;">
                            <div class="row">
                                <div class="col-xs-4">
                                    <label>Division</label>
                                </div>
                                <div class="col-xs-8">
                                    <select class="input-block-level" required name="division">
                                        <option selected disabled value="0">Select</option>
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
                            </div>
                            <div class="row">
                                <div class="desc col-xs-12">If a team name is not entered, your name will be used as the team name. Also, please keep the name family-friendly.
                                </div>
                                <div class="col-xs-4">
                                    <label>Team Name</label>
                                </div>
                                <div class="col-xs-8">
                                    <input type="text" class="input-block-level" name="team_name" id="team_name">
                                </div>
                            </div>
                            <div class="row">
                                <div class="desc col-xs-12">
                                    Use a passcode to add players to your roster in the future, or share this with your teammates to join your roster when they register. This must be unique (can only be used once).
                                </div>
                                <div class="col-xs-4">
                                    <label>Passcode</label>
                                </div>
                                <div class="col-xs-8">
                                    <input type="text" class="input-block-level" required name="passcode" id="passcode">
                                    <br /><span style="display:none;" class="full" id="duplicatePasscodeError">Passcode in use, please choose a unique passcode.</span>
                                </div>
                            </div>
                            <div class="row"> 
                                <div class="desc col-xs-12">
                                    (Optional) It is best to only add the players you will be paying for. Other teammates can be added later using the passcode you set. (Note: Do not add yourself here.)
                                </div>
                                <?php 
                                $numPlayers = $row['team_players'];
                                for($i = 1; $i < $numPlayers + 4; $i++) {
                                ?>
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <label>Player <?php echo $i+1; ?> Full Name</label>
                                        </div>
                                        <div class="col-xs-8">
                                            <input type="text" class="input-block-level" name="players[]">
                                        </div>
                                    
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>

                        <div id="free-agent" style="display:none;">
                            <div class="row">
                                <div class="col-xs-4">
                                    <label>Division</label>
                                </div>
                                <div class="col-xs-8">
                                    <select class="input-block-level" required name="division">
                                        <option selected disabled value="0">Select</option>
                                        <?php 
                                        $divisions = mysqli_query($conn,"SELECT divisions.id, divisions.division_label FROM divisions JOIN event_divisions ON event_divisions.division_id = divisions.id JOIN events ON events.id = event_divisions.event_id WHERE events.id = ".$eventId);
                                        while($division = mysqli_fetch_array($divisions)) 
                                        {
                                        ?>
                                            <option value="<?php echo $division['id']; ?>">
                                                <?php echo $division['division_label']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="existing-team" style="display:none;">
                            <div class="row">
                                <div class="desc col-xs-12">
                                    In order to join an existing team, enter the passcode set by the team captain. (case sensitive)
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-4">
                                    <label>Passcode</label>
                                </div>
                                <div class="col-xs-8">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <input type="text" class="input-block-level" name="passcode_check" id="passcode-check">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-2">
                                            <a class="btn btn-primary btn-small" id="search-passcode">Search</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-10">
                                    <div class="desc" id="team-search-result"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="desc col-xs-12">
                                    <input type="checkbox" name="current-free-agent"> Check this box if you were on the free agent list for this event.
                                </div>
                            </div>
                            
                        </div>
                    </div>
                
                </div>

                <div class="row">
                    <div class="col-xs-12 text-center">
                        <br /><br />
                        <button type="submit" name="event-registration" class="btn btn-primary btn-large" id="submit-registration">Submit</button>
                    </div>
                </div>
            </form>
            <br /><br /><br />

        </section>

<?php
    }
}
include('footer.php');
?> 

<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

<script type="text/javascript">
    var eventId = <?php echo $eventId; ?>;

    $('input[type=radio][name=type]').change(function() {
        if (this.value == 'new') {
            $('#free-agent').hide();
            $('#existing-team').hide();
            $('#new-team').fadeIn('slow');
        } else if (this.value == 'freeAgent') {
            $('#new-team').hide();
            $('#existing-team').hide();
            $('#free-agent').fadeIn('slow');
        } else if (this.value == 'existing') {
            $('#new-team').hide();
            $('#free-agent').hide();
            $('#existing-team').fadeIn('slow');
        }
    });

    // Look for the team based on the passcode entered
    $('#search-passcode').click(function () {
        var passcode = $('#passcode-check').val();
        eventId = <?php echo $eventId; ?>;

        $.ajax({
            url: 'includes/handleForm.php',
            type: 'GET',
            dataType: 'json',
            data: {
                'passcode': passcode,
                'eventId': eventId
            },
            complete: function(data){
                response = $.parseJSON(data.responseText);
                $('#team-search-result').html(response.message);
            }
        });
    });

    $('#submit-registration').click(function (e) {
        // Hide error messages
        $('#invalidNameError').hide();
        $('#duplicatePasscodeError').hide();
        $('#invalidEmailError').hide();
        $('#invalidPhoneError').hide();
        e.preventDefault();
        var validPasscode = true;

        if ($('#team_name').val() == '') {
            $('#team_name').val($('#full_name').val());
        }

        var passcode = $('#passcode').val();
        if (!passcode) {
            passcode = $('#passcode-check').val();
        } else {
            // Check if passcode is already used
            $.ajax({
                url: 'includes/handleForm.php',
                type: 'GET',
                dataType: 'json',
                data: {
                    'passcode': passcode
                },
                complete: function(data){
                    response = $.parseJSON(data.responseText);
                    if (response.type == 'failure') {
                        $('#duplicatePasscodeError').show(); 
                        validPasscode = false;   
                    }
                }
            });
        }
        var registerType = $('input[name="type"]:checked').val();

        var form = $("#registration-form");
        form.validate();
        validPhone = isPhone($('#phone').val());
        validEmail = isEmail($('#email').val());
        var specialEvent = "<?php echo $specialEvent; ?>";

        if ($('#full_name').val() == "") {
            $('#invalidNameError').show();
        } else if (!validEmail) {
            $('#invalidEmailError').show();
        } else if (!validPhone) {
            $('#invalidPhoneError').show();
        } else {

            if (form.valid() && specialEvent) {
                $.ajax({
                    url: 'includes/handleForm.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        'eventId': eventId,
                        'paidBySpecial': $('#full_name').val(),
                        'phone': $('#phone').val(),
                        'email': $('#email').val()
                    }, 
                    complete: function(data) {
                        link = $.parseJSON(data.responseText);
                        window.location.replace(link);
                    }
                });
            } else {
                $.ajax({
                    url: 'includes/handleForm.php',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        'passcode': passcode,
                        'eventId': eventId
                    },
                    complete: function(data){
                        response = $.parseJSON(data.responseText);

                        if (response.type == 'success') {
                            validPasscode = false;
                        }

                        if (form.valid() && (registerType == 'existing' || validPasscode)) {
                            // if passcode not found, don't submit form
                            $.get('includes/handleForm.php', { passcode: passcode }).done(function(data) {
                   
                                if (registerType == 'existing' && (passcode == '' || data.trim() == 'No team found.')) {
                                    $('#team-search-result').html(data);
                                } else {

                                    $('<input />').attr('type', 'hidden')
                                      .attr('name', 'event-registration')
                                      .attr('value', '1')
                                      .appendTo('#registration-form');

                                    $('#registration-form').submit();
                                }
                            });
                        } else if (!validPasscode) {
                            $('#duplicatePasscodeError').show();
                        }
                    }
                });
            }
        }
        
    });

    function isEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }

    function isPhone(phone) {
        length = phone.replace(/[^0-9]/g,"").length;
        return length == 10;
    }

</script>

