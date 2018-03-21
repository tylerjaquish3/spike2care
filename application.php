
<?php
$currentPage = 'Assistance';
include('header.php');
?>

    <section class="title">
        <div class="container">
            <div class="row-fluid">
                <div class="span6">
                    <h1>Application for Financial Assistance</h1>
                </div>
            </div>
        </div>
    </section>

    <section id="about-us" class="container main">
        
        <h3 class="center">Confidential Application</h3>
        <p>
            Spike2Care will accept all applications for individuals associated with the local volleyball community including household members of an individual associated with the volleyball community. An individual may also nominate themselves. Each application will be reviewed closely by the Spike2Care Distribution of Benefits Committee and will ultimately be approved or denied by the Spike2Care Executive Board. A member of the Spike2Care Distribution of Benefits Committee may contact the nominee or nominator to discuss the application or gather further information. The amount of the funds allocated is dependent upon the number of applicants and the amount available for distribution. Within 72 hours, Spike2Care will send a confirmation email that your application has been received. 
        </p>

        <h4>Attention Juniors!</h4>

        <p>Spike2Care is not accepting applications for assistance to pay for club fees. We are donating money to Inland Northwest Klassic (INK) who will review the applications and choose the recipients on our behalf. Please <a href="https://www.inkvolleyball.com/scholarships/">visit the INK website</a> for more information. 

        <hr>

        <div class="row">
            <div class="col-xs-12">
                <form name="assistance_application" id="assistance_application" action="#" method="POST" enctype="multipart/form-data">

                    <div class="row">
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <label for="nominator_full_name">Name of Nominator</label>
                                <input type="text" class="form-control" required name="nominator_full_name" placeholder="Full Name">
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <label for="nominator_phone">Phone #</label>
                                <input type="text" class="form-control" required name="nominator_phone" placeholder="xxx-xxx-xxxx">
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <label for="nominator_email">Email</label>
                                <input type="email" class="form-control" required name="nominator_email" aria-describedby="emailHelp" placeholder="Enter email">
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label for="nominee_full_name">Nominee</label>
                                <input type="text" class="form-control" required name="nominee_full_name" placeholder="Full Name">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-3">
                            <div class="form-group">
                                <label for="nominee_address">Mailing Address</label>
                                <input type="text" class="form-control" name="nominee_address" placeholder="Full Name">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-3">
                            <div class="form-group">
                                <label for="nominee_city">City</label>
                                <input type="text" class="form-control" name="nominee_city" placeholder="Full Name">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-3">
                            <div class="form-group">
                                <label for="nominee_state">State</label>
                                <select class="form-control" name="nominee_state">
                                    <?php loadStates(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-3">
                            <div class="form-group">
                                <label for="nominee_zip">Zip</label>
                                <input type="text" class="form-control" name="nominee_zip" placeholder="Full Name">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label for="nominee_phone">Phone #</label>
                                <input type="text" class="form-control" required name="nominee_phone" placeholder="xxx-xxx-xxxx">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label for="nominee_email">Email</label>
                                <input type="email" class="form-control" required name="nominee_email" aria-describedby="emailHelp" placeholder="Enter email">
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label for="volleyball_association">Please describe how the nominee is associated with the volleyball community</label>
                                <textarea class="form-control" required name="volleyball_association" rows="3"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label for="circumstances">Please describe the nominee's circumstances and specific financial need</label>
                                <textarea class="form-control" required name="circumstances" rows="3"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label for="amount_requested">Amount Requested</label>
                                <input type="text" class="form-control" required name="amount_requested" placeholder="$">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label for="requested_date">Requested date to have the funds by</label>
                                <div class='input-group date' id='datetimepicker1'>
                                    <input type='text' class="form-control" name="requested_date" placeholder="Date" />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div> 

                    <hr>

                    <div class="row">
                        <div class="col-xs-12">
                            <ul>
                                <li>You may attach additional pages to this application for use by the Distribution of Benefits committee to better understand the specific need.</li>
                                <li>I understand that acceptance of an application does not guarantee a funds allocation.</li>
                                <li>I understand the conditions of this funds allocation program and would like the nominated to be considered for the amount requested.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label for="attachment_path">Attachment</label>
                                <input type="file" class="form-control-file" name="attachment_path" aria-describedby="fileHelp">
                                <small id="fileHelp" class="form-text text-muted">Only doc, docx, pdf and txt formats are acceptable.</small>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label for="signature_path">Nominator Signature</label>
                                <div id="signature-pad" class="signature-pad">
                                    <div class="signature-pad--body">
                                        <canvas id="signatureCanvas"></canvas>
                                    </div>
                                    <div class="signature-pad--footer">
                                        <div class="description">Sign your name using your cursor/finger in the box above</div>

                                        <div class="signature-pad--actions">
                                            <div>
                                                <button type="button" class="button clear" data-action="clear">Clear</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label for="signed_date">Date</label>
                                <div class='input-group date' id='datetimepicker2'>
                                    <input type='text' class="form-control" name="signed_date" placeholder="Date" />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row format">
                        <div class="col-xs-12">
                            <button type="submit" name="assistance-application" class="btn btn-primary">Submit</button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </section>   

    <?php
    include('footer.php');
    ?>

    <script type="text/javascript" src="js/full_sparkle.js"></script>
    <script type="text/javascript" src="js/moment.min.js"></script>
    <script type="text/javascript" src="js/bootstrap-datetimepicker.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>

    <script type="text/javascript">

        var wrapper = document.getElementById("signature-pad");
        var clearButton = wrapper.querySelector("[data-action=clear]");
        var savePNGButton = wrapper.querySelector("[data-action=save-png]");
        var canvas = wrapper.querySelector("canvas");
        var signaturePad = new SignaturePad(canvas, {
            // It's Necessary to use an opaque color when saving image as JPEG;
            // this option can be omitted if only saving as PNG or SVG
            backgroundColor: 'rgb(255, 255, 255)'
        });

        // Adjust canvas coordinate space taking into account pixel ratio,
        // to make it look crisp on mobile devices.
        // This also causes canvas to be cleared.
        function resizeCanvas() {
            // When zoomed out to less than 100%, for some very strange reason,
            // some browsers report devicePixelRatio as less than 1
            // and only part of the canvas is cleared then.
            var ratio =  Math.max(window.devicePixelRatio || 1, 1);

            // This part causes the canvas to be cleared
            // canvas.width = canvas.offsetWidth * ratio;
            // canvas.height = canvas.offsetHeight * ratio;
            canvas.width = 350;
            canvas.height = 150;
            // canvas.getContext("2d").scale(ratio, ratio);

            // This library does not listen for canvas changes, so after the canvas is automatically
            // cleared by the browser, SignaturePad#isEmpty might still return false, even though the
            // canvas looks empty, because the internal data of this library wasn't cleared. To make sure
            // that the state of this library is consistent with visual state of the canvas, you
            // have to clear it manually.
            signaturePad.clear();
        }

        // On mobile devices it might make more sense to listen to orientation change,
        // rather than window resize events.
        window.onresize = resizeCanvas;
        resizeCanvas();

        clearButton.addEventListener("click", function (event) {
          signaturePad.clear();
        });

        $('#datetimepicker1').datetimepicker({
            format: 'MM/DD/YYYY'
        });

        $('#datetimepicker2').datetimepicker({
            format: 'MM/DD/YYYY'
        });

        $('#assistance_application').submit(function (e) {
            var formData = new FormData($(this)[0]);
            var base64img = signaturePad.toDataURL();   

            formData.append('img', base64img);

            if (signaturePad.isEmpty()) {
                addAlertToPage('error', 'Error', 'Please provide a signature', 5);
            } else {

                $.ajax({
                    url: 'sendapplication.php',
                    type: "POST",
                    data: formData,
                    async: false,
                    dataType: 'json',
                    success: function (response) {
                        if (response.type == 'error') {
                            addAlertToPage('error', 'Error', response.message, 10);
                        } else {
                            addAlertToPage('success', 'Success', 'Your application was sent!', 10);
                            $("#assistance_application :input").each(function(){
                                $(this).val('');
                                signaturePad.clear();
                            });
                        }
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });
            }

            e.preventDefault();
        });

    </script>


</body>
</html>
