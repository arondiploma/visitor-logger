<?php
	include_once("../config.php");

	$check_sql = "SELECT * FROM `user` WHERE `username_hash` = '".md5($_REQUEST['username'])."' and `password_hash` = '".md5($_REQUEST['password'])."'";
	$check_query = mysql_query($check_sql);

	if(@mysql_num_rows($check_query) > 0){
			echo "1";
	}else{
			echo "0";	
	}

?>

