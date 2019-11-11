<?php
	include_once("../config.php");
	
	$id = $_REQUEST['id'];
	$sql = "SELECT * FROM destination WHERE dest_id = '".$id."'";
	$query = mysql_query($sql);
	$data = mysql_fetch_array($query);

	$color_sql = "SELECT * FROM `colorcode`";
	$color_query = mysql_query($color_sql);
?>
<fieldset  class="editor">
    <legend id="LETitle"> Edit </legend>
    <form name="LEdestinationEditor" id="LEdestinationEditor" >
    	<input type="hidden" name="dest_id" id="dest_id" value ="<?php echo $data['dest_id']; ?>"/>
        <table width="99%" border="0" style="border-collapse:collapse;">
          <tr>
            <td>
           		<input type="text" name="destination" style="width: 315px; height:18px !important; margin-right:5px !important;" value ="<?php echo $data['destination']; ?>"/>
            </td>
            <td valign="middle"><span id="submit_editor_update"><a></a></span></td>
          </tr>
          <tr height="40">
            <td>
                    <table>
                        <tr>
                            <td>
                                Color Code 
                            </td>
                            <td>
                                <select style="width:260px" class="colorDropDown" name="colorCode">
                                <option value=""> -select- </option>
                                <?php
                                    while($row = mysql_fetch_array($color_query)){
										if($data['colorcode'] == $row['colorvalue']){
											echo "<option value='".$row['colorvalue']."' title='color/colorCode.php?color=".$row['colorvalue']."' selected='selected'>".$row['colorname']."</option>";
										}else{
											echo "<option value='".$row['colorvalue']."' title='color/colorCode.php?color=".$row['colorvalue']."'>".$row['colorname']."</option>";	
										}
                                    }
                                ?>
                                </select>
                            </td>
                        </tr>
                    </table>
            </td>
            <td valign="middle"><span id="cancel_editor_update"><a></a></span></td>
          </tr>
        </table>
    </form>
</fieldset>