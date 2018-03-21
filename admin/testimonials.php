<?php
session_start();

include('includes/header.php');

if (!isset($_SESSION["user_id"])) {
    header('location:'.URL);
}

$alert = 'false';

if (isset($_GET) && !empty($_GET)) {
    $alert = $_GET['alert'];
}   
?>

<!-- page content -->
<div class="right_col" role="main">
                    
    <div class="page-title">
        <div class="title_left">
            <h1>Testimonials</h1>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-xs-12">
            <div class="x_panel table_panel">
                <div class="x_title">
                    <h2>All Testimonials</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="col-xs-12">

                        <div class="pull-right">
                            <a href="editTestimonial.php" class="btn btn-info">Add New Testimonial</a>
                        </div>

                        <table class="table stripe compact" id="datatable-testimonials">
                            <thead>
                                <th>Name</th>
                                <th>Date Added</th>
                                <th>Active</th>
                                <th>View</th>
                                
                            </thead>
                            <tbody>
                                <?php 
                                $messages = mysqli_query($conn,"SELECT testimonials.id, full_name, testimonials.is_active, testimonials.created_at FROM testimonials JOIN people ON people.id = testimonials.user_id");
                                if (mysqli_num_rows($messages)) {
                                    while($row = mysqli_fetch_array($messages)) 
                                    { 
                                    ?>
                                        <tr>
                                            <td><?php echo $row['full_name']; ?></td>
                                            <td><?php echo date('Y.m.d', strtotime($row['created_at'])); ?></td>
                                            <td><?php echo ($row['is_active'] ? 'Yes' : 'No'); ?></td>
                                            <td><?php echo '<a href="editTestimonial.php?id='.$row['id'].'">View/Edit</a>'; ?></td>
                                        </tr>

                                    <?php 
                                    } 
                                } else { ?>
                                    <tr>
                                        <td colspan="4">No testimonials found</td>
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

        if ('<?php echo $alert;?>' == 'success') {
            addAlertToPage('success', 'Success', 'Your testimonial was successfully saved.', 10);
        }

        $('#datatable-testimonials').DataTable({
            "order": [[ 1, "desc" ]]
        });
    });

</script>
    