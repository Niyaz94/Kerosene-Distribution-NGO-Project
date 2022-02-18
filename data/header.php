<?php
    include_once "_general/backend/generalTemplate.php";
    include_once "_general/backend/generalFunctions.php";

    $pageInfo=json_decode($_returnDataFromFile->returnDataByPageName(basename($fileName,".php")),true);
   
    $type=$pageInfo["type"];
    if(!isset($_SESSION["is_login"])){
        header("location: login.php");
        echo "<script>window.location=`login.php`;</script>";
    }
    if($_SESSION["ADMProfileType"]==1){
        if($type=="devloper"){
            echo "<script>window.location=`starter.php`;</script>";
            exit;
        }
    }else if($_SESSION["ADMProfileType"]==0){
        $id=$pageInfo["id"];
        if($type=="devloper" || $type=="admin" || ($pageInfo["page_type"]=="normal" && !in_array($id,checkLoginActive()) ) ){
            echo "<script>window.location=`starter.php`;</script>";
            exit;
        }
    }
?>
<input type="hidden" id="ADMProfileType" name="ADMProfileType" value="<?php echo $_SESSION["ADMProfileType"];?>">
<input type='hidden' id='userPermission' name='userPermission' value="<?php echo strlen($_SESSION["userPermission"])>0?$_SESSION["userPermission"]:"{}";?>">
<input type='hidden' id='pageInfo' name='pageInfo' value='<?php echo json_encode($pageInfo);?>'>
<input type='hidden' id='PageName' name='PageName' value='<?php echo $fileName;?>'>
<link rel="stylesheet" href="_general/style/<?php echo basename($fileName,".php");?>.css">