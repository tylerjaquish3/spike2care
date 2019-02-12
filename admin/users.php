<?php
session_start();

include('includes/header.php');

if (!isset($_SESSION["user_id"])) {
    header('location:'.URL);
}
?>

<!-- page content -->
<div class="right_col" role="main">
        
    <div class="row">
        <div class="col-xs-6">
            <div class="title_left">
                <h1>Users</h1>
            </div>
        </div>
        <div class="col-xs-6 text-right down15">   
            <a href="createUser.php" class="btn btn-info">Add User</a>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-xs-12">
            <div class="x_panel table_panel">
                <div class="x_title">
                    <h2>All Users</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="col-md-12 col-sm-12">
                        <table class="table table-bordered table-striped table-responsive">
                            <thead>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Last Login</th>
                                <th>View/Edit</th>
                                
                            </thead>
                            <tbody>
                                <?php 
                                $result = mysqli_query($conn,"SELECT admin.id, user_name, email, name, admin.updated_at FROM admin JOIN roles ON roles.id = admin.role_id ORDER BY updated_at DESC");
                                while($user = mysqli_fetch_array($result)) 
                                { ?>
                                    <tr>
                                        <td><?php echo $user['user_name']; ?></td>
                                        <td><?php echo $user['email']; ?></td>
                                        <td><?php echo $user['name']; ?></td>
                                        <td><?php echo date('m/d/Y', strtotime($user['updated_at'])); ?></td>
                                        <td><?php echo '<a href="createUser.php?adminId='.$user['id'].'">View/Edit</a>'; ?></td>
                                    </tr>

                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>

<?php
include('includes/footer.php');
?>

    