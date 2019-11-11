<?php
	session_start();
	
	include_once("../config.php");
	
	function _json($index,$value){
		return '"'.$index.'":"'.$value.'"';
	}
	
	$recordscount = 0;
	$rowscount = 0;
	$rows = array();
	$q = "name";
	$v = isset($_REQUEST["v"]) ? $_REQUEST["v"] : "";
	if($v=="'" || $v=='"'){
		$v = "";	
	}
	
	$DTFrom = isset($_REQUEST['DTFrom']) ? $_REQUEST['DTFrom'] : "";
	$DTTo = isset($_REQUEST['DTTp']) ? $_REQUEST['DTTp'] : "";
	$all_destination = isset($_REQUEST['destination']) && $_REQUEST['destination'] != "" ? explode(",", $_REQUEST['destination']) : array();
	$all_purpose = isset($_REQUEST['purpose']) && $_REQUEST['purpose'] != "" ? explode(",", $_REQUEST['purpose']) : array();
	$all_visitornum =isset($_REQUEST['visitornum']) && $_REQUEST['visitornum'] != "" ? explode(",", $_REQUEST['visitornum']) : array();
	$withVehicle = isset($_REQUEST['withVehicle']) ? ($_REQUEST['withVehicle'] == 0 ? false : true) : false;

	$sql = "SELECT * FROM `visitor` 
	
				LEFT OUTER JOIN destination on visitor.dest_id = destination.dest_id 
	
				LEFT OUTER JOIN purpose on visitor.pur_id = purpose.pur_id 
				
		";
		
	if($DTFrom != ""){
		$DTFrom_format = new DateTime($DTFrom);
		$DTTo_format = new DateTime($DTTo);
		$sql = $sql."WHERE (`in` BETWEEN '".$DTFrom_format->format("Y-j-m H:i")."' AND '".$DTTo_format->format("Y-j-m H:i")."') ";
	}
	
	if(count($all_destination) > 0){
		$desWhereClause = "";
		for($i=0;$i<count($all_destination);$i++){
			$desWhereClause = $desWhereClause.($desWhereClause=="" ? "" : " OR ")." `visitor`.`dest_id` = ".$all_destination[$i];
		}	
		$sql = $sql.(strrpos($sql,"WHERE")>0?" AND ":" WHERE ")." (".$desWhereClause.") ";
	}

	if(count($all_purpose) > 0){
		$purWhereClause = "";
		for($i=0;$i<count($all_purpose);$i++){
			$purWhereClause = $purWhereClause.($purWhereClause=="" ? "" : " OR ")." `visitor`.`pur_id` = ".$all_purpose[$i];
		}
		$sql = $sql.(strrpos($sql,"WHERE")>0?" AND ":" WHERE ")." (".$purWhereClause.") ";
	}
	
	
	if(count($all_visitornum) > 0){
		$visitorWhereClause = "";
		for($i=0;$i<count($all_visitornum);$i++){
			$visitorWhereClause = $visitorWhereClause.($visitorWhereClause=="" ? "" : " OR ")." `gatepassnum` = ".$all_visitornum[$i];
		}
		$sql = $sql.(strrpos($sql,"WHERE")>0?" AND ":" WHERE ")." (".$visitorWhereClause.") ";
	}
	
	if($withVehicle){
		$sql = $sql.(strrpos($sql,"WHERE")>0?" AND ":" WHERE ")." (`platenumber` IS NOT NULL AND  `platenumber` != '') ";
	}
	
	$query_all = mysql_query($sql);
	$sql.=($q!="" ? (strrpos($sql,"WHERE")>0?" AND ":" WHERE ").$q." LIKE '%".$v."%'" : ""); //where clause
	$query_records = mysql_query($sql); 
	$query_rows = mysql_query($sql." ORDER BY `in` DESC");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
	<head>
		<title>List of Visitors</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
    <script>
		function init()
		{
			//window.print();
		}
	</script>
	<style>
		 html, body {
			width: 100%;
			height: 100%;
			margin: 0px;
			font-family:Arial;
			font-size:12px;
			overflow:auto;
			background-color:#FFF;

		 }
		 table
		 {
			font-family:Arial;
			font-size:12px;
			border-collapse:collapse;
			overflow:hidden;
		 }
		 .data
		 {
			 font-weight:bold;
			 letter-spacing:.5px;
		 }
	</style>
</head>
<body onLoad="init();">

    <table width="100%" border="0">
                  <tr>
                    <td align="center" colspan="2">Republic of the Philippines</td>
                  </tr>
                  <tr>
                    <td align="center" style=" font-size:13px; font-weight:bold;" colspan="2">CARLOS HILADO MEMORIAL STATE COLLEGE</td>
                  </tr>
                  <tr>
                    <td align="center" colspan="2">Talisay Campus, Talisay City, Negros Occidental</td>
                  </tr>
                  <tr>
                    <td><div style="height:5px; overflow:hidden;"></div></td>
                  </tr>
				<tr align="center">
                    <td>LIST OF VISITORS</td>
                  </tr>
                  <tr>
                    <td><div style="height:5px; overflow:hidden;"></div></td>
                  </tr>
    </table>
    
    <table width="100%" border="1">
      <tr>
        <td rowspan="2">#</td>
        <td rowspan="2" align="center">Name</td>
        <td colspan="2" align="center">Time</td>
        <td rowspan="2" align="center">Destination</td>
        <td rowspan="2" align="center">Purpose</td>
        <td rowspan="2" align="center">Gate Pass<br>Number</td>
        <td rowspan="2" align="center">Plate<br>Number</td>
        <td rowspan="2" align="center">Guard<br>On-Duty</td>
      </tr>
      <tr>
        <td align="center">IN</td>
        <td align="center">OUT</td>
      </tr>
	<?php

		for($i=1;$row = mysql_fetch_array($query_rows);$i++){
			
			$name = str_replace($v, "<label style='background-color:#FFEC17;'>".$v."</label>",$row['name']);
			
			$name = str_replace(ucwords($v), "<label style='background-color:#FFEC17;'>".ucwords($v)."</label>",$name);
			
			$date = new DateTime($row['in']);
			
			$date2 = new DateTime($row['out']);

			echo "<tr>
					<td>".$i."</td>
					<td>".$row['name']."</td>
					<td>".$date->format("m/j/Y h:i A")."</td>
					<td>".$date2->format("m/j/Y h:i A")."</td>
					<td>".$row['destination']."</td>
					<td>".($row['purpose']=="" ? $row['others'] : $row['purpose'])."</td>
					<td>".$row['gatepassnum']."</td>
					<td>".$row['platenumber']."</td>
					<td>".$row['username']."</td>
				  </tr>";
		}
		
	?>
    </table>

    
    
    <br />
<div style="border-bottom:1px dotted #000;"></div>
    <table width="100%" style="border-collapse:collapse;">
        <tr>
            <td ><span style='font-size:9px;'> Visitor Logger. Copyright &copy; 2012. The Elite System Group&trade;.  by:: ZeroCool. Printed:: <?php echo date("F d, Y @ h:i:s A");?>. <?php echo "User:: ".$_SESSION['_fullname']; ?>.</span></td>
        </tr>
        <tr>
            <td >
                <span style='font-size:9px;'>
                <?php 
                    echo $_SERVER['REQUEST_URI'];
                ?>	
                </span>
            </td>
        </tr>
    </table>
</body>
</html>