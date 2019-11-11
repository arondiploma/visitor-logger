<?php
	include("config.php");
	
	$u = md5($_REQUEST['un']);
	$p = md5($_REQUEST['pa']);
	
	$query = mysql_query("SELECT * FROM `user` WHERE username_hash='".$u."' and password_hash='".$p."'");
	if( mysql_num_rows($query) >0 ){
		session_start();
		$row = mysql_fetch_array($query);
		$_SESSION['loggedin'] = "true";
		$_SESSION['_username'] = $row['username'];
		$_SESSION['_fullname'] = $row['fullname'];
		$_SESSION['_accesslevel'] = $row['utype'];
		echo "1";		
	}else{
		echo "0";
	}
?>