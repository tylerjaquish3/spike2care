
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
                <img src="images/catalog/<?php echo $image1_path; ?>" width="100%">
                
            </div>
            <div class="col-xs-12 col-md-6">
                <h2><?php echo $title; ?></h2>
                <h3><?php echo convertMoney($price); ?></h3>

                <p><?php echo $description; ?></p>

                <form action="shopCheckout.php" method="POST">
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

<script>
    $('#colors').select2({
        placeholder: 'Select color'
    });

    $('#sizes').select2({
        placeholder: 'Select size'
    });

    $('#buyNow').click(function () {
        alert('stripe modal');
    });
</script>

</body>
</html>
