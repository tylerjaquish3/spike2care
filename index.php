<?php

$currentPage = 'Home';
include('header.php');

$message = '';

if (isset($_GET['message']) && $_GET['message'] == 'thankyou') {
    $message = "Thank you for your donation to Spike2Care!";
}

if (isset($_GET['message']) && $_GET['message'] == 'shopthankyou') {
    $message = "Your order has been placed. Thank you for your support of Spike2Care!";
}

$content = mysqli_query($conn,"SELECT * FROM content");
if (mysqli_num_rows($content) > 0) {
    while($row = mysqli_fetch_array($content)) {
        if ($row['context'] == 'mission_statement') { 
            $mission = $row['content_text']; 
        } else if ($row['context'] == 'about_s2c') {
            $about = $row['content_text'];
        }
    }
}
     
?>

<div id="banner" class="skewed-div">
    <img src="images/basic-banner-blue-min.jpg">
</div>

<section class="main-info">
    <div class="skewed-div"></div>
    <div class="container">
        <div class="row-fluid">
            <div class="col-xs-12 col-sm-10">
                <h3>Doing what we love, helping those we love</h3><br />
                <h4><?php echo $mission; ?></h4>
            </div>
            <div class="col-xs-12 col-sm-2 center">
                <a class="btn btn-primary btn-large" href="checkout.php">Donate Now!</a>
            </div>
        </div>
    </div>
</section>

<section id="events">
    <div class="container">
        <div class="row">
            <div class="center">
                <h3>Upcoming Events</h3>
            </div>
        </div>
        <div class="row">
            <?php
            $today = date('Y-m-d 00:00:00');
            $result = mysqli_query($conn,"SELECT * FROM events WHERE is_active = 1 AND special_event = 1 AND event_date >= '".$today."' ORDER BY event_date ASC LIMIT 1");
                if (mysqli_num_rows($result) == 1) {
                    while($row = mysqli_fetch_array($result)) {
                    ?>
                    <div class="col-xs-12">
                        <a href="showSpecialEvent.php?eventId=<?php echo $row['id']; ?>">
                            <div class="special-event" style="background-image: url('images/events/<?php echo $row['image_path']; ?>')"></div>    
                        </a>
                    </div>
                    </div>
                    <div class="row">
            <?php
                }
            }

            $today = date('Y-m-d 00:00:00');
            $result = mysqli_query($conn,"SELECT * FROM events WHERE is_active = 1 AND special_event = 0 AND event_date >= '".$today."' ORDER BY event_date ASC LIMIT 4");
            if (mysqli_num_rows($result) == 0) {
                ?>
                <div class="col-xs-12 col-md-6 col-md-push-3">
                    <div class="empty-event-card">
                        <div class="row">
                            <div class="col-xs-12 center">
                                <br />
                                <h3>There are currently no upcoming tournaments, but please check back soon for the latest information regarding tournaments and events!</h3>
                            </div>
                        </div>
                    </div>
                </div>
                
            <?php
            } elseif (mysqli_num_rows($result) == 1) {
                showCards('<div class="col-xs-12 col-md-6 col-md-push-3">', $result, $conn);
            } else {
                showCards('<div class="col-md-6 col-xs-12 down20">', $result, $conn);
            } 

            function showCards($div, $result, $conn)
            {
                while($row = mysqli_fetch_array($result)) 
                {
                    echo $div;?>
                        <div class="event-card">
                            <div class="row">
                                <div class="col-xs-12">
                                    <h2><?php echo $row['title']; ?></h2>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-8 col-md-6">
                                    <?php 
                                    if ($row['image_path'] != "") { ?>
                                        <div class="center-cropped" style="background-image: url('images/events/<?php echo $row['image_path']; ?>')"></div>
                                    <?php
                                    } else { ?>
                                        <div class="center-cropped" style="background-image: url('images/noImage.png')"></div>
                                    <?php
                                    } ?>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-6 format">

                                    <div class="pull-right">
                                        <a class="btn btn-view btn-large" id="view-register" href="showEvent.php?eventId=<?php echo $row['id']; ?>">View</a><br />
                                        <?php 
                                        $today = date('Y-m-d H:i:s');
                                        // $wedBefore = strtotime(date('Y-m-d 23:59:59', strtotime('previous wednesday', strtotime($row['event_date']))));
                                        if ($row['registration_open'] && strtotime($today) <= strtotime($row['registration_deadline'])) { ?>
                                            <a class="btn btn-primary btn-large pull-right" id="view-register" href="register.php?id=<?php echo $row['id']; ?>">Register</a>
                                        <?php } ?>

                                    </div>
                                    
                                    <h3><?php echo date_create($row['event_date'])->format('D, M j'); ?></h3>
                                    <h3><?php echo $row['location']; ?></h3>
                                    <h4>$ <?php echo $row['price']; ?> <small>(<?php echo $row['price_for']; ?>)</small></h4>

                                    <?php
                                    $teams = mysqli_query($conn,"SELECT *, count(*) as num_teams FROM teams WHERE is_active = 1 AND event_id = ".$row['id']." GROUP BY id");
                                    if (mysqli_num_rows($teams) == 1) {
                                        while($team = mysqli_fetch_array($teams)) { ?>
                                            <h4><small><?php echo $team['num_teams'].'/'.$row['max_teams']; ?> spots filled</small></h4>
                                        <?php 
                                        } 
                                    } ?>
                                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="format">
                                        <h4>Format</h4>
                                        <p><?php echo $row['format']; ?></p>
                                    
                                        <h4>Details</h4>
                                        <p>
                                            <?php 
                                            if (strlen($row['description']) > 275) {
                                                echo substr($row['description'], 0, 240).'...';
                                                echo '<a href="showEvent.php?eventId='.$row['id'].'">See more</a>';
                                            } else {
                                                echo $row['description']; 
                                            }
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php 
                }  
            } ?>
            
        </div>
        <div class="row">
            <div class="center">
                <br />
                <p class="lead"><a href="events.php" class="btn btn-primary">View Full Calendar</a></p>
            </div>
        </div>
    </div>

