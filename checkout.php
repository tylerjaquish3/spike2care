<?php
	$currentPage = 'Checkout';
	include('header.php');

    if (isset($_GET['eventId'])) {
    	$eventId = $_GET['eventId'];
    }
   
    $teamId = '';
    if (isset($_GET['teamId']) && $_GET['teamId'] != '') {
        $teamId = $_GET['teamId'];
    }

    $paidBy = '';
    if (isset($_SESSION['newPersonId']) && $_SESSION['newPersonId'] != '') {
        $paidBy = $_SESSION['newPersonId'];
    }

    $specialEvent = false;
    if (isset($_GET['specialEventId'])) {
        $specialEvent = true;
        $result = mysqli_query($conn,"SELECT * FROM events WHERE id = ".$_GET['specialEventId']);
        while($row = mysqli_fetch_array($result)) 
        {
            $eventId = $row['id'];
        }
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

            <form action="includes/handleForm.php" method="POST" id="checkout-form">
                <input type="hidden" id="stripeToken" name="stripeToken">
                <input type="hidden" id="paidBy" name="paidBy" value="<?php echo $paidBy; ?>">
                <input type="hidden" id="totalDonation" name="totalDonation">
                <input type="hidden" id="totalAmount" name="totalAmount">

                <?php if (isset($eventId)) { ?>
                    <input type="hidden" id="eventId" name="event_id" value="<?php echo $eventId; ?>">
                    <div class="row">
                        <?php if ($specialEvent) { ?>
                            <div class="col-xs-12 col-md-6">
                                Registration is not complete until paid. Choose a desired quantity, add a donation (optional), and don't forget to check the box for covering the processing fee (also optional), which will auto update based on your subtotal and donation.
                            </div>
                            <div class="col-xs-12 col-md-6">
                                Your privacy and security is 100% guaranteed. <a href="privacy.php">View privacy statement.</a>
                                <br /> 
                                We issue full refunds for events, up to 30 days before the event date. <a href="refundPolicy.php">View refund policy.</a>
                            </div>
                        <?php } elseif ($teamId) { ?>
                            <div class="col-xs-12 col-md-6">
                                Team registration is not complete until at least one player has paid. Choose the players to pay for, add a donation, and don't forget to check the box for covering the processing fee, which will auto update based on your subtotal and donation.
                            </div>
                            <div class="col-xs-12 col-md-6">
                                Your privacy and security is 100% guaranteed. <a href="privacy.php">View privacy statement.</a>
                                <br /> 
                                We issue full refunds for events, up to 5 days before the event date. <a href="refundPolicy.php">View refund policy.</a>
                            </div>
                        <?php } ?>
                        
                    </div>
                    <div class="row checkout-item">
                        <div class="col-xs-12">
                            Event Registration
                        </div>
                    </div>
                    <?php 
                    $result = mysqli_query($conn,"SELECT * FROM events WHERE id = $eventId ORDER BY event_date DESC");
                    while($event = mysqli_fetch_array($result)) 
                    {
                    ?>
                        <input type="hidden" name="specialEvent" value="<?php echo $specialEvent; ?>">
                        <input type="hidden" id="eventPrice" name="eventPrice" value="<?php echo $event['price']; ?>">
                        <div class="row checkout" id="event-form">
                            <div class="col-xs-12">
                                <?php 
                                if ($teamId == '' && !$specialEvent) { ?>
                                    <div class="col-xs-12 text-center">
                                        <p>You have been successfully added to the free agent list. Free agent registration payment is not required at this time, but donations are always welcome.</p>
                                    </div>
                                <?php } else { ?>
                                    <input type="hidden" id="teamId" name="team_id" value="<?php echo $teamId; ?>">
                                    <input type="hidden" id="team-players" value="<?php echo $event['team_players']; ?>">

                                    <div class="row">
                                        <div class="col-xs-6 col-md-6 col-md-push-2">
                                            Entry Fee:
                                        </div>
                                        <div class="col-xs-6">
                                            $ <span id="player-fee"><?php echo $event['price']; ?></span>
                                        </div>
                                    </div>
                                    <?php if ($specialEvent) { ?>
                                        <div class="row">
                                            <div class="col-xs-4 col-md-6 col-md-push-2">
                                                Quantity: 
                                            </div>
                                            <div class="col-xs-8 col-md-4">
                                                <input type="number" class="form-control" name="quantity" id="quantity">
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="row">
                                            <div class="col-xs-4 col-md-6 col-md-push-2">
                                                Paying for: 
                                            </div>
                                            <div class="col-xs-8 col-md-4">
                                                <select id="paying-for" name="players_paid[]" class="form-control" multiple="multiple">></select>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="row">
                                        <div class="col-xs-6 col-md-6 col-md-push-2">
                                            Subtotal: 
                                        </div>
                                        <div class="col-xs-6">
                                            <span id="amount"></span>
                                        </div>
                                    </div>

                                <?php } ?>
                                
                            </div>
                        </div>

                <?php }
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
                        <div class="col-xs-12">
                            <div class="row checkout">
                                <div class="col-xs-3 col-md-6 col-md-push-2">
                                    Donation:
                                </div>
                                <div class="col-xs-9 col-md-6">
                                    <input type="text" id="donation" name="donation" class="input-block-level" placeholder="Amount">
                                    <span class="full" id="msg_donation"></span>
                                </div>
                            </div>
                            <div class="row checkout">
                                <div class="col-xs-3 col-md-6 col-md-push-2">
                                    Choose a specific cause (optional):
                                </div>
                                <div class="col-xs-9 col-md-6">
                                    <select id="causes" name="cause[]" class="form-control">
                                        <option selected value="0">S2C General Fund</option>
                                    </select>
                                    <br />
                                    <span id="msg_cause"></span>
                                </div>
                            </div>
                            <div class="row">
                                <?php if (!isset($eventId)) { ?>
                                    <div class="col-xs-12 col-md-4">
                                        <input type="text" name="full_name" id="full_name" class="input-block-level" placeholder="Full name">
                                        <span id="error_msg1" class="full" style="display:none;">Required</span>
                                    </div>
                                    <div class="col-xs-12 col-md-4">
                                        <input type="text" name="phone" id="phone" class="input-block-level" placeholder="Phone">
                                        <span id="error_msg2" class="full" style="display:none;">Required</span>
                                    </div>
                                    <div class="col-xs-12 col-md-4">
                                        <input type="text" name="email" id="email" class="input-block-level" placeholder="Email">
                                        <span id="error_msg3" class="full" style="display:none;">Required</span>
                                    </div>

                                    <input type="hidden" name="new-donation-person" value="1">
                                <?php } ?>
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

        $(document).ready(function() {
            var eventId = $('#eventId').val();
            if (eventId) {
                updateEventAmount();
                updateProcessingFee();
                updateTotal();
            } else {
                $('#donation-form-div').show();
            }
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

        var teamId = $('#teamId').val();

        if (teamId) {
            $.ajax({
                url: 'includes/handleForm.php',
                type: 'GET',
                dataType: 'json',
                data: {
                    'teamId': teamId,
                    'paid': 0
                },
                complete: function(data){
                    players = $.parseJSON(data.responseText);
                   
                    $('#paying-for').select2({
                        data: players,
                        placeholder: 'Select players'
                    });
                }
            });
        }

        // User selects people they are paying for
        $("#paying-for").on("select2:select select2:unselect", function (e) {
            updateEventAmount();
            updateProcessingFee();
            updateTotal();

            playersPaid = 0;

            if (payingFor) {
                playersPaid = payingFor.length;
            }
            $('#players-paid').val(playersPaid);
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

        $('#quantity').focusout(function () {
            updateEventAmount();
            updateProcessingFee();
            updateTotal();
        });

        $('#donation').focusout(function () {
            var donation = $('#donation').val();

            if (isNaN(donation)) {
                $('#msg_donation').html('Please enter a valid amount');
                $('#processing-fee').html('');
            } else {
                $('#msg_donation').html('');
                updateProcessingFee();
                updateTotal();
            }
        });

        $('#pay').click(function () {
            eventId = $('#eventId').val();
            specialEvent = "<?php echo $specialEvent; ?>";
            eventAmount = 0;
            freeAgentDonation = false;
            if (eventId && specialEvent) {
                eventAmount = getEventAmount();
            } else if (eventId) {
                if ("<?php echo $teamId; ?>" == '') {
                    freeAgentDonation = true;
                } else {
                    eventAmount = getEventAmount();
                }
            } else {
                donationFieldsValid = validateDonationValues();
            }

            if (eventId || donationFieldsValid || freeAgentDonation) {
                subtotal = parseFloat(eventAmount);
                donation = 0;

                if ($('#donation').val().length > 0) {
                    donation = $('#donation').val();
                    subtotal = subtotal + parseFloat(donation);
                }

                if ((subtotal == 0 || isNaN(subtotal)) && eventId && "<?php echo $teamId; ?>" != '') {
                    addAlertToPage('danger', 'Error', 'Please select players to pay for before paying.', 5);
                } else if ((subtotal == 0 || isNaN(subtotal)) && "<?php echo $teamId; ?>" == '') {
                    addAlertToPage('danger', 'Error', 'Please add a donation before paying.', 5);
                } else {
                    processingFee = updateProcessingFee();
                    total = subtotal;
                    if ($('#process-fee').is(':checked')) {
                        total = subtotal + processingFee;
                        $('input#totalDonation').val(((parseFloat(donation) + parseFloat(processingFee)) * 100).toFixed(0));
                    } else {
                        $('input#totalDonation').val((parseFloat(donation) * 100).toFixed(0));
                    }
                    
                    chargeAmount = parseFloat(total.toFixed(2));
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
                            $('#checkout-form').submit();
                        }
                    });

                    handler.open({
                        amount: Math.round(chargeAmount)
                    });
                }
            }
        });

        function updateEventAmount()
        {
            playerFee = $('#player-fee').html();
            payingFor = $('#paying-for').val();
            playersPaid = 0;

            if (payingFor) {
                playersPaid = payingFor.length;
            } else if ($('#quantity').val()) {
                playersPaid = $('#quantity').val();
            } else {
                playersPaid = 0;
            }

            eventAmount = playerFee * playersPaid;

            $('#amount').html('$ '+eventAmount.toFixed(2));
        }

        function updateProcessingFee()
        {
            eventAmount = getEventAmount();
            subtotal = parseFloat(eventAmount);
            donation = 0;

            if (isNaN(subtotal)) {
                subtotal = 0;
            }

            if ($('#donation').val().length > 0) {
                donation = $('#donation').val();
                subtotal = subtotal + parseFloat(donation);
            }

            processingFee = (subtotal * .029) + .30;
            if (isNaN(processingFee)) {
                processingFee = 0;
            }
            $('#processing-fee').html('$ '+processingFee.toFixed(2));

            return processingFee;
        }

        function getEventAmount()
        {
            eventId = $('#eventId').val();
            eventAmount = playersPaid = 0;
            if (eventId) {
                playerFee = $('#player-fee').html();
                payingFor = $("#paying-for").val();

                if (payingFor) {
                    playersPaid = payingFor.length;
                } else {
                    playersPaid = $('#quantity').val();
                }

                eventAmount = playerFee * playersPaid;
            }

            return parseFloat(eventAmount);
        }

        function updateTotal()
        {
            eventAmount = getEventAmount();
            subtotal = parseFloat(eventAmount);
            donation = 0;

            if ($('#donation').val().length > 0) {
                donation = $('#donation').val();
                subtotal = subtotal + parseFloat(donation);
            }
            
            processingFee = updateProcessingFee();

            total = subtotal;
            if ($('#process-fee').is(':checked')) {
                total = subtotal + processingFee;
            }

            if (isNaN(total)) {
                total = 0;
            }
            
            $('#total').html(total.toFixed(2));
        }

        function validateDonationValues()
        {
            response = true;
            $('#error_msg1').hide();
            $('#error_msg2').hide();
            $('#error_msg3').hide();

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

            return response;
        }

    </script>