
<?php
$currentPage = 'Events';
include('header.php');
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

    <section class="services">
        <div class="container">

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
            ?>

            <div class="row-fluid">
                <div class="page-header">

                    <div class="pull-right form-inline">
                        <div class="btn-group">
                            <button class="btn btn-success" data-calendar-nav="prev"><< Prev</button>
                            <button class="btn btn-default" data-calendar-nav="today">Today</button>
                            <button class="btn btn-success" data-calendar-nav="next">Next >></button>
                        </div>
                    </div>

                    <h3></h3>

                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div id="calendar"></div>
                    </div>
                </div>

                <div class="clearfix"></div>

            </div>
        </div>
    </section>

    <section class="recaps">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 center">
                    <h1>Event Recaps</h1>
                </div> 
            </div>

            <?php
            $recaps = mysqli_query($conn,"SELECT r.id, e.title, e.event_date, r.recap_text, e.id as event_id FROM recaps as r JOIN events as e ON r.event_id = e.id WHERE r.is_active = 1 ORDER BY event_date DESC");
            if (mysqli_num_rows($recaps) > 0) {
                while($row = mysqli_fetch_array($recaps)) 
                {
                    $recapId = $row['id'];
                    $eventId = $row['event_id'];
                ?>
                    <div class="event-recap">
                        <div class="recap-text">
                            <div class="row">
                                <div class="col-xs-12">
                                    <h3><?php echo $row['title']; ?></h3>
                                    <h4><?php echo date_create($row['event_date'])->format('D, M j'); ?></h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <p><?php echo $row['recap_text']; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="row">
                                    <?php
                                    $photos = mysqli_query($conn,"SELECT * FROM photos WHERE event_id = ".$eventId." ORDER BY id DESC LIMIT 4");
                                    while($photo = mysqli_fetch_array($photos)) 
                                    {
                                    ?>
                                    <div class="col-xs-12 col-md-3 frame">
                                        <img src="images/recaps/<?php echo $photo['image_path']; ?>" width="100%">
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="container comment-section">

                        <h4>Comments</h4>
                        <?php
                        $result2 = mysqli_query($conn,"SELECT * FROM recap_comments as rc JOIN recaps as r ON rc.recap_id = r.id WHERE r.is_active = 1 AND rc.recap_id = $recapId ORDER BY rc.created_at ASC");
                        while($row2 = mysqli_fetch_array($result2)) 
                        {
                        ?>
                            <div class="row comment">
                                <div class="col-xs-12">
                                    <?php echo $row2['comment_text']; ?>
                                    <br />
                                    - <?php echo $row2['commenter_name']; ?>
                                </div>
                            </div>
                        <?php } ?>

                        <form action="" class="recap-comment">
                            <input type="hidden" name="recap-id" value="<?php echo $recapId; ?>">
                            <div class="row">
                                <div class="col-xs-12 col-sm-8">
                                    <input type="text" name="comment" class="comment-input" placeholder="Add a comment about this event...">
                                </div>
                                <div class="col-xs-12 col-sm-2">
                                    <input type="text" name="commenter" placeholder="Name (optional)">
                                </div>
                                <div class="col-xs-12 col-sm-2 center">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <hr />
                <?php 
                }
            } else {
                echo '<center><h4>No recaps yet, sit tight!</h4></center>';
            } ?>

            <br /><br /><br />
        </div>
    </section>

<?php
include('footer.php');
?>

<script type="text/javascript">

    $( ".recap-comment" ).submit(function( event ) {
        event.preventDefault();

        var recapId = $(this).closest("div.comment-section").find("input[name='recap-id']").val();
        var comment = $(this).closest("div.comment-section").find("input[name='comment']").val();
        var commenter = $(this).closest("div.comment-section").find("input[name='commenter']").val();

        if (comment != '') {

            $.ajax({
                url: 'includes/handleForm.php',
                type: 'GET',
                dataType: 'json',
                data: {
                    'recap_id': recapId,
                    'comment': comment,
                    'commenter': commenter
                },
                success: function(data){
                    if(data.success == true) {
                        location.reload(); 
                    }
                }
            });
        } else {
            alert('Please enter a comment before submitting.');
        }
    });

</script>

</body>
</html>
