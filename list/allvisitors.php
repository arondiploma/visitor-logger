<?php
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
	
	$posStart = $_REQUEST['posStart'];
	$count= $_REQUEST['count'];
	

	$sql = "SELECT * FROM `visitor` 
	
				LEFT OUTER JOIN destination on visitor.dest_id = destination.dest_id 
	
				LEFT OUTER JOIN purpose on visitor.pur_id = purpose.pur_id 
				
		";
        
	$query_all = mysql_query($sql);
        
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
	
	
	$sql.=($q!="" ? (strrpos($sql,"WHERE")>0?" AND ":" WHERE ").$q." LIKE '%".$v."%'" : ""); //where clause
	$query_records = mysql_query($sql); 
	$query_rows = mysql_query($sql." ORDER BY `in` DESC  LIMIT ".$posStart.",".$count);

	header('Content-type: application/json; charset=utf-8');

	if(mysql_errno() > 0){
		echo "{";
		echo _json("db_error_code",mysql_errno()) .",";
		echo _json("db_error_message",mysql_error());
		echo "}";
	}else{
		
		$recordscount = mysql_num_rows($query_all);
		$rowscount = mysql_num_rows($query_records);
		
		$global = array(
						"rowscount" => $rowscount,
						"recordscount" => $recordscount
					);
		
		for($i=0;$row = mysql_fetch_array($query_rows);$i++){
			
			$name = str_replace($v, "<label style='background-color:#FFEC17;'>".$v."</label>",$row['name']);
			
			$name = str_replace(ucwords($v), "<label style='background-color:#FFEC17;'>".ucwords($v)."</label>",$name);
			
			$date = new DateTime($row['in']);
			
			$date2 = new DateTime($row['out']);
			
			$rows[$i] = array(	"id"=> $row['id'],
								"row"=> array(	"gatepassnum"=> $row['gatepassnum'],
												"name"=> $name,
												"bcolor" => $row['colorcode'],
												"time"=>  $date->format("m/j/Y h:i A").($row['out']!="" ? " - ".$date2->format("m/j/Y h:i A") : ""), 
												"dest"=> $row['destination'], 
												"pur"=> ($row['purpose']=="" ? $row['others'] : $row['purpose']),
												"image"=> "pictures/".$row['picture']."_thumb.jpeg",
												"userdata" => array("name" => $name, "id" => $row['id'])
											)
							);
		}

		$global["rows"] = $rows;

		echo json_encode($global);

	}
?>

