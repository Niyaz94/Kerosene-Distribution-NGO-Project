<?php
	class _returnDataFromFile{
		private $fileContaent;
		public function __construct($fileName,$fileType="json"){
            if(file_exists("_general/json/$fileName.$fileType") && is_readable("_general/json/$fileName.$fileType")){
                $this->fileContaent=json_decode(file_get_contents("_general/json/$fileName.$fileType"),true);
            }else if(file_exists("../_general/json/$fileName.$fileType") && is_readable("../_general/json/$fileName.$fileType")){
                $this->fileContaent=json_decode(file_get_contents("../_general/json/$fileName.$fileType"),true);
            }else if(file_exists("../../_general/json/$fileName.$fileType") && is_readable("../../_general/json/$fileName.$fileType")){
                $this->fileContaent=json_decode(file_get_contents("../../_general/json/$fileName.$fileType"),true);
            }
		}
		public function returnFullData(){
			return $this->fileContaent;
        }
        public function returnFullReport(){
            $total=count($this->fileContaent);
            $report=array();
            for($i=0;$i<$total;$i++){
                if($this->fileContaent[$i]["page_type"]=="report"){
                    array_push($report,$this->fileContaent[$i]);
                }
            }
			return $report;
        }
        public function returnDataByPageName($page){
            $total=count($this->fileContaent);
            for($i=0;$i<$total;$i++){
                if($this->fileContaent[$i]["page"]==$page){
                    return json_encode($this->fileContaent[$i]);
                }
            }
			return json_encode(array());
        }
        function returnALLPermissionPage(){
            $detailPage=array();
            for($i=0;$i<count($this->fileContaent);$i++){
                if($this->fileContaent[$i]["type"]!="devloper" && $this->fileContaent[$i]["type"]!="admin" && $this->fileContaent[$i]["page_type"]!="report"){
                    $detailPage[]=$this->fileContaent[$i];
                }
            }
            return json_encode($detailPage);
        }
        function returnALLPermissionButton(){
            $detailUser=array();
            for($i=0;$i<count($this->fileContaent);$i++){
                if($this->fileContaent[$i]["type"]!="devloper" && $this->fileContaent[$i]["type"]!="admin" && $this->fileContaent[$i]["page_type"]!="report"){
                    $buttonArray=array();
                    for($j=0;$j<count($this->fileContaent[$i]["buttons"]);$j++){
                        $buttonArray[$this->fileContaent[$i]["buttons"][$j]["id"]]=$this->fileContaent[$i]["buttons"][$j]["default"];
                    }
                    $detailUser[$this->fileContaent[$i]["id"]]=array("active"=>1,"buttons"=>$buttonArray);
                }
            }
            return json_encode($detailUser);
        }
		function __destruct() {
			$this->fileContaent=null;
		}
	}
?>