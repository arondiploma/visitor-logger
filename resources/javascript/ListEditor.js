
function submitListEditor(id,q,grid){
    ajaxCommonListEditor("action/"+id+"_submit.php",q, function(result){
        //$("#"+LEcomboboxIDs[id]).append("<option value='"+result.data.id+"'>"+result.data.text+"</option>");
        if(result.data.id != ""){
            var opt=new Array();
            opt[0]=new Array();
            opt[0][0]=result.data.id;
            opt[0][1]=result.data.text;
            dhxComboBox[id].addOption(opt);
        }

        if(result.data.colorcode != ""){
            Nexus.destination[result.data.id] = result.data.colorcode;
        }
        grid.clearAndLoad("list/"+id+".php");
        document.forms[id + "Editor"].reset();
        $("form[name='"+id+"Editor']").children("input[type='text']").focus();
    });
}

function updateListEditor(id,q,grid){
    ajaxCommonListEditor("action/"+id+"_update.php",q, function(result){
        //$("#"+LEcomboboxIDs[id] +" option[value='"+result.data.id+"']").html(result.data.text);
        if(result.data.id != ""){
            dhxComboBox[id].updateOption(result.data.id,result.data.id,result.data.text);
        }
					
        grid.clearAndLoad("list/"+id+".php");
        grid.setUserData("","editRecordID","");
        swicthFormMode("add",grid,id);
        if(result.data.colorcode != ""){
            Nexus.destination[result.data.id] = result.data.colorcode;
        }
    });
}
function deleteListEditor(parentId,recordId,grid){
    $.NXSdialog({
        message	:	"Are you sure you want to delete?",
        icon	:	"icon-question",
        title	:	"Confirmation",
        loading :	false,
        buttons	:	{
            "Yes": function(invoker,dialog){
                ajaxCommonListEditor("action/"+parentId+"_delete.php","key="+recordId, 
														
                    function(result){
                        if(result.data.id != ""){
                            dhxComboBox[parentId].deleteOption(result.data.id);
                        }
                        grid.deleteSelectedRows();
                        grid.setUserData("","cellClicked",false);
                        if(grid.getUserData("","editRecordID") == recordId){
                            swicthFormMode("add",grid,parentId);
                            grid.setUserData("","editRecordID","");
                        }
                    });
														
                dialog.close();
            },
            "No": function(invoker,dialog){
                dialog.close();	
                grid.setUserData("","cellClicked",false);
            }
        }					
    });

}

