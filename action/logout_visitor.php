<?php
	include_once("../config.php");
	
	$out =  date("Y-m-j H:i:s");
	$id  = $_REQUEST['id'];
	
	$remark = $_REQUEST['remark'];
	
	$update_sql = "UPDATE `visitor` SET `out` = '".$out."', `remarks` = '".$remark."' WHERE `id` = '".$id."'";
	$update_query = mysql_query($update_sql);
	
	if($update_query){

		$select_sql = "SELECT * FROM `visitor` WHERE `id` = '".$id."'";
		$select_query = mysql_query($select_sql);
		$visitor_data = mysql_fetch_array($select_query);
		$d = array('status' => 'success',
				   'message' => $visitor_data['name'].' was successfully logged out.',
				   'gatepassnum' => $visitor_data['gatepassnum']
				   );
	
	}else{
		$d = array('status' => 'failed',
				   'message' => 'Failed to update. Please refresh the page and try again or contact the system administrator.', 'query'=>$update_sql);	
	}
	
	echo json_encode($d);
?>

