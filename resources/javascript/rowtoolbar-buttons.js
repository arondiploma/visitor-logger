/*! Copyright (c) 2012 Aron G. Diploma
 *
 *	row toolbar buttons configuration file
 * 
 *	NEXUS
 */		
var rowtoolbarbuttons = {
    "visitor":{
        "Log out":{
            "icon"  	: "clock",
            "face"		: "",
            "display" 	: "textIcon",  //for now icon or textIcon only
            "click" 	: function(repeater,row){
                var id = row.getId();
												
                $("#captureWebcam").hide(); //HIDE CAM FIRST
							
                $.NXSdialog({
                    "url"		:	Nexus.url + "forms/logout.php?id=" + row.getId(),
                    "icon"		:	"icon-question",
                    "title"		:	"Log-out confirmation",
                    "height"	: 	350,
                    "width"		: 	700,
                    "onClose" 	:function(){
                        $("#captureWebcam").show();	// THEN SHOW CAM
                    },
                    "loadCallback":function(){
                        SetCaretAtEnd(document.getElementById("logout_remarks"));
                        $("a#remarks_edit").click(function(){
                            $("span#remark_detail").hide();
                            $("#logout_remarks").show().focus();
                        });
                    },
                    "buttons"	:	{
                        "Log-out": function(invoker,dialog){
                            $.ajax({
                                url : "action/logout_visitor.php?id=" + id + "&remark=" + $("#logout_remarks").val(),
                                cache: false,
                                success: function(data) {
                                    var result =  jQuery.parseJSON( data );
																											
                                    if(result.status == "failed"){
                                        alert(result.message);	
                                    }else{
                                        $.jGrowl(result.message);
                                    }
																											
                                    var opt=new Array();
                                    opt[0]=new Array();
                                    opt[0][0]=result.gatepassnum;
                                    opt[0][1]=result.gatepassnum;
																											
                                    removeFromList(result.gatepassnum);
                                    refreshGatePassNumCBO();
																											
                                    dataRepeater.call.delete_row(id);
                                    dataRepeater.call.updateRepeaterView(id);
                                    dataRepeaterAllVisitors.call.reload();
                                },
                                statusCode: {
                                    404: function() {
                                        alert('error @ load data :: _logout');
                                    }
                                }
                            });
                            dialog.close();
                        },
                        "Cancel": function(invoker,dialog){
                            dialog.close();				
                        }
                    }						
                });//$.NXdialog	
							
												
            }
        },
        "Details":{
            "icon"  	: "view",
            "face"		: "",
            "display"	: "textIcon",  //for now icon or textIcon only
            "click" 	: function(repeater,row){
                $("#captureWebcam").hide(); //HIDE CAM FIRST
                $.NXSdialog({
                    "url"		:	Nexus.url + "forms/info.php?id=" + row.getId(),
                    "icon"		:	"icon-question",
                    "title"		:	"Visitor's information",
                    "height"	: 	350,
                    "width"		: 	700,
                    "onClose" 	:function(){
                        $("#captureWebcam").show();	// THEN SHOW CAM
                    },
                    "buttons"	:	{
                        "Close"	: function(invoker,dialog){
                            dialog.close();	
                            $("#captureWebcam").show();	// THEN SHOW CAM		
                        }
                    }					
                });//$.NXdialog	
            }
        }
    },
		
    "allvisitor":{
			
        "Details":{
            "icon"  	: "view",
            "face"		: "",
            "display" : "textIcon",  //for now icon or textIcon only
            "click" 	: function(repeater,row){
                $("#captureWebcam").hide(); //HIDE CAM FIRST
                $.NXSdialog({
                    "url"		:	Nexus.url + "forms/info.php?id=" + row.getId(),
                    "icon"	:	"icon-question",
                    "title"	:	"View visitor information",
                    "height"	: 	350,
                    "width"	: 	700,
                    "onClose" :function(){
                        $("#captureWebcam").show();	// THEN SHOW CAM
                    },
                    "buttons"	:	{
                        "Close"	: function(invoker,dialog){
                            dialog.close();	
                            $("#captureWebcam").show();	// THEN SHOW CAM		
                        }
                    }					
                });//$.NXdialog	
            }
        }
			
    }		
		
};
								
	