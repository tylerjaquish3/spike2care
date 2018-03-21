
<?php
$currentPage = 'Shop';
include('header.php');

if (isset($_GET)) {
    $id = $_GET['id'];

    $items = mysqli_query($conn,"SELECT * FROM catalog WHERE id = ".$id);
    while($row = mysqli_fetch_array($items)) 
    {
        $title = $row['title'];
        $image = $row['image_path'];
        //$category = $row['category'];
        $price = $row['price'];
        // $quantity = $row['quantity'];
        // $size = $row['size'];
        $description = $row['description'];
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
                <img src="images/catalog/<?php echo $image; ?>">
                
            </div>
            <div class="col-xs-12 col-md-6">
                <h2><?php echo $title; ?></h2>
                <h3><?php echo convertMoney($price); ?></h3>



                <p><?php echo $description; ?></p>
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


</body>
</html>
