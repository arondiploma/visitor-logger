<?php
	session_start();
		
	if(isset($_SESSION['loggedin'])){
		if($_SESSION['loggedin'] != "true" || $_SESSION['loggedin'] != true){
			include_once("login.php");
		}else{
			include_once("main.php");	
		}
	}else{
		include_once("login.php");	
	}
?>