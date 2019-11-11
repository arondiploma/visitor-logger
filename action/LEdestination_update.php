<?php
	include_once("../config.php");
	mysql_query('SET AUTOCOMMIT = 0');
	mysql_query("START TRANSACTION");
    mysql_query("BEGIN");
	
	$update_sql = "UPDATE `destination` SET `destination` = '".addslashes($_REQUEST['destination'])."', `colorcode` = '".$_REQUEST['colorCode']."' WHERE dest_id = '".$_REQUEST['dest_id']."'";
	
	$update_query = mysql_query($update_sql);

	if($update_query){

			mysql_query("COMMIT");
			$d = array('status' => 'success',
					   'message' => 'Successfully updated!',
					   	'data' => array('id' => $_REQUEST['dest_id'],
										"text" => $_REQUEST['destination'],
										"colorcode" => $_REQUEST['colorCode']
									)
					   );

	}else{
			mysql_query("ROLLBACK");
			$d = array('status' => 'failed',
					   'message' => 'Failed to update [destination]. Please refresh the page and try again or contact the system administrator.',
					    'callback' => ""
					   );	
	}
	
	echo json_encode($d);
?>

