<?php
session_start();

include('includes/header.php');

if (!isset($_SESSION["user_id"])) {
    header('location:'.URL);
}

$itemId = $title = $price = $description = $image1_path = $image2_path = $image3_path = $category_id = $active = '';
$isNew = true;

if (isset($_GET) && !empty($_GET)) {
    $isNew = false;
    $itemId = $_GET['itemId'];    

    $event = mysqli_query($conn,"SELECT * FROM catalog WHERE id = ".$itemId);
    if (mysqli_num_rows($event) > 0) {
        while($row = mysqli_fetch_array($event)) {
            $title = $row['title'];
            $price = $row['price'];
            $description = $row['description'];
            $image1_path = $row['image1_path'];
            $image2_path = $row['image2_path'];
            $image3_path = $row['image3_path'];
            $category_id = $row['category_id'];
            $active = $row['active'];
        }
    }

    $colors = mysqli_query($conn,"SELECT * FROM catalog_colors WHERE catalog_id = ".$itemId);
    if (mysqli_num_rows($colors) > 0) {
        while($row = mysqli_fetch_array($colors)) {
            $existingColors[] = $row['color_id'];
        }
    }

    $sizes = mysqli_query($conn,"SELECT * FROM catalog_sizes WHERE catalog_id = ".$itemId);
    if (mysqli_num_rows($sizes) > 0) {
        while($row = mysqli_fetch_array($sizes)) {
            $existingSizes[] = $row['size_id'];
        }
    }

    if ($active) {
        $active = 'checked';
    }
}
?>

<!-- page content -->
<div class="right_col" role="main">
                    
    <div class="page-title">
        <div class="title_left">
            <h1>Create Item</h1>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_content">
                    <form action="includes/handleForm.php" method="POST" enctype="multipart/form-data">
                        <div class="col-sm-12">

                            <input type="hidden" name="item_id" value="<?php echo $itemId; ?>">
                            <input type="hidden" name="is_new" value="<?php echo ($title == '' ? 'true' : 'false'); ?>">

                            <div class="form-group">
                                <label for="title">Title <span class="required">*</span></label>
                                <input type="text" class="form-control" name="title" required placeholder="Title" value="<?php echo $title; ?>">
                            </div>

                            <div class="form-group">
                                <label for="title">Price <span class="required">*</span></label>
                                <input type="text" class="form-control" name="price" required placeholder="Dollar Amount" value="<?php echo $price; ?>">
                            </div>

                            <div class="form-group">
                                <label for="description">Item Description <span class="required">*</span></label>
                                <textarea class="form-control ckeditor" id="ckeditor" required name="description" rows="6"><?php echo $description; ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="colors">Colors</label>
                                <select id="colors-multiple" name="colors[]" multiple="multiple">
                                    <?php
                                    $colors = mysqli_query($conn,"SELECT * FROM colors");
                                    if (mysqli_num_rows($colors) > 0) {
                                        while($div = mysqli_fetch_array($colors)) {
                                            if (in_array($div['id'], $existingColors)) { ?>
                                                <option selected="selected" value="<?php echo $div['id']; ?>"><?php echo $div['color']; ?></option>
                                            <?php } else { ?>
                                                <option value="<?php echo $div['id']; ?>"><?php echo $div['color']; ?></option>
                                            <?php }
                                        }
                                    } ?>
                                </select>
                                <a class="btn btn-primary" id="addColorModal">Add New Color</a>
                                <br /><small id="fileHelp" class="form-text text-muted">Note: If you add new colors, you must refresh the page to be able to choose them. It is recomended to save your changes, and then come back to edit the item.</small>
                            </div>

                            <div class="form-group">
                                <label for="sizes">Sizes</label>
                                <select id="sizes-multiple" name="sizes[]" multiple="multiple">
                                    <?php
                                    $sizes = mysqli_query($conn,"SELECT * FROM sizes");
                                    if (mysqli_num_rows($sizes) > 0) {
                                        while($div = mysqli_fetch_array($sizes)) {
                                            if (in_array($div['id'], $existingSizes)) { ?>
                                                <option selected="selected" value="<?php echo $div['id']; ?>"><?php echo $div['size']; ?></option>
                                            <?php } else { ?>
                                                <option value="<?php echo $div['id']; ?>"><?php echo $div['size']; ?></option>
                                            <?php }
                                        }
                                    } ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="is_active">Active on Site?</label>
                                <input type="checkbox" name="is_active" <?php echo $active; ?>>
                            </div>

                            <div class="form-group">
                                <label for="image_path">Item Images</label>
                                <input type="file" class="form-control-file" name="image1_path" aria-describedby="fileHelp">
                                <input type="file" class="form-control-file" name="image2_path" aria-describedby="fileHelp">
                                <input type="file" class="form-control-file" name="image3_path" aria-describedby="fileHelp">
                                <small id="fileHelp" class="form-text text-muted">Only jpg, gif, and png formats are acceptable.</small>
                            </div>
                        </div>
                    
                        <div class="row">
                            <?php if ($image1_path != '') { ?>
                                <div class="col-xs-6 col-md-4">
                                    <img src="../images/catalog/<?php echo $image1_path; ?>" width="100%">
                                </div>
                            <?php } ?>
                            <?php if ($image2_path != '') { ?>
                                <div class="col-xs-6 col-md-4">
                                    <img src="../images/catalog/<?php echo $image2_path; ?>" width="100%">
                                </div>
                            <?php } ?>
                            <?php if ($image3_path != '') { ?>
                                <div class="col-xs-6 col-md-4">
                                    <img src="../images/catalog/<?php echo $image3_path; ?>" width="100%">
                                </div>
                            <?php } ?>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 center">
                                <br /><br />
                                <button type="submit" name="save-item" class="btn btn-info">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add-color-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add New Color</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="color">Color <span class="required">*</span></label>
                    <input type="text" class="form-control" id="newColor" required>
                </div>
            </div>
            <div class="modal-footer">
                <div class="modal-buttons pull-right">
                    <button type="button" class="btn btn-warning" class="close" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="save-color-btn">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('includes/footer.php');
?>
<!-- 
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script> -->
<script src="/js/select2.min.js"></script>

<script>

    $('#colors-multiple').select2({
        placeholder: 'Select multiple'
    });

    $('#sizes-multiple').select2({
        placeholder: 'Select multiple'
    });

    $('#addColorModal').click(function () {
        $('#add-color-modal').modal('show');
    });

    $('#save-color-btn').click(function () {
        $.ajax({
            url: 'includes/handleForm.php',
            type: 'post',
            cache: false,
            data: {
                action: 'addColor',
                color: $('#newColor').val(), 
            },
            success: function () {
                addAlertToPage('success', 'Success', 'Color was successfully added.', 10);
                $('#add-color-modal').modal('hide');
            },
            error: function () {
                addAlertToPage('error', 'Error', 'Error adding color.', 10);
            }
        });
    });

</script>
    