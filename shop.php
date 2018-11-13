
<?php
$currentPage = 'Shop';
include('header.php');
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

    <section id="merchandise" class="container main">    
        <div class="row">
            <div class="col-xs-12">

                <!-- catalog populated from database -->
                <div class="grid">
                    <?php
                    
                    $products = mysqli_query($conn,"SELECT * FROM catalog WHERE active = 1 ORDER BY id DESC");
                    if (mysqli_num_rows($products) > 0 ) {
                        while($row = mysqli_fetch_array($products)) 
                        {
                            $id = $row['id'];
                            $title = $row['title'];
                            $image = $row['image1_path'];
                            $category = $row['category_id'];
                            $price = $row['price'];
                        ?> 
                            <div class="element-item <?php echo $category; ?>">
                                <a href="item.php?id=<?php echo $id; ?>">
                                    <?php 
                                    if ($image) { ?>
                                        <img class="img-responsive center-cropped" src="images/catalog/<?php echo $image; ?>">
                                    <?php
                                    } else { ?>
                                        <img class="img-responsive center-cropped" src="images/noImage.png">
                                    <?php
                                    } ?>
                                </a>
                                <h3><?php echo $title; ?></h3>
                                    
                                <?php echo convertMoney($price); ?> 
                                <a class="btn btn-primary" href="item.php?id=<?php echo $id; ?>">View</a>
                            </div>
                        <?php
                        }
                    } else {
                        echo '<h2><center>There are no items available for purchase at this time. Please check back soon.</center></h2>';
                    }      
                    ?>
                </div>
            </div>
        </div>
        
    </section>

<?php
include('footer.php');
?>

<script type="text/javascript">
    
</script>

</body>
</html>
