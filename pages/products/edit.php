<?php

// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: /");
    exit;
}

require_once '../../Inc/Models/Products.php';
$product = new \Inc\Models\Products;


if(isset($_POST['edit_product'])){
  $product = new \Inc\Models\Products;
  $edit = $product->editProduct($_POST);
  var_dump($edit);
  if($edit === true){
    $message = "Record updated successfully!!";
    $_SESSION["message"] = $message;                            

    header("location: /");
  }else{
    $message = $edit;
  }
  die;
}

if(isset($_GET['id']) && is_int(intval($_GET['id']))){
  $record = $product->viewRecord(intval($_GET['id']));
  $statuses = $product->fetchStatuses();
}else{
  header("location: /");
}

?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <?php require_once '../../assets/style_and_scripts.php'; ?>
    <link rel="stylesheet" href="/assets/css/add_product.css" type="text/css">
</head>
<body>
<?php include '../../templates/navbar.php'; ?>

<div class="wrapper">

<?php if(isset($message)){ ?>
<div class="alert alert-info alert-dismissible fade show" role="alert">
  <p class="text-center ml-3"><?php echo $message ?></p>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<?php } ?>

<div class="float-left" style="margin-bottom: 3%;">
    <a class="btn btn-sm btn-dark" href="/">Home</a>
</div>
<div class="m-5">
<h2 class="h2 m-4">Edit Product</h2>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
  <div class="form-group">
    <label for="name">Product Name</label>
    
  <!-- $statuses  -->
    <input type="text" class="form-control" required="required" name="name" id="name" value="<?php echo $record['name'] ?>" aria-describedby="nameHelp" placeholder="Enter Product Name">
  </div>
  <div class="form-group">    
    <label for="price">Price</label>
    <input type="number" step="0.01" required="required" min="0" id="price" value="<?php echo $record['price'] ?>" name="price" class="form-control" aria-describedby="priceHelp">
  </div>
  
  <div class="form-group">    
    <label for="quantity">Quantity</label>
    <input type="hidden" name="id" value="<?php echo $record['id'] ?>">
    <input type="number" min="0" id="quantity" required="required" name="quantity" class="form-control" value="<?php echo $record['quantity'] ?>" aria-describedby="quantityHelp">
  </div>
  
  <div class="form-group">    
    <label for="status">Status</label>
    <select id="status" required="required" name="status" class="form-control" value="<?php echo $record['status_id'] ?>" aria-describedby="selectHelp">
    <?php foreach($statuses as $status){ ?>
      <option value="<?php echo $status['id'] ?>"><?php echo $status['name'] ?></option>
    <?php } ?>
    </select>
  </div>
  
  <button type="submit" class="btn btn-primary" name="edit_product">Edit Product</button>
</form>
</div>

</div>    
<?php include '../../templates/footer.php'; ?>
</body>
</html>