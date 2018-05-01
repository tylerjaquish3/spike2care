<?php

    if (!isset($_SESSION["user_id"])) {
        header('location:'.URL);
    }
    $userId = $_SESSION["user_id"]; 

    $sql = "SELECT * FROM admin JOIN roles on admin.role_id = roles.id WHERE admin.id = $userId";
    $result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_array($result)) 
    {
        $roleId = $row['role_id'];
    }
?>

<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="index.php" class="site_title">
                <img src="images/logo-small-pink.png" id="full">
                <img src="images/logo-small-pink.png" id="small">
                <span>Spike2Care Admin</span>
            </a>
        </div>

        <div class="clearfix"></div>

        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <ul class="nav side-menu">
                    <li><a href="index.php"><i class="fa fa-tachometer"></i> Dashboard</a></li>
                    <?php
                    if ($roleId == 1 || $roleId == 4 || $roleId == 5)
                    echo '<li><a href="events.php"><i class="fa fa-sun-o"></i> Events</a></li>';
                    if ($roleId == 1 || $roleId == 4 || $roleId == 5)
                    echo '<li><a href="payments.php"><i class="fa fa-credit-card"></i> Payments</a></li>';
                    if ($roleId == 1 || $roleId == 2 || $roleId == 5)
                    echo '<li><a href="sales.php"><i class="fa fa-shopping-cart"></i> Sales</a></li>';
                    if ($roleId == 1 || $roleId == 3 || $roleId == 5)
                    echo '<li><a href="content.php"><i class="fa fa-sticky-note"></i> Content</a></li>';
                    if ($roleId == 1 || $roleId == 3 || $roleId == 5)
                    echo '<li><a href="minutes.php"><i class="fa fa-clock-o"></i> Meeting Minutes</a></li>';
                    if ($roleId == 1 || $roleId == 3 || $roleId == 5)
                    echo '<li><a href="testimonials.php"><i class="fa fa-comment"></i> Testimonials</a></li>';
                    if ($roleId == 1 || $roleId == 5)
                    echo '<li><a href="applications.php"><i class="fa fa-wpforms"></i> Applications</a></li>';
                    echo '<li><a href="messages.php"><i class="fa fa-send"></i> Messages</a></li>';
                    if ($roleId == 1 || $roleId == 5)
                    echo '<li><a href="users.php"><i class="fa fa-users"></i> Users</a></li>';

                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>