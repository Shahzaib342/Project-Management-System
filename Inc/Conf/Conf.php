<?php

namespace Inc\Conf;

/**
 *
 * @type {{Created by Shahzaib 14 August,2020}}
 */

class Conf
{

    function connectMe()
    {
        $serverName = "localhost";
        $username = "root";
        $password = "";
        $db = "pms";
        /* Attempt to connect to MySQL database */
        $link = mysqli_connect($serverName, $username, $password, $db);

       // Check connection
       if($link === false){
          die("ERROR: Could not connect. " . mysqli_connect_error());
        }
       else
         return $link;
        }

}