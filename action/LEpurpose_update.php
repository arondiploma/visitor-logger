<?php
	include_once("../config.php");
	
	mysql_query('SET AUTOCOMMIT = 0');
	mysql_query("START TRANSACTION");
    mysql_query("BEGIN");
	
	$update_sql = "UPDATE `purpose` SET `purpose` = '".addslashes($_REQUEST['purpose'])."' WHERE pur_id = '".$_REQUEST['pur_id']."'";
	
	$update_query = mysql_query($update_sql);

	if($update_query){

			mysql_query("COMMIT");
			$d = array('status' => 'success',
					   'message' => 'Successfully updated!',
					   	'data' => array('id' => $_REQUEST['pur_id'],
										"text" => $_REQUEST['purpose'],
										"colorcode" => ""
									)
					   );

	}else{
			mysql_query("ROLLBACK");
			$d = array('status' => 'failed',
					   'message' => 'Failed to update [purpose]. Please refresh the page and try again or contact the system administrator.',
					   'callback' => ""
					   );	
	}
	
	echo json_encode($d);
?>

