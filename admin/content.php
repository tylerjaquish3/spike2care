
<?php
session_start();

include('includes/header.php');

if (!isset($_SESSION["user_id"])) {
    header('location:'.URL);
}

$content = mysqli_query($conn,"SELECT * FROM content");
while($row = mysqli_fetch_array($content)) {
  
    if ($row['context'] == 'mission_statement') {
        $mission_statement = $row['content_text'];
    } elseif ($row['context'] == 'about_s2c') {
        $about_s2c = $row['content_text'];
    } elseif ($row['context'] == 'what_is_s2c') {
        $what_is_s2c = $row['content_text'];
    } elseif ($row['context'] == 'faq') {
        $faq = $row['content_text'];
    }
}

?>

<!-- page content -->
<div class="right_col" role="main">
                    
    <div class="page-title">
        <div class="title_left">
            <h1>Content</h1>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Edit Site Content</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">

                    <div class="row">
                        <div class="col-xs-12 edit-content">

                            <form action="includes/handleForm.php" method="POST" enctype="multipart/form-data">

                                <div class="form-group">
                                    <label for="format">Mission Statement</label>
                                    <textarea class="form-control ckeditor" id="ckeditor" name="mission_statement" rows="3"><?php echo $mission_statement; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="description">About Spike2Care</label>
                                    <textarea class="form-control ckeditor" id="ckeditor" name="about_s2c" rows="6"><?php echo $about_s2c; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="description">What is Spike2Care?</label>
                                    <textarea class="form-control ckeditor" id="ckeditor" name="what_is_s2c" rows="6"><?php echo $what_is_s2c; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="description">Frequently Asked Questions</label>
                                    <textarea class="form-control ckeditor" id="ckeditor" name="faq" rows="6"><?php echo $faq; ?></textarea>
                                </div>

                        </div>
                        <div class="row">
                            <div class="col-xs-12 center">
                                <br /><br />
                                <button type="submit" name="save-content" class="btn btn-info">Save</button>
                            </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Edit Board Member Bios</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">


                    <div class="row">
                        <div class="col-xs-12">

                            <form action="includes/handleForm.php" method="POST" enctype="multipart/form-data">

                                <?php 
                                    $bios = mysqli_query($conn,"SELECT * FROM board_bios bb JOIN people ON bb.people_id = people.id JOIN board_positions bp ON bb.position_id = bp.id WHERE bb.is_active = 1 AND bp.is_active = 1 ORDER BY bp.id ASC");
                                    while($row = mysqli_fetch_array($bios)) {
                                        $posId = $row['position_id'];
                                        $peepId = $row['people_id'];
                                    ?>

                                        <div class="row">
                                            <div class="col-xs-12 col-md-4">
                                                <?php 
                                                    $positions = mysqli_query($conn,"SELECT * FROM board_positions WHERE is_active = 1 ORDER BY id ASC");
                                                    while($pos = mysqli_fetch_array($positions)) {
                                                        if ($pos['id'] == $row['position_id']) {
                                                             echo '<h3>'.$pos['position'].'</h3>';
                                                        }
                                                    }
                                                ?>
                                                <div class="form-group">
                                                    <label for="description">Name</label>
                                                    <input type="text" class="form-control" name="full_name[<?php echo $peepId; ?>]" value="<?php echo $row['full_name']; ?>">
                                                </div>

                                            </div>
                                            <div class="col-xs-12 col-md-8">
                                                <div class="form-group">
                                                    <label for="description">Bio Text</label>
                                                    <textarea class="form-control ckeditor" id="ckeditor" name="bio_text[<?php echo $peepId; ?>]" rows="4"><?php echo $row['bio_text']; ?></textarea>
                                                </div>
                                            </div>
                                        </div>

                                    <?php
                                    }
                                ?>

                            </div>

                        </div>
                        <div class="row">
                            <div class="col-xs-12 center">
                                <br /><br />
                                <button type="submit" name="save-board" class="btn btn-info">Save</button>
                            </div>

                            </form>

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
