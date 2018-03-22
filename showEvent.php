<?php

$currentPage = 'Events';
include('header.php');

if(!IS_DEV && (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off")){
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    exit();
}

if ($_GET) {
    $eventId = $_GET['eventId'];
} else {
    die('<script type="text/javascript">window.location.href="404.php";</script>');
}
?>

    <section class="title">
        <div class="container">
            <div class="row-fluid">
                <div class="span6">
                    <h1>Events</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="services">
        <div class="container">
            
            <?php
            $result = mysqli_query($conn,"SELECT * FROM events WHERE id = ".$eventId);
            if (mysqli_num_rows($result) == 1) {
                while($row = mysqli_fetch_array($result)) {
                    $deadline = strtotime($row['event_date'].' -3 days'); 
            ?>

                <div class="row">
                    <div class="col-xs-12 col-md-10 col-md-push-1">
                        <h2><?php echo $row['title']; ?></h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-5">
                        <img class="show-event-img pull-right" src="images/events/<?php echo $row['image_path']; ?>">
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <?php 
                        $today = date('Y-m-d H:i:s');
                        $wedBefore = strtotime(date('Y-m-d 23:59:59', strtotime('previous wednesday', strtotime($row['event_date']))));
                        if ($row['registration_open'] && strtotime($today) <= $wedBefore) { ?>
                            <a class="btn btn-primary btn-large pull-right" href="register.php?id=<?php echo $row['id']; ?>">Register</a>
                        <?php } ?>
                        <h3><?php echo $row['team_players'].' on '.$row['team_players']; ?></h3>
                        <h4>$ <?php echo $row['price']; ?> <small>(per player)</small></h4>
                        <h3><?php echo date_create($row['event_date'])->format('D, M j'); ?></h3>
                        <h3><?php echo $row['location']; ?></h3>
                        <h4><small><?php echo $row['address'].', '.$row['city']; ?></small></h4>

                        <h4>Check In: <?php echo $row['checkin_time']; ?></h4>
                        <h4>Captain's Meeting: <?php echo $row['meeting_time']; ?></h4>
                        <h4><?php echo 'Start Play: '.$row['play_time']; ?></h4>
                    </div>
                </div>
                
        </div>
    </section>

    <section id="services">
        <div class="container">

            <div class="row">
                <div class="col-xs-12 col-md-10 col-md-push-1">
                    
                    <div class="format">
                        
                        <h3>Format</h3>
                        <p><?php echo $row['format']; ?></p>
                    
                        <?php if ($row['description']) {
                            echo '<h3>Details</h3>';
                            echo '<p>'.$row['description'].'</p>';
                        } ?>

                        <?php if ($row['additional_info']) {
                            echo '<h3>Additional Info</h3>';
                            echo '<p>'.$row['additional_info'].'</p>';
                        } ?>

                        <h4>Registration Deadline: <?php echo date('D, M j 11:59', $wedBefore).'pm'; ?></h4>

                        <?php if ($row['fb_link']) {
                            echo '<p><a href="'.$row['fb_link'].'">Link to Facebook Event</a></p>';
                        } ?>
                        
                    </div>
                </div>
            </div>
        </div>

    </section>

    <section id="testimonials">
        <div class="container">

            <div class="row">
                <div class="col-xs-12 col-md-10 col-md-push-1">
                    
                    <div class="format">
                        <?php 

                        // Until this gets fully developed, this button should be hidden
                        $today = date('Y-m-d H:i:s');
                        $eventDateMinusFive = strtotime($row['event_date'].' -5 days');
                        if ($eventDateMinusFive >= strtotime($today)) { ?>
                            <div class="pull-right" style="display:none;">
                                <a class="btn btn-primary" id="request-refund" >Request Refund</a>
                            </div>
                        <?php } ?>
                        
                        <h3>Teams</h3>
                        <?php
                        $teams = mysqli_query($conn,"SELECT *, count(*) as num_teams FROM teams WHERE is_active = 1 AND event_id = ".$eventId);
                        if (mysqli_num_rows($teams) == 1) {
                            while($team = mysqli_fetch_array($teams)) {
                        ?>
                            <p><?php echo $team['num_teams'].'/'.$row['max_teams']; ?> spots filled</p>
                        <?php } 
                        } 

                        $sql = mysqli_query($conn,"SELECT d.id, division_label FROM event_divisions ed JOIN divisions d ON d.id = ed.division_id WHERE event_id = ".$eventId." ORDER BY d.id ASC");
                        if (mysqli_num_rows($sql) > 0) {
                            while($divisions = mysqli_fetch_array($sql)) {
                        
                                echo '<br /><h4>'.$divisions['division_label'].' Division</h4><hr>';
                                
                                $sql2 = mysqli_query($conn,"SELECT * FROM teams WHERE is_active = 1 AND event_id = ".$eventId." AND division_id = ".$divisions['id']." ORDER BY id ASC");
                                if (mysqli_num_rows($sql2) > 0) {
                                    while($team = mysqli_fetch_array($sql2)) { ?>

                                        <div class="row">
                                            <div class="col-xs-12 col-md-10 col-md-push-1">
                                                <b><?php echo $team['team_name']; ?> - </b>

                                                <?php
                                                $teamMembers = '';

                                                $sql3 = mysqli_query($conn,"SELECT * FROM teams AS t 
                                                    JOIN team_players AS tp ON tp.team_id = t.id
                                                    JOIN people AS p ON tp.people_id = p.id 
                                                    WHERE tp.is_active = 1 AND t.id = ".$team['id']);

                                                if (mysqli_num_rows($sql3) > 0) {
                                                    while($player = mysqli_fetch_array($sql3)) {
                                                        $teamMembers .= $player['full_name'].', ';
                                                    }
                                                }

                                                $teamMembers = rtrim($teamMembers, ', ');
                                                echo $teamMembers;
                                                ?>
                                            </div>
                                        </div>

                                    <?php }
                                } else { ?>
                                    <div class="row">
                                        <div class="col-xs-12 col-md-10 col-md-push-1">
                                            <b>There are no registrations for this division yet.</b>
                                        </div>
                                    </div>
                                <?php
                                }
                            } 
                        } ?>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-10 col-md-push-1">
                    <div class="format">

                        <h4>Free Agents</h4><hr />
                        <?php
                        $sql5 = mysqli_query($conn, "SELECT * FROM free_agents JOIN people ON free_agents.people_id = people.id LEFT JOIN divisions on divisions.id = free_agents.division_id WHERE is_active = 1 AND event_id = ".$eventId);
                        if (mysqli_num_rows($sql5) > 0) {
                            while($freeAgent = mysqli_fetch_array($sql5)) { ?>
                                <div class="row">
                                    <div class="col-xs-12 col-md-10 col-md-push-1">
                                        <?php echo $freeAgent['full_name'].' - Division: '.$freeAgent['division_label']; ?>
                                    </div>
                                </div>
                            <?php }
                        } else { ?>
                            <div class="row">
                                <div class="col-xs-12 col-md-10 col-md-push-1">
                                    <b>There are no free agents for this event yet.</b>
                                </div>
                            </div>
                        <?php
                        }

                        ?>
                    </div>
                </div>
            </div>
                        
                   

            <?php } 
            } ?>
            

        </div>
    </section>

    <!--  Refund form -->
    <div class="modal fade" id="refundModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Request Refund</h4>
                </div>
                <div class="modal-body">
                    <form class="form-inline" method="post" id="refund-precheck">
                        <input type="hidden" name="refund-precheck" value="1">
                        <input type="hidden" name="eventId" value="<?php echo $eventId; ?>">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <input type="text" name="email" placeholder="Email">
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <input type="text" name="passcode" placeholder="Team Passcode">
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 text-center">
                                <a id="searchRefund" class="btn btn-primary">Search</a>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-xs-12 format">
                            <span style="display:none;" id="precheck-results"></span>
                            <form class="form-inline" method="post" id="refund-form" style="display:none;">
                                <div id="captain-options" style="display:none;">
                                    You are the captain! Would you like to: <br />
                                    <input type="radio" name="captainChoice" value="refund-entire-team"> <label>Refund the entire team</label><br />
                                    <input type="radio" name="captainChoice" value="refund-specific-players"> <label>Refund specific players</label><br />
                                </div>

                                <div id="refund-specific-players" style="display:none;">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            Choose player(s) to refund:
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <select name="refund-players[]" id="refund-players" multiple="multiple" style="width: 100%"></select>
                                        </div>
                                    </div>
                                </div>
                                <div id="choose-new-captain" style="display:none;">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            Choose player to be new captain:
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <select name="new-captain" id="new-captain" style="width: 100%"></select>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="refund-form" value="1">
                                <input type="hidden" name="eventId" id="eventId" value="">
                                <input type="hidden" name="userId" id="userId" value="">
                                <input type="hidden" name="token" id="token" value="">
                                <input type="hidden" name="teamId" id="teamId" value="">
                                <a id="submitRefund" class="btn btn-primary format">Submit Refund</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<?php
include('footer.php');
?> 

<script type="text/javascript" src="js/full_sparkle.js"></script>
<script src="js/select2.min.js"></script>
<script type="text/javascript">

    $('#request-refund').click(function () {
        $('#refundModal').modal('show');
    });

    var teamId = null;
    var userId = null;

    $('#searchRefund').click(function (e) {
         e.preventDefault();

        var formData = $('#refund-precheck').serialize();
        $.ajax({
            url: 'includes/handleForm.php',
            type: "POST",
            data: formData,
            dataType: 'json',
            complete: function (response) {
                data = $.parseJSON(response.responseText);

                if (data.type == 'error') {
                    $('#precheck-results').html(data.message);
                } else if (data.type == 'success') {
                    $('#precheck-results').html('<h4>Payment found for '+data.playerName+'</h4>');
                    $('#eventId').val(data.eventId);
                    $('#userId').val(data.userId);
                    $('#token').val(data.token);
                    $('#teamId').val(data.teamId);
                    teamId = data.teamId;
                    userId = data.userId;

                    $('#refund-form').show();

                    if (data.captain == true) {
                        $('#captain-options').show();
                    }

                    loadPlayerOptions();
                }
                $('#precheck-results').show();
            }
        })
    });

    function loadPlayerOptions()
    {
        $.ajax({
            url: 'includes/handleForm.php',
            type: 'GET',
            dataType: 'json',
            data: {
                'teamId': teamId,
                'paid': 1
            },
            complete: function(data){
                players = $.parseJSON(data.responseText);
                $('#refund-players').select2({
                    placeholder: 'Select players',
                    data: players
                });

                $('#new-captain').select2({
                    placeholder: 'Select player',
                    data: players,
                    minimumResultsForSearch: -1
                });
            }
        });
    }

    $('input[type=radio][name=captainChoice]').change(function() {
        if (this.value == 'refund-specific-players') {
            $('#refund-specific-players').show();
        } else if (this.value == 'refund-entire-team') {
            $('#refund-specific-players').hide();
        }
    });

    $("#refund-players").on("select2:select select2:unselect", function (e) {
        refunding = $("#refund-players").val();
        // If refunding the captain, make user choose new captain
        if ($.inArray(userId, refunding) > -1) {
            loadPlayerOptions();
            $('#choose-new-captain').show();
        }
    });

    $('#submitRefund').click(function (e) {
        e.preventDefault();

        var formData = $('#refund-form').serialize();
        $.ajax({
            url: 'includes/handleForm.php',
            type: "POST",
            data: formData,
            dataType: 'json',
            complete: function (response) {
                $('#refundModal').modal('hide');
                data = $.parseJSON(response.responseText);
                if (data.type == 'error') {
                    addAlertToPage('error', 'Error', data.message, 0);
                } else if (data.type == 'success') {
                    addAlertToPage('success', 'Success', data.message, 0);
                }
            }
        })
    });



</script>