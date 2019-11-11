<?php
	include("../config.php");
	error_reporting(E_ALL ^ E_NOTICE);
	
	global $gate_pass_nums;
	$gate_pass_nums = array();
	$used_gatePass_sql = "SELECT * FROM visitor WHERE (`out` IS NULL or `out` = '0000-00-00 00:00:00')";
	$used_gatePass_query = mysql_query($used_gatePass_sql);
	for($i=0;$gatePass_row = mysql_fetch_array($used_gatePass_query);$i++){
		$gate_pass_nums[$i] = $gatePass_row['gatepassnum'];
	}
	
	function isInUsed($num){
		global $gate_pass_nums;
		for($x=0;$x < count($gate_pass_nums);$x++){
			if($num == $gate_pass_nums[$x]){
				return true;	
			}
		}
		return false;
	}


	if (stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml")) {
		header("Content-type: application/xhtml+xml");
	} 
	else {
		header("Content-type: text/xml");
	}
	
	$colorCode = $_REQUEST['color'];
	
	echo "<complete>";         
	for($i=1;$i<=100;$i++){
		if(!isInUsed($i)){
			echo "<option value='".$i."' img_src='color/colorCode.php?color=".$colorCode."'>".$i."</option>";
		}
	}
	
	echo "</complete>";
?>
