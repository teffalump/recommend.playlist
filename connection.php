<?php

/* This file will handle all the connection-related actions required to connect to the database */

require_once "variables.php";

$connection = new Mongo();
$db = $connection->DATABASE;
?>


