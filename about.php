
<?php
$currentPage = 'About';
include('header.php');
?>

    <section class="title">
        <div class="container">
            <div class="row-fluid">
                <div class="span6">
                    <h1>About</h1>
                </div>
            </div>
        </div>
    </section>

    <section id="about-us" class="container main">
        <?php 
        $content = mysqli_query($conn,"SELECT * FROM content");
        if (mysqli_num_rows($content) > 0) {
            while($row = mysqli_fetch_array($content)) {
                if ($row['context'] == 'what_is_s2c') { 
                    $definition = $row['content_text']; 
                } else if ($row['context'] == 'faq') { 
                    $faq = $row['content_text']; 
                }
            }
        }
        ?>

        <div class="row">
            <div class="col-xs-12">
                <h2>What is Spike2Care?</h2>
            </div>
            <div class="col-xs-12 col-md-10 col-md-push-1">
                <?php echo $definition; ?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 center">
                <iframe src="https://player.vimeo.com/video/241911621" id="s2cVideo" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <h2>Frequently Asked Questions</h2>
            </div>

            <div class="col-xs-12 col-md-10 col-md-push-1">
                <?php echo $faq; ?>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-xs-12">
                <h2>Board Members</h2>
            </div>
        </div>
        <div class="row">
            <?php
            $boardBios = mysqli_query($conn,
                "SELECT * FROM board_bios as bb 
                JOIN board_positions as bp ON bb.position_id = bp.id 
                JOIN people as p ON p.id = bb.people_id
                WHERE bp.is_active = 1 AND bb.is_active = 1 
                ORDER BY bp.id ASC");
            while($row = mysqli_fetch_array($boardBios)) 
            {
            ?>
                <div class="col-xs-10 col-xs-push-1">
                    <h3 class="board-name"><?php echo $row['full_name']; ?></h3>
                    <h4><?php echo $row['position']; ?></h4>

                    <div class="ellipsis-text" style="max-height:100px">
                        <p><?php

                            //echo '<img src="images/board/'.$row['image_path'].'" align="right" width="250">';
                            echo $row['bio_text']; 
                            echo '<a class="more">More</a>';
                            echo ' <a class="less">Less</a>';

                        ?></p>
                    </div>

                </div>

            <?php } ?>

        </div>
        
        <hr>

        <div class="row">
            <div class="col-xs-12">
                <h2>Meeting Minutes</h2>
            </div>
        </div>
        <div class="row">
             <div class="col-xs-10 col-xs-push-1">
                <ul>

                    <?php
                    $meetingMinutes = mysqli_query($conn, "SELECT * FROM meeting_minutes WHERE is_active = 1 ORDER BY meeting_date DESC LIMIT 10");
                    
                    if (mysqli_num_rows($meetingMinutes) > 0) {
                        while($row = mysqli_fetch_array($meetingMinutes)) 
                        {
                        ?>
                            <li><a href="minutes/<?php echo $row['file_path']; ?>"><?php echo date('m/d/Y', strtotime($row['meeting_date'])); ?> Meeting Minutes</a></li>

                        <?php } 
                    } else {?>
                        <h3>No new meeting minutes available at this time.</h3>
                    <?php } ?>
                    <p>Previous meeting minutes available upon request.</p>

                </ul>
            </div>
        </div>

    </section>   

<?php
include('footer.php');
?>

<script src="js/jquery.dotdotdot.min.js" type="text/javascript"></script>

<script type="text/javascript">
    $(function() {
        $("div.ellipsis-text").dotdotdot({
            after: 'a.more',
            callback: dotdotdotCallback
        });
        $("div.ellipsis-text").on('click','a',function() {
            if ($(this).text() == "More") {
                var div = $(this).closest('div.ellipsis-text');
                div.trigger('destroy').find('a.more').hide();
                div.css('max-height', '');
                $("a.less", div).show();
            }
            else {
                $(this).hide();
                $(this).closest('div.ellipsis-text').css("max-height", "100px").dotdotdot({ 
                    after: "a.more", 
                    callback: dotdotdotCallback });
            }
        });

        function dotdotdotCallback(isTruncated, originalContent) {
            if (!isTruncated) {
             $("a", this).remove();   
            }
        }
    });

</script>


</body>
</html>
