/*! Copyright (c) 2011 Aron G. Diploma
 *
 *	Tooltip
 */
(function($) {

	$.fn.NXStooltip = function(args) {

		var settings = $.extend({
			"text" : "",
			"live" : false,
			"target" : ""
		}, args);

		$._prepareTooltip();

		if(!settings.live) {

			return this.each(function() {

				var that = $(this);
				var text = (settings.text == "" ? that.attr('title') : settings.text);
				that.attr('title', "");

				that.hover(function() {
					$._showToolTip(this, text);
				}, function() {
					$('div.toolbar_tooltip').hide();
				});
				
				return that
			});
		} else {
			if(settings.target == "") {
				$.error("Element is required in NXStooltip if live is true.")
			} else {
				var that = $(this);
				
				that.on("mouseenter", settings.target, function() {
					if($.data(this,"text") == "" || $.data(this,"text") == undefined){
						var text = (settings.text == "" ? $(this).attr('title') : settings.text);
						$.data(this,"text",text);
					}
					$._showToolTip(this, $.data(this,"text"));
				}).on("mouseleave", settings.target, function() {
					$('div.toolbar_tooltip').hide();
				});
			}

		}
	}

	$._showToolTip = function(invoker, text) {
		var tooltip = $('div.toolbar_tooltip');
		tooltip.children('p').text(text);
		t = Math.floor(tooltip.outerWidth(true) / 2);
		b = Math.floor($(invoker).outerWidth(true) / 2);
		y = ($(invoker).offset().top - tooltip.height());
		x = ($(invoker).offset().left - (t - b));
		tooltip.css({
			'top' : (y-1) + 'px',
			'left' : x + 'px',
			'display' : 'block'
		});
		tooltip.find('div.tooltip_bottom_arrow').attr("style", "");
	}

	$._prepareTooltip = function() {
		if($("div.toolbar_tooltip").size() == 0) {
			$('body').prepend('<div class="toolbar_tooltip"><p>&nbsp;</p><div class="tooltip_bottom_arrow"></div></div>');
		}
	}

	$.NXStooltip_1 = function(invoker, text, setting) {
		var tooltip_setting = {};

		$.prepareTooltip();
		invoke = $(invoker);
		tooltip = $('div.toolbar_tooltip');

		/** tooltip default settings **/
		tooltip_setting.top = Math.floor(tooltip.outerWidth(true) / 2);
		tooltip_setting.bottom = Math.floor(invoke.outerWidth(true) / 2);
		tooltip_setting.y = (invoke.offset().top - tooltip.height());
		tooltip_setting.x = (invoke.offset().left - (tooltip_setting.top - tooltip_setting.bottom));

		/** overriding default settings **/
		if(setting != undefined) {
			for(index in setting) {
				tooltip_setting[index] = setting[index];
			}
		}

		tooltip.children('p').text((text == "" ? invoke.attr('title') : text));
		t = tooltip_setting.top;
		b = tooltip_setting.bottom;
		y = tooltip_setting.y;
		x = tooltip_setting.x;
		tooltip.css({
			'top' : y + 'px',
			'left' : x + 'px',
			'display' : 'block'
		});

		return tooltip;
	};
})(jQuery);
