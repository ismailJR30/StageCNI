<?php
function getDbConnection($databaseName = 'bd_arabe')
{
    static $connections = [];

    if (!isset($connections[$databaseName])) {
        $connections[$databaseName] = mysqli_connect("localhost", "root", "", $databaseName);

        if (!$connections[$databaseName]) {
            die("Database connection failed: " . mysqli_connect_error());
        }

        mysqli_set_charset($connections[$databaseName], "utf8");
    }

    return $connections[$databaseName];
}
