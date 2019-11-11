/*! Copyright (c) 2012 Aron G. Diploma
 *
 *	Data repeater
 * 
 *	NEXUS
 */

(function($) {

    $._createRowToolbar = function(el, opts) {

        var that = $(el);
        var rowtoolbar = {};
        var settings = $.extend({
            hideable: true,
            buttons: {}
        }, opts);

        rowtoolbar.strip = $("<ul></ul>").addClass("row_toolbar").addClass((settings.hideable ? "hideable" : "unhideable")).attr("id", "row_toolbar");

        var counter = 1;
        var buttonsLength = $.getLength(settings.buttons);

        $.each(settings.buttons, function(key, val) {

            a = $("<a href='#'></a>").attr("title", key).html(key);
            li = $("<li></li>");
            li.append(a);

            if ($.getLength(settings.buttons) > 1) {
                if (counter == 1) {
                    li.addClass("left");
                    a.addClass("left");
                } else if (counter == buttonsLength) {
                    li.addClass("right");
                    a.addClass("right");
                }
            } else {
                li.addClass("all");
                a.addClass("all");
            }

            $.each(val, function(k, v) {

                if (k == "icon") {
                    a.addClass(v);
                } else if (k == "face") {
                    li.addClass(v);
                } else if (k == "display") {
                    if (v == "icon") {
                        a.addClass("iconOnly");
                        a.NXStooltip();
                    }
                } else if (k == "click") {
                    a.bind("click", function() {
                        var _click = v;
                        _click(settings.repeater, settings.row);
                        settings.repeater.setSelectedRow(settings.row);
                        $(this).blur();
                        return false;
                    });
                }
            });
            rowtoolbar.strip.append(li);
            counter++;
        });


        if (settings.hideable == true) {
            rowtoolbar.strip.hide();
        }



        that.mouseenter(function() {
            if (settings.hideable == true && !that.hasClass("data_repeater_row_selected")) {
                rowtoolbar.strip.show();
            }
        }).mouseleave(function() {
            if (settings.hideable == true && !that.hasClass("data_repeater_row_selected")) {
                rowtoolbar.strip.hide();
            }
        });

        that.append(rowtoolbar.strip);

        return that;

    }


    $.fn.rowtoolbar = function(opts) {

        return this.each(function() {

            $._createRowToolbar(this, opts);

        });

    };

})(jQuery);