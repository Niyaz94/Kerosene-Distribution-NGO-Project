<?php
    function jsonMessages($type,$number){
        $data="";
        if($type==true){
            if($number==1){
                $data="The Process Finished Sucessfully.";
            }else if($number==2){
                $data="The Data added Successfully.";
            }else if($number==3){
                $data="The Delete Operation Finished Successfully";
            }else if($number==4){
                $data="Successfully Update Your System Information";
            }else if($number==5){
                $data="Empty";
            }
        }else{
            if($number==1){
                $data="Error!!! Something Wrong Please Try Again.";
            }else if($number==2){
                $data="Erorr!!! The Name Repeated.";
            }else if($number==3){
                $data="Error!!! You can't Delete Because in the System have translated word.";
            }else if($number==4){
                $data="Error!!! Passwords does not match.";
            }else if($number==5){
                $data="Error!!! You Are Not Allowed To perform this Action.";
            }else if($number==6){
                $data="Error!!! Super Admin Can Not be Deleted or Deactive, it Should be at least one Super Admin.";
            }else if($number==7){
                $data="Error!!! We have the same Username please enter another Username.";
            }else if($number==8){
                $data="Error!!! We have No Data for Update All data the same.";
            }else if($number==9){
                $data="Error!!! Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            }else if($number==10){
                $data="Error!!! Sorry, your file is too large.";
            }else if($number==11){
                $data="Error!!! File is not an image.";
            }else if($number==12){
                $data="Error!!! All Data The Same, Not Need Update.";
            }else if($number==13){
                $data="Error!!! The Date Wrong, Must be Enter greter Date.";
            }else if($number==14){
                $data="Error!!! No Product For This Camp Available or product Amount Zero";
            }else if($number==15){
                $data="Error!!! Not Fount This Barcode In System !!!";
            }else if($number==16){
                $data="Error!!! Please Enter CSV File !!!";
            }else if($number==17){
                $data="Error!!! No Active Round In the System !!!";
            }else if($number==18){
                $arabic="العائلة قد استنفذت استحقاقها من النفط الأبيض لهذه الدورة. رجاءا المراجعة في الدورة التالية";
                $english="The family has received all it's entitlements of kerosene for this round. Please come back in the next round";
                $data="<h6>".$arabic."</h6><h6>".$english."</h6>";
            }else if($number==19){
                $data="Error!!! The End Date of Round Must be Greater Than Start Date !!!";
            }else if($number==20){
                $data="Error!!! The Date Of Distribution of the round ended !!!";
            }else if($number==21){
                $data="Error!!! The Family Case ID must not repeat!!!";
            }else if($number==22){
                $data="Error!!! The Distribution Time Ended You Can't Distributed Today !!!";
            }else if($number==23){
                $data="Error!!! You can't delete this family because they're already receive distrbution (The family should remain for archive purpose).";
            }else if($number==24){
                $data="Error!!! You can't delete this product because It's already receive to the families (The product should remain for archive purpose).";
            }else if($number==25){
                $data="Error!!! You can't delete this camp because families are there.";
            }else if($number==26){
                $data="Error!!! You can't delete this Round because families already receive distrbution in this round.";
            }else if($number==27){
                $data="Error!!! This family does not belong this Camp, They have to go to their camp.";
            }else if($number==28){
                $data="Error!!! Some rows not inserted succussfully please check the CSV file and try again!!!";
            }
        }
        return json_encode(array(
            "is_success"=>$type,
            "data"=>$data
        ));
    }
    function jsonMessages2($type,$data){
        return json_encode(array(
            "is_success"=>$type,
            "data"=>$data
        ));
    }
    function testData($data,$type=0){
        if($type=="0"){
            print_r($data);
        }else if($type=="1"){
            echo $data;
        }
        exit;
    }

    function returnPermissionJSON(){
        return json_decode(file_get_contents("models/json/permission.json"),true);
    }
    function checkLoginActive(){
        $data=json_decode(html_entity_decode(is_string($_SESSION["userPermission"])?$_SESSION["userPermission"]:array()),true);
        $newData=array();
        if(is_array($data)){
            foreach($data as $key=>$value){
                if($data[$key]["active"]==1){
                    $newData[]=$key;
                }
            }
        }
        return $newData;
    }
    function uploadingImageFile($file,$path,$name){
        $target_file = $path . basename($fileSaveName);
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		if(strlen($file[$name]["tmp_name"])==0 || getimagesize($file[$name]["tmp_name"]) == false) {// Check if image file is a actual image or fake image
            return "false11";
        }
		if ($file[$name]["size"] > 1500000) {
            return "false10";	
        }
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
            return "false9";
        }
        $result=move_uploaded_file($_FILES[$name]["tmp_name"], $target_file);
        if($result){
            chmod($target_file,0777);
            return "true1";
        }else{
            return "false1";
        }
        
        
    }
    function uploadingImageFileV2($file,$path){
        $fileDatabaseName=array();
        foreach($file as $fileName=>$fileInfo){
            if(isset(explode("_",$fileName)[1][3]) && explode("_",$fileName)[1][3]=="R" && $file[$fileName]["size"]==0){//file required and user not uploaded
                return jsonMessages2(false,"Inserting Image Required Please Upload Image.");
            }
            if(isset(explode("_",$fileName)[1][3]) && explode("_",$fileName)[1][3]=="E" ){//file remove image
                $fileDatabaseName[$fileName]="";
                continue;
            }
            if($file[$fileName]["size"]==0){
                continue;
            }
            if ($file[$fileName]["size"] > 1500000) {
                return jsonMessages2(false,"The Image To large Please Enter Another Image.");	
            }
            if($file[$fileName]["type"] != "image/jpg" && $file[$fileName]["type"] != "image/png" && $file[$fileName]["type"] != "image/jpeg" && $file[$fileName]["type"] != "image/gif" ){
                return jsonMessages2(false,"The File Not Image, Please Upload Image File.");	
            }
            $fileSaveName = rand(1000,100000) . "-" . $_FILES[$fileName]['name'];
            $target_file = $path . basename($fileSaveName);
            $result=move_uploaded_file($_FILES[$fileName]["tmp_name"], $target_file);
            if($result){
                chmod($target_file,0777);
                $fileDatabaseName[$fileName]=$fileSaveName;
            }
        }
        return $fileDatabaseName;
    }
    function output($data,$succ=2,$fail=1){
        if ($data>0) {	
            return jsonMessages(true,$succ);
        }else{
            return jsonMessages(false,$fail);
        }
    }
?>