<?php
	include_once("../config.php");
	$data="";
	$query = mysql_query("SELECT * FROM `destination` ORDER BY dest_id desc");
	$total_row = mysql_num_rows($query);
	
	while($row = mysql_fetch_array($query)){
		
		$check_sql = "SELECT * FROM visitor WHERE dest_id = '".$row['dest_id']."'";
		$check_query = mysql_query($check_sql);
		if(mysql_num_rows($check_query) > 0 ){
			$allow_del = 0;
			$del = "<div id=\"del_".$row['dest_id']."\" class=\"grid-item item-action item-delete_dis\" title=\"Delete\"></div>";	
		}else{
			$allow_del = 1;
			$del = "<div id=\"del_".$row['dest_id']."\" class=\"grid-item item-action item-delete\" title=\"Delete\"></div>";	
		}
		
		$color = $row['colorcode'] == "" ? "ffffff" : $row['colorcode'];
		
		if($row['colorcode']==""){
			$img = "<img src='color/colorCode.php?color=".$color."' style='border:1px solid #8b8b8b'/>";
		}else{
			$img = "<img src='color/colorCode.php?color=".$color."'/>";	
		}
		

		$data = $data."<row id=\"".$row['dest_id']."\"> 
							<cell><![CDATA[".$img." ".htmlentities($row['destination'])."]]></cell> 
							<cell><![CDATA[<div id=\"edit_".$row['dest_id']."\" class=\"grid-item item-action item-edit\" title=\"Edit\"></div>]]></cell>
							<cell><![CDATA[".$del."]]></cell>
							<userdata name=\"allowdelete\"><![CDATA[".$allow_del."]]></userdata>
							<userdata name=\"id\"><![CDATA[".$row['dest_id']."]]></userdata>
							<userdata name=\"destination\"><![CDATA[".$row['destination']."]]></userdata>
						</row>";		
	}


	error_reporting(E_ALL ^ E_NOTICE);
	
	if (stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml")) {
		header("Content-type: application/xhtml+xml");
	} 
	else {
		header("Content-type: text/xml");
	}
	
	echo "<rows pos=\"0\" total_count=\"".$total_row."\">
			".$data."
		</rows>";
?>

