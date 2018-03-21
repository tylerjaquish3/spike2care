<?php
session_start();

include('includes/header.php');

if (!isset($_SESSION["user_id"])) {
    header('location:'.URL);
}

if (isset($_GET) && !empty($_GET)) {
    $teamId = $_GET['teamId'];  

    $result = mysqli_query($conn,"SELECT * FROM teams WHERE id = $teamId");
    while($team = mysqli_fetch_array($result)) 
    {
        $teamName = $team['team_name'];
    }
}
?>

<!-- page content -->
<div class="right_col" role="main">
                    
    <div class="page-title">
        <div class="title_left">
            <h1>Edit Team</h1>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-xs-12">
            <div class="x_panel table_panel">
                <div class="x_title">
                    <div id="name-show"><h2><?php echo $teamName; ?> <a href="#" id="change-name">Change Name</a></h2></div>
                    <div id="name-edit" style="display:none;"><input type="text" id="name-input"><a class="btn-primary btn-sm" id="name-save">Save</a></div>

                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="col-md-12 col-sm-12">

                        <table class="table table-bordered table-striped table-responsive" id="datatable-team">
                            <thead>
                                <th>Captain</th>
                                <th>Player Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Paid?</th>
                                <th>Edit</th>
                            </thead>
                            <tbody>
                                <?php 
                                $sql = "SELECT * FROM teams AS t 
                                    JOIN team_players AS tp ON tp.team_id = t.id
                                    JOIN people AS p ON tp.people_id = p.id 
                                    WHERE tp.is_active = 1 AND t.id = $teamId AND t.is_active = 1";
                                $teams = mysqli_query($conn,$sql);
                                if (mysqli_num_rows($teams) > 0) {
                                    while($team = mysqli_fetch_array($teams)) 
                                    { ?>
                                        <tr>
                                            <td width="25px" align="center"><?php echo $team['id'] == $team['captain_id'] ? '<i class="fa fa-check-circle green" style="font-size:28px;"></i>' : ''; ?></td>
                                            <td><?php echo $team['full_name']; ?></td>
                                            <td><?php echo $team['email']; ?></td>
                                            <td><?php echo $team['phone']; ?></td>
                                            <td><?php echo $team['paid'] ? 'Yes' : 'No'; ?></td>
                                            <td><a class="btn btn-warning" onclick="removePlayer(<?php echo $team['id'].','.$team['paid']; ?>);">Remove</a></td>
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

<div class="modal fade" id="remove-modal" role="dialog" aria-hidden="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            </div>
            <div class="modal-body">

                <span id="confirmText"></span>
                <span id="playerId" style="display:none;"></span>

                <div class="modal-buttons">
                    <button type="button" class="btn btn-warning" class="close" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-success" id="remove-player-btn">Yes</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('includes/footer.php');
?>

<script type="text/javascript">

    $('#datatable-team').DataTable({
        "order": [[ 0, "desc" ]]
    });

    function removePlayer(playerId, playerPaid)
    {
        if (playerPaid == 1) {
            confirmText = 'This player has paid. Are you sure you want to refund this player and remove them from the team?';
        } else {
            confirmText = 'Are you sure you want to remove this player from the team?';
        }
        $('#confirmText').html(confirmText);
        $('#playerId').html(playerId);
        $('#remove-modal').modal('show');
    }

    $('#remove-player-btn').click(function () {
        playerId = $('#playerId').html();

        $.ajax({
            url: 'includes/handleForm.php',
            type: 'GET',
            dataType: 'json',
            data: {
                'playerId': playerId,
                'teamId': <?php echo $teamId; ?>
            },
            complete: function(data){
                $('#remove-modal').modal('hide');
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

    $('#change-name').click(function () {
        $('#name-show').hide();
        $('#name-input').val("<?php echo $teamName; ?>");
        $('#name-edit').show();
    });

    $('#name-save').click(function () {

        newTeamName = $('#name-input').val();

        $.ajax({
            url: 'includes/handleForm.php',
            type: 'POST',
            dataType: 'json',
            data: {
                'teamName': newTeamName,
                'teamId': <?php echo $teamId; ?>
            },
            complete: function(data){
                $('#name-edit').modal('hide');
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