</section>

<!--Services-->
<section id="services">
    <div class="container">
        <div class="center gap">
            <h3>About Spike2Care</h3>
            <div class="quote">
                <?php echo $about; ?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="media">
                    <div class="pull-left">
                        <i class="fa fa-clock-o icon-medium"></i>
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">Events</h4>
                        <p>Spike2Care holds an average of two adult tournaments per month, plus a fun quarterly community event to involve friends and family.</p>
                    </div>
                </div>
            </div>
        
            <div class="col-xs-12 col-sm-6 col-md-3">       

                <div class="media">
                    <div class="pull-left">
                        <i class="fa fa-child icon-medium"></i>
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">Merchandise</h4>
                        <p>This is where fashion envy is born! Jump on in, get your gear on, and get out there and play ball! Be sure to snap lots of photos because we want to see S2C gear around the world!</p>
                    </div>
                </div>
            </div>     

            <div class="col-xs-12 col-sm-6 col-md-3">   
                <div class="media">
                    <div class="pull-left">
                        <i class="fa fa-life-bouy icon-medium squeeze"></i>
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">Assistance</h4>
                        <p>Spike2Care accepts applications for persons nominated within our volleyball community. Do you or someone you know need financial assistance? Submit an application today.</p>
                    </div>
                </div>
            </div>
        
            <div class="col-xs-12 col-sm-6 col-md-3">   

                <div class="media">
                    <div class="pull-left">
                        <i class="fa fa-shopping-bag icon-medium squeeze"></i>
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">Volunteer</h4>
                        <p>Volunteers Wanted: No pay, no benefits, but a huge return - it makes you feel all warm and cozy inside. Contact us now!</p>
                    </div>
                </div>
            </div>            

        </div>

        <div class="row">
            <div class="center">
                <br />
                <p class="lead"><a href="application.php" class="btn btn-primary">Apply for Assistance</a></p>
            </div>
        </div>

    </div>
</section>
<!--/Services-->

<section id="photos">
    <div class="container">
        <div class="row desktop">
            <div class="col-xs-12">
                <!-- LightWidget WIDGET -->
                <script src="//lightwidget.com/widgets/lightwidget.js"></script><iframe src="//lightwidget.com/widgets/42c6f56da7fe5887a638be8a592f893c.html" scrolling="no" allowtransparency="true" class="lightwidget-widget" style="width: 100%; border: 0; overflow: hidden;"></iframe>
            </div>
        </div>
        <div class="row mobile">
            <div class="col-xs-12">
                <!-- LightWidget WIDGET -->
                <script src="//lightwidget.com/widgets/lightwidget.js"></script><iframe src="//lightwidget.com/widgets/d7ff8f87ca7e56e6895a3f35150dd051.html" scrolling="no" allowtransparency="true" class="lightwidget-widget" style="width: 100%; border: 0; overflow: hidden;"></iframe>
            </div>
        </div>
        
        <div class="row">
            <div class="col-xs-12 center">
                <p class="lead"><a href="photos.php" class="btn btn-primary">View More</a></p>
            </div>
        </div>

    </div>

</section>

<section id="testimonials">
    <div class="container">
        <div class="row">
            <h3 class="center">Testimonials</h3>
        </div>
        <div class="row">
            <?php
            $today = date('Y-m-d 00:00:00');
            $result = mysqli_query($conn,"SELECT * FROM testimonials JOIN people ON testimonials.user_id = people.id WHERE is_active = 1 ORDER BY testimonials.created_at ASC LIMIT 5");
            if (mysqli_num_rows($result) == 0) {
                ?>
                <div class="col-xs-12 col-md-6 col-md-push-3">
                    <div class="empty-event-card">
                        <div class="row">
                            <div class="col-xs-12 center">
                                <br />
                                <h3>There are currently no testimonials written, but please check back soon!</h3>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php
            } else { ?>
                
                <div class="container"> 
                    <div id="myCarousel" class="carousel slide" data-ride="carousel" data-interval="20000">

                        <!-- Wrapper for slides -->
                        <div class="carousel-inner">
                            <?php
                            $activeClass = 'active';
                            while($row = mysqli_fetch_array($result)) 
                            {
                            ?>
                                <div class="item <?php echo $activeClass; ?>">
                                    <h4><?php echo $row['testimonial_text']; ?></h4>
                                    <p>- <?php echo $row['full_name']; ?></p>
                                </div>
                            <?php 
                            $activeClass = '';
                            }?>

                        </div>

                        <!-- Left and right controls -->
                        <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                            <span class="glyphicon glyphicon-chevron-left"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="right carousel-control" href="#myCarousel" data-slide="next">
                            <span class="glyphicon glyphicon-chevron-right"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>

            <?php } ?>
            
        </div>
    </div>

</section>

<?php
include('footer.php');
?>

</body>

<script type="text/javascript" src="js/full_sparkle.js"></script>

<script type="text/javascript">
    $('.carousel').carousel();

    var message = "<?php echo $message;?>";
    if (message != "") {
        addAlertToPage('success', 'Thank You', message, 0);
    }
</script>

</html>