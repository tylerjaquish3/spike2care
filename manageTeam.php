
<?php
$currentPage = 'Events';
include('header.php');

if (isset($_GET)) {
    $eventId = $_GET['id'];
} else {
    die('<script type="text/javascript">window.location.href="404.php";</script>');
}
$eventFull = $specialEvent = false;

$result = mysqli_query($conn,"SELECT * FROM events WHERE id = ".$eventId);
while($row = mysqli_fetch_array($result)) 
{
    
    $teams = mysqli_query($conn,"SELECT *, count(*) as num_teams FROM teams WHERE is_active = 1 AND event_id = ".$eventId);
    if (mysqli_num_rows($teams) > 0) {
        while($team = mysqli_fetch_array($teams)) {
            if ($team['num_teams'] == $row['max_teams']) {
                $eventFull = true;
            }
        } 
    } 
    
?>

    <section class="title">
        <div class="container">
            <div class="row-fluid">
                <div class="span6">
                    <h1>Manage Team</h1>
                </div>
            </div>
        </div>
    </section>

    <section id="contact-page" class="container">
        
        <form id="manage-team-form" name="manage-team-form" method="post" action="includes/handleForm.php">  
            <input type="hidden" name="event_id" value="<?php echo $row['id']; ?>">
            
            <div class="row">
                <div class="desc col-xs-12">
                    First, enter the captain's email and passcode.
                </div>
                <div class="row">
                    <div class="col-xs-4">
                        <label>Email Address</label>
                    </div>
                    <div class="col-xs-8">
                        <input type="text" class="input-block-level" required name="email" id="email-check">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-xs-4">
                        <label>Passcode</label>
                    </div>
                    <div class="col-xs-8">
                        <div class="row">
                            <div class="col-xs-12">
                                <input type="text" class="input-block-level" required name="passcode_check" id="passcode-check">
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
            </div>

            <div id="teamResults" style="display:none;">  

                <div class="row">
                    <div class="col-xs-4">
                        <label>Team Name</label>
                    </div>
                    <div class="col-xs-8">
                        <input type="text" class="input-block-level" required name="team-name">
                    </div>
                </div>

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
                <div class="row"> 
                    <div class="desc col-xs-12">
                        Team members can be entered here or filled/edited at a later date.
                    </div>

                    <div class="row">
                            <div class="col-xs-4">
                                <label>Captain Full Name</label>
                            </div>
                            <div class="col-xs-8">
                                <input type="text" class="input-block-level" name="players[]">
                            </div>
                        
                        </div>
                    <?php 
                    $numPlayers = $row['team_players'];
                    for($i = 1; $i < $numPlayers; $i++) {
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

                <div class="row">
                    <div class="col-xs-12 text-center">
                        <br /><br />
                        <button type="submit" name="event-registration" class="btn btn-primary btn-large" id="submit-registration">Submit</button>
                    </div>
                </div>
            </div>
        </form>

    </section>

<?php
}
include('footer.php');
?> 

<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

<script type="text/javascript">

    $('#search-passcode').click(function () {
        var passcode = $('#passcode-check').val();
        var email = $('#email-check').val();
        var eventId = parseInt("<?php echo $eventId; ?>");

        $.ajax({
            url: 'includes/handleForm.php',
            type: 'POST',
            dataType: 'json',
            data: {
                'passcode': passcode,
                'email': email,
                'eventId': eventId
            },
            complete: function(data){
                response = $.parseJSON(data.responseText);

                if (response.type == 'success') {
                    $('#teamResults').fadeIn();
                    $('#team-search-result').fadeOut();
                } else {
                    $('#teamResults').fadeOut();
                    $('#team-search-result').html(response.message);
                }
            }
        });
    });

    $('#submit-team-changes').click(function (e) {
        
        e.preventDefault();

        if ($('#team_name').val() == '') {
            $('#team_name').val($('#full_name').val());
        }

        var passcode = $('#passcode-check').val();
        var registerType = $('input[name="type"]:checked').val();

        var form = $( "#registration-form" );
        form.validate();

        validPasscode = true;

        $.ajax({
            url: 'includes/handleForm.php',
            type: 'GET',
            dataType: 'json',
            data: {
                'passcode': passcode
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
        
    });

</script>

