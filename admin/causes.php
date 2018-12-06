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
            <h1>Donation Causes</h1>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="x_panel">
        <div class="x_title">
            <h2>Add New Cause</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="col-xs-12">
                <div class="form-group">
                    <label for="cause">Name <span class="required">*</span></label>
                    <input type="text" class="form-control" id="cause" required placeholder="Cause">
                </div>

                <div class="form-group">
                    <label for="active">Active?</label>
                    <input type="checkbox" id="active">
                </div>

                <button class="btn btn-primary" id="save-cause-btn">Save</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="x_panel table_panel">
                <div class="x_content">
                    <div class="col-md-12 col-sm-12">
                        <table class="table table-bordered table-striped table-responsive" id="datatable-causes">
                            <thead>
                                <th>Cause</th>
                                <th>Total Donations</th>
                                <th>Active?</th>
                            </thead>
                            <tbody>
                                <?php 
                                $sql = "SELECT c.id, c.name, c.active, SUM(donation_amount) as total_donations FROM causes c 
                                    LEFT JOIN payments p ON p.cause_id = c.id
                                    GROUP BY c.id";

                                $result = mysqli_query($conn, $sql);
                                if (mysqli_num_rows($result) > 0) {

                                    while($cause = mysqli_fetch_array($result)) 
                                    { ?>
                                        <tr>
                                            <td><?php echo $cause['name']; ?></td>
                                            <td>$ <?php echo number_format($cause['total_donations'] / 100, 2); ?></td>
                                            <td>
                                                <input type="checkbox" onclick="toggleActive(<?php echo $cause['id']; ?>);" <?php echo $cause['active'] ? 'checked' : ''; ?>>
                                            </td>
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

<?php
include('includes/footer.php');
?>

<script type="text/javascript">

    $(document).ready(function(){
        $('#datatable-causes').DataTable({
            stateSave: true,
            "order": [[ 0, "asc" ]]
        });
    });

    function toggleActive(id) {
        
        console.log(id);
        $.ajax({
            url: 'includes/handleForm.php',
            type: 'post',
            cache: false,
            data: {
                action: 'toggleCauseActive',
                id: id
            },
            success: function () {
                location.reload();
            },
            error: function () {
                addAlertToPage('error', 'Error', 'Error adding color.', 10);
            }
        });
        
    }

    $('#save-cause-btn').click(function () {
        $.ajax({
            url: 'includes/handleForm.php',
            type: 'post',
            cache: false,
            data: {
                action: 'addCause',
                name: $('#cause').val(),
                active: $('#active').is(':checked')
            },
            success: function () {
                location.reload();
            },
            error: function () {
                addAlertToPage('error', 'Error', 'Error adding color.', 10);
            }
        });
    });

</script>