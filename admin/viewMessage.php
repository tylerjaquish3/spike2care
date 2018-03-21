<?php
session_start();

include('includes/header.php');

if (!isset($_SESSION["user_id"])) {
    header('location:'.URL);
}

if (isset($_GET)) {
    $messageId = $_GET['messageId'];

    $messages = mysqli_query($conn,"SELECT * FROM messages JOIN people on messages.people_id = people.id WHERE messages.id = $messageId");
    while($row = mysqli_fetch_array($messages)) 
    { 
        $name = $row['full_name'];
        $phone = $row['phone'];
        $email = $row['email'];  

        $message = $row['message_text'];
        $status = $row['status'];
        $created_at = $row['created_at'];
    }
}
?>

<!-- page content -->
<div class="right_col" role="main">
                    
    <div class="page-title">
        <div class="title_left">
            <h1>Message</h1>
        </div>
    </div>

    <div class="clearfix"></div>
        <div class="row">
            <div class="col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <div class="inline">
                            Status: 
                            <select name="status" class="form-control" id="statusSelect">
                                <option value="New" <?php echo ($status == 'New' ? 'selected' : ''); ?>>New</option>
                                <option value="Read" <?php echo ($status == 'Read' ? 'selected' : ''); ?>>Read</option>
                                <option value="Responded" <?php echo ($status == 'Responded' ? 'selected' : ''); ?>>Responded</option>
                            </select>
                        </div>
                        <h2>Message from: <?php echo $name; ?></h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="row">
                            <div class="col-xs-12 col-md-8">
                   
                                <label class="control-label">Name</label>
                                <div class="no-input">
                                    <p><?php echo $name; ?></p>
                                </div>
                            
                                <label class="control-label">Email</label>
                                <div class="no-input">
                                    <p><?php echo $email; ?></p>
                                </div>

                                <label class="control-label">Date</label>
                                <div class="no-input">
                                    <p><?php echo $created_at; ?></p>
                                </div>
                            
                            </div>
                        </div>

                        <hr />

                        <div class="row">
                            <div class="col-xs-12">
                                
                                <label class="control-label col-xs-12">Message</label>
                                <div class="no-input col-xs-12">
                                    <p><?php echo $message; ?></p>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('includes/footer.php');
?>
    
<script>

    $('#statusSelect').change(function () {

        var status = $('#statusSelect option:selected').val();
        var messageId = "<?php echo $messageId; ?>";

        $.ajax({
            url: 'includes/handleForm.php',
            type: 'post',
            cache: false,
            data: {
                action: 'updateMessageStatus',
                messageId: messageId, 
                status: status
            },
            success: function () {
                addAlertToPage('success', 'Success', 'Status was successfully changed.', 10);
            }
            // error: function () {
            //     addAlertToPage('error', 'Error', 'Error canceling item.', 10);
            // }
        });
    });

</script>