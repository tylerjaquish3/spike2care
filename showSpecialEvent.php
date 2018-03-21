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
    $result = mysqli_query($conn,"SELECT * FROM events WHERE id = ".$eventId);
    if (mysqli_num_rows($result) == 1) {
        while($row = mysqli_fetch_array($result)) {
    ?>
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
                            <div class="col-xs-12 col-md-10 col-md-push-1">
                                <div class="format">
                                    <h4><?php echo date_create($row['event_date'])->format('D, M j'); ?></h4>
                                    <h3><?php echo $row['location']; ?> <small><?php echo $row['address'].', '.$row['city']; ?></small></h3>
                                    <h4> $ <?php echo $row['price']; ?> <small>(per person)</small> <?php echo 'Starts at '.$row['play_time']; ?></h4>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="format">
                                    <h3>Details</h3>
                                    <p><?php echo $row['description']; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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