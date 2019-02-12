<?php
session_start();

include('includes/header.php');

if (!isset($_SESSION["user_id"])) {
    header('location:'.URL);
}
?>

<!-- page content -->
<div class="right_col" role="main">
           
    <div class="row">
        <div class="col-xs-6">
            <div class="title_left">
                <h1>Meeting Minutes</h1>
            </div>
        </div>
        <div class="col-xs-6 text-right down15">   
            <a href="addMinutes.php" class="btn btn-primary">Add Minutes</a>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-xs-12">
            <div class="x_panel table_panel">
                <div class="x_content">
                    <div class="col-xs-12">

                        <table class="table table-bordered table-striped table-responsive" id="datatable-minutes">
                            <thead>
                                <th>Meeting Date</th>
                                <th>File Path</th>
                                <th>Download</th>
                                <th>Remove</th>
                            </thead>
                            <tbody>
                                <?php 
                                $minutes = mysqli_query($conn,"SELECT * FROM meeting_minutes ORDER BY meeting_date DESC");
                                if (mysqli_num_rows($minutes) > 0) {
                                    while($row = mysqli_fetch_array($minutes)) 
                                    { 
                                    ?>
                                        <tr>
                                            <td><?php echo date('Y.m.d', strtotime($row['meeting_date'])); ?></td>
                                            <td><?php echo $row['file_path']; ?></td>
                                            <td><a target="_blank" href="../minutes/<?php echo $row['file_path'];?>">Download</a></td>
                                            <td><a href="includes/handleForm.php?action=removeMinutes&minutesId=<?php echo $row['id'];?>">Remove</a>
                                        </tr>

                                    <?php 
                                    } 
                                } else { ?>
                                    <tr>
                                        <td colspan="4">No meeting minutes found</td>
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
        $('#datatable-minutes').DataTable({
            "order": [[ 0, "desc" ]]
        });
    });

</script>