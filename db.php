<!-- This file is for connecting sql server to your website -->
<?php
$serverName = "localhost"; //The name of your sql server
$connectionOptions = array(
    "Database" => "Leave",//the name of your database
    "Uid" => "",//Username of your database
    "PWD" => "",//Password of your database
    "CharacterSet" => "UTF-8"
);
$conn = sqlsrv_connect($serverName, $connectionOptions);

if (!$conn) {
    die(print_r(sqlsrv_errors(), true));
}
?>