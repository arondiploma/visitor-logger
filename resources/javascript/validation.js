function eval_input(input){
	if(input.val() == ""){
		input.addClass("inputbox_req");	
		input.attr("title",error_msg[input.attr("name")]);
		return true;
	}else{
		
		input.removeClass("inputbox_req");	
		input.attr("title","");
		return false;
	}
}

function showErrorHighlight(input){
		input.addClass("inputbox_req");	
		input.attr("title",error_msg[input.attr("name")]);
}
function hideErrorHighlight(input){
		$(input).removeClass("inputbox_req");	
		$(input).attr("title","");
}
function eval_DHXinput(input,val){

	if($(input).val() == "" || val == null){
		$(input).parent().addClass("inputbox_req");
		return true;
	}else{
		$(input).parent().removeClass("inputbox_req");
		return false;
	}
}

function eval_DHXinput2(input,val){
	if( $("input[id='purposeoption']").attr("checked") == "checked"){
		if($(input).val() == "" || val == null){
			$(input).parent().addClass("inputbox_req");
			return true;
		}else{
			$(input).parent().removeClass("inputbox_req");
			return false;
		}
	}else{
		$(input).parent().removeClass("inputbox_req");
		return false;
	}
}

function eval_input_wOption(input){
	
	if( $("input[id='otheroptions']").attr("checked") == "checked"){
		if(input.val() == ""){
			input.addClass("inputbox_req");	
			input.attr("title",error_msg[input.attr("name")]);
			return true;
		}else{
			
			input.removeClass("inputbox_req");	
			input.attr("title","");
			return false;
		}
	}else{
			input.removeClass("inputbox_req");	
			input.attr("title","");
			return false;
	}
	

}

function isValidCurrentPassword(un,pa)
{
		var returnVal = false;
		
		$.ajax({
			url : "action/validate_userpassword.php",
			type: 'POST',
  			data: "username="+un+"&password="+pa,
			cache: false,
			async: false,
			success: function(data) {
				if(data == "1" || data == 1){
					returnVal = true;	
				}
			},
			statusCode: {
				404: function() {
				  alert('error @ load data :: isValidCurrentPassword');
				}
			  }
		});
		
		return returnVal;
}
