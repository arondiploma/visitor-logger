<?php
	include_once("../config.php");
	
	$id = $_REQUEST['id'];
	$sql = "SELECT * FROM user WHERE username = '".$id."'";
	$query = mysql_query($sql);
	$data = mysql_fetch_array($query);

?>
<fieldset  class="editor" style="width:516px;">
   <legend id="LETitle"> Edit </legend>
    <form name="UserEditor" id="UserEditor">
        <table width="99%" border="0" style="border-collapse:collapse;">
          <tr height="37">
            <td>Username</td>
            <td>
            	<input  type="hidden" name="username_orig" id="username_orig" value="<?php echo $data['username']; ?>" />
            	<input  type="text" name="username" id="username" tabindex="100" value="<?php echo $data['username']; ?>" style="width: 315px; height:18px !important; margin-right:5px !important;" />
            </td>
            <td valign="middle"><span id="submit_editor_update"><a></a></span></td>
          </tr>
          <tr height="35">
            <td>Fullname</td>
            <td>
            	<input  type="text" name="fullname" tabindex="101" value="<?php echo $data['fullname']; ?>" style="width: 315px; height:18px !important; margin-right:5px !important;" />
            </td>
            <td valign="middle">
            	<span id="cancel_editor_update"><a></a></span>
            </td>
          </tr>
          <tr height="35">
           <td>Access level</td>
            <td colspan="2"><select id="accesslevel" name="accesslevel" tabindex="102" style='width:164px;'>
			 					 <option value="">-select-</option>
                                 <option value="standard" <?php echo $data['utype']=="standard" ? "selected='selected'" : ""; ?>>Standard User</option>
                                 <option value="admin" <?php echo $data['utype']=="admin" ? "selected='selected'" : ""; ?>>Administrator</option>
                			</select>
                   
            </td>
          </tr>
          <tr height="35">
             <td colspan="3">
             <table width="99%" border="0" style="border-collapse:collapse;">
             	<tr>
                	<td>
                    	<input  type="password" name="password3" tabindex="103" style="width: 150px; height:18px !important; margin-right:5px !important;" />
                    </td>
                    <td>
                    	<input  type="password" name="password1" tabindex="104" style="width: 150px; height:18px !important; margin-right:5px !important;" />
                    </td>
                    <td>
                    	<input  type="password" name="password2" tabindex="105" style="width: 150px; height:18px !important; margin-right:5px !important;" />
                    </td>
                </tr>
                <tr>
                	<td align="center" style="color:#666; font-size:12px;">Current Password</td>
                	<td align="center" style="color:#666; font-size:12px;">New Password</td>
                	<td align="center" style="color:#666; font-size:12px;">Re-type Password</td>
                </tr>
             </table>
            
            </td>
          </tr>
        </table>

    </form>
</fieldset>