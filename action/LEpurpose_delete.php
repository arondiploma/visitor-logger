<?php
	include_once("../config.php");
	mysql_query('SET AUTOCOMMIT = 0');
	mysql_query("START TRANSACTION");
    mysql_query("BEGIN");
	
	$del_sql = "DELETE FROM `purpose` WHERE `pur_id` = ".$_REQUEST['key'];
	$del_query = mysql_query($del_sql);

	if($del_query){

			mysql_query("COMMIT");
			$d = array('status' => 'success',
					   'message' => 'Successfully deleted!',
					   	'data' => array('id' => $_REQUEST['key'],
										"text" => ""
									)
					   );

	}else{
			mysql_query("ROLLBACK");
			$d = array('status' => 'failed',
					   'message' => 'Failed to delete. Please refresh the page and try again or contact the system administrator.',
					    'callback' => ""
					   );	
	}
	
	echo json_encode($d);
?>

