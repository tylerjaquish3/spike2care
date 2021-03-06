<?php
session_start();

include('includes/header.php');

if (!isset($_SESSION["user_id"])) {
    header('location:'.URL);
}

$message = '';

if (isset($_GET['message']) && $_GET['message'] == 'success') {
    $message = "Saved successfully!";
}
?>

<!-- page content -->
<div class="right_col" role="main">

    <div class="row">
        <div class="col-xs-4">
            <div class="title_left">
                <h1>Events</h1>
            </div>
        </div>
        <div class="col-xs-8 text-right down15">   
            <a href="createEvent.php" class="btn btn-info">Create Event</a>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-xs-12">
            <div class="x_panel table_panel">
                <div class="x_content">
                    <div class="col-xs-12">
                        <table class="table  table-bordered table-striped table-responsive stripe compact" id="datatable-events">
                            <thead>
                                <th>Title</th>
                                <th>Date</th>
                                <th>Location</th>
                                <th>Donations</th>
                                <th>Entrants</th>
                                <th>Recap</th>
                                <th>Remove</th>
                            </thead>
                            <tbody>
                                <?php 
                                $result = mysqli_query($conn,"SELECT * FROM events");
                                while($event = mysqli_fetch_array($result)) 
                                { ?>
                                    <tr>
                                        <td><?php echo '<a href="createEvent.php?eventId='.$event['id'].'">'.$event['title'].'</a>'; ?></td>
                                        <td><?php echo date('Y.m.d', strtotime($event['event_date'])); ?></td>
                                        <td><?php echo $event['location']; ?></td>
                                        <td>$ <?php echo $event['specified_donations'];?></td>
                                        <td>
                                            <?php if ($event['special_event'] == 0) { 
                                                echo '<a href="teams.php?eventId='.$event['id'].'">View/Edit</a>'; 
                                            } else { 
                                                echo '<a href="entrants.php?eventId='.$event['id'].'">View</a>'; 
                                            }?>        
                                        </td>
                                        <td><?php echo '<a href="editRecap.php?eventId='.$event['id'].'">Edit Recap</a>'; ?></td>
                                        <td><a href="includes/handleForm.php?action=remove&eventId=<?php echo $event['id']; ?>" onclick="if(!confirm('Are you sure?')) return false">Remove</a></td>
                                    </tr>

                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
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
        if ("<?php echo $message;?>" != "") {
            addAlertToPage('success', 'Success', 'Saved successfully!', 5);
        }
    
        $('#datatable-events').DataTable({
            stateSave: true,
            "order": [[ 1, "desc" ]]
        });
    });

</script>