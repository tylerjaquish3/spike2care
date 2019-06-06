<?php
$currentPage = 'Events';
include('header.php');

$message = '';
if ($_GET) {
    $eventId = $_GET['eventId'];

    if (isset($_GET['message']) && $_GET['message'] == 'success') {
        $message = 'success';
    }
} else {
    die('<script type="text/javascript">window.location.href="404.php";</script>');
}
?>

    <section class="title">
        <div class="container">
            <div class="row-fluid">
                <div class="span6">
                    <h1>Events</h1>
                </div>
            </div>
        </div>
    </section>

    <?php
    $sql = $conn->prepare("SELECT * FROM events WHERE id = ?");
    $sql->bind_param('i', $eventId);
    $sql->execute();

    $result = $sql->get_result();

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) { ?>
            <section class="services">
                <div class="container">
                    <div class="total-event-card">
                        
                        <div class="row">
                            <div class="col-xs-12">
                                <h2><?php echo $row['title']; ?></h2>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <img class="special-event-img" src="images/events/<?php echo $row['image_path']; ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-8 col-md-push-2">
                                <div class="format">
                                    <h4><?php echo date_create($row['event_date'])->format('D, M j'); ?></h4>
                                    <h3><?php echo $row['location']; ?> <small><?php echo $row['address'].', '.$row['city']; ?></small></h3>
                                    <h4> $ <?php echo $row['price']; ?> <?php echo ($row['price_for'] != "na") ? "<small>(".$row['price_for'].")</small>" : "-"; ?> <?php echo 'Starts at '.$row['play_time']; ?></h4>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-4">
                                <?php
                                $today = date('Y-m-d H:i:s');
                                
                                if ($row['registration_open'] && strtotime($today) <= strtotime($row['registration_deadline'])) { ?>
                                    <a class="btn btn-primary btn-large" href="register.php?id=<?php echo $row['id']; ?>">Register</a>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="format">
                                    <?php if ($row['description']) {
                                        echo '<h3>Details</h3>';
                                        echo '<p>'.$row['description'].'</p>';
                                    } ?>

                                    <?php if ($row['additional_info']) {
                                        echo '<h3>Additional Info</h3>';
                                        echo '<p>'.$row['additional_info'].'</p>';
                                    } 
                                    $deadline = strtotime($row['registration_deadline']);
                                    ?>

                                    <h4>Registration Deadline: <?php echo date('D, M j 11:59', $deadline).'pm'; ?></h4>

                                    <?php if ($row['fb_link']) {
                                        $fbLink = $row['fb_link'];
                                        if (substr(strtolower($row['fb_link']), 0, 4) != "http") {
                                            $fbLink = 'http://'.$row['fb_link'];
                                        } 
                                        echo '<p><a href="'.$fbLink.'">Link to Facebook Event</a></p>';
                                    } ?>
                        
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br /><br /><br />
            </section>               

    <?php } 
    } ?>
            
<?php
include('footer.php');
?> 

<script type="text/javascript" src="js/full_sparkle.js"></script>

<script type="text/javascript">
    if ("<?php echo $message; ?>" == 'success') {
        addAlertToPage('success', 'Success', 'Your registration was successful!', 0);
    }
</script>