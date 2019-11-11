		<div style="border-bottom:1px dotted #000;"></div>
        <table width="100%" style="border-collapse:collapse;">
            <tr>
                <td ><span style='font-size:9px;'> Student Information System. Copyright &copy; 2010. The Elite System Group&trade;.  by:: ZeroCool. Printed:: <?php echo date("F d, Y @ h:i:s A");?>. <?php echo "User:: ".$_SESSION['user_fullname']; ?>.</span></td>
            </tr>
            <tr>
                <td >
                	<span style='font-size:9px;'>
					<?php 
						echo $_SERVER['REQUEST_URI'];
					?>	
					</span>
				</td>
            </tr>
        </table>