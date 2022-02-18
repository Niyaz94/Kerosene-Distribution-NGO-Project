<?php 

session_start();
$_SESSION=array();
session_destroy();



//print_r($_SESSION["languageSetting"]);
//echo '<script >sessionStorage.clear();</script>';
header("Location: ../ ");



?>

<script  >
    sessionStorage.clear();
    window.location='../';
</script>