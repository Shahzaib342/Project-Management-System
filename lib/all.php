<?php
include '../vendor/autoload.php';

use Inc\Models\Products as Products;

/**
 *
 * @type {{Created by Shahzaib 14 August,2019}}
 */

$action = (isset($_GET['action'])) ? $_GET['action'] : die('Nothing to do');
$actions = array();
array_push($actions, array('name' => 'sampleFunction', 'action' => 'sampleFunction'));

// Go through the actions list and run the associated functions
foreach ($actions as $act) {
    if ($act['name'] == $action) {
        $functionName = $act['action'] . '();';

        eval($functionName);
    }
}

//Sample function
function sampleFunction()
{
    //code go here
}