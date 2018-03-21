<?php
session_start();

include('includes/header.php');

if (!isset($_SESSION["user_id"])) {
    header('location:'.URL);
}

$testimonialId = $testimonialName = $testimonialText = $active = '';
$isNew = true;
$active = '';

if (isset($_GET) && !empty($_GET)) {
    $isNew = false;     
    $testimonialId = $_GET['id'];

    $testimonial = mysqli_query($conn,"SELECT * FROM testimonials t join people on t.user_id = people.id WHERE t.id = ".$testimonialId);
    if (mysqli_num_rows($testimonial) > 0) {
        while($row = mysqli_fetch_array($testimonial)) {
            $testimonialName = $row['full_name'];
            $testimonialText = $row['testimonial_text'];
            ($row['is_active'] == 1 ? $active = ' checked' : $active = '');
        }
    }

}
?>

<!-- page content -->
<div class="right_col" role="main">
                    
    <div class="page-title">
        <div class="title_left">
            <h1>Testimonials</h1>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Add/Edit Testimonial</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">

                    <form method="POST" id="save-testimonial">

                        <input type="hidden" name="save-testimonial" value="<?php echo $testimonialId; ?>">
                        <input type="hidden" name="is-new" value="<?php echo $isNew; ?>">

                        <div class="row">
                            <div class="col-xs-12">

                                <div class="form-group">
                                    <label for="testimonial_name">Name <span class="required">*</span></label>
                                    <input type='text' name="testimonial_name" class="form-control" required value="<?php echo $testimonialName; ?>" />
                                </div>

                                <div class="form-group">
                                    <label for="testimonial_text">Testimonial Text</label>
                                    <textarea name="testimonial_text" required class="form-control"><?php echo $testimonialText; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="testimonial_text">Active on site?</label>
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" value="1" <?php echo $active; ?>>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-xs-12 center">
                                <br /><br />
                                <button type="submit" class="btn btn-info">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    $('#save-testimonial').submit(function (e) {

        e.preventDefault();
        var formData = $('#save-testimonial').serialize();
    
        $.ajax({
            url: 'includes/handleForm.php',
            type: "POST",
            data: formData,
            async: false,
            dataType: 'json',
            success: function (response) {
                if (response.type == 'error') {
                    addAlertToPage('error', 'Error', response.message, 10);
                } else {
                    window.location.replace("testimonials.php?alert=success");
                }
            }
        });
    });

</script>


<?php
include('includes/footer.php');
?>
    
