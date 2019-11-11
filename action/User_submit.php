<?php
	include_once("../config.php");
	
	$check_query = mysql_query("SELECT * FROM `visitorlogger`.`user` WHERE username = '".$_REQUEST['username']."'");
	if(mysql_num_rows($check_query)>0){
		
		$d = array('status' => 'failed',
				   'message' => 'Username already exist.',
				   'callback' => "highlightUsername"
				   );
	}else{
		$mysqli = new mysqli($host, $user, $pass, $db_name);
	
		$mysqli->query('SET AUTOCOMMIT = 0');
		$mysqli->query("START TRANSACTION");
		$mysqli->query("BEGIN");
		
		$insert_sql = "INSERT INTO `visitorlogger`.`user` (`username` ,
																`username_hash`,
																`password_hash` ,
																`fullname`,
																`utype`
															)
														VALUES (
															'".$_REQUEST['username']."' ,
															'".md5($_REQUEST['username'])."' ,
															'".md5($_REQUEST['password1'])."' ,
															'".$_REQUEST['fullname']."',
															'".$_REQUEST['accesslevel']."'
														)";
		if($mysqli->query($insert_sql)){
	
				$mysqli->query("COMMIT");
				$d = array('status' => 'success',
						   'message' => 'Successfully saved!',
						   'data' => array('id'=>"")
						   );
	
		}else{
				mysql_query("ROLLBACK");
				$d = array('status' => 'failed',
						   'message' => 'Failed to add new user. Please refresh the page and try again or contact the system administrator.',
						   'callback' => ""
						   );	
		}
	}
	
	
	echo json_encode($d);
?>

