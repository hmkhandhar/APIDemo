jQuery(function ($) {
    "use strict";
   $.browserversion = function () {
        var appname = navigator.appName,
            useragent = navigator.userAgent,
            matchedVersion,
            matched  = useragent.match(/(opera|chrome|safari|firefox|msie)\/?\s*(\.?\d+(\.\d+)*)/i);
        if (matched  && (matchedVersion = useragent.match(/version\/([\.\d]+)/i)) !== null) {
            matched [2] = matchedVersion[1];
        }
        matched  = matched  ? [matched [1], matched [2]] : [appname, navigator.appVersion, "-?"];
        return matched [0];
    };
        var scrollbg = function () {
           if ($(".scrollbg").length > 0 ) {
                $(".scrollbg").each(function () {
                    var scroll_div = $(this),
                    scrollposition_current = $(scroll_div).css('backgroundPosition').split(" "),
                     scrollposition = 0,
                     background_image = $(this).css('background-image');
                    background_image = /^url\((['"]?)(.*)\1\)$/.exec(background_image);
                    background_image = background_image ? background_image[2] : "";
                    var image_new = new Image();
                    image_new.src = background_image;
                    var browser = $.browserversion().toLowerCase();
                    $(image_new).load(function () {
                        var maxwidth = image_new.width;
                        var maxheight = image_new.height;
                        var bsmove = function () {
                                if (scrollposition > maxwidth) {
                                    scrollposition = 0;
                                }
                                if (browser === 'netscape' || browser === 'msie') {
                                    $(scroll_div).css('background-position-x', (scrollposition--)+"px");
                                } else {
                                    $(scroll_div).css('background-position', (scrollposition--)+"px " + scrollposition_current[1]);
                                }
                            requestAnimationFrame(bsmove);
                        };
                        requestAnimationFrame(bsmove);
                    });
                });
            }
        };

            scrollbg();
    
});

