<?php
session_start();

include('includes/header.php');

if (!isset($_SESSION["user_id"])) {
    header('location:'.URL);
}
?>

<!-- page content -->
<div class="right_col" role="main">
                    
    <div class="page-title">
        <div class="title_left">
            <h1>Messages</h1>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-xs-12">
            <div class="x_panel table_panel">
                <div class="x_title">
                    <h2>All Messages</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="col-xs-12">

                        <table class="table table-bordered table-striped table-responsive" id="datatable-messages">
                            <thead>
                                <th>Status</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Date Received</th>
                                <th>View</th>
                            </thead>
                            <tbody>
                                <?php 
                                $messages = mysqli_query($conn,"SELECT messages.id, full_name, email, status, messages.created_at FROM messages JOIN people ON people.id = messages.people_id");
                                if (mysqli_num_rows($messages)) {
                                    while($row = mysqli_fetch_array($messages)) 
                                    { 
                                    ?>
                                        <tr>
                                            <td><?php echo $row['status']; ?></td>
                                            <td><?php echo $row['full_name']; ?></td>
                                            <td><?php echo $row['email']; ?></td>
                                            <td><?php echo date('Y.m.d', strtotime($row['created_at'])); ?></td>
                                            <td><?php echo '<a href="viewMessage.php?messageId='.$row['id'].'">View</a>'; ?></td>
                                        </tr>
                                    <?php 
                                    } 
                                } ?>
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
        $('#datatable-messages').DataTable({
            stateSave: true,
            "order": [[ 3, "desc" ]]
        });
    });

</script>
    