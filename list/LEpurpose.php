<?php
	include_once("../config.php");
	$data ="";
	$query = mysql_query("SELECT *FROM `purpose` ORDER BY pur_id desc");
	
	$total_row = mysql_num_rows($query);
	
	while($row = mysql_fetch_array($query)){
		
		$check_sql = "SELECT * FROM visitor WHERE pur_id = '".$row['pur_id']."'";
		$check_query = mysql_query($check_sql);
		if(mysql_num_rows($check_query) > 0 ){
			$allow_del = 0;
			$del = "<div id=\"del_".$row['pur_id']."\" class=\"grid-item item-action item-delete_dis\" title=\"Delete\"></div>";	
		}else{
			$allow_del = 1;
			$del = "<div id=\"del_".$row['pur_id']."\" class=\"grid-item item-action item-delete\" title=\"Delete\"></div>";	
		}

		$data = $data."<row id=\"".$row['pur_id']."\"> 
							<cell><![CDATA[".$row['purpose']."]]></cell> 
							<cell><![CDATA[<div id=\"edit_".$row['pur_id']."\" class=\"grid-item item-action item-edit\" title=\"Edit\"></div>]]></cell>
							<cell><![CDATA[".$del."]]></cell>
							<userdata name=\"allowdelete\"><![CDATA[".$allow_del."]]></userdata>
							<userdata name=\"id\"><![CDATA[".$row['pur_id']."]]></userdata>
							<userdata name=\"purpose\"><![CDATA[".$row['purpose']."]]></userdata>
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

