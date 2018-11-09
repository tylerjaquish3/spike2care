<?php
	$currentPage = 'Checkout';
	include('header.php');

    // If post is set, the user clicked Buy Now, add the items to session
    if (isset($_POST['itemId'])) {

        if (isset($_SESSION['items'])) {
            array_push($_SESSION['items'], $_POST);
        } else {
            $_SESSION['items'][] = $_POST;
        }

        // Calculate total
        $result = mysqli_query($conn,"SELECT * FROM catalog WHERE id = ".$_POST['itemId']);
        while($item = mysqli_fetch_array($result)) 
        {
            $itemPrice = $item['price'];
        } 
        if (isset($_SESSION['total']) && $_SESSION['total'] != 0) {
            $total = $_SESSION['total'] + ($_POST['quantity'] * $itemPrice);
        } else {
            $total = $_POST['quantity'] * $itemPrice;
        }
        
        $_SESSION['total'] = $total;
    }

    if (isset($_SESSION['total'])) {
        $total = $_SESSION['total'];
    }
?>

	<section class="title">
        <div class="container">
            <div class="row-fluid">
                <div class="span6">
                    <h1>Check Out</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="services">
        <div class="container">

            <form action="includes/handleForm.php" method="POST" id="shopCheckout-form">
                <input type="hidden" id="stripeToken" name="stripeToken">
                <input type="hidden" id="totalDonation" name="totalDonation">
                <input type="hidden" id="totalAmount" name="totalAmount">
                <input type="hidden" name="merchandise" value="true">

                <?php 
                if (isset($_SESSION['total'])) { ?>
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            Finish your purchase, choose your preferred shipping method, add a donation, and don't forget to check the box for covering the processing fee, which will auto update based on your subtotal and donation.
                        </div>
                        <div class="col-xs-12 col-md-6">
                            Your privacy and security is 100% guaranteed. <a href="privacy.php">View privacy statement.</a>
                        </div>
                    </div>
                    <div class="row checkout-item">
                        <div class="col-xs-12">
                            Purchase Merchandise
                        </div>
                    </div>
                    
                    <div class="row checkout">
                        <div class="col-xs-12 col-md-6"> 
                            
                            <div class="row">
                                <div class="col-xs-4">Name:</div>
                                <div class="col-xs-8">
                                    <input type="text" class="form-control" name="full_name" id="full_name">
                                    <span id="error_msg1" class="full" style="display:none;">Required</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-4">Phone:</div>
                                <div class="col-xs-8">
                                    <input type="text" class="form-control" name="phone" id="phone">
                                    <span id="error_msg2" class="full" style="display:none;">Required</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-4">Email:</div>
                                <div class="col-xs-8">
                                    <input type="text" class="form-control" name="email" id="email">
                                    <span id="error_msg3" class="full" style="display:none;">Required</span>
                                </div>
                            </div>

                            <div id="addressFields" style="display: none;">
                                <div class="row">
                                    <div class="col-xs-4">Address:</div>
                                    <div class="col-xs-8">
                                        <input type="text" class="form-control" name="address" id="address">
                                        <span id="error_msg5" class="full" style="display:none;">Required</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-4">City:</div>
                                    <div class="col-xs-8">
                                        <input type="text" class="form-control" name="city" id="city">
                                        <span id="error_msg6" class="full" style="display:none;">Required</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-4">State:</div>
                                    <div class="col-xs-8">
                                        <input type="text" class="form-control" name="state" id="state">
                                        <span id="error_msg7" class="full" style="display:none;">Required</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-4">Postal Code:</div>
                                    <div class="col-xs-8">
                                        <input type="text" class="form-control" name="zip" id="zip">
                                        <span id="error_msg8" class="full" style="display:none;">Required</span>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <div class="col-xs-12 col-md-6"> 
                            <div class="row">
                                <div class="col-xs-4">Cart Price:</div>
                                <div class="col-xs-8">
                                    $ <span id="player-fee"><?php echo number_format($total, 2); ?></span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-4">Shipping:</div>
                                <div class="col-xs-8">
                                    <input type="radio" name="shipping" value="shipIt"> Ship it ($10.00)
                                    <input type="radio" name="shipping" value="pickUp"> Pick up (Free)
                                    <span id="error_msg4" class="full" style="display:none;"><br />Required</span>
                                </div>
                            </div>   
                        
                            <div class="row">
                                <div class="col-xs-4">Subtotal:</div>
                                <div class="col-xs-8">
                                    <span id="subtotal"></span>
                                </div>
                            </div>

                            <div class="row" style="display: none;" id="pickup-info">
                                <div class="col-xs-12">
                                    <br />Pick up your merchandise from a future Spike2Care event. <br /><a target="_blank" href="events.php">View our calendar</a> to find an event.
                                </div>
                            </div>
                        </div>
                    </div>

                <?php 
                } ?>

                <div class="row checkout-item" id="add-donation">
                    <div class="col-xs-12">
                        + Add a donation
                    </div>
                </div>
              
                <div id="donation-form-div" style="display:none;">
                    <div class="row">
                        <div class="col-xs-12 text-center checkout">
                            <p>Spike2Care thanks you for your donation. All donations are tax deductible and the Spike2Care tax ID # is 47-4545145.</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <div class="row checkout">
                                <div class="col-xs-4">
                                    Donation:
                                </div>
                                <div class="col-xs-8">
                                    <input type="text" id="donation" name="donation" class="input-block-level" placeholder="Amount">
                                    <span class="full" id="msg_donation"></span>
                                </div>
                            </div>
                            <div class="row checkout">
                                <div class="col-xs-4">
                                    Choose a specific cause (optional):
                                </div>
                                <div class="col-xs-8">
                                    <select id="causes" name="cause[]" class="form-control">
                                        <option selected value="0">S2C General Fund</option>
                                    </select>
                                    <br />
                                    <span id="msg_cause"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row checkout-item">
                    <div class="col-xs-12">
                        <input type="checkbox" id="process-fee"> I would also like to donate <span id="processing-fee"></span> to cover the processing fee.
                    </div>
                </div>

                <div class="row checkout">
                    <div class="col-xs-8 col-md-6 col-md-push-2">
                        Total:
                    </div>
                    <div class="col-xs-4">
                        $ <span id="total"></span>
                    </div>
                </div>
            </form>

            <div class="row">
                <div class="col-xs-12 text-center">
                    <form action="includes/handleForm.php" method="POST">
                        <a class="btn btn-primary" class="stripe-button" id="pay">Pay Now</a>

                        <?php 
                            if (IS_DEV) {
                                $key = 'pk_test_l5nLnBo7S9jFLYzqN4H0HZBg';
                            } else {
                                $key = 'pk_live_0PGzT1orM2nv6TGUrQ7KoVoU';
                            }
                        ?>

                        <div style="display:none;">
                            <script
                                src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                data-key="<?php echo $key; ?>"
                                data-name="Spike2Care"
                                data-zip-code="true"
                                data-description="One time payment"
                                data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
                                data-locale="auto">
                            </script>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <?php
    include('footer.php');
    ?>

    <script type="text/javascript" src="js/full_sparkle.js"></script>
    <script src="js/select2.min.js"></script>
    <script src="https://checkout.stripe.com/checkout.js"></script>

    <script type="text/javascript">
        var cartPrice = parseFloat("<?php echo $total; ?>");
        var shippingCost = 0;    
        
        $(document).ready(function() {
            updateProcessingFee();
            subtotal = updateSubtotal();
            updateTotal();
        });

        $.ajax({
            url: 'includes/handleForm.php',
            type: 'GET',
            dataType: 'json',
            data: {
                'getCauses': true
            },
            complete: function(data){
                causes = $.parseJSON(data.responseText);
               
                $('#causes').select2({
                    data: causes,
                    placeholder: 'Select cause',
                    minimumResultsForSearch: -1
                });
            }
        });

        $('input[type=radio][name=shipping]').change(function() {
            if (this.value == 'shipIt') {
                $('#addressFields').show();
                $('#pickup-info').hide();
                shippingCost = 10;
                updateSubtotal();
            } else {
                $('#addressFields').hide();
                $('#pickup-info').show();
                shippingCost = 0;
                updateSubtotal();
            }
            updateProcessingFee();
            updateTotal();
        });


        $('#add-donation').click(function() {
            $('#donation-form-div').fadeToggle();
        });

        $('#process-fee').change(function() {
            updateTotal();
        });

        $('#donation').keypress(function(e){
            if(e.which == 13){
                e.preventDefault();
                $(this).blur();    
            }
        });

        $('#donation').focusout(function () {
            if (isNaN(donation)) {
                $('#msg_donation').html('Please enter a valid amount');
                $('#processing-fee').html('');
            } else {
                $('#msg_donation').html('');
                updateTotal();
            }
        });

        $('#pay').click(function () {
            total = updateTotal();
            customerFieldsValid = validateCustomerValues();
            
            if (total && customerFieldsValid) {
                
                if ((total == 0 || isNaN(total))) {
                    addAlertToPage('danger', 'Error', 'Your total is zero.');
                } else {
                    if ($('#process-fee').is(':checked')) {
                        $('input#totalDonation').val(((parseFloat(donation) + parseFloat(processingFee)) * 100).toFixed(0));
                    } else {
                        $('input#totalDonation').val((parseFloat(donation) * 100).toFixed(0));
                    }
                    
                    chargeAmount = parseFloat(total).toFixed(2);
                    chargeAmount = (chargeAmount * 100).toFixed(0); // Needs to be an integer!
                    $('input#totalAmount').val(chargeAmount);
                    
                    var dev = "<?php echo IS_DEV; ?>";
                    if (dev) { 
                        pk_key = 'pk_test_l5nLnBo7S9jFLYzqN4H0HZBg';
                    } else { 
                        pk_key = 'pk_live_0PGzT1orM2nv6TGUrQ7KoVoU';
                    }

                    var handler = StripeCheckout.configure({
                        key: pk_key,
                        locale: 'auto',
                        name: 'Spike2Care',
                        description: 'One-time payment',
                        token: function(token) {
                            $('input#stripeToken').val(token.id);
                            $('#shopCheckout-form').submit();
                        }
                    });

                    handler.open({
                        amount: Math.round(chargeAmount)
                    });
                }
            }
        });

        function updateSubtotal()
        {
            cartPrice = "<?php echo $total; ?>";
            subtotal = parseFloat(cartPrice) + parseFloat(shippingCost);

            $('#subtotal').html('$ '+subtotal.toFixed(2));

            return subtotal.toFixed(2);
        }

        function getDonationAmount()
        {
            donation = 0;
            if ($('#donation').val().length > 0) {
                donation = $('#donation').val();
            }

            return parseFloat(donation);
        }

        function updateProcessingFee()
        {
            subtotal = parseFloat(updateSubtotal());
            donation = parseFloat(getDonationAmount());
            processingFee = ((subtotal + donation) * .029) + .30;
            if (isNaN(processingFee)) {
                processingFee = 0;
            }
            $('#processing-fee').html('$ '+processingFee.toFixed(2));

            return processingFee;
        }

        function updateTotal()
        {
            processingFee = updateProcessingFee();
            total = parseFloat(updateSubtotal()) + parseFloat(getDonationAmount());
            if ($('#process-fee').is(':checked')) {
                total = parseFloat(total) + parseFloat(processingFee);
            }
            if (isNaN(total)) {
                total = 0;
            }
            $('#total').html(parseFloat(total).toFixed(2));

            return parseFloat(total).toFixed(2);
        }

        function validateCustomerValues()
        {
            response = true;
            $('#error_msg1').hide();
            $('#error_msg2').hide();
            $('#error_msg3').hide();
            $('#error_msg4').hide();
            $('#error_msg5').hide();
            $('#error_msg6').hide();
            $('#error_msg7').hide();
            $('#error_msg8').hide();

            if ($("#full_name").val() == '') {
                $('#error_msg1').show();
                response = false;
            }
            if ($("#phone").val() == '')  {
                $('#error_msg2').show();
                response = false;
            }
            if ($("#email").val() == '')  {
                $('#error_msg3').show();
                response = false;
            }
            if ($('input[name=shipping]:checked').val() == undefined)  {
                $('#error_msg4').show();
                response = false;
            }
            if ($('input[name=shipping]:checked').val() == 'shipIt') {
                if ($("#address").val() == '') {
                    $('#error_msg5').show();
                    response = false;
                }
                if ($("#city").val() == '')  {
                    $('#error_msg6').show();
                    response = false;
                }
                if ($("#state").val() == '')  {
                    $('#error_msg7').show();
                    response = false;
                }
                if ($("#zip").val() == '')  {
                    $('#error_msg8').show();
                    response = false;
                }
            }

            return response;
        }

    </script>