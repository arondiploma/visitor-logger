<?php
	include_once("../config.php");
	
	$id = $_REQUEST['id'];
	$sql = "SELECT * FROM purpose WHERE pur_id = '".$id."'";
	$query = mysql_query($sql);
	$data = mysql_fetch_array($query);
	
?>
<fieldset class="editor">
    <legend id="LETitle"> Edit </legend>
    <form name="LEpurposeEditor" id="LEpurposeEditor">
    	<input type="hidden" name="pur_id" id="pur_id" value ="<?php echo $data['pur_id']; ?>"/>
		<input type="text" name="purpose" style="width: 221px; height:18px !important; margin-right:5px !important;" value ="<?php echo $data['purpose']; ?>"/>
    	<span id="submit_editor_update"><a></a></span>
        <span id="cancel_editor_update"><a></a></span>
    </form>
</fieldset>