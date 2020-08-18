<?php

    namespace Inc\Models;


    /**
     *
     * @type {{Created by Shahzaib 14 August,2020}}
     */
    class Products
    {
        private $conn;

        //construct function to initialize DB Connection
        function __construct($api = false)
        {
            if(!$api){
                if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
                    header("location: auth/login.php");
                    exit;
                }
            }
            $serverName = "localhost";
            $username = "root";
            $password = "";
            $db = "pms";

            /* Attempt to connect to MySQL database */
            $link = mysqli_connect($serverName, $username, $password,$db);

        // Check connection
        if(mysqli_connect_errno()){
            die("ERROR: Could not connect. " . mysqli_connect_error());
            }
        else{
            //  return $link;
            $this->conn = $link;
            }
        }

        function viewRecord($id){
            $record = $this->getRecord($id);
            if(!$record || !is_array($record)){
                return "<p class=' text-danger'>Record does not exist</p>";
            }else{
                return $record;
            }
        }
        function getRecord($id){
            $link = $this->conn;     
            $sql = "SELECT `pr`.`id` as id, `pr`.`name` as name, `pr`.`price` as price, `pr`.`created_at` as created, `pr`.`modified_at` as modified, `pr`.`quantity` as quantity, `st`.`id` as status_id, `st`.`name` as status_name, `us`.username as created_by, `mus`.username as modified_by from `products` `pr` INNER JOIN `statuses` `st` ON `pr`.`status_id` = `st`.`id` INNER JOIN `users` `us` ON `pr`.`created_by` = `us`.`id` LEFT JOIN `users` `mus` ON `pr`.`modified_by` = `mus`.`id`  WHERE `pr`.`id` = ?";
            // mysqli_report(MYSQLI_REPORT_ALL);
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "i", $id);            
                if(mysqli_stmt_execute($stmt)){
                    $res = $stmt->get_result();
                    if($res->num_rows < 1){
                        return false;
                    }else{
                        return $res->fetch_assoc();
                    }                
                } else{
                    echo "<p class='text-danger '>Oops! Something went wrong. Please try again later.</p>";
                }
                // Close statement
                mysqli_stmt_close($stmt);
            }
        }
        function archievedItems(){
            $link = $this->conn;                
            //get status id
            $status = $this->getStatusId("archived");

            $sql = "SELECT `pr`.`id` as id, `pr`.`name` as name, `pr`.`price` as price, `pr`.`quantity` as quantity, `st`.`name` as status_name from `products` `pr` INNER JOIN `statuses` `st` ON  `pr`.`status_id` = `st`.`id` WHERE `st`.`id` = ?";
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "i", $status);
                if(mysqli_stmt_execute($stmt)){                
                    $res = $stmt->get_result();
                    if($res->num_rows < 1){
                        return "<p class=' text-info'>There is currently no archived product.</p>";
                    }else{
                        $fetchedData = [];
                        $i = 0;
                        while($data = $res->fetch_assoc()){
                            $fetchedData[$i]['id'] = $data['id'];
                            $fetchedData[$i]['name'] = $data['name'];
                            $fetchedData[$i]['status'] = $data['status_name'];
                            $fetchedData[$i]['price'] = $data['price'];
                            $fetchedData[$i]['quantity'] = $data['quantity'];
                            $i++;
                        }
                        return $fetchedData;
                    }                
                } else{
                    echo "<p class='text-danger'>Oops! Something went wrong. Please try again later.</p>";
                }
                // Close statement
                mysqli_stmt_close($stmt);
            }
        }

        function fetchStatuses(){
            $link = $this->conn;            
            $sql = "SELECT `id`, `name` from `statuses`";
            if($stmt = mysqli_prepare($link, $sql)){        
                if(mysqli_stmt_execute($stmt)){
                    $res = $stmt->get_result();
                    if($res->num_rows < 1){
                        return [];
                    }else{
                        $fetchedData = [];
                        while($data = $res->fetch_assoc()){
                            $fetchedData[] = $data;
                        }
                        return $fetchedData;
                    }                
                } else{
                    echo "<p class='text-danger'>Oops! Something went wrong. Please try again later.</p>";
                }
                // Close statement
                mysqli_stmt_close($stmt);
            }
        }
        function allItems($field = "id", $order = "ASC")
        {
            $link = $this->conn;            
            //get status id
            $status = $this->getStatusId("archived");
            $sql = "SELECT `pr`.`id` as id, `pr`.`name` as name, `pr`.`price` as price, `pr`.`quantity` as quantity, `st`.`name` as status_name from `products` `pr` INNER JOIN `statuses` `st` ON `pr`.`status_id` = `st`.`id` WHERE `pr`.`status_id` != ? ORDER BY $field $order";
            
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "i", $status);            
                if(mysqli_stmt_execute($stmt)){
                    $res = $stmt->get_result();
                    if($res->num_rows < 1){
                        return "<p class=' text-info'>There is currently no active product in the table.</p>";
                    }else{
                        $fetchedData = [];
                        $i = 0;
                        while($data = $res->fetch_assoc()){
                            $fetchedData[$i]['id'] = $data['id'];
                            $fetchedData[$i]['name'] = $data['name'];
                            $fetchedData[$i]['status'] = $data['status_name'];
                            $fetchedData[$i]['price'] = $data['price'];
                            $fetchedData[$i]['quantity'] = $data['quantity'];
                            $i++;
                        }
                        return $fetchedData;
                    }                
                } else{
                    echo "<p class='text-danger'>Oops! Something went wrong. Please try again later.</p>";
                }
                // Close statement
                mysqli_stmt_close($stmt);
            }
        }
        
        function deleteRecord(int $id){
            $link = $this->conn;
            //check if product exists in the table
            $checkIfExists = $this->getRecord($id);
            if(!$checkIfExists){ //record is already active
                return false;
            }

            //delete
            $sql = "DELETE from  products  WHERE id = ?";
            $stmt = mysqli_prepare($link, $sql);
            mysqli_stmt_bind_param($stmt, "i", $id);
            if(mysqli_stmt_execute($stmt)){
                return "<p class='text-success'>Record deleted successfully!!</p>";
            }else{
                return "<p class='text-danger'>Oops! Something went wrong. Please try again later.</p>";
            }
            mysqli_stmt_close($stmt);
        }
        function archiveRecord(int $id){
            $link = $this->conn;
            //fetch active status' id
            $archived_status_id = $this->getStatusId("archived");
            //check if product is already active
            $checkIfExists = $this->getRecord($id);
            if(!$checkIfExists){ //record doesn't exist
                return false;
            }
            
            if($archived_status_id == $checkIfExists['status_id']){
                // return '<p class="text-warning">Record has been archived already</p>';
                return false;
            }
            //get status id
            //set the params to be updated
            $sql = "UPDATE  products SET status_id = ?  WHERE id = ?";
            $stmt = mysqli_prepare($link, $sql);
            mysqli_stmt_bind_param($stmt, "ii", $archived_status_id, $id);
            if(mysqli_stmt_execute($stmt)){
                return "<p class='text-success'>Record has been archived successfully!!</p>";
            }else{
                return "<p class='text-danger'>Oops! Something went wrong. Please try again later.</p>";
            }
            mysqli_stmt_close($stmt);
        }
        function makeActive(int $id){
            $link = $this->conn;
            //get status id
            $active_status_id = $this->getStatusId("active");
            //check if product is already active
            $checkIfExists = $this->getRecord($id);
            if(!$checkIfExists){ //record doesn't exist
                return false;
            }
            
            if($active_status_id == $checkIfExists['status_id']){
                // return '<p class="text-warning">Record has been made active already</p>';
                return false;
            }

            //set the params to be updated
            $sql = "UPDATE  products SET status_id = ?  WHERE id = ?";
            $stmt = mysqli_prepare($link, $sql);
            mysqli_stmt_bind_param($stmt, "ii", $active_status_id, $id);
            if(mysqli_stmt_execute($stmt)){
                return "<p class='text-success'>Record made active successfully!!</p>";
            }else{
                return "<p class='text-danger'>Oops! Something went wrong. Please try again later.</p>";
            }
            mysqli_stmt_close($stmt);
        }
    
        function getStatusId($status){
            $link = $this->conn;
            $sql = "SELECT id FROM statuses WHERE name = ?";
            $stmt = mysqli_prepare($link, $sql);
            mysqli_stmt_bind_param($stmt, "s", $status);
            mysqli_stmt_execute($stmt);
            $fetched = mysqli_stmt_get_result($stmt);
            $status_id = mysqli_fetch_assoc($fetched)['id']; //set the status id to $status_id
            return $status_id;
        }

        function addProduct($post){
            $link = $this->conn;
            //get status id
            $status_id = $this->getStatusId("active");
            //set the params to be inserted
            $name = trim($post['name']);
            $price = (isset($post['price'])) ? floatval(trim($post['price'])) : 0;
            $quantity = (isset($post['price'])) ? intval(trim($post['quantity'])) : 0;
            $created_at = date("Y-m-d H:i:s", time());
            $user_id = $_SESSION["id"];
            // insert into products table
            $addSql = "INSERT INTO products ( name, quantity, price, status_id, created_at, created_by ) VALUES (?,?,?,?,?,?)";
            $addStmt = mysqli_prepare($link, $addSql);
            mysqli_stmt_bind_param($addStmt, "sidisi", $name, $quantity, $price, $status_id, $created_at, $user_id);
            if(mysqli_stmt_execute($addStmt)){
                return "<p class='text-success'>Record created successfully!!</p>";
            }else{
                return "<p class='text-danger'>Oops! Something went wrong. Please try again later.</p>";
            }
            mysqli_stmt_close($addStmt);
        }
        
        function editProduct($post){
            $link = $this->conn;
            //get status id
            //set the params to be inserted
            $name = trim($post['name']);
            $price = floatval(trim($post['price']));
            $quantity = intval(trim($post['quantity']));
            $status_id =  intval(trim($post['status']));
            $id =  intval(trim($post['id']));
            $user_id = $_SESSION["id"];
            // insert into products table
            $editSql = "UPDATE  products SET name=?, price=?, quantity=?, status_id = ?, modified_by=?  WHERE id = ?";
            // mysqli_report(MYSQLI_REPORT_ALL);

            $editStmt = mysqli_prepare($link, $editSql);
            mysqli_stmt_bind_param($editStmt, "sdiiii", $name, $price, $quantity, $status_id, $user_id, $id);
            if(mysqli_stmt_execute($editStmt)){
                return true;
            }else{
                return "<p class='text-danger'>Oops! Something went wrong. Please try again later.</p>";
            }
            mysqli_stmt_close($editStmt);
        }
        

        //destroy DB connection in destruct function
        function __destruct()
        {
            $this->conn = NULL;
        }
    }
