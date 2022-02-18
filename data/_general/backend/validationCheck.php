<?php
    if(file_exists("../../API/vendor/autoload.php") && is_readable("../../API/vendor/autoload.php")){
        include_once '../../API/vendor/autoload.php';
    }else if(file_exists("models/vendor/autoload.php") && is_readable("models/svendor/autoload.php")){
        include_once 'models/vendor/autoload.php';
    }else if(file_exists("../vendor/autoload.php") && is_readable("../vendor/autoload.php")){
        include_once '../vendor/autoload.php';
    }
    use Respect\Validation\Validator as validation_data;

	class valatador{

		public function __construct(){
		}
		function __destruct() {
			$this->validation_data=null;
        }
        public static function checkForTheSame($data){
            foreach ($data as $key1=>$value1){
                $split1=explode("_",$key1);
                if(!isset($split1[1])){
                    continue;
                }elseif($split1[1]=="IWW" || $split1[1]=="UWW"){
                    $checkField=$split1[0];
                    foreach ($data as $key2=>$value2){
                        $split2=explode("_",$key2);
                        if(!isset($split2[1])){
                            continue;
                        }else if($split1[0]==$split2[0] && !validation_data::identical($value1)->validate($value2) && $key1!=$key2){
                            return "false";
                        }
                    }
                }
            }
            return "true";
        }
        public static function checkForDifference($new,$old){
            return validation_data::identical($new)->validate($old)?"false":"true";
        }
        public static function checkInsideObject($data,$key){
            return validation_data::key($key)->validate($data)?"true":"false";
        }
	}
?>