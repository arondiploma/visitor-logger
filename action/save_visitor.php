<?php

session_start();

include_once("../config.php");
include_once("SimpleImageProcessor.php");

$d = array();
$in = date("Y-m-j H:i:s");
$n = $_REQUEST['imgname'];

if ($_REQUEST['purpose_option'] == "from_options") {
    $pur_id = "'" . $_REQUEST['purpose_select'] . "'";
    $others = "NULL";
} else {
    $pur_id = "NULL";
    $others = "'" . $_REQUEST['purpose_others'] . "'";
}
mysql_query('SET AUTOCOMMIT = 0');
mysql_query("START TRANSACTION");
mysql_query("BEGIN");

if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}

$fn = $n . "_" . md5($ip);

$insert_sql = "INSERT INTO `visitorlogger`.`visitor` (

														`id` ,
														`name` ,
														`in` ,
														`out` ,
														`pur_id` ,
														`others` ,
														`dest_id` ,
														`gatepassnum`,
														`platenumber` ,
														`remarks` ,
														`picture`,
														`username`)
													VALUES (
														NULL , 
														'" . $_REQUEST['visitor_name'] . "', 
														'" . $in . "', 
														NULL,
														" . $pur_id . ", 
														" . $others . ",
														'" . $_REQUEST['destination'] . "',
														'" . $_REQUEST['gatepassnum'] . "',
														'" . $_REQUEST['plate_number'] . "',
														'" . htmlentities($_REQUEST['remarks'], ENT_QUOTES, "UTF-8") . "',
														'" . $fn . "',
														'" . $_SESSION['_username'] . "'
													)";
$insert_query = mysql_query($insert_sql);

if ($insert_query) {

    if (copy("../temp/" . $n . ".jpeg", "../pictures/" . $fn . "_large.jpeg")) {

        unlink("../temp/" . $n . ".jpeg");  //delete the image after copy

        $image = new SimpleImage();
        $image->load("../pictures/" . $fn . "_large.jpeg");
        $image->resize(122, 89);
        $image->save("../pictures/" . $fn . "_thumb.jpeg");


        mysql_query("COMMIT");
        $d = array('status' => 'success',
            'message' => 'Successfully saved!');
    } else {

        mysql_query("ROLLBACK");
        $d = array('status' => 'failed',
            'message' => 'Failed to save new visitor log. Please refresh the page and try again or contact the system administrator.');
    }
} else {
    mysql_query("ROLLBACK");
    
    $d = array('status' => 'failed',
        'message' => 'Failed to save new visitor log. Please refresh the page and try  again or contact the system administrator.');
}

echo json_encode($d);
?>

