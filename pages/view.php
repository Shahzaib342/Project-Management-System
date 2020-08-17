<?php

// include './vendor/autoload.php';
// // Include config file
// use Inc\Models\Products as Products;
require_once './Inc/Models/Products.php';

// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: auth/login.php");
    exit;
}else{
    $product = new \Inc\Models\Products;
    if(isset($_SESSION["message"])){
        $message = "<p class='text-success'>".$_SESSION["message"]."</p>";
        unset($_SESSION["message"]);
    }
    if(isset($_GET['delete']) && is_int(intval($_GET['delete']))){
        $message = $product->deleteRecord(intval($_GET['delete']));
        if(!$message){
            header("location: /");
        }
    }
    if(isset($_GET['archive']) && is_int(intval($_GET['archive']))){
        $message = $product->archiveRecord(intval($_GET['archive']));
        
        if(!$message){
            header("location: /");
        }
    }
    if(isset($_GET['view']) && is_int(intval($_GET['view']))){
        $message = $product->viewRecord(intval($_GET['view']));
        if(!$message){
            header("location: /");
        }else{
            header("location: /pages/products/view.php?id=".$_GET['view']);
        }
    }
    if(isset($_GET['edit']) && is_int(intval($_GET['edit']))){
        header("location: /pages/products/edit.php?id=".$_GET['edit']);
    }
    

    $allItems = $product->allItems();
    if(!is_array($allItems)){
        $noRecord = $allItems;
    }
}
?>
<div class="container pt-3 d-block">
	<p class="text-bold">Welcome <?php echo $_SESSION["username"] ; ?></p>
    <div class="d-block">
            <?php if(isset($message) || isset($noRecord)){ ?>
            <div class="d-block alert alert-info alert-dismissible fade show" role="alert">
                <p class="ml-3 text-center"><?php echo (isset($message) && !empty($message)) ? $message : '' ?></p>
                <?php if(isset($noRecord)){ ?>
                <p class="ml-3 text-center"><?php echo (isset($noRecord) && !empty($noRecord))? $noRecord : "" ?></p>
                <?php } ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php } ?>
        <div class="float-right m-3">
            <a class="btn btn-sm btn-primary" href="/pages/products/add.php">Add Product</a>
            <a class="btn btn-sm btn-dark" href="/pages/products/archive.php">View Archive</a>
        </div>
    </div>
        


    
    <table class="mt-5 table table-bordered table-hover text-center">
    <thead class="thead-dark">
        <tr>
        <!-- <th scope="col">#</th> -->
        <th scope="col">Name</th>
        <th scope="col">Price</th>
        <th scope="col">Quantity</th>
        <th scope="col">Status</th>
        <th scope="col-auto">Actions</th>
        </tr>
    </thead>
    <?php if(is_array($allItems) && !empty($allItems)){ ?>
    <tbody>
        <?php foreach($allItems as $items){ ?>
        <tr>
        <td scope="col"><?php echo $items['name'] ?></td>
        <td scope="col"><?php echo $items['price'] ?></td>
        <td scope="col"><?php echo $items['quantity'] ?></td>
        <td scope="col" class="badge badge-success badge-pill m-1"><?php echo $items['status'] ?></td>
        <td scope="col-auto">
        <a class="btn btn-sm btn-primary" href="/?edit=<?php echo $items['id'] ?>"> Edit </a> 
        <a class="btn btn-sm btn-dark" href="/?archive=<?php echo $items['id'] ?>"> Archive </a> 
        <a class="btn btn-sm btn-info" href="/?view=<?php echo $items['id'] ?>"> View </a> 
        <a class="btn btn-sm btn-danger" onclick="confirmAction('delete')" href="/?delete=<?php echo $items['id'] ?>"> Delete </a> </td>
        </tr>
        <?php }  ?>
    </tbody>
<?php } ?>
</table>
    <!-- </div>
    </div> -->

</div>