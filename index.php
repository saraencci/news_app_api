<?php
require_once('includes/config.php');
require_once('includes/database.class.php');
$db= new database($pdo);
$db->clietBookingMobile(); 
?>




