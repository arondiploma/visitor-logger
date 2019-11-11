<?php
include("config.php");

$des = array();
$query = mysql_query("SELECT * FROM `destination`");
$total_row = mysql_num_rows($query);
while ($row = mysql_fetch_array($query)) {
    $des[$row['dest_id']] = $row['colorcode'];
}


$destination_sql = "Select * FROM destination ORDER BY `destination` ASC";
$destination_query = mysql_query($destination_sql);

$purpose_sql = "Select * FROM purpose";
$purpose_query = mysql_query($purpose_sql);

global $gate_pass_nums;
$gate_pass_nums = array();
$used_gatePass_sql = "SELECT * FROM visitor WHERE (`out` IS NULL or `out` = '0000-00-00 00:00:00')";
$used_gatePass_query = mysql_query($used_gatePass_sql);
for ($i = 0; $gatePass_row = mysql_fetch_array($used_gatePass_query); $i++) {
    $gate_pass_nums[$i] = $gatePass_row['gatepassnum'];
}

function isInUsed($num) {
    global $gate_pass_nums;
    for ($x = 0; $x < count($gate_pass_nums); $x++) {
        if ($num == $gate_pass_nums[$x]) {
            return true;
        }
    }
    return false;
}

////////////////////
$allDestination = "[";
while ($destination_row = mysql_fetch_array($destination_query)) {
    if ($allDestination == "[") {
        $allDestination = $allDestination . "['" . $destination_row['dest_id'] . "','" . addSlashes($destination_row['destination']) . "']";
    } else {
        $allDestination = $allDestination . ",['" . $destination_row['dest_id'] . "','" . addSlashes($destination_row['destination']) . "']";
    }
}
$allDestination = $allDestination . "]";

//////////////////////////////////
$allUsedVisitorsNum = "new Array(0";
for ($i = 1; $i <= 100; $i++) {
    if (isInUsed($i)) {
        $allUsedVisitorsNum = $allUsedVisitorsNum . "," . $i;
    }
}
$allUsedVisitorsNum = $allUsedVisitorsNum . ")";

