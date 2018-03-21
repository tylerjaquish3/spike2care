
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
            <div class="col-xs-12 text-center">

                <div class="button-group filters-button-group">
                    <button class="btn btn-primary is-checked" data-filter="*">Show All</button>
                    <?php
                    
                    $categories = mysqli_query($conn,"SELECT * FROM categories ORDER BY id ASC");
                    while($row = mysqli_fetch_array($categories)) 
                    {
                        $id = $row['id'];
                        $category = $row['category'];
                        ?>

                        <button class="btn btn-primary" data-filter=".<?php echo $id; ?>"><?php echo $category; ?></button>
                    <?php } ?>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">

                <!-- catalog populated from database -->
                <div class="grid">
                    <?php
                    
                    $products = mysqli_query($conn,"SELECT * FROM catalog WHERE active=1 ORDER BY id DESC");
                    while($row = mysqli_fetch_array($products)) 
                    {
                        $id = $row['id'];
                        $title = $row['title'];
                        $image = $row['image_path'];
                        $category = $row['category_id'];
                        $price = $row['price'];
                    ?> 
                        <div class="element-item <?php echo $category; ?>">

                            <img class="img-responsive center-cropped" src="images/catalog/<?php echo $image; ?>">
                            <h3><?php echo $title; ?></h3>
                                
                            <?php echo convertMoney($price); ?> 
                            <a class="btn btn-primary" href="item.php?id=<?php echo $id; ?>">View</a>
                        </div>
                    <?php
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
    // init Isotope
    var $grid = $('.grid').isotope({
        itemSelector: '.element-item',
        layoutMode: 'cellsByRow',
        animationEngine : 'jquery'
    });

    // bind filter button click
    $('.filters-button-group').on( 'click', 'button', function() {

        var filterValue = $( this ).attr('data-filter');
        $grid.isotope({ filter: filterValue });

    });
    // change is-checked class on buttons
    $('.button-group').each( function( i, buttonGroup ) {

        var $buttonGroup = $( buttonGroup );

        $buttonGroup.on( 'click', 'button', function() {
            $buttonGroup.find('.is-checked').removeClass('is-checked');
            $( this ).addClass('is-checked');
            });
        });
</script>

</body>
</html>
