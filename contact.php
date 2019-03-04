
<?php
$currentPage = 'Contact';
include('header.php');
?>

    <section class="no-margin">
        <iframe width="100%" height="200" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d121608.26615827662!2d-117.38378301200183!3d47.66133581189872!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sus!4v1486609342705"></iframe>
    </section>

    <section id="contact-page" class="container">
        <div class="row-fluid">

            <div class="col-xs-12 col-md-6">
                <h4>Contact Form</h4>

                <form id="main-contact-form" class="contact-form" name="contact-form" method="post">
                    <div class="row-fluid">
                        <div class="span5">
                            <label>Full Name</label>
                            <input type="text" class="input-block-level" required name="username">
                            <label>Email Address</label>
                            <input type="email" class="input-block-level" required name="email">
                        </div>
                        <div class="span7">
                            <label>Message</label>
                            <textarea name="message" id="message" required class="input-block-level" rows="8"></textarea>
                        </div>

                    </div>

                    <button type="submit" class="btn btn-primary btn-large pull-right">Send Message</button>

                </form>
            </div>

            <div class="col-xs-12 col-md-6">
                <h4>Contacts</h4>
                
                <p>
                    For general information &amp; financial assistance questions: <br />
                    <i class="fa fa-envelope"></i> &nbsp;<a href="mailto: info@spike2care.org">info@spike2care.org</a>
                </p>
                <p>
                    For information about events and tournaments: <br />
                    <i class="fa fa-envelope"></i> &nbsp;<a href="mailto: events@spike2care.org">events@spike2care.org</a> 
                </p>              
                <p>
                    For fundraising inquiries (including auctions and volunteering): <br />
                    <i class="fa fa-envelope"></i> &nbsp;<a href="mailto: fundraising@spike2care.org">fundraising@spike2care.org</a>
                </p>  
                <p>
                    For marketing inquiries (including merchandise &amp; social media): <br />
                    <i class="fa fa-envelope"></i> &nbsp;<a href="mailto: marketing@spike2care.org">marketing@spike2care.org</a>
                </p>  
                <p>
                    For IT and technical inquiries: <br />
                    <i class="fa fa-envelope"></i> &nbsp;<a href="mailto: itResources@spike2care.org">itResources@spike2care.org</a>
                </p>  

                <p>Mailing Address: <br />
                    7115 N. Division St  <br />
                    Suite B, PMB 277 <br />
                    Spokane, WA 99208

                <p class="social-icons"> 
                    <a href="http://facebook.com/Spike2Care"><i class="fa fa-facebook-square"></i></a>
                    <a href="http://instagram.com/spike2care"><i class="fa fa-instagram"></i></a>
                </p>
            </div>



        </div>

    </section>
    <br /><br /><br /><br />

    <?php
    include('footer.php');
    ?> 

    <script type="text/javascript" src="js/full_sparkle.js"></script>

    <script type="text/javascript">

        $('#main-contact-form').submit(function () {

            var formData = $('#main-contact-form').serialize();
            $.ajax({
                url: 'sendemail.php',
                type: "POST",
                data: formData,
                dataType: 'json',
                complete: function (response) {
                    $("#main-contact-form :input").each(function(){
                        $(this).val('');
                    });
                    addAlertToPage('success', 'success', 'Your message was sent!', 10);
                }
            })
        });

    </script>

</body>
</html>
