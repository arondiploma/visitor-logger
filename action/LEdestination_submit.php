<?php
	include_once("../config.php");
	
	$mysqli = new mysqli($host, $user, $pass, $db_name);

	$mysqli->query('SET AUTOCOMMIT = 0');
	$mysqli->query("START TRANSACTION");
    $mysqli->query("BEGIN");
	
	$insert_sql = "INSERT INTO `visitorlogger`.`destination` (`dest_id` ,`destination`, `colorcode` )
													VALUES (
														NULL , '".addslashes($_REQUEST['destination'])."', '".$_REQUEST['colorCode']."'
													)";
	$mysqli->query($insert_sql);
	$id = $mysqli->insert_id;
	
	if($id>0){

			$mysqli->query("COMMIT");
			$d = array('status' => 'success',
					   'message' => 'Successfully saved!',
					   	'data' => array('id' => $id,
										"text" => $_REQUEST['destination'],
										"colorcode" => $_REQUEST['colorCode']
									)
					   );

	}else{
			$mysqli->query("ROLLBACK");
			$d = array('status' => 'failed',
					   'message' => 'Failed to add new destination. Please refresh the page and try again or contact the system administrator.',
					    'callback' => ""
					   );	
	}
	
	echo json_encode($d);
?>

