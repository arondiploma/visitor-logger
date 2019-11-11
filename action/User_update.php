<?php
	include_once("../config.php");
	
		mysql_query('SET AUTOCOMMIT = 0');
		mysql_query("START TRANSACTION");
		mysql_query("BEGIN");
	
		if($_REQUEST['password1']!="" && $_REQUEST['password2'] != "" && $_REQUEST['password3'] != ""){
			$update_sql = "UPDATE `user` SET 
												`username` = '".$_REQUEST['username']."',
												`username_hash` = '".md5($_REQUEST['username'])."',
												`password_hash` = '".md5($_REQUEST['password1'])."',
												`fullname` = '".$_REQUEST['fullname']."',
												`utype` = '".$_REQUEST['accesslevel']."'
									WHERE 
												 `username` = '".$_REQUEST['username_orig']."'";
		}else{
			$update_sql = "UPDATE `user` SET 
												`username` = '".$_REQUEST['username']."',
												`username_hash` = '".md5($_REQUEST['username'])."',
												`fullname` = '".$_REQUEST['fullname']."',
												`utype` = '".$_REQUEST['accesslevel']."'
									WHERE 
												 `username` = '".$_REQUEST['username_orig']."'";
		}


		$update_query = mysql_query($update_sql);

		if($update_query){
				mysql_query("COMMIT");
				$d = array('status' => 'success',
						   'message' => 'Successfully updated!',
							'data' => array('id' => "",
											"text" => "",
											"colorcode" => ""
										)
						   );
	
		}else{
				mysql_query("ROLLBACK");
				$d = array('status' => 'failed',
						   'message' => 'Failed to update. Please refresh the page and try again or contact the system administrator.',
						   'callback' => ""
						   );	
		}
		
		echo json_encode($d);
?>

