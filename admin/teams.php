<?php
session_start();

include('includes/header.php');

if (!isset($_SESSION["user_id"])) {
    header('location:'.URL);
}

if (isset($_GET) && !empty($_GET)) {
    $eventId = $_GET['eventId'];  

    $result = mysqli_query($conn,"SELECT * FROM events WHERE id = $eventId");
    while($event = mysqli_fetch_array($result)) 
    {
        $eventName = $event['title'];
    }
}
?>

<!-- page content -->
<div class="right_col" role="main">
                    
    <div class="page-title">
        <div class="title_left">
            <h1><?php echo $eventName; ?></h1>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-xs-12">
            <div class="x_panel table_panel">
                <div class="x_title">
                    <h2>Teams</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="col-md-12 col-sm-12">

                        <div class="pull-right">
                            <a href="addTeam.php?eventId=<?php echo $eventId; ?>" class="btn btn-info">Add Team</a>
                            <a href="exportTeams.php?eventId=<?php echo $eventId; ?>" class="btn btn-info">Export Teams</a>
                        </div>
                        <table class="table table-bordered table-striped table-responsive" id="datatable-teams">
                            <thead>
                                <th>Team</th>
                                <th>Captain</th>
                                <th>Division</th>
                                <th>Players Registered</th>
                                <th>Players Paid</th>
                                <th>Passcode</th>
                            </thead>
                            <tbody>
                                <?php 
                                $sql = "SELECT t.id, team_name, division_label, passcode, full_name, players_paid
                                    FROM teams t 
                                    JOIN divisions d on t.division_id = d.id 
                                    JOIN people p on p.id = t.captain_id 
                                    WHERE t.event_id = $eventId AND t.is_active = 1
                                    GROUP BY t.id
                                    UNION
                                    (SELECT rt.id, NULL, division_label, NULL, captain_name, 0 
                                    FROM reserved_teams rt
                                    JOIN divisions ON divisions.id = rt.division_id
                                    WHERE is_active = 1 AND event_id = $eventId)";
                                $teams = mysqli_query($conn, $sql);
                                if (mysqli_num_rows($teams) > 0) {
                                    while($team = mysqli_fetch_array($teams)) 
                                    { 
                                        $registeredPlayers = getRegisteredPlayers($conn, $team['id']); ?>
                                        <tr>
                                            <td><a href="editTeam.php?teamId=<?php echo $team[0]; ?>"><?php echo $team['team_name']; ?></a></td>
                                            <td><?php echo $team['full_name']; ?></td>
                                            <td><?php echo $team['division_label']; ?></td>
                                            <td><?php echo $registeredPlayers; ?></td>
                                            <td><?php echo $team['players_paid']; ?></td>
                                            <td><?php echo $team['passcode']; ?></td>
                                        </tr>

                                    <?php }
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-xs-12">
            <div class="x_panel table_panel">
                <div class="x_title">
                    <h2>Free Agents</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="col-md-12 col-sm-12">

                        <table class="table table-bordered table-striped table-responsive" id="datatable-freeagents">
                            <thead>
                                <th>Name</th>
                                <th>Division</th>
                                <th>Remove</th>
                            </thead>
                            <tbody>
                                <?php 
                                $sql = "SELECT fa.id, division_label, full_name
                                    FROM free_agents fa 
                                    JOIN divisions d on fa.division_id = d.id 
                                    JOIN people p on p.id = fa.people_id 
                                    WHERE event_id = $eventId AND fa.is_active = 1
                                    GROUP BY fa.id";
                                $teams = mysqli_query($conn, $sql);
                                if (mysqli_num_rows($teams) > 0) {
                                    while($team = mysqli_fetch_array($teams)) 
                                    { ?>
                                        <tr>
                                            <td><?php echo $team['full_name']; ?></td>
                                            <td><?php echo $team['division_label']; ?></td>
                                            <td><a class="btn btn-warning" onclick="removeFA(<?php echo $team['id'];?>);">Remove</a></td>
                                        </tr>
                                    <?php }
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="remove-fa-modal" role="dialog" aria-hidden="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            </div>
            <div class="modal-body">

                <span id="confirmText">
                    Are you sure you want to remove this player from the free agent list?
                </span>
                <span id="faId" style="display:none;"></span>

                <div class="modal-buttons">
                    <button type="button" class="btn btn-warning" class="close" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-success" id="remove-fa-btn">Yes</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('includes/footer.php');
?>

<script type="text/javascript">

    $(document).ready(function(){
        $('#datatable-teams').DataTable({
            stateSave: true,
            "order": [[ 3, "desc" ]]
        });

        $('#datatable-freeagents').DataTable({
            stateSave: true,
            "order": [[ 1, "desc" ]]
        });
    });

    function removeFA(faId)
    {
        $('#faId').html(faId);
        $('#remove-fa-modal').modal('show');
    }

    $('#remove-fa-btn').click(function () {
        faId = $('#faId').html();

        $.ajax({
            url: 'includes/handleForm.php',
            type: 'GET',
            dataType: 'json',
            data: {
                'faId': faId
            },
            complete: function(data){
                $('#remove-fa-modal').modal('hide');
                response = $.parseJSON(data.responseText);

                if (response.type == 'success') {
                    addAlertToPage('success', 'Success', response.message);
                    setTimeout(function(){
                        location.reload();
                    }, 3000);
                } else {
                    addAlertToPage('error', 'Error', response.message);
                }
                
            }
        });
    });

</script>