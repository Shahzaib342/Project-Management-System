
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
        $viewRecord = $product->viewRecord(intval($_GET['id']));
        
        $titles = ["name" =>"Product Name", "price" => "Price",  "quantity" => "Quantity", "status_name" => "Status",
        "created_by" => "Created By", "created" => "Created On"];
        if($viewRecord['modified_by']){
            $titles = array_merge($titles, ["modified_by" => "Modified By", "modified" => "Modified On"]);
        }
    }else{
        header("location: /");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Record</title>
    <?php require_once '../../assets/style_and_scripts.php'; ?>
    <link rel="stylesheet" href="/assets/css/add_product.css" type="text/css">
</head>
<body>
<?php include '../../templates/navbar.php'; 
?>
<div class="wrapper">

<h2 class="h2 m-4">Product</h2>
<div class="container pt-3 d-block">
    <div class="float-left" style="margin-bottom: 3%;">
        <a class="btn btn-sm btn-dark" href="/">Home</a>
    </div>
   
    <!-- <div class="row justify-content-center">
    <div class="col-auto"> -->
    <table class="mt-5 table table-bordered table-hover text-center">
    <thead class="thead-dark">
        <tr>
        <!-- <th scope="col">#</th> -->
        <th scope="col">Property</th>
        <th scope="col">Value</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach($titles as $key => $item){ ?>
        <tr>
        <th scope="col"><?php echo $item ?></th>
        <td scope="col" class="<?php echo ($key == "status_name")? "badge badge-success badge-pill m-1" : "" ?>"><?php echo $viewRecord[$key] ?></td>
        </tr>
        <?php } ?>
    </tbody>
    </table>

</div>
</div>    
<?php include '../../templates/footer.php'; ?>
</body>
</html>