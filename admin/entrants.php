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
            <h1>Teams</h1>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-xs-12">
            <div class="x_panel table_panel">
                <div class="x_title">
                    <h2><?php echo $eventName; ?></h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="col-md-12 col-sm-12">

                        <div class="pull-right">
                            <a href="exportEntrants.php?eventId=<?php echo $eventId; ?>" class="btn btn-info">Export Entrants</a>
                        </div>
                        <table class="table table-bordered table-striped table-responsive" id="datatable-specialEvent">
                            <thead>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Quantity</th>
                                <th>Paid</th>
                                <th>Order Date</th>
                            </thead>
                            <tbody>
                                <?php 
                                $sql = "SELECT full_name, email, phone, quantity, paid, pmt.created_at FROM payments pmt JOIN people p on p.id = pmt.paid_by WHERE event_id = $eventId AND is_refunded = 0";
                                $result = mysqli_query($conn,$sql);
                                if (mysqli_num_rows($result) > 0) {
                                    while($entrant = mysqli_fetch_array($result)) 
                                    { ?>
                                        <tr>
                                            <td><?php echo $entrant['full_name']; ?></td>
                                            <td><?php echo $entrant['email']; ?></td>
                                            <td><?php echo $entrant['phone']; ?></td>
                                            <td><?php echo $entrant['quantity']; ?></td>
                                            <td><?php echo ($entrant['paid'] ? 'Yes' : 'No'); ?></td>
                                            <td><?php echo date('m.d.Y', strtotime($entrant['created_at'])); ?></td>
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
        $('#datatable-specialEvent').DataTable({
            "order": [[ 1, "desc" ]]
        });
    });

</script>