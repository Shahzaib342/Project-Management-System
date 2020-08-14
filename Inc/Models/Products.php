<?php

namespace Inc\Models;

use Inc\Conf\Conf as DB;

/**
 *
 * @type {{Created by Shahzaib 14 August,2020}}
 */
class Products
{
    private $conn;

    //construct function to initialize DB Connection
    function __construct()
    {
        $this->conn = DB::connectMe();
    }

    //comment
    function sampleFunction()
    {
        //code
    }

    //destroy DB connection in destruct function
    function __destruct()
    {
        $this->conn = NULL;
    }
}



