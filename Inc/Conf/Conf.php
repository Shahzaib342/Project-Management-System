<?php

namespace Inc\Conf;

/**
 *
 * @type {{Created by Shahzaib 14 August,2020}}
 */

use PDO;

class Conf
{

    function connectMe()
    {
        $serverName = "localhost";
        $username = "root";
        $password = "";
        $db = "pms";
        try {
            $conn = new PDO("mysql:host=$serverName;dbname=$db", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            return "Connection failed: " . $e->getMessage();
        }
    }

}