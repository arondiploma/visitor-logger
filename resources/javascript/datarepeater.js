/*! Copyright (c) 2012 Aron G. Diploma
 *
 *	Data repeater
 * 
 *	NEXUS
 */
 
(function( $ ){
	 

								
    $._createRepeater = function(elem,opts){
		
        var that = $(elem);
		
        if(that.call){
            return false
        }
		
        var repeater = {
            "id"			:	0,
            "timer"			:	null,
            "xhr"	 		:	null,
            "selected_row"		:	"",
            "criteria"	   		: 	"",
            "scroll_range"		: 	{},
            "per_load"			:	40,
            "recordscount"		:	0,
            "rowscount"			:	0
        };
											
        var settings = $.extend( {
            "row_prefix" 		: 	"dr_row_",
            "row_height"		:	100,
            "data_url"			:	"",
            "items"			:	{},
            "toolbarItems"		: 	{},
            "rowtoolbarbuttons"         :	{},
            "row_template"		: "<table width='100%' border='0' height='80'><tr><td width='130' rowspan='5' align='center'>{Image}</td><td height='19'>Name:{Name}</td></tr><tr><td height='19'>Address:{Address}</td></tr><tr><td height='19'>Birth Date:{BirthDate}</td></tr><tr><td height='19'>Birth Place:{BirthPlace}</td></tr></table>",
            "defaultToolbarSeachOptions" : [],
            "toolbar"			:	true,
            "autoload"			:	true,
            "recodsindicator"           :	""
        }, opts);	

        var scrollable, viewPort, loader,searchloader;
        var next_pick = 0;
        var loading = false;
        

        
        var html = ""; //DEBUG PURPOSE ONLY
		
        var methods = {
					
            _USERDATA_: {},
            _ROWS_:new Array(),
					
            init_repeater_view	: function(range){

                //wrapper of repeater row
                //the main wrapper
                repeater.wrapper = $("<div></div>");
                repeater.wrapper.addClass("data_repeater_content_wrapper");
            
                //repeater view 
                repeater.view = $("<div style='overflow:auto;' class='data_repeater_view'></div>");
                this._resize();
                repeater.view.width(that.width());
                this.layout_preloader_on();
                
                that.height(that.parent().height()-5);
                repeater.view.height(that.height());
                
                repeater.view.append(repeater.wrapper);
                
                that.bind('_resize', function(){
                    methods._resize();
                    repeater.view.width($(this).width());	
                });
									
                if($("#datarepeatercss").size() == 0){
                    $("<style type='text/css' id='datarepeatercss'> .data_repeater_content{ height:"+(settings.row_height-1)+"px; } </style>").appendTo("head");
                }

            },
            
            _resize:function(){

                that.height(that.parent().height()-5);
                repeater.view.height(that.height());    
            },
            prepare_view: function(){
						
                //TOP DIV of all repeater row
                repeater.view.scrollTop(0);
                repeater.selected_row = "";							
                repeater.wrapper.html("");
            },

            setURL:function(u){
                settings.data_url = u;	
            },
            getURL:function(u){
                return settings.data_url;
            },
            getFullURL:function(u){
                _url = settings.data_url + getUrlSymbol(settings.data_url) + repeater.criteria;
                return _url;
            },
            create_repeater_view: function(){
						
                var loader=null;
 						
                repeater.view.bind('scroll',function(e){
																					
                    //repeater.xhr.abort(); //abort current ajax loading

                   scrollValue = repeater.view.scrollTop() + repeater.view.height();
                   //dhxLayout.cells("d").attachHTMLString(repeater.wrapper.height() + "-"+ scrollValue);
                   if(scrollValue >= repeater.wrapper.height() && !loading && next_pick < repeater.rowscount ){
                        loading = true;
                        methods.preloader_on();
                        try{
                            setTimeout(function() {
                                methods._load(false, settings.data_url, next_pick);
                            }, 300); 
                        }catch(e){
                            methods._load(false, settings.data_url, next_pick);
                        }
                   }


                });
											
                that.append(repeater.view);
                
                if(settings.autoload){
                    this._load(true, settings.data_url, 0);
                }
			
            },
            layout_preloader_on:function(){
                repeater.wrapper.html("<table width='100%' height='" + (that.height()-100) + "' border='0'><tr><td valign='middle' align='center'><div class='data_repeater_view_preloader'>LOADING</div></td></tr></table>");	
            },
            preloader_on: function(){
                repeater.wrapper.append("<div id='kkkkk' style='padding:10px; text-align:center;'><div class='data_repeater_view_preloader'>LOADING</div></div>")
            },
            preloader_off:function(){
                 $('#kkkkk').remove();
            },
            updateRecordIndicator:function(){
                if(repeater.rowscount == 0){
                    $("#"+settings.recodsindicator).html("No records found!");
                }else{
                    $("#"+settings.recodsindicator).html(repeater.rowscount + " of " + repeater.recordscount);	
                }	
            },
					
            updateRepeaterView:function(_id_){
                repeater.selected_row = "";
                this.delete_row(_id_);
                repeater.recordscount = repeater.recordscount-1;
                repeater.rowscount =repeater.rowscount-1;
                //this.init_scroll_range();
                this.updateRecordIndicator();
            },
					
            reload:function(){
                this.load();
            },
					
            load:function(){
                this._load(true, settings.data_url, 0, repeater.per_load);
            },
            getUrlSymbol: function(str){
                if (str.indexOf("?")!= -1)
                    return "&"
                else
                    return "?"
            },
            _load: function(init, data_url, posStart){
									
                _url = settings.data_url + getUrlSymbol(settings.data_url) + repeater.criteria + "&posStart=" + posStart + "&count=" + repeater.per_load;
                load_error = false;
                
                $.ajax({
                    url:_url, //((posEnd+1) - posStart),
                    dataType: 'json',
                    cache: false,
                    success: function(data) {
												
                        var rows;
                        $.each(data, function(key, val){
                            try{
                                if(key == "db_error_message" && val != ""){
                                    load_error = true;
                                    alert("DB Error : " + val);
                                }else if(key == "recordscount" && init == true){
                                    repeater.recordscount = parseInt(val);
                                }else if(key == "rowscount" && init == true){
                                    repeater.rowscount = parseInt(val);	
                                }else if(key == "userdata"){
                                    $.each(val,function(name,value){
                                        methods.setUserData("",name,value);
                                    });
                                }else if(key == "rows"){
                                    rows = val;
                                }
                            }catch(e){
                                load_error = true;
                                alert("ERROR @ " + _url);
															
                            }
                        });//$.each(val, function(key, val){
												
                        if(!load_error){
													
                            //count = repeater.rowscount > count ? count : repeater.rowscount-1  ;
											
                            if(repeater.recordscount > 0 && repeater.rowscount >0 && init == true){
                                
                                methods.prepare_view();
                                methods._ROWS_= new Array();
                                methods._USERDATA_["global"] = {};
                                methods._USERDATA_["row"] = {};
                                next_pick = 0;
                            }
                            
                            methods.updateRecordIndicator();
                            methods.finalize_rows(rows,posStart);

                        }
	

                    },
                    statusCode: {
                        404: function() {
                            alert('error @ _load data :: '+data_url);
                        }
                    }
                });//$xhr=$.ajax({

            },
           
            delete_row:function(_id_){
						
                $("#" + settings.row_prefix + repeater.id + "_" + this.getRowUnique(_id_)).fadeOut( 500 ,function(){
                    $(this).remove();
                    methods.pullrow(_id_);
                });

            },
            pullrow:function(_id_){
                for(i=0;i<methods._ROWS_.length;i++){
                    if(	methods._ROWS_[i].getId() == _id_){
                        methods._ROWS_.splice(i,1);
                    }
                }
            },
					
            getRowIndex: function(_id_){
                for(i=0;i<methods._ROWS_.length;i++){
                    if(	methods._ROWS_[i].getId() == _id_){
                        return i;
                    }
                }
            },
            getRowUnique: function(_id_){
						
                for(i=0;i<methods._ROWS_.length;i++){
                    if(	methods._ROWS_[i].getId() == _id_){
                        return methods._ROWS_[i].property.unique;
                    }
                }
            },
            finalize_rows : function(rows,posStart){
						
                var row;
                err = false;
                unique = posStart;
         
                methods.preloader_off();
              
                if(rows.length>0){
													
                    $.each(rows, function(key, val){
												
                        row = $("<div></div>");
                        row.addClass("data_repeater_content");
                        row.attr("id", settings.row_prefix + repeater.id + "_" + unique);
	            
                        if(val["id"]=="" && err == false){
                            alert("ERROR: Undefined datarepeater ID!");
                            err = true;
                        }
													
                        if(err == false){

                            row.property = {
                                "id" : val["id"], 
                                "unique" : unique
                            };
														
                            methods._ROWS_.push(row);
														
                            row.getId = function(){
                                return this.property.id;
                            }
											
                            row.getIndex = function(){
                                return methods.getRowIndex(this.property.id);
                            }
                            row.setId = function(val){
                                return this.property.id = val;
                            }
															
                            row.bind("click",function(){
                                methods.setSelectedRow(this);
                            });

                           repeater.wrapper.append(row);

                            //row.empty().append("#" + settings.row_prefix + repeater.id + "_" + unique);
                            var data = val["row"];
                            methods.create_row_content(row,data);
                           
                            unique++;	
                        }								
                    });//$.each(val, function(key, val){
                }else{
                    this.prepare_view();
                }
                
                next_pick = next_pick + repeater.per_load;
                loading =false;
                
            },
            
            setUserData: function(ownerId, i, v){
						
                if(ownerId==""){
                    this._USERDATA_["global"][i] = v;
                }else{
                    if(this._USERDATA_["row"][ownerId] == undefined){
                        this._USERDATA_["row"][ownerId] = {};
                    }
                    this._USERDATA_["row"][ownerId][i] = v;
                }
						
            },
            getUserData: function(ownerId, i){
					
                if(ownerId==""){
                    return this._USERDATA_["global"][i];
                }else{
                    return this._USERDATA_["row"][ownerId][i];
                }

            },
            create_row_content: function(row,data){
								
                var content = settings.row_template;
                var status;
									
                $.each(data, function(key, val){
										
                    switch(key){
                        case "image":
                            value = "<img src='" + val + "'>";
                            break;
                        case "userdata":
                            $.each(val,function(name,value){
                                methods.setUserData(row.getId(),name,value);
                            });
                            break;
                        case "status":
                            status = val;
                            break;
                        default:
                            value = val;
                            break;
                    }

                    try{
                        var re = new RegExp("{"+key+"}","g"); 
                        content = content.replace( re, value );
                    }catch(e){}

                });	

                row.append($(content).noSelect());
								
                var stat = 	{
                    Status	:{
                        icon  	:(status == "on" ? "status_active_btn" : "status_inactive_btn"),
                        face	:(status == "on" ? "status_active" : "")
                    }
                };

                row.rowtoolbar({	
                    hideable : (status == "on" ? false : true),
                    buttons : settings.rowtoolbarbuttons,//,=$.extend( settings.rowtoolbarbuttons , stat),
                    repeater: that.call,
                    row: row
                });
                
			
                 
                if(methods.isSelectedRow(row)){
                    row.addClass("data_repeater_row_selected");
                    row.find("#row_toolbar").show();
                }
                
              
            },
					
            isSelectedRow: function(row){
									
                if(repeater.selected_row){
                    if( repeater.selected_row.attr("id") == row.attr("id") ){
                        return true;
                    }
                }else{
                    return false;
                }
            },
					
            setSelectedRow: function(row){
                if(repeater.selected_row){
                    if($(row).attr("id") != repeater.selected_row.attr("id")){
                        $("#" + repeater.selected_row.attr("id")).removeClass("data_repeater_row_selected");
                        if(repeater.selected_row.find("#row_toolbar").hasClass("hideable")){
                            repeater.selected_row.find("#row_toolbar").hide();
                        }
                    }
                }
                repeater.selected_row = $(row).addClass("data_repeater_row_selected");
                repeater.selected_row.find("#row_toolbar").show();
            },
            _doSearch:function(){
                clearTimeout(searchloader);
                searchloader = setTimeout(function(){
                    methods.reload()
                },400);
            },
            _generateID:function(l){
                var key = "";
                var z = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                for (var q=0;q<l;q++){
                    key += z.charAt(Math.round(Math.random() * (z.length-1)));
                }
                return key;
            },
            filter:function(q){
                repeater.criteria = q;
                this._doSearch();
            }
           
		
			
        };
		
        toolbar.id = methods._generateID(5) ; //temporary?	
        repeater.id = methods._generateID(5);//temporary?
        if(settings.recodsindicator ==""){
            settings.recodsindicator = repeater.id + "_tb_recordsnum";
        }
		
        that.call = methods;
        if(settings.toolbar){
            that.call.create_toolbar(settings.toolbarItems);	
        }
		
        that.call.init_repeater_view();
        that.call.create_repeater_view();
		
        return that;
    }
	
    $.fn.dataRepeater = function( opts ) {  
        return $._createRepeater(this, opts);
    };
  
})( jQuery );