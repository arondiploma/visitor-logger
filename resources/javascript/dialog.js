/*! Copyright (c) 2012 Aron G. Diploma
 *
 *	Dialog
 * 
 */
 /*
 var dialog = $.NXSdialog({
							message	:	Nexus.config.client.del,
							icon	:	"icon-question",
							title	:	"Confirmation",
							buttons	:	{
											Delete	: function(){
																	dialog.disableAllButtons();
																	$(this).button( "option" , "label" ,"Deleting...");
																	Nexus.config.client.instance.del("client", $.data(row,"id"));
																},
											Cancel	: function(){
																	dialog.close();					
																}
										}					
						});
 */
 (function( $ ){

	var OkOnly = {	Ok	: function(invoker,dialog){
									dialog.close();	
							}
					};

	$.NXSdialog = function(opts) {  
		
		var settings = $.extend( {	"modal"	: false,
									"buttons"	: {},
									"icon"	: "",
									"title"	: "",
									"message"	: "",
									"height"	: 140,
									"width"	: 390,
									"url"		: "",
									"content" : "",
									"loading" : true,
									"loadCallback": null,
									"onClose": function(){}
								}, opts);
							
		var dialog = {	
						dialog:null,
						shadow:null,
						window:null,
						wrapper:null,
						close : function(){
								this.destroy();
						},
						disableAllButtons: function(){
								this._disableAllButtons();
							},
						create	: function(q,b){
							
								that = this;
											
								that.overlay = $("<div></div>").addClass("ui-widget-overlay");
								that.shadow = $("<div></div>").addClass("ui-widget-shadow ui-corner-all dialog_shadow");
								that.win = $("<div></div>").addClass("ui-widget ui-widget-content ui-corner-all dialog_window");
								
								that.wrapper =  $("<div></div>").addClass("ui-dialog-content ui-widget-content window_wrapper");
								that.wrapper.html("<p><div class='hbar_loader'></div></p>");
								
								that.shadow.css("height",37).css("width",102);
								that.win.css("height",35).css("width",100);
								
								that.center();
								that.win.append(that.wrapper);
								
								$("body").append(that.overlay).append(that.shadow).append(that.win);
								
								$(window).resize(function() {
									that.center();
								});	
								
								if(settings.loading){
									setTimeout(function(){ that.render() },500);
								}else{
								 	that.render();
								}
								
								$(window).on("keyup",function(e){
										if(e.keyCode == 27 || e.which == 27){
											dialog.destroy();
										}
									});
									
							},
						rebuild:function(opts){
								settings = $.extend( settings, opts);
								this.render();
							},
						render:function(){
								var hasFocus = false

								that.win.css("height",settings.height).css("width",settings.width);
								that.shadow.css("height",settings.height + 2).css("width",settings.width + 2);

								that.center();

								that.title = $("<div class='dialog_window_title'>" + settings.title + "</div>");
								that.wrapper.html(that.title);
								
								that.botton_cont = $("<div class='botton_cont'> </div>");
		
								if($.getLength(settings.buttons) == 0 || $.getLength(settings.buttons) == undefined){
									settings.buttons = OkOnly;
								}
								
								$.each(settings.buttons, function(key,func){
											btn = $("<a href='#' class='"+key+"'>"+key+"</a>").button();
											if(!hasFocus){
												btn.focus();
												hasFocus = true;	
											}
											if(typeof func == "function"){
												btn.bind("click",function(){
															func(this,dialog);
															return false;
													});
											}
											that.botton_cont.append(btn);
									});
								that.footer = $("<div class='dialog_window_footer'> </div>").append(that.botton_cont);
								that.wrapper.append(that.footer);
								
								if(settings.url != ""){
                                    $.ajax({
                                    url: settings.url,
                                    cache: false,
                                    success: function(data) {
                                            that.content = $("<p>"+data+"</p>");
                                            that.wrapper.append(that.content);
											if(typeof settings.loadCallback == "function"){
												settings.loadCallback();
											}
                                        }
                                    });
                                }else if(settings.content != ""){
                                    that.content = $("<div class='dialog_window_content'></div>").html(settings.content);  
                                    that.wrapper.append(that.content);
								}else{
                                    that.content = $("<div class='dialog_window_content'></div>").html("<div style='float:left; padding-right:10px;'><div class='"+settings.icon+"'></div> </div> <div style='float:left; padding-top:5px; width:320px;'>"+settings.message+"</div>");  
                                    that.wrapper.append(that.content);
								}
								
								if(settings.url == ""){
									if(typeof settings.loadCallback == "function"){
										settings.loadCallback();
									}
								}

							},
						center:function(){
								var new_win_width = Math.ceil($(window).width()/2);
								var shadow_width =  Math.ceil(that.shadow.width() / 2);
								
								this.win.css("left",(new_win_width - shadow_width)-1);
								this.shadow.css("left",(new_win_width - shadow_width));
								
								var new_win_height = Math.ceil($(window).height()/2);
								var shadow_height =  Math.ceil(that.shadow.height() / 2);
								
								this.win.css("top",(new_win_height - shadow_height)-1);
								this.shadow.css("top",(new_win_height - shadow_height));
							},
						_disableAllButtons:function(){	
								this.botton_cont.find("a").button( "disable" );		
							},
						
						destroy:function(){
								
								this.shadow.fadeOut(500,function(){$(this).remove()});
								this.win.fadeOut(500,function(){$(this).remove()});
								this.overlay.fadeOut(500,
										function(){$(this).remove()
										settings.onClose();
								});
								$(window).unbind("keyup");
							},
					

			};
		
		dialog.create();
		
		return dialog;
	};
  
})( jQuery );