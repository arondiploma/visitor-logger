<?php
	$color_sql = "SELECT * FROM `colorcode`";
	$color_query = mysql_query($color_sql);
?>
<fieldset  class="editor">
    <legend id="LETitle"> Add New</legend>
    <form name="LEdestinationEditor" >
        <table width="99%" border="0" style="border-collapse:collapse;">
          <tr>
            <td>
           		<input  type="text" name="destination" style="width: 315px; height:18px !important; margin-right:5px !important;" />
            </td>
            <td valign="middle"><span id="submit_editor_save"><a></a></span></td>
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
                                        echo "<option value='".$row['colorvalue']."' title='color/colorCode.php?color=".$row['colorvalue']."'>".$row['colorname']."</option>";
                                    }
                                ?>
                                </select>
                            </td>
                        </tr>
                    </table>
            </td>
            <td valign="middle"></td>
          </tr>
        </table>

    </form>
</fieldset>