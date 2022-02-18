<?php
//mysql -u niyaz -p -h localhost kt_kerosene < dump.sql

    include_once "database.php";
    include_once 'ssp.php';
    if(!isset($_SESSION)) { 
        session_start(); 
    } 
    $_SESSION['database_name']   = "dtsblumont_kerosene_1";
    $datatable_connection = array('user' =>"dtsblumont_keentech_user",'pass'=>"WOH],_wt@InS,tY#",'db'=>$_SESSION['database_name'],'host' =>"localhost");
    $database=new class_database("localhost","dtsblumont_keentech_user","WOH],_wt@InS,tY#",$_SESSION['database_name']);
?>