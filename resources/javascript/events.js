		
function setupEvents(){
	
    addSearchEventHandler("txtSearch1",dataRepeater);
    addSearchEventHandler("txtSearch2",dataRepeaterAllVisitors);
	
    dhxToolbar2.attachEvent("onClick", function(id){   
        if(id == "advanceSearch"){
            $("#captureWebcam").hide();	// hide cam first
			
            DRurl = ( dataRepeaterAllVisitors.call.getURL().indexOf("?")!=-1 ? dataRepeaterAllVisitors.call.getURL() : dataRepeaterAllVisitors.call.getURL()+"?").split("?");
			
            $.NXSdialog({
                "url"		:	Nexus.url + "forms/advanceSearch.php?" + DRurl[1],
                "icon"	:	"icon-question",
                "title"	:	"Advance Search",
                "height"	: 	420,
                "width"	: 	500,
                "onClose" :function(){
                    $("#captureWebcam").show();	// THEN SHOW CAM
                },
                "loadCallback" : function(){
								
                    $("#all_destination").multiselect({
                        noneSelectedText: 'Select destination(s)',
                        selectedText: "# of # selected",
                        allSelectedText: "(All Selected)",
                        selectedList: 3
                    });
									   
                    $("#all_purpose").multiselect({
                        noneSelectedText: 'Select purpose(s)',
                        selectedText: "# of # selected",
                        allSelectedText: "(All Selected)",
                        selectedList: 3
                    });
									   
                    $("#all_visitorPassNum").multiselect({
                        noneSelectedText: 'Select visitor pass number(s)',
                        selectedText: "# of # selected",
                        allSelectedText: "(All Selected)",
                        selectedList: 10,
                        position: {
                            my: 'left bottom',
                            at: 'left top'
                        }
                    });
									   
                    $( "#datepickerFrom, #datepickerTo" ).datepicker({
                        changeMonth: true,
                        changeYear: true
                    });
											
                },
                "buttons"	:	{
                    "Filter Now"	: function(invoker,dialog){

                        var dateFrom="",dateTo="";
                        var valid = true;
                        var datepickerFrom = $("#datepickerFrom").val();
                        var hourpickerFrom = $("#hourpickerFrom").val();
                        var minutepickerFrom = $("#minutepickerFrom").val();
															
                        var datepickerTo = $("#datepickerTo").val();
                        var hourpickerTo = $("#hourpickerTo").val();
                        var minutepickerTo = $("#minutepickerTo").val();
															
                        var all_destination = $("#all_destination").val();
                        var all_purpose = $("#all_purpose").val();
                        var all_visitorPassNum = $("#all_visitorPassNum").val();

                        if(datepickerFrom != "" && datepickerFrom != null){
																
                            if(	datepickerTo == "" || datepickerTo == null){
                                valid = false;
                                alert("Please specify the correct range!");	
                            }else{
                                dateFrom = 	datepickerFrom + " " + (hourpickerFrom=="*" ? "00" : hourpickerFrom) + ":" + (minutepickerFrom=="*" ? "01" :minutepickerFrom);
                                dateTo = 	datepickerTo + " " +  (hourpickerTo=="*" ? "23" : hourpickerTo) + ":" + (minutepickerTo=="*" ? "59" :minutepickerTo);
                            }
                        }
															
                        if(all_destination=="" || all_destination==null){
                            all_destination="";	
                        }
															
                        if(all_purpose=="" || all_purpose==null){
                            all_purpose="" ;
                        }
															
                        if(all_visitorPassNum=="" || all_visitorPassNum==null){
                            all_visitorPassNum="";
                        }
															
                        withVehicle = $("#withvehicle").attr("checked") == undefined ? 0 : 1;
															
                        if(valid){
                            dataRepeaterAllVisitors.call.setURL("list/allvisitors.php?DTFrom="+dateFrom+"&DTTo="+dateTo+"&destination="+all_destination+"&purpose="+all_purpose+"&visitornum="+all_visitorPassNum+"&withVehicle="+withVehicle);
                            dataRepeaterAllVisitors.call.reload();
                            dialog.close();	
                        }
																			
                    },
                    "Close"	: function(invoker,dialog){
                        dialog.close();	
														
                    }
                }					
            });//$.NXdialog	
        }else if(id == "showAll"){
            dataRepeaterAllVisitors.call.setURL("list/allvisitors.php");
            dataRepeaterAllVisitors.call.reload();	
        }else if(id == "print"){
            DRurl = ( dataRepeaterAllVisitors.call.getFullURL().indexOf("?")!=-1 ? dataRepeaterAllVisitors.call.getFullURL() : dataRepeaterAllVisitors.call.getFullURL()+"?").split("?");
            window.open("print/?r=visitor&"+DRurl[1]);
        }
    });
	
    dhxLayout.attachEvent("onPanelResizeFinish", function(){
        setTimeout("_resize();",200);
    });
		
    dhxLayout.attachEvent("onCollapse", function(a){
        setTimeout("_resize();",200);
    });

    $(window).resize(function() {
        setTimeout("_resize();",200);
    });

							
    $("#submit").NXSbutton({
        "icon":"nxs-button-save",
        "text":"Save",
        "width" : 65,
        "click":function(){
            _submit();
        }
    });
					
    $("#clear").NXSbutton({
        "icon":"nxs-button-cancel",
        "text":"Clear",
        "width" : 65,
        "click":function(){
            document.forms['add_form'].reset();
            _reset(true);
            getSWFObj("captureWebcam").clearSnapshot();
        }
    });

    $("#user").click(function(){
		
        dhxComboBox["LEdestination"].closeAll();
        dhxComboBox["LEpurpose"].closeAll();
				
        $("#captureWebcam").hide(); //HIDE CAM FIRST
        $.NXSdialog({
            content	:	"<div class='listeditor_main' style='width:530px;'><div class='add_edit' style='height:180px; width:530px;' id='add_edit'></div> <div class='listgrid' style='height:230px; width:530px;' id='GridUser'></div></div>",
            title	:	"User Management",
            height	: 	510,
            width	: 	550,
            onClose:function(){
                $("#captureWebcam").show();	// THEN SHOW CAM
                $("form[name='add_form']").find("input,select,textarea").removeAttr("disabled");
            },
            buttons	:	{
                Close	: function(invoker,dialog){
                    dialog.close();		
                }
            },
            loadCallback :function(){
                var grid;
                var label = {
                    "LEdestination":"Destination(s)",
                    "LEpurpose":"Purpose(s)"
                };
														 
                grid = new dhtmlXGridObject("GridUser");
                grid.setImagePath("resources/images/dhtmlx/imgs/");
											
                grid.setHeader("Username, Fullname, Access level, Action,#cspan");
                grid.setInitWidths("120,*,140,40,40");
                grid.setColAlign("left,left,left,left,left");
                grid.setColTypes("ro,ro,ro,ro,ro");
                grid.setColSorting("str");
                grid.init();
                grid.setSkin("dhx_terrace");
                grid.loadXML("list/user.php");
                grid.setUserData("","cellClicked",false); //special purpose
                grid.setUserData("","editRecordID",""); //special purpose
                grid.attachEvent("onRowSelect", function(id,ind,obj){
                    if(!grid.getUserData("","cellClicked")){
                        if(ind == 3){//EDIT
                            LEeditForm(grid,"User",grid.getUserData(id,"username"));
                        }else if(ind ==4){//DELETE
                            d = grid.getUserData(id,"allowdelete");
                            if(d == 1 || d == "1"){
                                grid.setUserData("","cellClicked",true);
                                deleteListEditor("User",grid.getUserData(id,"username"),grid);
                            }
                        }
                    }
                });
											
											
                swicthFormMode("add",grid,"User");
            }
        });
    });
										
    $("#LEdestination, #LEpurpose").NXSbutton({
        "icon":"list",
        "style"	: "LE",
        "click":function(that){
							
            dhxComboBox["LEdestination"].closeAll();
            dhxComboBox["LEpurpose"].closeAll();
									
            var parentId = $(that).parent().attr("id");
            var gridH ={
                "LEdestination":"295px",
                "LEpurpose":"335px"
            };
            var editorH ={
                "LEdestination":"115px",
                "LEpurpose":"75px"
            };
									
            $("#captureWebcam").hide(); //HIDE CAM FIRST
            $.NXSdialog({
                content	:	"<div class='listeditor_main'><div class='add_edit' style='height:"+editorH[parentId]+";' id='add_edit'></div> <div class='listgrid' style='height:"+gridH[parentId]+";' id='Grid"+parentId+"'></div></div>",
                title	:	"List Editor",
                height	: 	510,
                width	: 	470,
                onClose:function(){
                    $("#captureWebcam").show();	// THEN SHOW CAM
                    $("form[name='add_form']").find("input,select,textarea").removeAttr("disabled");
                },
                buttons	:	{
                    Close	: function(invoker,dialog){
                        dialog.close();		
                    }
                },
                loadCallback :function(){
                    var grid;
                    var label = {
                        "LEdestination":"Destination(s)",
                        "LEpurpose":"Purpose(s)"
                    };
																			 
                    grid = new dhtmlXGridObject("Grid"+parentId);
                    grid.setImagePath("resources/images/dhtmlx/imgs/");
																
                    grid.setHeader(label[parentId]+",Action,#cspan",null,["text-align:left;","text-align:center;","text-align:center"]);
                    grid.setInitWidths("*,40,40");
                    grid.setColAlign("left,left,left");
                    grid.setColTypes("ro,ro,ro");
                    grid.setColSorting("str");
                    grid.init();
                    grid.setSkin("dhx_terrace");
                    grid.loadXML("list/"+parentId+".php");
                    grid.setUserData("","cellClicked",false); //special purpose
                    grid.setUserData("","editRecordID",""); //special purpose
                    grid.attachEvent("onRowSelect", function(id,ind,obj){
                        if(!grid.getUserData("","cellClicked")){
                            if(ind == 1){//EDIT
                                LEeditForm(grid,parentId,grid.getUserData(id,"id"));
                            }else if(ind ==2){//DELETE
                                d = grid.getUserData(id,"allowdelete");
                                if(d == 1 || d == "1"){
                                    grid.setUserData("","cellClicked",true);
                                    deleteListEditor(parentId,grid.getUserData(id,"id"),grid);
                                }
                            }
                        }
                    });
																
																
                    swicthFormMode("add",grid,parentId);
                }
            });
									

        }
    }).children("a").NXStooltip();


	
    $("#purpose_option").on("focus",function(){
        $("#otherstxtbox").attr("disabled","disabled");
    });

    $("input[name=purpose_option]").click(function(){
        if($(this).val() == "from_options"){
            //$("#purpose_option").removeAttr("disabled");
            //$("#purpose_option").focus();
            ComboBoxPurpose.disable(false);
				
            $("#otherstxtbox").attr("disabled","disabled");
            $("#otherslabel").addClass("disable");
        }else{
            $("#otherstxtbox").removeAttr("disabled");
            $("#otherstxtbox").focus();
            //$("#purpose_option").attr("disabled","disabled");
            ComboBoxPurpose.disable(true);
            $("#otherslabel").removeClass("disable");
        }
    });	
}

function addSearchEventHandler(id,DR){
    
    $("#" + id).keyup(function(e){
        if(e.altKey == false && e.ctrlKey ==false){
            DR.call.filter("q=name&v=" + $(this).val()); 
        }

    }).focus(function(){
        $(this).val( ($(this).val() == "Search by Name..." ? "" : $(this).val()) );
        $(this).css({
            "color":"#000000"
        });
    }).focusout(function(){
        if($(this).val() == ""){
            $(this).val("Search by Name...").css({
                "color":"#939393"
            });
        }			
    });
	
}
