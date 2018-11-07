
<?php
$currentPage = 'Shop';
include('header.php');

if (isset($_GET)) {
    $id = $_GET['id'];

    $items = mysqli_query($conn,"SELECT * FROM catalog WHERE id = ".$id);
    while($row = mysqli_fetch_array($items)) 
    {
        $title = $row['title'];
        $image1_path = $row['image1_path'];
        $image2_path = $row['image2_path'];
        $image3_path = $row['image3_path'];
        //$category = $row['category_id'];
        $price = $row['price'];
        $description = $row['description'];
    }

    $colors = mysqli_query($conn,"SELECT * FROM catalog_colors WHERE catalog_id = ".$id);
    if (mysqli_num_rows($colors) > 0) {
        while($row = mysqli_fetch_array($colors)) {
            $existingColors[] = $row['color_id'];
        }
    }

    $sizes = mysqli_query($conn,"SELECT * FROM catalog_sizes WHERE catalog_id = ".$id);
    if (mysqli_num_rows($sizes) > 0) {
        while($row = mysqli_fetch_array($sizes)) {
            $existingSizes[] = $row['size_id'];
        }
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
                    <!-- Indicators -->
                    <ol class="carousel-indicators">
                        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                        <li data-target="#myCarousel" data-slide-to="1"></li>
                        <li data-target="#myCarousel" data-slide-to="2"></li>
                    </ol>

                    <!-- Wrapper for slides -->
                    <div class="carousel-inner" style=" width:100%; height: 500px">
                        <div class="item active">
                            <img src="images/catalog/<?php echo $image1_path; ?>" height="300px">
                        </div>

                        <div class="item">
                            <img src="images/catalog/<?php echo $image2_path; ?>">
                        </div>

                        <div class="item">
                            <img src="images/catalog/<?php echo $image3_path; ?>">
                        </div>
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

                <p><?php echo $description; 
                var_dump($_SESSION);
                ?></p>

                <form action="shopCheckout.php" id="itemForm" method="POST">

                    <div class="form-group">
                        <label for="quantity">Quantity</label><br />
                        <input type="number" name="quantity" id="quantity">
                    </div>

                    <div class="form-group">
                        <label for="color">Color</label>
                        <select id="colors" name="color">
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
                    </div>

                    <div class="form-group">
                        <label for="size">Size</label>
                        <select id="sizes" name="size">
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
                    </div>

                    <input type="hidden" name="itemId" value="<?php echo $id; ?>">

                    <button class="btn btn-primary" id="addToCart">Add to Cart</button>
                    <button type="submit" class="btn btn-primary">Buy Now</button>
                </form>
            </div>

        </div>

        <div class="row">
            <div class="col-xs-12">


            </div>
        </div>
        
    </section>

<?php
include('footer.php');
?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript" src="js/full_sparkle.js"></script>

<script>
    $('#colors').select2({
        placeholder: 'Select color',
        minimumResultsForSearch: -1
    });

    $('#sizes').select2({
        placeholder: 'Select size',
        minimumResultsForSearch: -1
    });

    $('#addToCart').click(function (e) {
        e.preventDefault();

        $.ajax({
            url: 'includes/handleForm.php',
            type: 'POST',
            dataType: 'json',
            data: {
                'addToCart': true,
                'formData': $('#itemForm').serializeArray()
            },
            complete: function(data){
                response = $.parseJSON(data.responseText);
                if (response == 'success') {
                    addAlertToPage('success', 'Success', 'Your cart has been updated', 10);   
                } else {
                    addAlertToPage('error', 'Error', 'Failed to update cart', 10);
                }     
            }
        });
    });
</script>

</body>
</html>