/////////////////////////
$allPurpose = "[";
while ($purpose_row = mysql_fetch_array($purpose_query)) {
    if ($allPurpose == "[") {
        $allPurpose = $allPurpose . "['" . $purpose_row['pur_id'] . "','" . $purpose_row['purpose'] . "']";
    } else {
        $allPurpose = $allPurpose . ",['" . $purpose_row['pur_id'] . "','" . $purpose_row['purpose'] . "']";
    }
}
$allPurpose = $allPurpose . "]";
?>
<!DOCTYPE html>
<html>
    <head>
        <title>CHMSC - Visitor Logger</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <link href="favicon.ico" rel="icon" type="image/x-icon" />
        <script>
            window.dhx_globalImgPath="resources/images/dhtmlx/imgs/";
            dhxTreeImagePath = "resources/images/dhtmlx/imgs/csh_books/";
        </script> 
        <?php
        $files = scandir("resources/stylesheet/");
        foreach ($files as $key => $value) {
            if ($key >= 2) {
                echo "<link href='resources/stylesheet/" . $value . "' rel='stylesheet'/>";
            }
        }
        ?>

        <?php
        $files = scandir("resources/javascript/");
        foreach ($files as $key => $value) {
            if ($key >= 2) {
                if (strstr($value, '.js')) {
                    echo "<script src='resources/javascript/" . $value . "'></script>";
                }
            }
        }
        ?>
        <style type="text/css" media="screen">
            html, body { height:100%; background-color: #ffffff;}

            *,body,ul,li{
                margin:0;
                padding:0;
                font-family: Arial, Helvetica, sans-serif;
                font-size:12px;

            }
            body { margin:0; padding:0; overflow:hidden; }
            #flashContent { width:100%; height:100%; }
            .hidden_elements{
                visibility:hidden;
            }
            .add_form {
                font-size:12px;
                padding:2px;
            }
            .otheroptions{
                font-size:12px;
            }
            .inputbox_req{
                border: solid 1px #FF0000 !important;
                box-shadow: 0 0 3px rgba(255, 0, 0, .7);
                -webkit-box-shadow: 0 0 3px rgba(255, 0, 0, .7); 
                -moz-box-shadow: 0 0 3px rgba(255, 0, 0, .7); 
            }
            .normal{
                border-radius:0 !important; 
                -moz-border-radius: 0 !important; 
                -webkit-border-radius: 0 !important; 
                -khtml-border-radius: 0 !important;
                padding-bottom:3px;
                padding-top:3px;
                margin-right:3px;
            }
            .disable{
                color:#666;	
            }
            input, textarea, .sel{
                border: solid 1px #868686 ;
                font-family:Verdana, Geneva, sans-serif;
                font-size:12px;
                padding:5px;
                margin-bottom:1px;
                margin-top:2px;
            }
            select{
                padding:2px;
            }
            .searchInp{
                padding-top:7px !important;
                padding-bottom:7px !important;
                margin:0px;
                border-radius:5px;
                -moz-border-radius: 5px !important; 
                -webkit-border-radius: 5px !important; 
                -khtml-border-radius: 5px !important;

                -moz-user-select: text !important; 
                width: 200px; 
                color:#939393
            }
            textarea{
                padding-right:0;	
            }
            .label{
                color:#666;	
            }

            .header{
                background-color:#fff;
                background-repeat:repeat-x;
                height:47px;
                width:100%;
            }
            .banner{
                background-image:url(resources/images/banner.png);
                background-repeat:no-repeat;
                height:47px;
                width:554px;
                float:left;
            }
            textarea{ 
                resize:none ;
            } 
            .data_repeater_view{
                border-top:1px solid #cacaca;
            }
            .indicator{
                float:right;
                position:absolute;
                right:0;
            }
            .listeditor_main{
                width:450px; 
                height: 400px;
            }
            .listeditor_main .add_edit{
                width:100%; 
            }

            .listeditor_main .listgrid{
                border:1px solid #999; 
                width:450px; 
            }
            fieldset.editor{
                padding:4px;	
                padding-left: 10px;
                border:1px solid #999; 
                width:436px;
                padding-bottom:6px;
            }
            fieldset.advanceSearch{
                padding:5px;
                margin:5px;
            }
            .colorCode{
                width:46px; 
                height:27px;
                border:1px solid  #CCC;	
                border-radius:0px !important;
            }
            .tm{
                background-color: #006;
            }
        </style>

        <script>

			
            var Nexus = {"url":"http://<?php echo $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; ?>",
                "noPro_url":"<?php echo $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; ?>",
                "destination":<?php echo json_encode($des); ?>
            };
			
            var error_msg = {	"visitor_name" : "Please specify the name of the visitor!",
                "destination" : "Please specify the destination!",
                "purpose": "Please specify the purpose!",
                "purpose_select" : "Please specify the purpose!",
                "purpose_option" : "Please specify the purpose!",
                "gatepassnum"	: "Please specify the gate pass number!"
            };
            var dhxLayout, dhxTabbar, dhxToolbar, dhxToolbar2, dataRepeater, dataRepeaterAllVisitors;
            var dhxToolbarHeight = 0;
			
            var ComboBoxDestination,ComboBoxVisitorPass,ComboBoxPurpose;
            var dhxComboBox = {	"LEdestination":null, "LEpurpose":null, "LEgatepassnum": null, "accesslevel": null};
            var allUsedVisitorsNum = <?php echo $allUsedVisitorsNum; ?>;
			
            function _submit(){
                try{
                    var error = false;
					
                    var inputs = $("form[name='add_form']").find("input,textarea,select");
                    $.each(inputs,function(k,v){
                        var inp = $(v);
                        var n = inp.attr("name");
						
                        switch(n){
							
                            case "visitor_name":
                                if(!error){
                                    error = eval_input(inp);
                                }else{
                                    eval_input(inp);
                                }
                                break;
                            case "destination":
                            case "gatepassnum":
                                if(!error){
                                    error = eval_DHXinput(inp,dhxComboBox["LE"+n].getSelectedValue());
                                }else{
                                    eval_DHXinput(inp,dhxComboBox["LE"+n].getSelectedValue());
                                }
                                break;
                            case "purpose_select":
                                if(!error){
                                    error = eval_DHXinput2(inp,dhxComboBox.LEpurpose.getSelectedValue());
                                }else{
                                    eval_DHXinput2(inp,dhxComboBox.LEpurpose.getSelectedValue());
                                }
                                break;
                            case "purpose_others":
                                if(!error){
                                    error = eval_input_wOption(inp);
                                }else{
                                    eval_input_wOption(inp);
                                }
                                break;
                        }
                    });
					
				
                    if(error){
                        $.jGrowl("Please specify correctly the required field(s)!");
                    }else if(!getSWFObj("captureWebcam").isHasCapturedImage()){
                        $.jGrowl("No image/picture captured!");
                    }else{
						
                        //	alert($("form[name='add_form']").serialize());
                        getSWFObj("captureWebcam").submitCapturedImage("_doSave()");
								
                    }

                }catch(e){
                    alert(e);	
                }
            }			
			
            function _doSave(imgName){
					
                //alert("action/save_visitor.php?"+$("form[name='add_form']").serialize() + "&imgname="+imgName);
                $.ajax({
                    url : "action/save_visitor.php?"+$("form[name='add_form']").serialize() + "&imgname="+imgName,
                    cache: false,
                    success: function(data) {
                        var result =  jQuery.parseJSON( data );
							
                        if(result.status == "failed"){
                            alert(result.message);	
                        }else{
                            $.jGrowl(result.message);
                        }
							
                        addFromList(dhxComboBox["LEgatepassnum"].getSelectedValue());
                        dhxComboBox["LEgatepassnum"].deleteOption(dhxComboBox["LEgatepassnum"].getSelectedValue());

                        //$("#gatepassnum option:selected").remove();
							
                        _reset(false);
                        dataRepeater.call.reload();
                        dataRepeaterAllVisitors.call.reload();
                    },
                    statusCode: {
                        404: function() {
                            alert('error @ load data :: _doSave');
                        }
                    }
                });
            }

            function _reset(cssOnly){
				
                var inputs = $("form[name='add_form']").find("input,textarea,select");
				
                $.each(inputs,function(k,v){
                    var inp = $(v);
					
                    if(cssOnly){
                        inp.removeClass("inputbox_req");	
                    }else{
                        if(inp.attr("type") != "button" && inp.attr("type") != "radio"){
                            inp.val("");
                            if(inp.attr("name") == "purpose_option" && inp.val() == "from_options"){
                                inp.attr("checked","checked");
                            }
                        }
                    }

                });	
				
                $("#destination div, #purpose_option div, #gatepassnum div").removeClass("inputbox_req");
                $("#destination input[type='text'], #purpose_option input[type='text'], #gatepassnum input[type='text']").attr("");
				
                $("#colorCode").css({"backgroundColor":"","borderColor":""});
				
                dhxComboBox["LEdestination"].setComboValue("");
                dhxComboBox["LEpurpose"].setComboValue("");
                dhxComboBox["LEgatepassnum"].setComboValue("");
				
                $("#visitor_name").focus();
            }

            var DR_ids = {"activevisitors":"DR1","allvisitors":"DR2"};
            function _resize(){
                //$("#"+DR_ids[dhxTabbar.getActiveTab()]).height(dhxTabbar._content[dhxTabbar.getActiveTab()].offsetHeight);
                $("#"+DR_ids[dhxTabbar.getActiveTab()]).trigger("_resize");
            }
			
            function loadDataReapeter(){
                dataRepeater.call.load();
                dataRepeaterAllVisitors.call.load();
            }
			
            function preloadDataReapeter(){
                
                dhxToolbar.addText("txtSearch", 0, "<input class='searchInp' value='Search by Name...' type='text' id='txtSearch1' onclick='(arguments[0]||window.event).cancelBubble=true;'>");
                dhxToolbar.addText("lbl", 1, "<div id='indicator1' class='indicator'></div>");
				
                dhxToolbar2.addText("txtSearch", 0, "<input class='searchInp'  value='Search by Name...' type='text' id='txtSearch2' style='width:150px;' onclick='(arguments[0]||window.event).cancelBubble=true;'>");
                dhxToolbar2.addButton("advanceSearch", 1, "Advance", "resources/images/dhtmlx/imgs/csh_dhx_terrace/iconAdvanceSearch.png");
                dhxToolbar2.setItemToolTip("advanceSearch", "Advance search");
                dhxToolbar2.addButton("showAll", 2, "", "resources/images/dhtmlx/imgs/csh_dhx_terrace/iconShowAll.png");
                dhxToolbar2.setItemToolTip("showAll", "Show all records");
                dhxToolbar2.addButton("print", 3, "Print", "");
                dhxToolbar2.addText("lbl",4, "<div id='indicator2' class='indicator'></div>");
                
                dataRepeater = $("#DR1").dataRepeater({ 
                    data_url : "list/visitors.php",
                    rowtoolbarbuttons : rowtoolbarbuttons["visitor"],
                    toolbar: false,
                    autoload: false,
                    recodsindicator : "indicator1",
                    row_template : "<table width='100%' border='0' height='80'><tr><td width='130' rowspan='4' align='center' valign='bottom' style='padding-top:5px;'>{image}</td><td align='center' width='40'><div class='normal' style='background-color:#{bcolor};'><strong>{gatepassnum}</strong></div></td><td height='19'><span class='label'>Name :</span> {name}</td></tr><tr><td height='19' colspan='2'><span class='label'>Destination :</span>{dest}</td></tr><tr><td height='19' colspan='2'><span class='label'>Time IN :</span>{in}</td></tr><tr><td height='19' colspan='2'><span class='label'>Purpose :</span>{pur}</td></tr></table>"
                });
							
                dataRepeaterAllVisitors = $("#DR2").dataRepeater({ 
                    data_url : "list/allvisitors.php",
                    rowtoolbarbuttons : rowtoolbarbuttons["allvisitor"],
                    toolbar: false,
                    autoload: false,
                    recodsindicator : "indicator2",
                    row_template : "<table width='100%' border='0' height='80'><tr><td width='130' rowspan='4' align='center' valign='bottom' style='padding-top:5px;'>{image}</td><td align='center' width='40'><div class='normal' style='background-color:#{bcolor};'><strong>{gatepassnum}</strong></div></td><td height='19'><span class='label'>Name :</span> {name}</td></tr><tr><td height='19' colspan='2'><span class='label'>Destination :</span>{dest}</td></tr><tr><td height='19' colspan='2'><span class='label'>Time :</span>{time}</td></tr><tr><td height='19' colspan='2'><span class='label'>Purpose :</span>{pur}</td></tr></table>"
                });
				
				
				

            }

            $(document).ready(function(){
                setTimeout("loadUI()",500);
            });
			
            function getGatePassNumList(){
                var num = new Array();
                for(i=1;i<=100;i++){
                    if(allUsedVisitorsNum.indexOf(i)==-1){
                        var opt = new Array();
                        opt.push(i);
                        opt.push(i);
                        num.push(opt);
                    }
                }
                return num;
            }
            function addFromList(num){
                allUsedVisitorsNum.push(parseInt(num));
            }
            function removeFromList(num){
				
                var idx = allUsedVisitorsNum.indexOf(parseInt(num),0);
                if(idx!=-1){ 
                    allUsedVisitorsNum.splice(idx, 1); 
                }
				
            }

            function refreshGatePassNumCBO(){
                ComboBoxVisitorPass.clearAll();
                ComboBoxVisitorPass.addOption(getGatePassNumList());
            }
			
            function loadUI()
            {
                $("#init_preloader").hide();
                dhxLayout = new dhtmlXLayoutObject(document.body,"4T");
				
                dhxLayout.setEffect("resize", false);
                dhxLayout.setEffect("collapse", false);
		
                //Configure cell "a"
                dhxLayout.cells("a").hideHeader();
                dhxLayout.cells("a").setHeight(49);
                dhxLayout.cells("a").fixSize(true, true);
                dhxLayout.cells("a").attachHTMLString("<div class='header'><div class='banner'></div></div>");

                //Configure cell "b"
                form_str = $("#add_form").html().replace("<!--","").replace("!-->","");
                dhxLayout.cells("b").hideHeader();
                dhxLayout.cells("b").setWidth(610);	
                dhxLayout.cells("b").fixSize(true, true);
                dhxLayout.cells("b").attachHTMLString(form_str);
				
                ComboBoxDestination = new dhtmlXCombo("destination", "destination", 260, 2);
                ComboBoxDestination.addOption(<?php echo $allDestination; ?>);
                ComboBoxDestination.setOptionHeight(200);
                ComboBoxDestination.readonly(true,true);
                ComboBoxDestination.enableOptionAutoPositioning();
                ComboBoxDestination.attachEvent("onChange", function(){
                    id = ComboBoxDestination.getSelectedValue();
                    //ComboBoxVisitorPass.loadXML("list/visitorspassnum.php="+Nexus.destination[id]);
                    $("#colorCode").css({
                        "backgroundColor":"#"+Nexus.destination[id],
                        "borderColor":"#"+Nexus.destination[id] 
                    });
                });
                $("#destination input[type='text']").attr("tabindex",2);
				
				
                ComboBoxVisitorPass = new dhtmlXCombo("gatepassnum", "gatepassnum", 100, 3);
                refreshGatePassNumCBO();
                ComboBoxVisitorPass.setOptionHeight(200);
                ComboBoxVisitorPass.enableOptionAutoPositioning();
                ComboBoxVisitorPass.readonly(true,true);
                $("#gatepassnum input[type='text']").attr("tabindex",3);

                ComboBoxPurpose = new dhtmlXCombo("purpose_option", "purpose_select", 240, 4); 
                ComboBoxPurpose.addOption(<?php echo $allPurpose; ?>);
                ComboBoxPurpose.setOptionHeight(200);
                ComboBoxPurpose.enableOptionAutoPositioning();
                ComboBoxPurpose.readonly(true,true);
                $("#purpose_option input[type='text']").attr("tabindex",4);

                dhxComboBox["LEdestination"] = ComboBoxDestination;
                dhxComboBox["LEpurpose"] = ComboBoxPurpose;
                dhxComboBox["LEgatepassnum"] = ComboBoxVisitorPass

                //Configure cell "c"
                dhxLayout.cells("c").setWidth(518);

                dhxTabbar = dhxLayout.cells("c").attachTabbar();
                dhxTabbar.setImagePath("resources/images/dhtmlx/imgs/");
                dhxTabbar.addTab("activevisitors","Current Visitor(s)");
                dhxTabbar.addTab("allvisitors","All Visitors");
                dhxTabbar.setTabActive("activevisitors");
                dhxTabbar.attachEvent("onSelect", function(id,last_id){
                    setTimeout("_resize();",200);
                    return true;
                });
		  
                dhxToolbar = dhxTabbar.cells("activevisitors").attachToolbar();
                dhxToolbar2 = dhxTabbar.cells("allvisitors").attachToolbar();
                dhxToolbar2.setSkin("dhx_terrace");
				
                dhxToolbarHeight = $(dhxTabbar._content["activevisitors"].innerHTML).height() + 7;
				
                dhxTabbar.setContentHTML("activevisitors","<div id='DR1' ></div>");
                dhxTabbar.setContentHTML("allvisitors","<div id='DR2' ></div>");
				
                //Configure cell "d"
                dhxLayout.cells("d").setText("Directory");
                html_str = $("#directories").html().replace("<!--","").replace("!-->","");
                dhxLayout.cells("d").attachHTMLString(html_str);
				
                preloadDataReapeter();
                setupEvents();		
				
                var swfVersionStr = "9.0.124";
                <!-- xiSwfUrlStr can be used to define an express installer SWF. -->
                var xiSwfUrlStr = "";
                var flashvars = {};
                var params = {	quality : "high",
                    bgcolor : "#ffffff",
                    play : "true",
                    loop : "true",
                    wmode : "window",
                    scale : "showall",
                    menu : "true",
                    devicefont : "false",
                    salign : "",
                    allowscriptaccess : "sameDomain"
                };
                var attributes = {	id : "captureWebcam",
                                        name : "captureWebcam",
                                        align : "middle"
                                };
                swfobject.embedSWF(
                                    "swf/captureWebcam.swf", "flashContent",
                                    "605", "265",
                                    swfVersionStr, xiSwfUrlStr,
                                    flashvars, params, attributes,function(){
                                        setTimeout('_init();',1500);
                                        $("#visitor_name").focus();
                                });	
                
            }
        </script>
    </head>
    <body>
        
        <div style="width:100%; margin-top:20%; font-family:Verdana, Geneva, sans-serif; color:#006" align="center" id="init_preloader">
            L O A D I N G...
        </div>
        <code class="hidden_elements" id="add_form" >
            <!--
            <?php
            require_once("forms/add.php");
            ?>
            !-->
        </code>

        <code class="hidden_elements" id="LEdestinationform">
            <!--
            <?php
            require_once("forms/list_editor_destination.php");
            ?>
            !-->
        </code>

        <code class="hidden_elements" id="LEpurposeform">
            <!--
            <?php
            require_once("forms/list_editor_purpose.php");
            ?>
            !-->
        </code>

        <code class="hidden_elements" id="Userform">
            <!--
            <?php
            require_once("forms/list_editor_user.php");
            ?>
            !-->
        </code>
        <code class="hidden_elements" id="directories">
            <!--
            <?php
            require_once("forms/directories.php");
            ?>
            !-->
        </code>

    </body>
</html>
