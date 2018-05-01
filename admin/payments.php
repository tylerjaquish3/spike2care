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
                                <th>Event</th>
                                <th>Received</th>
                            </thead>
                            <tbody>
                                <?php 
                                $sql = "SELECT max(PB.full_name) as paid_by, max(PB.email) as email, max(PF.full_name) as paid_for, 
                                    sum(donation_amount) as donation, sum(entry_amount) as entry, max(e.title) as event, max(p.created_at) as created_at
                                    FROM payments p 
                                    JOIN people as PB on PB.id = p.paid_by 
                                    LEFT JOIN people as PF on PF.id = p.paid_for 
                                    LEFT JOIN events e ON e.id = p.event_id 
                                    GROUP BY p.token, paid_for";

                                $result = mysqli_query($conn, $sql);
                                if (mysqli_num_rows($result) > 0) {

                                    while($payment = mysqli_fetch_array($result)) 
                                    { ?>
                                        <tr>
                                            <td><?php echo $payment['paid_by']; ?></td>
                                            <td><?php echo $payment['email']; ?></td>
                                            <td><?php echo $payment['paid_for']; ?></td>
                                            <td>$ <?php echo ($payment['donation']) ? $payment['donation']/100 : $payment['entry']/100; ?></td>
                                            <td><?php echo ($payment['donation']) ? 'Donation' : 'Event' ?></td>
                                            <td><?php echo $payment['event']; ?></td>
                                            <td><?php echo $payment['created_at']; ?></td>
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
            "order": [[ 6, "desc" ]]
        });
    });

</script>