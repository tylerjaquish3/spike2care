<?php
$currentPage = 'Reset Password';
include('header.php');
include('admin/includes/password.php');

$slug = $adminId = '';

if (isset($_GET) && !empty($_GET)) {
    $slug = $_GET['id'];

    $admin = mysqli_query($conn,"SELECT * FROM admin WHERE is_active = 1");
    if (mysqli_num_rows($admin) > 0) {
        while($row = mysqli_fetch_array($admin)) {
            if ($row['slug'] == $slug) {
                $adminId = $row['id'];
            }
        }
    }
}

?>

<section class="title">
    <div class="container">
        <div class="row-fluid">
            <div class="span6">
                <h1>Reset Password</h1>
            </div>
        </div>
    </div>
</section>

<section id="contact-page" class="container">
    <div class="row">
        <div class="col-xs-12">
            <form action="#" method="POST" id="reset-password" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6 col-xs-12">

                        <input type="hidden" name="reset-password" value="1">
                        <input type="hidden" name="userId" value="<?php echo $adminId; ?>">

                        <div class="form-group">
                            <label for="password1">New Password</label>
                            <input type="password" class="form-control" name="password1" id="password1">
                        </div>

                        <div class="form-group">
                            <label for="password2">Confirm Password</label>
                            <input type="password" class="form-control" name="password2" id="password2">
                        </div>

                        <span id="error-msg" style="display:none;">Passwords must match!</span>
                        <br />

                        <button id="save-user" class="btn btn-primary">Save</button>
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

<script>

    $('#save-user').click(function (e) {

        e.preventDefault();

        if ($('#password1').val() == $('#password2').val()) {
            $('#error-msg').hide();

            var formData = $('#reset-password').serialize();
        
            $.ajax({
                url: 'includes/handleForm.php',
                type: "POST",
                data: formData,
                dataType: 'json',
                success: function (response) {
                    if (response.type == 'error') {
                        addAlertToPage('error', 'Error', response.message, 10);
                    } else {
                        addAlertToPage('success', 'Success', response.message, 10);
                    }
                }
            });

        } else {
            $('#error-msg').show();
        }

    });

</script>
    