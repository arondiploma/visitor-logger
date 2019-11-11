<?php
	include_once("../config.php");
	$data ="";
	$query = mysql_query("SELECT * FROM `user`");
	
	$total_row = mysql_num_rows($query);
	
	while($row = mysql_fetch_array($query)){
		
		$check_sql = "SELECT * FROM visitor WHERE username = '".$row['username']."'";
		
		$check_query = mysql_query($check_sql);
		if(mysql_num_rows($check_query) > 0 ){
			$allow_del = 0;
			$del = "<div id=\"del_".$row['username']."\" class=\"grid-item item-action item-delete_dis\" title=\"Delete\"></div>";	
		}else{
			$allow_del = 1;
			$del = "<div id=\"del_".$row['username']."\" class=\"grid-item item-action item-delete\" title=\"Delete\"></div>";	
		}
		$data = $data."<row id=\"".$row['username']."\"> 
							<cell><![CDATA[".$row['username']."]]></cell> 
							<cell><![CDATA[".$row['fullname']."]]></cell> 
							<cell><![CDATA[".($row['utype'] == "standard" ? "Standard User" : "Administrator")."]]></cell> 
							<cell><![CDATA[<div id=\"edit_".$row['username']."\" class=\"grid-item item-action item-edit\" title=\"Edit\"></div>]]></cell>
							<cell><![CDATA[".$del."]]></cell>
							
							<userdata name=\"allowdelete\"><![CDATA[".$allow_del."]]></userdata>
							
							<userdata name=\"username\"><![CDATA[".$row['username']."]]></userdata>
							<userdata name=\"fullname\"><![CDATA[".$row['fullname']."]]></userdata>
							
							
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

