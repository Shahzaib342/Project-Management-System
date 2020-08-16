<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: auth/login.php");
    exit;
}
?>
<div class="container pt-3">
	<p class="text-bold">Welcome <?php echo $_SESSION["username"] ; ?></p>
Product View will go here as a tabular form and add a button on this page above the table which will take user to another page for adding a product
</div>