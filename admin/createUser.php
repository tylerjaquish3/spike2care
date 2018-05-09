<?php
session_start();

include('includes/header.php');

if (!isset($_SESSION["user_id"])) {
    header('location:'.URL);
}


$username = $email = $roleId = $adminId = $active = '';
$isNew = true;

if (isset($_GET) && !empty($_GET)) {

    $isNew = false;
    $adminId = $_GET['adminId'];
    $admin = mysqli_query($conn,"SELECT * FROM admin WHERE id = ".$adminId);
    if (mysqli_num_rows($admin) > 0) {
        while($row = mysqli_fetch_array($admin)) {
            $username = $row['user_name'];
            $email = $row['email'];
            $roleId = $row['role_id'];
            ($row['is_active'] == 1 ? $active = ' checked' : $active = '');
        }
    }
}

$roleOptions = '';
$roles = mysqli_query($conn,"SELECT * FROM roles");
while($row = mysqli_fetch_array($roles)) {

    if ($row['id'] == $roleId) {
        $roleOptions .= '<option selected value="'.$row['id'].'">'.$row['name'].'</option>';
    } else {
        $roleOptions .= '<option value="'.$row['id'].'">'.$row['name'].'</option>';
    }
}

?>

<!-- page content -->
<div class="right_col" role="main">
                    
    <div class="page-title">
        <div class="title_left">
            <h1>Admin Users</h1>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Create/Edit User</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                
                <div class="x_content">
                    <form action="#" method="POST" id="save-user" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6 col-xs-12">

                                <input type="hidden" name="save-user" value="1">
                                <input type="hidden" name="isNew" value="<?php echo $isNew; ?>">
                                <input type="hidden" name="userId" value="<?php echo $adminId; ?>">

                                <div class="form-group">
                                    <label for="user_name">Username</label>
                                    <input type="text" class="form-control" name="user_name" value="<?php echo $username; ?>">
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="text" class="form-control" name="email" value="<?php echo $email; ?>">
                                </div>

                                <div class="form-group">
                                    <label for="testimonial_text">Active</label>
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" value="1" <?php echo $active; ?>>
                                </div>

                                <div class="form-group">
                                    <label for="role">Role</label>
                                    <select class="form-control" name="role">
                                        <?php echo $roleOptions; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 col-xs-12">
                                <h3>Roles</h3>
                                <ul>
                                    <li>Administrator - has access to all modules, including editing other admin users</li>
                                    <li>Board - has access to all modules (only role able to access Applications)</li>
                                    <li>Merchandise - has access to Dashboard, Sales, and Messages</li>
                                    <li>Content - has access to Dashboard, Content, Testimonials, and Messages</li>
                                    <li>Events - has access to Dashboard, Events, and Messages</li>
                                </ul>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 center">
                                <br /><br />
                                <button type="submit" name="save-user" id="save-user-btn" class="btn btn-info">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('includes/footer.php');
?>

<script>

    $('#save-user-btn').click(function (e) {
        var formData = $('#save-user').serialize();
    
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
            },
        });

        e.preventDefault();
    });

</script>
    