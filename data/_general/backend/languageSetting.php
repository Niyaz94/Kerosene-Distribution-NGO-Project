<?php
	require_once "db_connect.php";
	$_SESSION["languageSetting"]=array("CSS"=>"css_ltr","LANG"=>"English11111111","DIR"=>"ltr");	
?>
<script >
	var CSS_PATH = '<?php echo $_SESSION["languageSetting"]["CSS"]; ?>';
	var LANName  = '<?php echo $_SESSION["languageSetting"]["LANG"]; ?>';
	var LANDir 	 = '<?php echo $_SESSION["languageSetting"]["DIR"]; ?>';
	function languageInfo(){
		return <?php echo json_encode($_SESSION["languageSetting"]);?>;
	}
	function Translation(msg){
		return msg;
	}
</script>