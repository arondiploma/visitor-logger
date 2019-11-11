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
	
	$posStart = $_REQUEST['posStart'];
	$count= $_REQUEST['count'];
	

	$sql = "SELECT * FROM `visitor` 
	
				LEFT OUTER JOIN destination on visitor.dest_id = destination.dest_id 
	
				LEFT OUTER JOIN purpose on visitor.pur_id = purpose.pur_id 
				
		";
	$query_records = mysql_query($sql." WHERE (`out` IS NULL or `out` = '0000-00-00 00:00:00')"); 
	$query_all = mysql_query($sql." WHERE (`out` IS NULL or `out` = '0000-00-00 00:00:00')");
	
	$sql.=($q!="" ? " WHERE ".$q." LIKE '%".$v."%'" : "")." and (`out` IS NULL or `out` = '0000-00-00 00:00:00')"; //where clause
	
	
	
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
			$rows[$i] = array(	"id"=> $row['id'],	//$row['colorcode']
								"row"=> array(	"gatepassnum"=> $row['gatepassnum'],
												"bcolor" => $row['colorcode'],
												"name"=> $name,
												"in"=>  $date->format("m/j/Y - h:i A"), 
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

