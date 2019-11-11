<?php

 date_default_timezone_set("Asia/Manila");

 $host="localhost";
 $user="root";
 $pass="";
 $db_name="visitorlogger";


 $connection=mysql_connect($host,$user,$pass)or die("Could not connect".mysql_error());
 $select_database=mysql_select_db($db_name) or die("Can't find Database".mysql_error());
 
?>