function swicthFormMode(mode,grid,parentId,recordid){
	
    $("form[name='add_form']").find("input,select,textarea").attr("disabled","disabled");
	
    if(mode == "add"){

        var _submit = function(){
            var error = false;
            form = $("form[name='"+parentId+"Editor']");

            $.each(form.find("input, select"),function(k,v){
                var inp = $(v);
                if(inp.attr("type") == "text" || inp.attr("type") == "password"){
                    if(!error){
                        error = eval_input(inp);
                    }else{
                        eval_input(inp);
                    }
                }else if(inp.attr("type") == undefined){
                    if(inp.val()==""){
                        error=true;
                        inp.parent().next().find("div:first-child").addClass("inputbox_req");	
                    }else{
                        inp.parent().next().find("div:first-child").removeClass("inputbox_req");	
                    }
                }else{
                    if(inp.attr("name") == "accesslevel"){
                        if(!error){
                            error = eval_DHXinput(inp,dhxComboBox.accesslevel.getSelectedValue());
                        }else{
                            eval_DHXinput(inp,dhxComboBox.accesslevel.getSelectedValue());
                        }	
                    }
                }

            });
							
            if(parentId=="User"){
                var pa = form.find("input[type='password']");
                var p1 =  $(pa[0]);
                var p2 =  $(pa[1]);
            }
							
            if(!error){
                if(parentId=="User"){
                    if(p1.val() != p2.val()){
                        $.jGrowl("Passwords you typed doesn't match!");
                        showErrorHighlight(p1);
                        showErrorHighlight(p2);
                        error=true;
                    }
                }
                if(!error){
                    hideErrorHighlight(p1);
                    hideErrorHighlight(p2);
                    submitListEditor(parentId,$("form[name='"+parentId+"Editor']").serialize(),grid);
                }
            }else{
                $.jGrowl("Please specify the required field(s)!");	
            }
        };
					
        html = $("#"+parentId+"form").html().replace("<!--","").replace("!-->","");
        $("#add_edit").html(html);
		

        $(".colorDropDown").msDropDown();
		
        $("#submit_editor_save").NXSbutton({
            "icon":"nxs-button-save",
            "text": "Save",
            "width" : 65,
            "height" : 29,
            "click":_submit
        });
		
        if(parentId=="User"){
            dhxComboBox["accesslevel"] = dhtmlXComboFromSelect("accesslevel");
            $("#username").focus();
        }

			
        $("form[name='"+parentId+"Editor']").bind("submit",function(){
            _submit();
            return false;
        }).children("input[type='text']").focus();

    }else{

        if(grid.getUserData("","editRecordID") != ""){
			
            var hasChanges = false;
            inputs = document.forms[parentId + "Editor"].getElementsByTagName("input");
			
            for(i=0;i<inputs.length;i++){
                var inp = $(inputs[i]);
                if(inp.attr("type") == "text"){								
                    if(grid.getUserData(grid.getUserData("","editRecordID"),inp.attr("name")) != "" && grid.getUserData(grid.getUserData("","editRecordID"),inp.attr("name")) != inp.val()){
                        hasChanges = true;
                    }
                }
            }
			
            if(hasChanges){
                grid.setUserData("","cellClicked",true);
                $.NXSdialog({
                    message	:	"Unsaved changes will discard. Do you want to continue?",
                    icon	:	"icon-question",
                    title	:	"Confirmation",
                    loading :	false,
                    buttons	:	{
                        "Yes": function(invoker,dialog){
                            swicthEditMode(grid,parentId,recordid);
                            dialog.close();
                            grid.setUserData("","cellClicked",false);
                        },
                        "No" : function(invoker,dialog){
                            dialog.close();
                            grid.setUserData("","cellClicked",false);
														
                        }
                    }
                });
            }else{
                swicthEditMode(grid,parentId,recordid);
            }
        }else{
            swicthEditMode(grid,parentId,recordid);	
        }

    }
}
function swicthEditMode(grid,parentId,recordid){
	
    var edit_form = {
        "LEdestination":"list_editor_destination_edit", 
        "LEpurpose":"list_editor_purpose_edit",
        "User":"list_editor_user_edit"
    };
    var _update = function(){
        var error = false;
        form = $("form[name='"+parentId+"Editor']");

        $.each(form.find("input, select"),function(k,v){
            var inp = $(v);
            if(inp.attr("type") == "text"){
                if(!error){
                    error = eval_input(inp);
                }else{
                    eval_input(inp);
                }
            }else if(inp.attr("type") == undefined){
                if(inp.val()==""){
                    error=true;
                    inp.parent().next().find("div:first-child").addClass("inputbox_req");	
                }else{
                    inp.parent().next().find("div:first-child").removeClass("inputbox_req");	
                }
            }else{
									
                if(inp.attr("name") == "accesslevel"){
                    if(!error){
                        error = eval_DHXinput(inp,dhxComboBox.accesslevel.getSelectedValue());
                    }else{
                        eval_DHXinput(inp,dhxComboBox.accesslevel.getSelectedValue());
                    }
                }
									
            }
        });
							

        var passwordHasModified=false;
							
        if(parentId=="User"){
								
            $.each(form.find("input[type='password']"),function(k,v){
                var inp = $(v);
                if(inp.val() != ""){
                    passwordHasModified=true;	
                }
            });
								
								
            var pa = form.find("input[type='password']");
            var p1 =  $(pa[0]);
            var p2 =  $(pa[1]);
            var p3 =  $(pa[2]);
        }

        if(!error){
								
            if(parentId=="User"){
                if(passwordHasModified){
                    var uname = form.find("input#username_orig");
										
                    if(!isValidCurrentPassword(uname.val(),p1.val())){
                        $.jGrowl("Current password you typed doesn't match!");
                        showErrorHighlight(p1);
                        error=true;
                    }else if(p2.val() != p3.val()){
                        $.jGrowl("Passwords you typed doesn't match!");
                        showErrorHighlight(p2);
                        showErrorHighlight(p3);
                        error=true;
                    }
                }

            }
            if(!error){
                updateListEditor(parentId,$("form[name='"+parentId+"Editor']").serialize(),grid);
            }
        }else{
            $.jGrowl("Please specify the required field(s)!");	
        }
    };
				
    grid.setUserData("","editRecordID",recordid);

		
    $("#add_edit").load("forms/"+edit_form[parentId]+".php?id="+recordid,
        function(){
            $("form[name='"+parentId+"Editor']").bind("submit",function(){
                _update();
                return false;
            });
								
            inputs = document.forms[parentId + "Editor"].getElementsByTagName("input");
								
            for(i=0;i<inputs.length;i++){
                var inp = $(inputs[i]);
                if(inp.attr("type") == "text"){								
                    SetCaretAtEnd(inputs[i]);
                    break;
                }
            }
            if(parentId=="User"){
                dhxComboBox["accesslevel"] = dhtmlXComboFromSelect("accesslevel");
                $("#username").focus();
            }								
            $(".colorDropDown").msDropDown();

            $("#submit_editor_update").NXSbutton({
                "icon":"nxs-button-save",
                "text": "Update",
                "width" : 65,
                "height" : 29,
                "click":_update
            });
								
            $("#cancel_editor_update").NXSbutton({
                "icon":"nxs-button-cancel",
                "text": "Cancel",
                "width" : 65,
                "height" : 29,
                "click":function(){
                    grid.setUserData("","editRecordID","");
                    swicthFormMode("add",grid,parentId);
                }
            });
																	
        });
}
function LEeditForm(grid,parentId,recordid){
    swicthFormMode("edit",grid,parentId,recordid);
}

function ajaxCommonListEditor(url,q,callback){
	
    $.ajax({
        url : url,
        type: 'POST',
        data: q,
        cache: false,
        success: function(data) {
            var result =  jQuery.parseJSON( data );
            if(result.status == "failed"){
                alert(result.message);	
                if(result.callback == "highlightUsername"){
                    showErrorHighlight($("#username"));
                }
            }else{
                $.jGrowl(result.message);
                callback(result);
            }
				
        },
        statusCode: {
            404: function() {
                alert('error @ load data :: ajaxCommonListEditor');
            }
        }
    });
}