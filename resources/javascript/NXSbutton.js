/*! Copyright (c) 2012 Aron G. Diploma
 *
 *	Vertical Tab
 * 
 *	NEXUS
 */
 

(function( $ ){
	 
    $.fn.NXSbutton = function(opts) {  
	
        var settings = $.extend( {	
            "width" : "auto",
            "height" : "auto",
            "icon" : "none",
            "click" : "",
            "text" : "",
            "click":"",
            "size": "",
            "tooltip": false,
            "clickParam":"",
            "style": "NXS"
										
        }, opts);
				
        return this.each(function() {
            
            
            if(settings.width != "auto"){
                $(this).width(settings.width);	
            }
					
            if(settings.height != "auto"){
                $(this).height(settings.height);	
            }

            if(typeof settings.click === 'function'){
                $(this).children("a").click(function(){
                    if(settings.clickParam !=""){
                        settings.click(settings.clickParam);
                    }else{
                        settings.click($(this));	
                    }
                    return false;
                });
            }
					
            if(settings.text == ""){
                $(this).children("a").html(".").css({
                    "text-indent":"-1000px"
                });
            }else{
                $(this).children("a").html(settings.text);
            }
					
					
            if(settings.tooltip == true){
                $(this).children("a").NXStooltip();	
            }
					
            $(this).addClass(settings.style + "button").children("a").addClass(settings.size).mouseenter(function(){
                $(this).parent().addClass("hover");
            }).mouseout(function(){
                $(this).parent().removeClass("hover");
            }).attr("href","#").addClass(settings.icon);

					
        });
				
    }
	
})( jQuery );