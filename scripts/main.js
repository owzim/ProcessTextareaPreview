(function($, window, undefined) { // safe closure

    console = typeof(console) !== "undefined" ? console : { log: function() {} };


    var HAS_CHANGED_TRUE = "true";
    var HAS_CHANGED_FALSE = "false";

    var DEFAULT_AJAX_SAVE_DELAY  = 1000;

    var IS_PREVIEW_CLASS         = "is-preview";
    var IS_ZOOM_CLASS            = "is-zoom";

    var ICON_ZOOM_CLASS          = "TAP-icon fa fa-arrows-alt";
    var ICON_PREVIEW_CLASS       = "TAP-icon fa fa-eye";
    var ICON_ZOOM_TITLE          = "zoom";
    var ICON_PREVIEW_TITLE       = "preview";
    var ICON_ELEMENT             = "<i></i>";
    var ICONS_PARENT_SELECTOR    = ".InputfieldHeader";
    var IGNORE_KEYS = [
        13, // enter
        32, // space
    ];

    owzim = typeof(owzim) !== "undefined" ? owzim : {};
    owzim.TextareaPreview = typeof(owzim.TextareaPreview) !== "undefined" ? owzim.TextareaPreview : {};

    owzim.TextareaPreview.initField = function($textarea, $textareaWrapper, $previewWrapper, $iframe, params) {

        var isPreviewActive = false;
        var isZoomActive = false;

        // console.log('params.ajaxUrl', params.ajaxUrl);
        // console.log('params.iframeUrl', params.iframeUrl);
        // console.log('params', params);

        var lastKeyPressed = -1;

        /*
            var to save the overflow setting of admins body element
            in full mode it's set to overflow: hidden
            back to normal more, the original value is set
         */
        var originalBodyOverflow;

        var $body = $("body");

        var $iconZoom =
            $(ICON_ELEMENT)
            .attr("class", ICON_ZOOM_CLASS)
            .attr("title", ICON_ZOOM_TITLE).hide();

        var $iconPreview =
            $(ICON_ELEMENT)
            .attr("class", ICON_PREVIEW_CLASS)
            .attr("title", ICON_PREVIEW_TITLE);

        var applyHeight = function() {
            $textarea.outerHeight($iframe.innerHeight());
        };

        var fetchFormattedString = function(value) {
            if (isPreviewActive) {
                $.ajax({
                    type: "POST",
                    url: params.ajaxUrl,
                    data: { text: value }
                })
                    .done(function(msg) {
                        setCookie(params.cookieNameText, msg);
                        setCookie(params.cookieNameChanged, HAS_CHANGED_TRUE);
                    });
            }
        };

        var fetchFormattedStringTrottled = throttle(
            fetchFormattedString,
            params.trackChangesInterval || DEFAULT_AJAX_SAVE_DELAY,
            { trailing: true }
        );

        $textareaWrapper.find(ICONS_PARENT_SELECTOR).prepend($iconZoom).prepend($iconPreview);

        $textarea.bind('input propertychange', function() {
            if (isAllowedKey(lastKeyPressed)) {
                 fetchFormattedStringTrottled(this.value);
            }
        });

        // event handlers
        (function() {

            $iconPreview.click(function(e) {

                e.preventDefault();
                e.stopImmediatePropagation();  // prevent header toggle to be fired

                if (isPreviewActive) {
                    $iconZoom.hide();
                    $previewWrapper.removeClass(IS_PREVIEW_CLASS);
                    $iframe.attr("src", "");
                } else {
                    $iconZoom.show();
                    $previewWrapper.addClass(IS_PREVIEW_CLASS);
                    $iframe.attr("src", params.iframeUrl);
                }

                isPreviewActive = !isPreviewActive;
                fetchFormattedString($textarea[0].value);
            });

            $iconZoom.click(function(e) {

                e.preventDefault();
                e.stopImmediatePropagation();  // prevent header toggle to be fired

                if (!isPreviewActive) return;

                if (isZoomActive) {
                    $previewWrapper.removeClass(IS_ZOOM_CLASS);
                    $textarea.height("");
                    $body.css("overflow", originalBodyOverflow);
                } else {

                    originalBodyOverflow = $body.css("overflow");
                    $body.css("overflow", "hidden");

                    $previewWrapper.addClass(IS_ZOOM_CLASS);

                    applyHeight();
                    // TODO
                    setTimeout(applyHeight, 500);
                }

                isZoomActive = !isZoomActive;
            });



            $(document).keyup(function(e) {
                if (isZoomActive) {
                    if (e.keyCode == 27) { // ESC
                        $iconZoom.click();
                    }
                }
            });

            $(document).keydown(function(e) {
                lastKeyPressed = e.keyCode;
            });


            $(window).on('resize', function(){
                if (isZoomActive) {
                    applyHeight();
                }
            });

        })();


    };

    owzim.TextareaPreview.initPreview = function($content, params) {

        var fetchCookie = function() {

            var hasChanged = getCookie(params.cookieNameChanged);
            var text = getCookie(params.cookieNameText);

            if (hasChanged === HAS_CHANGED_TRUE || !hasChanged) {
                console.log(params.cookieNameText + " has changed, refesh");
                $content.html(text);
                setCookie(params.cookieNameChanged, HAS_CHANGED_FALSE);

            }

            setTimeout(fetchCookie, 333);
        };
        fetchCookie();
    };

    var isAllowedKey = function(keyCode) {
        return (IGNORE_KEYS.indexOf(keyCode) === -1);
    };

    var setCookie = function(name, value) {
        if (window.localStorage) {
            // console.log("localStorage supported");
            localStorage[name] = value;
        } else {
            $.cookie(name, value, { expires: 1, path: '/' });
        }
    };

    var getCookie = function(name) {
        if (window.localStorage) {
            return localStorage[name];
        } else {
            return $.cookie(name);
        }
    };

    /*
        the following code is taken from underscore.js, modified a bit
        see: https://github.com/jashkenas/underscore/
             and http://underscorejs.org/#throttle
    */
    var throttle = function(func, wait, options) {
        var context, args, result;
        var timeout = null;
        var previous = 0;
        if (!options) options = {};
        var later = function() {
            previous = options.leading === false ? 0 : new Date().getTime();
            timeout = null;
            result = func.apply(context, args);
            if (!timeout) context = args = null;
        };
        return function() {
            var now = new Date().getTime();
            if (!previous && options.leading === false) previous = now;
            var remaining = wait - (now - previous);
            context = this;
            args = arguments;
            if (remaining <= 0 || remaining > wait) {
                clearTimeout(timeout);
                timeout = null;
                previous = now;
                result = func.apply(context, args);
                if (!timeout) context = args = null;
            } else if (!timeout && options.trailing !== false) {
                timeout = setTimeout(later, remaining);
            }
            return result;
        };
    };



})(jQuery, window);