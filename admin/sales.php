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
            <h1>Sales</h1>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>All Sales Orders</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="col-xs-12">

                        <table class="table table-bordered table-striped table-responsive" id="datatable-sales">
                            <thead>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Order Date</th>
                                <th>View</th>
                            </thead>
                            <tbody>
                                <?php 
                                $sql = "SELECT MAX(full_name) as name, p.id, MAX(status) as status, MAX(s.created_at) as order_date
                                    FROM people p
                                    JOIN sales s on p.id = s.person_id
                                    GROUP BY p.id
                                    ";
                                $sales = mysqli_query($conn, $sql);
                                if (mysqli_num_rows($sales) > 0) {
                                    while($row = mysqli_fetch_array($sales)) 
                                    { ?>
                                        <tr>
                                            <td><?php echo $row['name']; ?></td>
                                            <td><?php echo $row['status']; ?></td>
                                            <td><?php echo date('Y.m.d H:i:s', strtotime($row['order_date'])); ?></td>
                                            <td><a class="btn btn-warning" href="viewOrder.php?id=<?php echo $row['id'];?>">View</a></td>
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

        $('#datatable-sales').DataTable({
            "order": [[ 2, "desc" ]]
        });
    });

</script>
    