// JavaScript Document

(function($) {

	$.getLength = function(array) {
		var count = 0;
		$.each(array, function(key, val) {
			count++;
		});
		return count;
	};

	$.getServerDateTime = function(){
		var returnValue;

		$.ajax({
			url : Nexus.url + "/settings/datetime",
			cache : false,
			async : false,
			dataType:'json',
			success : function(result) {
				returnValue = result;
			}
		});

		return returnValue;
	};
	
	$.dateDiff = function(date1, date2){
		return (date1.getTime() - date2.getTime()) / (1000*60*60*24);
	};
	
	$.ping = function() {
		var returnValue;

		$.ajax({
			url : Nexus.url + "ping.php",
			cache : false,
			async : false,
			complete : function(jqXHR, textStatus) {
				if(textStatus == "success") {
					returnValue = true;
				} else {
					returnValue = false;
				}
			},statusCode: {
				404: function() {
				  	returnValue = false;
				}
			  }
		});

		return returnValue;
	};

	$.fn.NXSserialize = function() {

		var form = $("<form> </form>");
		form.html($(this).clone());
		return form.serialize();
	};
})(jQuery);
