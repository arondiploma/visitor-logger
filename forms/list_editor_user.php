<fieldset  class="editor" style="width:516px;">
    <legend id="LETitle"> Add New</legend>
    <form name="UserEditor" >
        <table width="99%" border="0" style="border-collapse:collapse;">
          <tr height="37">
            <td>Username</td>
          	
            <td><input  type="text" name="username" id="username" tabindex="100" style="width: 315px; height:18px !important; margin-right:5px !important;" />
            </td>
            <td valign="middle"><span id="submit_editor_save"><a></a></span></td>
          </tr>
          <tr height="37">
            <td>Fullname</td>
            <td colspan="2"><input  type="text" name="fullname" tabindex="101" style="width: 315px; height:18px !important; margin-right:5px !important;" />
            </td>
          </tr>
          <tr height="37">
           <td>Access level</td>
            <td colspan="2"><select id="accesslevel" name="accesslevel" tabindex="102" style='width:164px;'>
			 					 <option value="">-select-</option>
                                <option value="standard">Standard User</option>
                                <option value="admin">Administrator</option>
                			</select>
            </td>
          </tr>
          <tr height="37">
          	 <td>Password</td>
             <td colspan="2">
             				<input  type="password" name="password1" tabindex="103" style="width: 150px; height:18px !important; margin-right:5px !important;" />
                    Re-type <input  type="password" name="password2" tabindex="104" style="width: 150px; height:18px !important; margin-right:5px !important;" />
            </td>
          </tr>
        </table>

    </form>
</fieldset>