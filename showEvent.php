<?php

$currentPage = 'Events';
include('header.php');

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
                    <?php 
                    if($row['image_path']) { ?>
                        <div class="col-xs-12 col-md-5">
                            <img class="show-event-img pull-right" src="images/events/<?php echo $row['image_path']; ?>">
                        </div>
                        <div class="col-xs-12 col-md-6">
                    <?php 
                    } else { ?>
                        <div class="col-xs-12 col-md-6 col-md-push-1">
                    <?php 
                    } ?>
                        <div class="pull-right">
                            <?php
                            $today = date('Y-m-d H:i:s');
                            // $wedBefore = strtotime(date('Y-m-d 23:59:59', strtotime('previous wednesday', strtotime($row['event_date']))));
                            if ($row['registration_open'] && strtotime($today) <= strtotime($row['registration_deadline'])) { ?>
                                <a class="btn btn-primary btn-large" href="register.php?id=<?php echo $row['id']; ?>">Register</a>
                                <h4 class="padding5">$ <?php echo $row['price']; ?> <?php echo ($row['price_for'] != "na") ? "<small>(".$row['price_for'].")</small>" : "-"; ?></h4>
                                <a class="btn btn-view btn-large" href="#teams">View Teams</a>
                            <?php 
                            } ?>
                        </div>
                        <h3><?php echo $row['team_players'].' on '.$row['team_players']; ?></h3>
                        
                        <h3><?php echo date_create($row['event_date'])->format('D, M j'); ?></h3>
                        <h3><?php echo $row['location']; ?></h3>
                        <?php
                        if ($row['address'] && $row['city']) {
                            echo '<h4><small>'.$row['address'].', '.$row['city'].'</small></h4>';
                        } ?>
                        
                    </div>
                </div>
                <hr />
                <div class="row board-name">
                    <div class="col-xs-3 text-center">
                        <h4>Check In</h4><h3><?php echo $row['checkin_time']; ?></h3>
                    </div>
                    <div class="col-xs-6 text-center">
                        <h4>Captain's Meeting</h4><h3><?php echo $row['meeting_time']; ?></h3>
                    </div>
                    <div class="col-xs-3 text-center">
                        <h4>Start Play</h4><h3><?php echo $row['play_time']; ?></h3>
                    </div>
                
        </div>
    </section>

    <section id="services">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-10 col-md-push-1">
                    <div class="format">
                        
                        <?php if ($row['format']) {
                            echo '<h3>Format</h3>';
                            echo '<p>'.$row['format'].'</p>';
                        } ?>
                    
                        <?php if ($row['description']) {
                            echo '<h3>Details</h3>';
                            echo '<p>'.$row['description'].'</p>';
                        } ?>

                        <?php if ($row['additional_info']) {
                            echo '<h3>Additional Info</h3>';
                            echo '<p>'.$row['additional_info'].'</p>';
                        } 
                        $deadline = strtotime($row['registration_deadline']);
                        ?>

                        <h4>Registration Deadline: <?php echo date('D, M j 11:59', $deadline).'pm'; ?></h4>

                        <?php if ($row['fb_link']) {
                            echo '<p><a href="'.$row['fb_link'].'">Link to Facebook Event</a></p>';
                        } ?>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="teams">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-10 col-md-push-1">
                    <div class="format">
                        
                        <h3>Teams</h3>
                        <?php
                        $teams = mysqli_query($conn,"SELECT * FROM teams WHERE is_active = 1 AND event_id = ".$eventId);
                        ?>
                        
                        <?php 
                        $sql = mysqli_query($conn,"SELECT d.id, division_label, max_teams FROM event_divisions ed JOIN divisions d ON d.id = ed.division_id WHERE event_id = ".$eventId." ORDER BY d.id ASC");
                        if (mysqli_num_rows($sql) > 0) {
                            while($divisions = mysqli_fetch_array($sql)) {
                        
                                $sql2 = mysqli_query($conn,"SELECT * FROM teams WHERE is_active = 1 AND event_id = ".$eventId." AND division_id = ".$divisions['id']." ORDER BY id ASC");
                                $teamCount = mysqli_num_rows($sql2);

                                echo '<br /><h4>'.$divisions['division_label'].' Division <span class="subtitle">'.$teamCount.'/'.$divisions['max_teams']. ' spots filled</span></h4><hr>';
                                
                                $sql3 = mysqli_query($conn,"SELECT * FROM teams WHERE is_active = 1 AND event_id = ".$eventId." AND division_id = ".$divisions['id']." ORDER BY id ASC");
                                if (mysqli_num_rows($sql3) > 0) {
                                    while($team = mysqli_fetch_array($sql3)) { ?>

                                        <div class="row">
                                            <div class="col-xs-12 col-md-10 col-md-push-1">
                                                <b><?php echo $team['team_name']; ?> - </b>

                                                <?php
                                                $teamMembers = '';

                                                $sql4 = mysqli_query($conn,"SELECT * FROM teams AS t 
                                                    JOIN team_players AS tp ON tp.team_id = t.id
                                                    JOIN people AS p ON tp.people_id = p.id 
                                                    WHERE tp.is_active = 1 AND t.id = ".$team['id']);

                                                if (mysqli_num_rows($sql4) > 0) {
                                                    while($player = mysqli_fetch_array($sql4)) {
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
                        } ?>
                    </div>
                </div>
            </div>
            <br /><br /><br />
                        
            <?php } 
            } ?>
            
        </div>
    </section>

<?php
include('footer.php');
?> 

<script type="text/javascript" src="js/full_sparkle.js"></script>
<script src="js/select2.min.js"></script>
