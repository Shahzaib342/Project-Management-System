<?php

require_once '../../Inc/Models/Products.php';

// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: auth/login.php");
    exit;
}else{
    $product = new \Inc\Models\Products;
    if(isset($_GET['id']) && is_int(intval($_GET['id']))){
        $makeActive = $product->makeActive(intval($_GET['id']));
        if(!$makeActive){
            header("location: /");
        }
    }
    $archievedItems = $product->archievedItems();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Archive</title>
    <?php require_once '../../assets/style_and_scripts.php'; ?>
    <link rel="stylesheet" href="/assets/css/add_product.css" type="text/css">
</head>
<body>
<?php include '../../templates/navbar.php'; 
?>
<div class="wrapper">
<?php if(isset($makeActive)){ ?>
<div class="alert alert-info alert-dismissible fade show" role="alert">
  <p class="ml-3 text-center"><?php echo $makeActive ?></p>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<?php } ?>

<h2 class="h2 m-4">Archive</h2>
<div class="container pt-3 d-block">
    <div class="float-left" style="margin-bottom: 3%;">
        <a class="btn btn-sm btn-dark" href="/">Home</a>
    </div>
    <?php if(isset($archievedItems) && !empty($archievedItems)){ 
        if(!is_array($archievedItems)){
            echo "<div class='m-5'>";
            echo $archievedItems;
            echo "</div>";
        }else{
    ?>
    <!-- <div class="row justify-content-center">
    <div class="col-auto"> -->
    <table class="mt-5 table table-bordered table-hover text-center">
    <thead class="thead-dark">
        <tr>
        <!-- <th scope="col">#</th> -->
        <th scope="col">Name</th>
        <th scope="col">Price</th>
        <th scope="col">Quantity</th>
        <th scope="col">Status</th>
        <th scope="col-auto">Action</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach($archievedItems as $items){ ?>
        <tr>
        <td scope="col"><?php echo $items['name'] ?></td>
        <td scope="col"><?php echo $items['price'] ?></td>
        <td scope="col"><?php echo $items['quantity'] ?></td>
        <td scope="col" class="badge badge-secondary badge-pill m-1"><?php echo $items['status'] ?></td>
        <td scope="col-auto">
        <a class="btn btn-sm btn-success" href="/pages/products/archive.php?id=<?php echo $items['id'] ?>"> Make Active </a> 
        </tr>
        <?php } ?>
    </tbody>
    </table>
    <!-- </div>
    </div> -->
<?php }}else{ ?>
<div>
<p class="h4 text-info text-center">No Product Added Yet!!!</p>
</div>
<?php } ?>

</div>
</div>    
<?php include '../../templates/footer.php'; ?>
</body>
</html>