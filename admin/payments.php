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
            <h1>Payments</h1>
        </div>
        <a class="btn btn-primary" href="causes.php">Manage Causes</a>
        <a class="btn btn-primary" href="exportPayments.php">Export Payments</a>
    </div>

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-xs-12">
            <div class="x_panel table_panel">
                <div class="x_content">
                    <div class="col-md-12 col-sm-12">
                        <table class="table table-bordered table-striped table-responsive" id="datatable-payments">
                            <thead>
                                <th>Paid By</th>
                                <th>Email</th>
                                <th>Paid For</th>
                                <th>Amount</th>
                                <th>Type</th>
                                <th>Event/Cause</th>
                                <th>Refunded?</th>
                                <th>Received</th>
                            </thead>
                            <tbody>
                                <?php 
                                $sql = "SELECT PB.full_name as paid_by, PB.email as email, PF.full_name as paid_for, 
                                    donation_amount as donation, entry_amount as entry, merchandise_amount as merch, c.name as cause, e.title as title, is_refunded as refund, p.created_at as created_at
                                    FROM payments p 
                                    JOIN people as PB on PB.id = p.paid_by 
                                    LEFT JOIN people as PF on PF.id = p.paid_for 
                                    LEFT JOIN causes c ON c.id = p.cause_id
                                    LEFT JOIN events e ON e.id = p.event_id";

                                $result = mysqli_query($conn, $sql);
                                if (mysqli_num_rows($result) > 0) {

                                    while($payment = mysqli_fetch_array($result)) 
                                    { 
                                        if ($payment['donation']) {
                                            $type = 'Donation';
                                            $amount = number_format($payment['donation']/100, 2);
                                            $eventCause = $payment['cause'];
                                        } elseif ($payment['entry']) {
                                            $type = 'Event';
                                            $amount = number_format($payment['entry']/100, 2);
                                            $eventCause = $payment['title'];
                                        } else {
                                            $type = 'Merchandise';
                                            $amount = number_format($payment['merch']/100, 2);
                                            $eventCause = '';
                                        }
                                        ?>
                                        <tr>
                                            <td><?php echo $payment['paid_by']; ?></td>
                                            <td><?php echo $payment['email']; ?></td>
                                            <td><?php echo $payment['paid_for']; ?></td>
                                            <td align="right" style="padding-right: 50px;">$ <?php echo $amount; ?></td>
                                            <td><?php echo $type; ?></td>
                                            <td><?php echo $eventCause; ?></td>
                                            <td><?php echo ($payment['refund'] == 1) ? '<span class="badge badge-secondary">Yes</span>' : ''; ?></td>
                                            <td><?php echo date('Y.m.d H:i:s', strtotime($payment['created_at'])); ?></td>
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
        $('#datatable-payments').DataTable({
            stateSave: true,
            "order": [[ 7, "desc" ]]
        });
    });

</script>