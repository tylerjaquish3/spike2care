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
            <h1>Merchandise</h1>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>All Merchandise</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="col-xs-12">

                        <div class="pull-right">
                            <a href="createItem.php" class="btn btn-info">Add Item</a>
                        </div>
                        <table class="table stripe compact" id="datatable-merchandise">
                            <thead>
                                <th>Title</th>
                                <th>Price</th>
                                <!-- <th>Inventory</th>
                                <th>Sold</th> -->
                                <th>Active</th>
                                <th>Remove</th>
                            </thead>
                            <tbody>
                                <?php 
                                $result = mysqli_query($conn,"SELECT * FROM catalog");
                                while($item = mysqli_fetch_array($result)) 
                                { ?>
                                    <tr>
                                        <td><?php echo '<a href="createItem.php?itemId='.$item['id'].'">'.$item['title'].'</a>'; ?></td>
                                        <td>$ <?php echo $item['price']; ?></td>
                                        <!-- <td><?php echo $item['inventory']; ?></td>
                                        <td><?php echo $item['sold'];?></td> -->
                                        <td><?php echo ($item['active']) ? 'Active' : 'Inactive' ?></td>
                                        <td><a href="includes/handleForm.php?action=remove&itemId=<?php echo $item['id']; ?>" onclick="if(!confirm('Are you sure?')) return false">Remove</a></td>
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

        $('#datatable-merchandise').DataTable({
            "order": [[ 1, "desc" ]]
        });
    });

</script>
    