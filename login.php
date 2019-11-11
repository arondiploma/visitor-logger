<?php 

	$imageURL = array("body" => "resources/images/login/bg.png",
						"wrapper" => "resources/images/login/wrapper_bg.png",
						"banner" => "resources/images/login/banner.png",
						"input_wrapper" => "resources/images/login/input_wrapper.png"
					);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
    	<title>CHMSC Visitor Logger Login</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href='resources/stylesheet/login.css' rel='stylesheet'/>
        <link href='resources/stylesheet/loginbutton.css' rel='stylesheet'/>
        <link href='resources/stylesheet/tooltip.css' rel='stylesheet'/>
        <link href="favicon.ico" rel="icon" type="image/x-icon" />
        <script src='resources/javascript/2.jquery.js'></script>
		<script src='resources/javascript/3.jquery-ui.js'></script>
		<script src='resources/javascript/NXSbutton.js'></script>
		<script src='resources/javascript/util.js'></script>
		<script src='resources/javascript/tooltip.js'></script>
        <script src='resources/javascript/noselect.js'></script>
		
        <script>
		
				Nexus = {"url":"http://<?php echo $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']; ?>",state:0}
			
				function showError(msg){
					$(".error").html("<strong>Login failed:</strong> " + msg + " [ <a href='' class='error_clear'>Clear</a> ]");
					$('.input_wrapper').animate({ height: 140 }, 500, function(){
																			$(".error").fadeIn(500);
																			Nexus.state = 0;
																	});	
					$(".error_clear").bind("click",function(){
							hideError()
							return false;
					});
				}

				function hideError(){
						$(".error").fadeOut(200,function(){
								$('.input_wrapper').animate({ height: 92 }, 200);
						});
				}
				
				function auth(f){
					
					Nexus.state = 1;
					var frm = $(f);
					
					if($("#username").val() == "" || $("#password").val() == ""){
						 $(".input_wrapper").effect("shake",{distance:10,times:2},70,function(){
							 		Nexus.state = 0;
							 });
					}else{
	
						$("#submit").children("a").addClass("preloading").html("Logging In");
						
						setTimeout(function(){
								$("#submit").children("a").html("Checking connectivity");
								setTimeout(function(){
										if($.ping()){
											
											$("#submit").children("a").html("Authenticating user");
											setTimeout(function(){
											
												$.ajax({"url": frm.attr("action") + "auth.php",
														"type":"POST",
														"cache": false,
														"data": $("#login-form").serialize(),
														"success":function(r){
																if(r === 0 || r === "0" || r == "0"){
																	$("#submit").children("a").removeClass("preloading").html("Log In");
																	showError("Invalid username or password!");
																	$("#username").focus();
																}else{
																	$("#submit").children("a").html("Loading main interface");
																	setTimeout(function(){
																		window.location = Nexus.url;
																	},300);
																	Nexus.state = 0;
																}
														},
														"statusCode": {
																404: function() {
																	$("#submit").children("a").removeClass("preloading").html("Log In");
																	showError("Page not found!");
																	$("#username").focus();
																}
															  }		
														});

											},500);

										}else{
											showError("Server not found!");
											$("#submit").children("a").addClass("preloading").html("Logging In");
										}
								},500);
						},500);
						
					}
					
				}
				
				$(document).ready(function(){
							
							$("#submit").NXSbutton({ "width": 402, "icon": "", "text": "Log In",
																		 "click":function(){
																						$("#login-form").submit();							
																				}
																});
							$("#username, #password").bind("keyup",function(event){
										if($(this).val() != ""){
											$(this).next().fadeIn(200);	
										}else{
											$(this).next().fadeOut(200);
										}
										if(event.keyCode == 13 || event.which == 13){
											$("#login-form").submit();	
										}
								}).val("").next().bind("click",function(){
										$(this).prev().val("");	
										$(this).fadeOut(200);	
										return false;
								}).noSelect().NXStooltip();
							
							$(".item>div").noSelect();
							
							$("#login-form").bind("submit",function(){
															if(Nexus.state == 0){
																hideError();
																auth(this);
															}
															return false;
														});

							$(".banner").fadeIn(500,function(){
												setTimeout(function(){
															$('.banner').animate({ top: 80, }, 
																				700, 
																				function(){
																						$(".content_wrapper").fadeIn(500,function(){
																								$("#username").focus();
																							});
																						}
																		);
												},500);
							});						
				});
		
		</script>
    </head>
    <body style="background-image:url(data:image/png;base64,<?php echo  base64_encode(file_get_contents($imageURL["body"])); ?>);">     
    
       <div class="wrapper" style="background-image:url(data:image/png;base64,<?php echo  base64_encode(file_get_contents($imageURL["wrapper"])); ?>);">
       
       		<div class="inner_wrapper">
            
                <div class="banner" style="background-image:url(data:image/png;base64,<?php echo  base64_encode(file_get_contents($imageURL["banner"])); ?>);"></div>
                
                <div class="content_wrapper">
                
                    <div class="input_wrapper" style="background-image:url(data:image/png;base64,<?php echo  base64_encode(file_get_contents($imageURL["input_wrapper"])); ?>);">
                        <form action="http://<?php echo $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']; ?>" method="post" name="login-form" id="login-form">
                            <div class="item">
                                <div>Username</div><input type="text" name="un" id="username" autocomplete="off" maxlength="45"  tabindex="1" /><a tabindex="4" href="" title="Clear" class="clear">X</a>
                            </div>
                            <div class="item">
                                <div>Password</div><input type="password" name="pa" id="password" autocomplete="off" maxlength="45" tabindex="2"/><a tabindex="5" href="" title="Clear"  class="clear">X</a>
                            </div>
                        </form>
                        <div class="error" style="">Login failed: Invalid username or password! [ <a tabindex="6"  href="" class="error_clear">Clear</a> ]</div>
                    </div>
                    
                   <span id="submit"><a tabindex="3"></a></span>
 
               </div>
               
           </div>
           
       </div>
    </body>
</html>