<?php
	require_once "validationCheck.php";
	class class_database{
		private $conn_pdo;
		public function __construct($servername,$username,$password,$db_name){
			$this->conn_pdo = new PDO("mysql:host=".$servername.";dbname=".$db_name.";",$username,$password);
			$this->conn_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
			$this->conn_pdo->exec("SET NAMES 'utf8'");
			$this->conn_pdo->exec('SET CHARACTER SET utf8');
		}
		public function return_data($sql_code,$type){
			/*
				index_all: return all data with index/value
				key_all: return all data with key/value
				both_all: return all data with both index/value and key/value
				index: return 1 row with index/value
				key: return 1 row with key/value
				both: return 1 row with both index/value and key/value
			*/
			if($data=$this->conn_pdo->query($sql_code)){
				if($type=="index_all"){
					return $data->fetchAll(PDO::FETCH_NUM);					
				}elseif($type=="key_all"){
					return $data->fetchAll(PDO::FETCH_ASSOC);					
				}elseif($type=="both_all"){
					return $data->fetchAll();										
				}elseif($type=="index"){
					return $data->fetch(PDO::FETCH_NUM);					
				}elseif($type=="key"){
					return $data->fetch(PDO::FETCH_ASSOC);					
				}elseif($type=="both"){
					return $data->fetch();										
				}else if($type=="row_count"){
					return $data->rowCount();															
				}else{
					return array();
				}
			}
		}
		public function return_data2($sql){
			$select="select ".implode(",",$sql["columnsName"])." ";
			$from="from ".implode(",",$sql["tablesName"])." ";
			$where="where ".$this->whereCondition($sql["conditions"]);
			$others=$sql["others"];
			$type=$sql["returnType"];
			$sql_code=$select.$from.$where.$others;
			//echo $sql_code;
			//exit;

			if($data=$this->conn_pdo->query($sql_code)){
				if($type=="index_all"){
					return $data->fetchAll(PDO::FETCH_NUM);					
				}elseif($type=="key_all"){
					return $data->fetchAll(PDO::FETCH_ASSOC);					
				}elseif($type=="both_all"){
					return $data->fetchAll();										
				}elseif($type=="index"){
					return $data->fetch(PDO::FETCH_NUM);					
				}elseif($type=="key"){
					return $data->fetch(PDO::FETCH_ASSOC);					
				}elseif($type=="both"){
					return $data->fetch();										
				}else if($type=="row_count"){
					return $data->rowCount();															
				}else{
					return array();
				}
			}
		}
		public function return_data3($sql){
			$returnParimaryKey=$this->return_data("
				SELECT
					k.table_name,k.column_name
				FROM
					information_schema.table_constraints t JOIN 
					information_schema.key_column_usage k 
					USING(CONSTRAINT_NAME,table_schema,TABLE_NAME)
				WHERE
					t.constraint_type = 'PRIMARY KEY' AND 
					t.table_schema='".$_SESSION['database_name']."' and
					t.table_name in (".implode(",",$sql["tablesName"]).");
			","key_all");

			$referenceCondion="WHERE ";
			for($i=0;$i<count($returnParimaryKey);++$i){
				$referenceCondion.="(REFERENCED_TABLE_NAME = '".$returnParimaryKey[$i]["table_name"]."' AND REFERENCED_COLUMN_NAME ='".$returnParimaryKey[$i]["column_name"]."')  ".(count($returnParimaryKey)-1!=$i?"OR ":" ");
			}
			$refrences=$this->return_data("
				SELECT
					TABLE_NAME,TABLE_SCHEMA,COlUMN_NAME,REFERENCED_TABLE_NAME,REFERENCED_TABLE_SCHEMA,REFERENCED_COlUMN_NAME
				FROM
					information_schema.KEY_COLUMN_USAGE
				$referenceCondion
			","key_all");
			$extraCondion="";
			for($i=0;$i<count($refrences);++$i){
				if(in_array("'".$refrences[$i]["TABLE_NAME"]."'",$sql["tablesName"])){
					$extraCondion.= $refrences[$i]["TABLE_NAME"].".".$refrences[$i]["COlUMN_NAME"]."=".$refrences[$i]["REFERENCED_TABLE_NAME"].".".$refrences[$i]["REFERENCED_COlUMN_NAME"]." AND "/*(count($refrences)-1!=$i?" And ":" ")*/;
				}
			}
			$select="select ".implode(",",$sql["columnsName"])." ";
			$from="from ".str_replace("'","",implode(",",$sql["tablesName"]))." ";
			$where="where ".$extraCondion.$this->whereCondition($sql["conditions"]);
			$others=$sql["others"];
			$type=$sql["returnType"];
			$sql_code=$select.$from.$where.$others;
			//echo $sql_code;
			//exit;

			if($data=$this->conn_pdo->query($sql_code)){
				if($type=="index_all"){
					return $data->fetchAll(PDO::FETCH_NUM);					
				}elseif($type=="key_all"){
					return $data->fetchAll(PDO::FETCH_ASSOC);					
				}elseif($type=="both_all"){
					return $data->fetchAll();										
				}elseif($type=="index"){
					return $data->fetch(PDO::FETCH_NUM);					
				}elseif($type=="key"){
					return $data->fetch(PDO::FETCH_ASSOC);					
				}elseif($type=="both"){
					return $data->fetch();										
				}else if($type=="row_count"){
					return $data->rowCount();															
				}else{
					return array();
				}
			}
		}	
		public function insert_data($sql_code){
			$this->conn_pdo->query($sql_code);
			return $this->conn_pdo->lastInsertId();
		}
		public function insert_data2($table_name,$user_data){
			$table_info=$this->conn_pdo->query("
				select 
					COLUMN_NAME,
					ORDINAL_POSITION,
					COLUMN_DEFAULT,
					IS_NULLABLE,
					DATA_TYPE,
					COLUMN_TYPE,
					COLUMN_KEY,
					COLUMN_COMMENT,
					NUMERIC_PRECISION
				from 
					information_schema.columns 
				where 
					table_schema = '".$_SESSION['database_name']."' and 
					table_name = '$table_name'
			")->fetchAll(PDO::FETCH_ASSOC);

			$sql="INSERT INTO $table_name( ";
			$table_length=count($table_info);
			//add columns name
			for($i=0;$i<$table_length;++$i){
				if($table_info[$i]["COLUMN_KEY"]=="PRI")//if the column is primary key
					continue;
				else if(explode(",",$table_info[$i]["COLUMN_COMMENT"])[0]=="active" || valatador::checkInsideObject($user_data,$table_info[$i]["COLUMN_NAME"])=="true")
					$sql.=$table_info[$i]["COLUMN_NAME"].($table_length-1==$i ?"":",");
			}
			$sql .=" )VALUES( ";
			//add values
			$logValue=array();
			for($i=0;$i<$table_length;++$i){
				if($table_info[$i]["COLUMN_KEY"]=="PRI")
					continue;
				else if(explode(",",$table_info[$i]["COLUMN_COMMENT"])[0]=="active" || valatador::checkInsideObject($user_data,$table_info[$i]["COLUMN_NAME"])=="true"){

					$valueData=isset($user_data[$table_info[$i]["COLUMN_NAME"]])
					?
					$user_data[$table_info[$i]["COLUMN_NAME"]]://this column exist from post data sended by user
					$this->returnDefaultData($table_info[$i]["DATA_TYPE"],$table_info[$i]["COLUMN_DEFAULT"],$table_info[$i]["COLUMN_COMMENT"]);
					
					$logValue[]=array($table_info[$i]["COLUMN_NAME"],"",$valueData);
					$sql.=
						$this->checkColumnType($table_info[$i]["NUMERIC_PRECISION"]).//add qoutation if data string
						(
							$valueData
						).
						$this->checkColumnType($table_info[$i]["NUMERIC_PRECISION"]).($table_length-1==$i ?"":",");//add qoutation if data string
				}
			}
			$sql .=" ) ";
			//echo $sql;
			//exit;
			$this->conn_pdo->query($sql);
			$id=$this->conn_pdo->lastInsertId();

			$this->system_log($table_name,$user_data["PageName"],"Insert",$id,1,$logValue);
			return $id;
		}
		public function insert_prepare($sql,$feild,$value){		
			$stmt = $this->conn_pdo->prepare($sql);
			for($i=0;$i<count($value);$i++){
				for($j=0;$j<count($feild);$j++){
					$stmt->bindParam(':'.$feild[$j],$value[$i][$j]);
				}
				$stmt->execute();
			}
			if($stmt){
				return true;
			}else{
				return false;
			}
		}
		public function update_data($sql_code){
			if($this->conn_pdo->query($sql_code))
				return true;
			else
				return false;
		}
		public function update_data2($data){
			if(isset($data["userData"]["primaryKey"])){//adding primary key to condtion
				array_push(
					$data["conditions"],array("columnName"=>$data["userData"]["primaryKey"]["key"],"operation"=>"=","value"=>$data["userData"]["primaryKey"]["value"],"link"=>"")
				);
			}else if($data["userData"]["foreignKey"]){//adding foreign key to condtion
				array_push(
					$data["conditions"],array("columnName"=>$data["userData"]["foreignKey"]["key"],"operation"=>"=","value"=>$data["userData"]["foreignKey"]["value"],"link"=>"")
				);
			}
			
			$userDataOld = $this->return_data2(array(
				"tablesName"=>array($data["tablesName"]),
				"columnsName"=>array("*"),
				"conditions"=>$data["conditions"],
				"others"=>"",
				"returnType"=>"key"
			));
			
			$newUserData=array();
			foreach($data["userData"] as $key=>$value){
				if(isset($userDataOld[$key]) && $userDataOld[$key]!=$value){
					$newUserData[$key]=array("old"=>$userDataOld[$key],"new"=>$value);
				}
			}
			//print_r($newUserData);
			//exit;
			$update="update ".$data["tablesName"]." ";
			if(strlen($this->setUpdate($newUserData))>0){
				$set="set ".$this->setUpdate($newUserData);
			}else{
				return -1;//mean we have no data for update
			}

			$where="where ".$this->whereCondition($data["conditions"]);
			$sql=$update." ".$set." ".$where;
			//echo $sql;
			//exit; 
			$res=$this->conn_pdo->query($sql);
			$new=array();
			foreach($newUserData as $key=>$value){
				$new[]=array($key,$value["old"],$value["new"]);
			}
			if($res && isset($data["userData"]["primaryKey"])){
				$this->system_log(
					$data["tablesName"],
					$data["userData"]["PageName"],
					isset($data["userData"]["LogType"])?$data["userData"]["LogType"]:"Update",
					$data["userData"]["primaryKey"]["value"],
					1,
					$new
				);
			}else if($res && isset($data["userData"]["foreignKey"])){
				$primarykey=$this->return_data("
					SELECT
						key_column_usage.column_name
					FROM
						information_schema.key_column_usage
					WHERE
						table_schema = SCHEMA() AND CONSTRAINT_NAME = 'PRIMARY' AND TABLE_NAME ='".$data["tablesName"]."'
				","key")["column_name"];
				$foreignkeyData = $this->return_data2(array(
					"tablesName"=>array($data["tablesName"]),
					"columnsName"=>array($primarykey),
					"conditions"=>$data["conditions"],
					"others"=>"",
					"returnType"=>"key_all"
				));
				for($i=0;$i<count($foreignkeyData);++$i)
					$this->system_log(
						$data["tablesName"],
						$data["userData"]["PageName"],
						isset($data["userData"]["LogType"])?$data["userData"]["LogType"]:"Update",
						$foreignkeyData[$i][$primarykey],
						1,
						$new
					);
			}
			return $res;
		}

		public function delete_data($sql_code){
			/*
				delete function
				return ==> count deleted number
			*/
			return $this->conn_pdo->exec($sql_code);
		}
		public function delete_data2($data){
			if(isset($data["userData"]["primaryKey"])){//adding primary key to condtion
				array_push(
					$data["conditions"],array("columnName"=>$data["userData"]["primaryKey"]["key"],"operation"=>"=","value"=>$data["userData"]["primaryKey"]["value"],"link"=>"")
				);
			}else if(isset($data["userData"]["foreignKey"])){//adding foreign key to condtion
				array_push(
					$data["conditions"],array("columnName"=>$data["userData"]["foreignKey"]["key"],"operation"=>"=","value"=>$data["userData"]["foreignKey"]["value"],"link"=>"")
				);
			}
			$userDataOld = $this->return_data2(array(
				"tablesName"=>array($data["tablesName"]),
				"columnsName"=>array("*"),
				"conditions"=>$data["conditions"],
				"others"=>"",
				"returnType"=>"key"
			));
			//print_r($userDataOld);
			$newUserData=array();//found the data that changed by user
			foreach($data["userData"] as $key=>$value){
				if(isset($userDataOld[$key]) && $userDataOld[$key]!=$value){
					$newUserData[$key]=array("old"=>$userDataOld[$key],"new"=>$value);
				}
			}
			$update="update ".$data["tablesName"]." ";
			if(strlen($this->setUpdate($newUserData))>0){
				$set="set ".$this->setUpdate($newUserData);
			}else{
				return -1;//mean we have no data for update
			}

			$where="where ".$this->whereCondition($data["conditions"]);
			$sql=$update." ".$set." ".$where;
			//echo $sql;exit;
			
			$res=$this->conn_pdo->query($sql);

			$new=array();
			foreach($newUserData as $key=>$value){
				$new[]=array($key,$value["old"],$value["new"]);
			}
			if($res && isset($data["userData"]["primaryKey"])){
				//how to convert associative array to indexed array
				$this->system_log($data["tablesName"],$data["userData"]["PageName"],"Delete",$data["userData"]["primaryKey"]["value"],1,$new);
			}else if($res && isset($data["userData"]["foreignKey"])){
				$primarykey=$this->return_data("
					SELECT
						key_column_usage.column_name
					FROM
						information_schema.key_column_usage
					WHERE
						table_schema = SCHEMA() AND CONSTRAINT_NAME = 'PRIMARY' AND TABLE_NAME ='".$data["tablesName"]."'
				","key")["column_name"];
				$foreignkeyData = $this->return_data2(array(
					"tablesName"=>array($data["tablesName"]),
					"columnsName"=>array($primarykey),
					"conditions"=>$data["conditions"],
					"others"=>"",
					"returnType"=>"key_all"
				));
				for($i=0;$i<count($foreignkeyData);++$i)
					$this->system_log($data["tablesName"],$data["userData"]["PageName"],"Delete",$foreignkeyData[$i][$primarykey],1,$new);
			}
			return $res;
		}
		public function delete_data3($data){
			if(isset($data["userData"]["primaryKey"])){//adding primary key to condtion
				array_push(
					$data["conditions"],array("columnName"=>$data["userData"]["primaryKey"]["key"],"operation"=>"=","value"=>$data["userData"]["primaryKey"]["value"],"link"=>"")
				);
			}else if(isset($data["userData"]["foreignKey"])){//adding foreign key to condtion
				array_push(
					$data["conditions"],array("columnName"=>$data["userData"]["foreignKey"]["key"],"operation"=>"=","value"=>$data["userData"]["foreignKey"]["value"],"link"=>"")
				);
			}
			$newUserData=array();//found the data that changed by user
			$newUserData[$data["symbol"]."Deleted"]=array("old"=>0,"new"=>1);

			$update="update ".$data["tablesName"]." ";
			if(strlen($this->setUpdate($newUserData))>0){
				$set="set ".$this->setUpdate($newUserData);
			}else{
				return -1;//mean we have no data for update
			}
			$where="where ".$this->whereCondition($data["conditions"]);
			$sql=$update." ".$set." ".$where;			
			$new=array();
			foreach($newUserData as $key=>$value){
				$new[]=array($key,$value["old"],$value["new"]);
			}
			if(isset($data["userData"]["primaryKey"])){
				$res=$this->conn_pdo->query($sql)->rowCount();
				//how to convert associative array to indexed array
				$this->system_log($data["tablesName"],$data["userData"]["PageName"],"Delete",$data["userData"]["primaryKey"]["value"],1,$new);
				return $res;							
			}else if(isset($data["userData"]["foreignKey"])){
				$primarykey=$this->return_data("
					SELECT
						key_column_usage.column_name
					FROM
						information_schema.key_column_usage
					WHERE
						table_schema = SCHEMA() AND CONSTRAINT_NAME = 'PRIMARY' AND TABLE_NAME ='".$data["tablesName"]."'
				","key")["column_name"];
				$foreignkeyData = $this->return_data2(array(
					"tablesName"=>array($data["tablesName"]),
					"columnsName"=>array($primarykey),
					"conditions"=>$data["conditions"],
					"others"=>"",
					"returnType"=>"key_all"
				));
				$res=$this->conn_pdo->query($sql)->rowCount();
				for($i=0;$i<count($foreignkeyData);++$i){
					$this->system_log($data["tablesName"],$data["userData"]["PageName"],"Delete",$foreignkeyData[$i][$primarykey],1,$new);
				}
				return $res;			
			}
		}

		public function system_log($table_name,$PageName,$LOGAction,$LOGRowID,$check,$all_value){
			$id=$this->insert_data("
				INSERT INTO system_log(
					LOGPage,
					LOGTable,
					LOGRowID,
					LOGAction,
					LOGNote,
					LOGCreateBY,
					LOGCreateAt
				)VALUES(
					'$PageName',
					'$table_name',
					'$LOGRowID',
					'$LOGAction',
					'',
					'".$_SESSION["user_id"]."',
					'".date("Y-m-d H:i:s")."'	
				);
			");
            if($check==1){//labo update krdne
                
				$field=array("LogColumnName","SLDOldValue","SLDNewValue");
				$sql="
					INSERT INTO system_log_detail(
						SLDForID,
						LogColumnName,
						SLDOldValue,
						SLDNewValue,
						SLDCreateAt
					)VALUES(
						'".$id."',
						:LogColumnName,
						:SLDOldValue,
						:SLDNewValue,
						'".date("Y-m-d H:i:s")."'
					)
				";
				$this->insert_prepare($sql,$field,$all_value);
			}

		}

		public function whereCondition($conditions){
			$where="";
			for($i=0;$i<count($conditions);$i++){
				if($conditions[$i]["operation"]=="between"){
					$where.=$conditions[$i]["columnName"]." ".$conditions[$i]["operation"]." '".$conditions[$i]["value"][0]."' and '".$conditions[$i]["value"][1]."' ".$conditions[$i]["link"]. " ";
				}else if($conditions[$i]["operation"]=="in"){
					$where.=$conditions[$i]["columnName"]." IN "." ( ".$conditions[$i]["value"]." ) ".$conditions[$i]["link"]. " ";
				}else if($conditions[$i]["operation"]=="nin"){
					$where.=$conditions[$i]["columnName"]." NOT IN "." ( ".$conditions[$i]["value"]." ) ".$conditions[$i]["link"]. " ";
				}else{
					$where.=$conditions[$i]["columnName"].$conditions[$i]["operation"]."'".$conditions[$i]["value"]."'".$conditions[$i]["link"]. " ";
				}
			}
			return $where;
		}
		public function setUpdate($data){
			$set="";
			foreach($data as $key=>$value){
				$set.=$key."='".$value["new"]."', ";
			}
			return strlen($set)>0?substr($set,0,strlen($set)-2):$set;
		}
		//if the column not numeric add single qoutate around the value
		public function checkColumnType($NUMERIC_PRECISION){
			if($NUMERIC_PRECISION>0 ){
				return "";
			}else{
				return "'";
			}
		}
		//return the default value for the column if not have a value
		public function returnDefaultData($DATA_TYPE,$COLUMN_DEFAULT,$COLUMN_COMMENT){
			if(explode(",",$COLUMN_COMMENT)[0]=="usd_rate"){
				return $_SESSION["USD_TO_IQD"];
			}else if(explode(",",$COLUMN_COMMENT)[0]=="userid"){
				return $_SESSION["userid"];
			}
			else if($DATA_TYPE=="datetime"){
				return date("Y-m-d H:i:s");
			}else{
				return $COLUMN_DEFAULT;
			}
		}
		public function checkIsSet($var,$value){
			return isset($var)?$var:$value;
		}
		function __destruct() {
			$this->conn_pdo=null;
		}
	}
?>