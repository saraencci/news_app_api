<?php

$host= "127.0.0.1";
$db_name="news_api_database";
$db_username="root";
$db_password="";

try{
    
    $pdo= new PDO('mysql:host='. $host .';dbname='.$db_name, $db_username, $db_password);
    $pdo->exec("set names utf8");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   
}catch(PDOException $e){

    exit("Error Connectiong to database" . $e->getMessage()) ;

}


?>