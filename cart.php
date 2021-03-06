<?php
	$currentPage = 'Cart';
	include('header.php');

    $message = '';
    if (isset($_GET['message']) && $_GET['message'] == 'error') {
        $message = "An error occurred placing your order. Please contact a Spike2Care admin.";
    }
?>

	<section class="title">
        <div class="container">
            <div class="row-fluid">
                <div class="span6">
                    <h1>Cart</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="services">
        <div class="container">

            <?php if (isset($_SESSION) && count($_SESSION['items']) > 0) { ?>
                <form action="includes/handleForm.php" method="POST" id="cart-form">          
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            These are the items in your cart, and they will remain in your cart for a limited time.<br>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            Your privacy and security is 100% guaranteed. <a href="privacy.php">View privacy statement.</a>
                        </div>
                    </div>
                    <div class="row checkout-item">
                        <div class="col-xs-12">
                            Items
                        </div>
                    </div>
                    <?php 
                    $total = 0;
                    foreach ($_SESSION['items'] as $key => $sessionItem) {
                        $result = mysqli_query($conn,"SELECT * FROM catalog WHERE id = ".$sessionItem['itemId']);
                        while($item = mysqli_fetch_array($result)) 
                        {
                            $itemTitle = $item['title'];
                            $itemPrice = $item['price'];
                        } 
                        if (isset($sessionItem['color'])) {
                            $result = mysqli_query($conn,"SELECT * FROM colors WHERE id = ".$sessionItem['color']);
                            while($item = mysqli_fetch_array($result)) 
                            {
                                $color = $item['color'];
                            } 
                        }
                        if (isset($sessionItem['size'])) {
                            $result = mysqli_query($conn,"SELECT * FROM sizes WHERE id = ".$sessionItem['size']);
                            while($item = mysqli_fetch_array($result)) 
                            {
                                $size = $item['size'];
                            } 
                        }
                        $total += $itemPrice * $sessionItem['quantity'];
                        $subtotal = $itemPrice * $sessionItem['quantity'];
                        ?>
                        
                        <div class="row checkout">
                            <div class="col-xs-4">
                                <h3><?php echo $itemTitle; ?></h3>
                                <p>
                                    Quantity: <?php echo $sessionItem['quantity']; ?>
                                    <?php
                                    if (isset($color)) {
                                        echo ', Color: '.$color;
                                    }
                                    if (isset($size)) {
                                        echo ', Size: '.$size;
                                    } ?>   
                                </p>
                            </div>
                            <div class="col-xs-4"><a href="#" onclick="remove(<?php echo $key; ?>)">Remove</a></div>
                            <div class="col-xs-4">
                                <h4>$ <?php echo number_format($subtotal, 2); ?></h4>
                                <p><?php if ($sessionItem['quantity'] > 1) { 
                                    echo '($'.$itemPrice.' each)';
                                    } ?>
                                </p>
                            </div>
                        </div>

                <?php 
                    } ?>
                
                    <div class="row checkout">
                        <div class="col-xs-8">
                            <span class="pull-right"><h4>Total:</h4></span>
                        </div>
                        <div class="col-xs-4">
                            <h4>$ <?php echo number_format($total, 2); ?></h4>
                        </div>
                    </div>

                </form>

                <div class="row text-center">
                    <div class="col-xs-12">
                        <a class="btn btn-primary" href="shopCheckout.php">Checkout</a>
                    </div>
                </div>
            <?php 
                $_SESSION['total'] = $total;
            } else { ?>
                <h2>There are no items in your cart. <a href="shop.php">Vist our shop</a> to add items.</h2>
            <?php 
            } ?>
        </div>
    </section>

    <?php
    include('footer.php');
    ?>

    <script type="text/javascript" src="js/full_sparkle.js"></script>

    <script type="text/javascript">

        var message = "<?php echo $message;?>";
        if (message != "") {
            addAlertToPage('error', 'Error', message, 0);
        }
        
        function remove(sessionKey) {
            $.ajax({
                url: 'includes/handleForm.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    'removeFromCart': true,
                    'sessionItem': sessionKey
                },
                complete: function(data){
                    response = $.parseJSON(data.responseText);
                    if (response == 'success') {
                        location.reload();
                    } else {
                        addAlertToPage('error', 'Error', 'Failed to update cart, please contact Spike2Care', 10);
                    }     
                }
            });
        }
    </script>