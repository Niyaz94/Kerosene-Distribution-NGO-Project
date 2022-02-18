<?php
	ini_set('memory_limit','1024M');
	ini_set('max_execution_time', 1200);
	ini_set("pcre.backtrack_limit", "1000000000");
	
	require_once '../../API/vendor/autoload.php';
	require_once "../_general/backend/db_connect.php";
	require_once "../_general/backend/generalFunctions.php";

	include_once "../_general/backend/_header.php";
	require_once "../models/_reportClass.php";
    $_report=new _reportClass($database);
?>