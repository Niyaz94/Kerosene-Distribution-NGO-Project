<?php
	include_once '../../API/vendor/autoload.php';
    use Respect\Validation\Validator as validation_data;
	/* 
		The First Letter after _
		I => Insert
		U => Update
		B => if not exist Insert Otherwise Update

		The Second Letter after _
		S => String
		W => Password
		P => Phone Num

		A => ckeditor
		B => Photo Input File
		C => select
		D => Date
		E => Email
		F => Photo image element
		G => Select2 database
		H => Select2
		I => Integer Number
		J => Deceal Number
		K => data just year and month
		L => bootstrapDualListbox
		M => multiselect
		
		The Thered Letter after _
		Z => Not use for show data to user
		R => use for show data to u

		The Fourth Letter after _
		P => primary Key
		F => foriegn Key
		R => Require
		E => this Letter just use with file upload update mean removed image
		N => Not Used
		A => Input Read Only 

		If after _ , this string have
		UWW or IWW => this field not insert in database and must be the same as the first part
		If after _ ,this string have
		RRR => this mean the input read only not need update or insert** this is have problem
	*/
	class class_validation{

		private $userData;
		public function __construct($userdata,$startWith){
			$this->userData=$this->cleanData($userdata,$startWith);
		}
		public function removeUseless($data,$startWith){
			$new_data=array();
			foreach($data as $key=>$value){
				if(validation_data::startsWith($startWith)->validate($key) || $key=="PageName" || $key=="LogType"){
					$new_data[$key]=$value;
				}
			}
			return $new_data;
		}
		public function cleanData($data,$startWith){
			foreach($data as $key=>$value){
				$data[$key]=$this->securityTest($value);
			}
			$data=$this->removeUseless($data,$startWith);
			$data=$this->splitKeys($data);

			return $data;
		}
		public function splitKeys($data){
			$new_data=array();
			foreach($data as $key=>$value){
				$splitKey=explode("_",$key);

				if(isset($splitKey[1][3]) && $splitKey[1][3]=="P"){//if the column Primary key
					$new_data[$splitKey[0]]=$value;
					$new_data["primaryKey"]=array("key"=>$splitKey[0],"value"=>$value);
				}else if(isset($splitKey[1][3]) && $splitKey[1][3]=="F"){//if the column foreign key
					$new_data[$splitKey[0]]=$value;
					$new_data["foreignKey"]=array("key"=>$splitKey[0],"value"=>$value);
				}else if(isset($splitKey[1][3]) && $splitKey[1][3]=="A"){//if the input readonly
					continue;
				}else if(isset($splitKey[1][1]) && $splitKey[1][1]=="N"){//if the input Multi Select Array we not need the data array
					continue;
				}else if(isset($splitKey[1]) && $splitKey[1]=="RRR"){//if the input readonly, In the feauture we remove this
					continue;
				}else{
					if(isset($splitKey[1]) && $splitKey[1][1]=="W"){//if password fiels
						$new_data[$splitKey[0]]=hash('sha256',$value);
					}/*else if(isset($splitKey[1]) && $splitKey[1][1]=="N"){
						$new_data[$splitKey[0]]=""$value;
					}*/else{
						$new_data[$splitKey[0]]=$value;
					}
				}
			}
			return $new_data;
		}
		public function securityTest($data) {


			if(!is_array($data)){
				return htmlspecialchars(addslashes(trim($data)));
			}else{
				return $data;
			}
			//$data = stripslashes($data);
		}	
		public function returnLastVersion(){
            return $this->userData;
		}

		function __destruct() {
			$this->validation_data=null;
		}
	}
?>