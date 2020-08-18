<?php
include '../vendor/autoload.php';

use Inc\Models\Products as Products;

/**
 *
 * @type {{Created by Shahzaib 14 August,2019}}
 */
$order = (isset($_GET['order'])) ? $_GET['order'] : die('Nothing to do');
$field = (isset($_GET['field'])) ? $_GET['field'] : die('Nothing to do');

sortRecords($order, $field);


// $actions = array();
// array_push($actions, array('name' => 'sortRecords', 'action' => 'sortRecords'));

// // Go through the actions list and run the associated functions
// foreach ($actions as $act) {
//     if ($act['name'] == $action) {
//         $functionName = $act['action'] . '();';

//         eval($functionName);
//     }
// }

//Sample function
function sortRecords($order, $field)
{
    $products = new Products(true);
    $records = $products->allItems($field, $order);
    echo json_encode(['data' => $records]);
}