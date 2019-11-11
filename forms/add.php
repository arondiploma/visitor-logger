<?php
	include_once("config.php");
?>

<div class="add_form">
    <div style="border-top:1px solid #CCC; border-bottom:1px solid #CCC; width:99%; padding:3px; margin-bottom:4px;" >
    	<div style="width:400px; float:left; " align="left">
        	<img src="resources/images/OL_user.png" style="float:left; margin-top:3px; " />&nbsp;
            <div style="width:350px; float:left; margin-top:10px;">
        	<?php
            	if(isset($_SESSION['_fullname'])){
					echo $_SESSION['_fullname'];
				}
		?>
            &nbsp;|&nbsp; 
            <?php
				if(isset($_SESSION['_accesslevel'])){
					if($_SESSION['_accesslevel'] == "admin"){
						echo "<a href=\"#\" id=\"user\" style=\"color: #00C; text-decoration:none; \">USER</a> &nbsp;|&nbsp;";
					}
				}
			?>
            <a href="logout.php" style="color: #00C; text-decoration:none; ">LOG OUT</a>
           	</div>
            
    	</div>
        <div style="width:190px; float:right;"  align="right">
            <span id="submit"><a></a></span>
            <span id="clear"><a></a></span>
    	</div>
        <div style="clear:both"></div>
    </div>

	<form action="action/save.php"  method="post" name="add_form" id="add_form">
            <table width="100%" border="0" style="margin-bottom:3px;">
              <tr>
                <td width="221">Name</td>
                <td width="818" ><input type="text" name="visitor_name" id ="visitor_name" style='width:250px;' tabindex="1"/></td>
                <td width="89" rowspan="6" valign="top">

                 </td>
              </tr>
              
              <tr>
                <td>Destination</td>
                <td valign="top">
                	<table width="100%" border="0" style="border-collapse:collapse">
                      <tr>
                        <td width="262">
                        	<div id="destination" style="width:267px; height:30px;"></div>
                        </td>
                        <td><span id="LEdestination"><a title="List editor"></a></span></td>
                      </tr>
                    </table>
                </td>
              </tr>
              <tr>
                <td>Visitor Pass #</td>
                <td >
                	<table width="100%" border="0" style="border-collapse:collapse">
                      <tr>
                        <td width="50">
                        <div id="colorCode" class="colorCode"></div>
                        </td>
                        <td>
                        	<div id="gatepassnum" style="width:100px; height:30px;"></div>
                        </td>
                      </tr>
                    </table>
                	
                </td>
              </tr>
              <tr>
                <td>Purpose</td>
                <td>
                	<table width="100%" border="0" style="border-collapse:collapse">
                      <tr>
                          <td width="20"><input type="radio" name="purpose_option" value="from_options" id="purposeoption"  checked="checked" tabindex="4"/></td>
                          <td width="247">
                                <div id="purpose_option" style="width:247px; height:30px;"></div>
                          </td>
                          <td><span id="LEpurpose"><a title="List editor"></a></span></td>
                      </tr>
                    </table>
                </td>
              </tr>
              <tr>
                <td></td>
                <td>
                	<input type="radio" name="purpose_option" value="other_options"  id="otheroptions"  tabindex="6"/>
                    <label for="otheroptions" class="otheroptions disable" id="otherslabel">Others</label>
                    <input type="text" name="purpose_others" id="otherstxtbox" disabled="disabled" style='width:195px;'  tabindex="7"/>
                </td>
              </tr>
              <tr>
                <td valign="top">Remarks</td>
                <td ><textarea name="remarks" style='width:256px;' tabindex="8" ></textarea></td>
              </tr>
              <tr>
                <td>Plate no.</td>
                <td ><input type="text" name="plate_number"  tabindex="9"  style="text-transform: uppercase;"/></td>

              </tr>
            </table>
		</form>
</div>
<div  style="padding-left:5px;">
        <div id="flashContent">
            <a href="http://www.adobe.com/go/getflash">
                <img src="resources/images/get_flash_player.gif" alt="Get Adobe Flash player" />
            </a>
          <p>This page requires Flash Player version 9.0.124 or higher.</p>
        </div>
</div>