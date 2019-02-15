
<?php
$currentPage = 'Shop';
include('header.php');

if (isset($_GET)) {
    $id = $_GET['id'];
    $existingColors = [];
    $existingSizes = [];
    $message = false;

    $sql = $conn->prepare("SELECT * FROM catalog WHERE id = ?");
    $sql->bind_param('i', $id);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $title = $row['title'];
            $image1_path = $row['image1_path'];
            $image2_path = $row['image2_path'];
            $image3_path = $row['image3_path'];
            //$category = $row['category_id'];
            $price = $row['price'];
            $description = $row['description'];
        }
    }

    $sql = $conn->prepare("SELECT * FROM catalog_colors WHERE catalog_id = ?");
    $sql->bind_param('i', $id);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $existingColors[] = $row['color_id'];
        }
    }

    $sql = $conn->prepare("SELECT * FROM catalog_sizes WHERE catalog_id = ?");
    $sql->bind_param('i', $id);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $existingSizes[] = $row['size_id'];
        }
    }

    if (isset($_GET['cartAddSuccess'])) {
        $message = true;
    }
}
?>

    <section class="title">
        <div class="container">
            <div class="row-fluid">
                <div class="span6">
                    <h1>Shop</h1>
                </div>
            </div>
        </div>
    </section>  

    <section id="merchandise" class="container">    
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <div id="myCarousel" class="carousel slide" data-ride="carousel" data-interval="false">

                    <!-- Wrapper for slides -->
                    <div class="carousel-inner" style=" width:100%;">
                        <div class="item active">
                            <?php 
                            if ($image1_path) { ?>
                                <img src="images/catalog/<?php echo $image1_path; ?>">
                            <?php
                            } else { ?>
                                <img src="images/noImage.png">
                            <?php
                            } ?>
                        </div>
                        <?php 
                        if (isset($image2_path)) { ?>
                            <div class="item">
                                <img src="images/catalog/<?php echo $image2_path; ?>">
                            </div>
                        <?php
                        }
                        if (isset($image3_path)) { ?>
                            <div class="item">
                                <img src="images/catalog/<?php echo $image3_path; ?>">
                            </div>
                        <?php 
                        } ?>
                    </div>

                    <!-- Left and right controls -->
                    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#myCarousel" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
            <div class="col-xs-12 col-md-6">
                <h2><?php echo $title; ?></h2>
                <h3><?php echo convertMoney($price); ?></h3>

                <p><?php echo $description; ?></p>

                <form action="shopCheckout.php" id="itemForm" method="POST">

                    <div class="form-group">
                        <label for="quantity">Quantity</label><br />
                        <input type="number" name="quantity" id="quantity" class="form-control">
                        <span id="error_msg1" class="full" style="display:none;"><br />Required</span>
                    </div>

                    <?php
                    if (count($existingColors) > 0) { ?>
                        <div class="form-group">
                            <label for="color">Color</label><br />
                            <select id="colors" name="color" class="form-control">
                                <option></option>
                                <?php
                                $colors = mysqli_query($conn,"SELECT * FROM colors");
                                if (mysqli_num_rows($colors) > 0) {
                                    while($div = mysqli_fetch_array($colors)) {
                                        if (in_array($div['id'], $existingColors)) { ?>
                                            <option value="<?php echo $div['id']; ?>"><?php echo $div['color']; ?></option>
                                        <?php }
                                    }
                                } ?>
                            </select>
                            <span id="error_msg2" class="full" style="display:none;"><br />Required</span>
                        </div>
                    <?php
                    } ?>    

                    <?php
                    if (count($existingSizes) > 0) { ?>
                        <div class="form-group">
                            <label for="size">Size</label><br />
                            <select id="sizes" name="size" class="form-control">
                                <option></option>
                                <?php
                                $sizes = mysqli_query($conn,"SELECT * FROM sizes");
                                if (mysqli_num_rows($sizes) > 0) {
                                    while($div = mysqli_fetch_array($sizes)) {
                                        if (in_array($div['id'], $existingSizes)) { ?>
                                            <option value="<?php echo $div['id']; ?>"><?php echo $div['size']; ?></option>
                                        <?php }
                                    }
                                } ?>
                            </select>
                            <span id="error_msg3" class="full" style="display:none;"><br />Required</span>
                        </div>
                    <?php 
                    } ?>

                    <input type="hidden" name="itemId" value="<?php echo $id; ?>">

                    <button class="btn btn-primary" id="addToCart">Add to Cart</button>
                    <button class="btn btn-primary" id="buyNow">Buy Now</button>
                </form>
            </div>
        </div> 
    </section>

    <div class="down20"><br /><br /><br /><br /><br /></div>

<?php
include('footer.php');
?>

<script src="js/select2.min.js"></script>
<script type="text/javascript" src="js/full_sparkle.js"></script>

<script>

    var showMessage = "<?php echo $message; ?>";
    if (showMessage == 1) {
        addAlertToPage('success', 'Success', 'Your cart has been updated', 5); 
    }

    $('#colors').select2({
        placeholder: 'Select color',
        minimumResultsForSearch: -1
    });

    $('#sizes').select2({
        placeholder: 'Select size',
        minimumResultsForSearch: -1
    });

    $('#buyNow').click(function (e) {
        e.preventDefault();

        // Do some validation on the form
        isValid = validateValues();

        if (isValid) {
            $('#itemForm').submit();
        }
    });

    $('#addToCart').click(function (e) {
        e.preventDefault();

        // Do some validation on the form
        isValid = validateValues();

        if (isValid) {
            $.ajax({
                url: 'includes/handleForm.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    'addToCart': true,
                    'formData': $('#itemForm').serializeArray()
                },
                complete: function(data){
                    // console.log(data.responseText);
                    response = $.parseJSON(data.responseText);
                    if (response[0] == 'success') {
                        window.location = 'item.php?id='+response[1].itemId+'&cartAddSuccess=true';
                    } else {
                        addAlertToPage('error', 'Error', 'Failed to update cart', 10);
                    }     
                }
            });
        }
    });

    function validateValues()
    {
        response = true;
        $('#error_msg1').hide();
        $('#error_msg2').hide();
        $('#error_msg3').hide();

        if ($("#quantity").val() == '') {
            $('#error_msg1').show();
            response = false;
        }
        if ($("#colors").val() == "")  {
            $('#error_msg2').show();
            response = false;
        }
        if ($("#sizes").val() == "")  {
            $('#error_msg3').show();
            response = false;
        }

        return response;
    }
</script>

</body>
</html>
