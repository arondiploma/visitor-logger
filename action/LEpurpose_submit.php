<?php
	include_once("../config.php");
	$mysqli = new mysqli($host, $user, $pass, $db_name);

	$mysqli->query('SET AUTOCOMMIT = 0');
	$mysqli->query("START TRANSACTION");
    $mysqli->query("BEGIN");
	
	$insert_sql = "INSERT INTO `visitorlogger`.`purpose` (`pur_id` ,`purpose` )
													VALUES (
														NULL , '".addslashes($_REQUEST['purpose'])."'
													)";
	$mysqli->query($insert_sql);
	$id = $mysqli->insert_id;
	
	if($id>0){

			$mysqli->query("COMMIT");
			$d = array('status' => 'success',
					   'message' => 'Successfully saved!',
					   	'data' => array('id' => $id,
										"text" => $_REQUEST['purpose'],
										"colorcode" => ""
									)
					   );

	}else{
			mysql_query("ROLLBACK");
			$d = array('status' => 'failed',
					   'message' => 'Failed to add new purpose. Please refresh the page and try again or contact the system administrator.',
					    'callback' => ""
					   );	
	}
	
	echo json_encode($d);
?>

