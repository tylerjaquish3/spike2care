<?php
session_start();

include('includes/header.php');

if (!isset($_SESSION["user_id"])) {
    header('location:'.URL);
}
?>

<!-- page content -->
<div class="right_col" role="main">
                    
    <div class="title_left">
        <h1>Assistance Applications</h1>
    </div>
    
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-xs-12">
            <div class="x_panel table_panel">
                <div class="x_content">
                    <div class="col-md-12 col-sm-12">

                        <table id="datatable-applications" class="table table-bordered table-striped table-responsive">
                            <thead>
                                <th>Nominee</th>
                                <th>Email</th>
                                <th>Submitted Date</th>
                                <th>Status</th>
                                <th>View</th>
                                
                            </thead>
                            <tbody>
                                <?php 
                                $applications = mysqli_query($conn,"SELECT applications.id, full_name, email, status, submitted_at FROM applications JOIN people ON people.id = applications.nominee_id");
                                while($app = mysqli_fetch_array($applications)) 
                                { 
                                ?>
                                    <tr>
                                        <td><?php echo $app['full_name']; ?></td>
                                        <td><?php echo $app['email']; ?></td>
                                        <td><?php echo date('Y.m.d', strtotime($app['submitted_at'])); ?></td>
                                        <td><?php echo ucwords($app['status']);?></td>
                                        <td><?php echo '<a href="viewApplication.php?appId='.$app['id'].'">View</a>'; ?></td>
                                    </tr>

                                <?php 
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
        $('#datatable-applications').DataTable({
            stateSave: true,
            "order": [[ 2, "desc" ]]
        });
    });

</script>

    