<?php
	$currentPage = 'Cart';
	include('header.php');

    // $itemId = $color = $size = '';

    // if (isset($_SESSION)) {
    // 	$itemId = $_POST['itemId'];
    //     $color = $_POST['color'];
    //     $size = $_POST['size'];
    // }

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

            <form action="includes/handleForm.php" method="POST" id="cart-form">          

                <?php if (isset($_SESSION)) { ?>
                    <input type="hidden" id="eventId" name="event_id" value="<?php echo $eventId; ?>">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            These are the items in your cart.<br>
                            <?php var_dump($_SESSION); ?>
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
                    foreach ($_SESSION['items'] as $item) {
                        $result = mysqli_query($conn,"SELECT * FROM catalog WHERE id = $item['itemId']");
                        while($item = mysqli_fetch_array($result)) 
                        {
                            $itemTitle = $item['title'];
                            $itemPrice = $item['price'];
                        } ?>
                        <input type="hidden" id="itemPrice" name="itemPrice" value="<?php echo $itemPrice; ?>">
                        <div class="row checkout">
                            <div class="col-xs-12"> 
                                <div class="row">
                                    <div class="col-xs-3"><?php echo $itemTitle; ?></div>
                                    <div class="col-xs-3"><?php echo $itemPrice; ?></div>
                                </div>
                            </div>
                            
                        </div>

                <?php   } 
                    }?>

                <div class="row checkout">
                    <div class="col-xs-8 col-md-6 col-md-push-2">
                        Total:
                    </div>
                    <div class="col-xs-4">
                        $ <span id="total"></span>
                    </div>
                </div>
            </form>

            
        </div>
    </section>

    <?php
    include('footer.php');
    ?>

    <script type="text/javascript">
        

    </script>