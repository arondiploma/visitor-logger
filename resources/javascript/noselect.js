

(function( $ ){
	
		jQuery.fn.noSelect = function() {
		
			// Since the string 'none' is used three times, storing it in a variable gives better results after minification
			var none = 'none';
			
			// onselectstart and ondragstart for WebKit & IE
			// onmousedown for WebKit & Opera
			return this.each(function() {
				
							$(this).bind('selectstart dragstart mousedown', function() {
																				return false;
														}).css({
															'MozUserSelect': none,
															'msUserSelect': none,
															'webkitUserSelect': none,
															'userSelect': none
														});
				}); 
							
		};

})( jQuery );