<?php
session_start();

include('includes/header.php');

if (!isset($_SESSION["user_id"])) {
    header('location:'.URL);
}

if (isset($_GET)) {
    $personId = $_GET['id'];

    $sales = mysqli_query($conn,"SELECT * FROM sales JOIN people on sales.person_id = people.id WHERE people.id = $personId");
    while($row = mysqli_fetch_array($sales)) 
    { 
        $name = $row['full_name'];
        $phone = $row['phone'];
        $email = $row['email'];  
        $address = $row['address'];
        $city = $row['city'];
        $state = $row['state']; 
        $zip = $row['zip'];

        $status = $row['status'];
        $created_at = $row['created_at'];
    }
}
?>

<!-- page content -->
<div class="right_col" role="main">
                    
    <div class="page-title">
        <div class="title_left">
            <h1>Sales Order</h1>
        </div>
    </div>

    <div class="clearfix"></div>
        <div class="row">
            <div class="col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <div class="inline">
                            Status: 
                            <select name="status" class="form-control" id="statusSelect">
                                <option value="Created" <?php echo ($status == 'Created' ? 'selected' : ''); ?>>Created</option>
                                <option value="Fulfilled" <?php echo ($status == 'Fulfilled' ? 'selected' : ''); ?>>Fulfilled</option>
                            </select>
                        </div>
                        <h2>Order for: <?php echo $name; ?></h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="row">
                            <div class="col-xs-12 col-md-6">
                   
                                <label class="control-label">Name</label>
                                <div class="no-input">
                                    <p><?php echo $name; ?></p>
                                </div>
                            
                                <label class="control-label">Email</label>
                                <div class="no-input">
                                    <p><?php echo $email; ?></p>
                                </div>

                                <label class="control-label">Date</label>
                                <div class="no-input">
                                    <p><?php echo $created_at; ?></p>
                                </div>

                            </div>

                            <div class="col-xs-12 col-md-6">
                            
                                <?php 
                                if (isset($address) && $address != "") { ?>

                                    <p>Customer chose to have this order shipped to this address:</p>

                                    <label class="control-label">Address</label>
                                    <div class="no-input">
                                        <p><?php echo $address; ?></p>
                                    </div>
                                
                                    <label class="control-label">City</label>
                                    <div class="no-input">
                                        <p><?php echo $city; ?></p>
                                    </div>

                                    <label class="control-label">State</label>
                                    <div class="no-input">
                                        <p><?php echo $state; ?></p>
                                    </div>

                                    <label class="control-label">Zip Code</label>
                                    <div class="no-input">
                                        <p><?php echo $zip; ?></p>
                                    </div>
                                    
                                    
                                <?php
                                } else { ?>
                                    
                                    <p>Customer chose to pick up this order at a future event.</p>
                                    
                                <?php
                                }?>
                            </div>
                        </div>

                        <hr />

                        <div class="row">
                            <div class="col-xs-12">
                                
                                <table class="table table-bordered table-striped table-responsive" id="datatable-sales">
                                    <thead>
                                        <th>Item</th>
                                        <th>Quantity</th>
                                        <th>Color</th>
                                        <th>Size</th>
                                        <th>Unit Price</th>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $sql = "SELECT title, price, quantity, color, size FROM sales s 
                                            JOIN catalog c on s.catalog_id = c.id
                                            JOIN colors on s.color_id = colors.id
                                            JOIN sizes on s.size_id = sizes.id
                                            WHERE person_id = $personId";
                                        $sales = mysqli_query($conn, $sql);
                                        if (mysqli_num_rows($sales) > 0) {
                                            while($row = mysqli_fetch_array($sales)) 
                                            { ?>
                                                <tr>
                                                    <td><?php echo $row['title']; ?></td>
                                                    <td><?php echo $row['quantity']; ?></td>
                                                    <td><?php echo $row['color']; ?></td>
                                                    <td><?php echo $row['size']; ?></td>
                                                    <td><?php echo $row['price']; ?></td>
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
    </div>
</div>

<?php
include('includes/footer.php');
?>
    
<script>

    $('#statusSelect').change(function () {

        var status = $('#statusSelect option:selected').val();

        $.ajax({
            url: 'includes/handleForm.php',
            type: 'post',
            cache: false,
            data: {
                action: 'updateOrderStatus',
                messageId: messageId, 
                status: status
            },
            success: function () {
                addAlertToPage('success', 'Success', 'Status was successfully changed.', 10);
            }
            // error: function () {
            //     addAlertToPage('error', 'Error', 'Error canceling item.', 10);
            // }
        });
    });

</script>