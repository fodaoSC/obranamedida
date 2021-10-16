<?php

$db_host="localhost";
$db_user="root";
$db_password="";
$db_name="obra_na_medida";

try {

    $db = new PDO("mysql:host{$db_host}; dbname={$db_name}", $db_user, $db_password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "ALL OK";

}   catch(PDOEXCEPTION $e) {
    $e->getMessage();
    // echo "Error";
}


?>