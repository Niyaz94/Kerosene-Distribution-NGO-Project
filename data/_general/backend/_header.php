<?php
    if(!isset($_SESSION)) { 
        session_start(); 
    }
    date_default_timezone_set($_SESSION['SYSTimezone']);

    if(file_exists("../_general/backend/validationData.php")){
        if(!isset($_SESSION['is_login']) ){
            header("location: login.php");
            echo '<script>window.location="login.php";</script>';
        }    
        require_once "../_general/backend/validationData.php";
        require_once "../_general/backend/db_connect.php";
        require_once "../_general/backend/generalFunctions.php";
    }else if(file_exists("../../_general/backend/generalFunctions.php")){
        if(!isset($_SESSION['is_login']) ){
            header("location: login.php");
            echo '<script>window.location="../login.php";</script>';
        }    
        //require_once "../../_general/backend/validationData.php";
        require_once "../../_general/backend/db_connect.php";
        require_once "../../_general/backend/generalFunctions.php";
    }
    
?>