<?php
	include_once("../config.php");

	$destination_sql = "Select * FROM destination";
	$destination_query = mysql_query($destination_sql);
	
	$purpose_sql = "Select * FROM purpose";
	$purpose_query = mysql_query($purpose_sql);
	
	$DTFrom = isset($_REQUEST['DTFrom']) ? $_REQUEST['DTFrom'] : "";
	$DTTo = isset($_REQUEST['DTTp']) ? $_REQUEST['DTTp'] : "";
	$all_destination = isset($_REQUEST['destination']) && $_REQUEST['destination'] != "" ? explode(",", $_REQUEST['destination']) : array();
	$all_purpose = isset($_REQUEST['purpose']) && $_REQUEST['purpose'] != "" ? explode(",", $_REQUEST['purpose']) : array();
	$all_visitornum =isset($_REQUEST['visitornum']) && $_REQUEST['visitornum'] != "" ? explode(",", $_REQUEST['visitornum']) : array();
	$withVehicle = isset($_REQUEST['withVehicle']) ? ($_REQUEST['withVehicle'] == 0 ? false : true) : false;
	
	$DTFrom_format_date="";
	$DTFrom_format_hour="";
	$DTFrom_format_min="";
	$DTTo_format_date="";
	$DTTo_format_hour="";
	$DTTo_format_min="";
	
	if($DTFrom != ""){
		$DTFrom_format = new DateTime($DTFrom);
		$DTTo_format = new DateTime($DTTo);
		
		$DTFrom_format_date = $DTFrom_format->format("m/j/Y");
		$DTFrom_format_hour = $DTFrom_format->format("H");
		$DTFrom_format_min = $DTFrom_format->format("i");
		
		$DTTo_format_date = $DTTo_format->format("m/j/Y");
		$DTTo_format_hour = $DTTo_format->format("H");
		$DTTo_format_min = $DTTo_format->format("i");
	}
	
?>
<div style="padding:5px;">
    <fieldset class="advanceSearch">
      <legend id="LETitle">Date Range</legend>
        <table width="100%" border="0">
          <tr>
            <td width="11%">From</td>
            <td width="11%" align="right">Date</td>
            <td width="39%"><input type="text" id="datepickerFrom" readonly="readonly" value="<?php echo $DTFrom_format_date; ?>"></td>
            <td width="8%" align="right">Time</td>
            <td width="31%">
            	<select class="sel" style="width:60px;" id="hourpickerFrom">
                	<option value="*">HH</option>
                    <?php
						for($h=1;$h<24;$h++){
							$hour = $h<10 ? "0".$h : $h;
							if($DTFrom_format_hour == $h || $DTFrom_format_hour == $hour){
								echo "<option value='".$h."' selected='selected'>".$hour."</option>";
							}else{
								echo "<option value='".$h."'>".$hour."</option>";	
							}
						}
					?>
                </select>
                :
            	<select class="sel" style="width:60px;" id="minutepickerFrom" >
                	<option value="*">MM</option>
                    <?php
						for($m=1;$m<60;$m++){
							$min = $m<10 ? "0".$m : $m;
							if($DTFrom_format_min == $m || $DTFrom_format_min == $min){
								echo "<option value='".$m."' selected='selected'>".$min."</option>";
							}else{
								echo "<option value='".$m."'>".$min."</option>";	
							}
						}
					?>
                </select>
            </td>
          </tr>
          <tr>
            <td>To</td>
            <td align="right">Date</td>
            <td><input type="text" id="datepickerTo" readonly="readonly" value="<?php echo $DTTo_format_date; ?>"></td>
            <td align="right">Time</td>
            <td>
            	<select class="sel" style="width:60px;" id="hourpickerTo">
                	<option value="*">HH</option>
                    <?php
						for($h=1;$h<24;$h++){
							$hour = $h<10 ? "0".$h : $h;
							if($DTTo_format_hour == $h || $DTTo_format_hour == $hour){
								echo "<option value='".$h."' selected='selected'>".$hour."</option>";
							}else{
								echo "<option value='".$h."'>".$hour."</option>";	
							}
						}
					?>
                </select>
                :
            	<select class="sel" style="width:60px;" id="minutepickerTo">
                	<option value="*">MM</option>
                    <?php
						for($m=1;$m<60;$m++){
							$min = $m<10 ? "0".$m : $m;
							if($DTTo_format_min == $m || $DTTo_format_min == $min){
								echo "<option value='".$m."' selected='selected'>".$min."</option>";
							}else{
								echo "<option value='".$m."'>".$min."</option>";	
							}
						}
					?>
                </select>
            </td>
          </tr>
        </table>

    </fieldset>
    
    <fieldset class="advanceSearch">
      <legend id="LETitle">Destination(s)</legend>
        <table width="100%" border="0">
           <tr>
            <td>
                <select multiple="multiple" name="all_destination" size="5" id="all_destination" style="width:458px">>
                <?php
                    while($destination_row = mysql_fetch_array($destination_query)){
						if(in_array($destination_row['dest_id'], $all_destination)){
							echo "<option value='".$destination_row['dest_id']."' selected='selected'>".$destination_row['destination']."</option>";
						}else{
							echo "<option value='".$destination_row['dest_id']."'>".$destination_row['destination']."</option>";	
						}
                    }
                ?>
                </select>
            </td>
          </tr>
        </table>
	</fieldset>

    <fieldset class="advanceSearch">
      <legend id="LETitle">Purpose(s)</legend>
        <table width="100%" border="0">
           <tr>
            <td>
                <select multiple="multiple" name="all_purpose" size="5" id="all_purpose" style="width:458px">>
                <?php
					while($purpose_row = mysql_fetch_array($purpose_query)){
						if(in_array($purpose_row['pur_id'], $all_purpose)){
							echo "<option value='".$purpose_row['pur_id']."' selected='selected'>".$purpose_row['purpose']."</option>";
						}else{
							echo "<option value='".$purpose_row['pur_id']."'>".$purpose_row['purpose']."</option>";
						}
						 
					}
                ?>
                </select>
            </td>
          </tr>
        </table>
	</fieldset>
    
    <fieldset class="advanceSearch">
      <legend id="LETitle">Visitor pass number(s)</legend>
        <table width="100%" border="0">
           <tr>
            <td>
                <select multiple="multiple" name="all_visitorPassNum" size="5" id="all_visitorPassNum" style="width:458px">>
                <?php
					for($i=1;$i<=100;$i++){
						if(in_array($i, $all_visitornum)){
							echo "<option value='".$i."' selected='selected'>".$i."</option>";
						}else{
							echo "<option value='".$i."'>".$i."</option>";
						}	
					}
                ?>
                </select>
            </td>
          </tr>
        </table>
	</fieldset>
    <fieldset class="advanceSearch">
    <table width="100%" border="0">
      <tr>
        <td width="25"><input type="checkbox" name="withvehicle" id="withvehicle" <?php echo ($withVehicle ? "checked='checked'" : ""); ?>/></td>
        <td><label for="withvehicle">Visitors with vehicle</label></td>
      </tr>
    </table>
    </fieldset>

</div>