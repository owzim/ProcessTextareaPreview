(function($, window, undefined) { // safe closure

    console = typeof(console) !== "undefined" ? console : { log: function() {} };

    var AJAX_RELOAD_DELAY     = 1000;
    var PARAMS_DATA_KEY       = "params";

    var IS_PREVIEW_CLASS      = "is-preview";
    var IS_ZOOM_CLASS         = "is-zoom";

    var ICON_ZOOM_CLASS       = "TAP-icon fa fa-arrows-alt";
    var ICON_PREVIEW_CLASS    = "TAP-icon fa fa-eye";
    var ICON_ZOOM_TITLE       = "zoom";
    var ICON_PREVIEW_TITLE    = "preview";
    var ICON_ELEMENT          = "<i></i>";
    var ICONS_PARENT_SELECTOR = ".InputfieldHeader";

    owzim = typeof(owzim) !== "undefined" ? owzim : {};

    owzim.TextareaPreview = function($textarea, $textareaWrapper, $previewWrapper, $ajaxContainer) {

        var isPreviewActive = false;
        var isZoomActive = false;

        var params = $previewWrapper.data(PARAMS_DATA_KEY);

        var originalBodyOverflow;
        var $body = $("body");

        var $iconZoom =
            $(ICON_ELEMENT)
            .attr("class", ICON_ZOOM_CLASS)
            .attr("title", ICON_ZOOM_TITLE);

        var $iconPreview =
            $(ICON_ELEMENT)
            .attr("class", ICON_PREVIEW_CLASS)
            .attr("title", ICON_PREVIEW_TITLE);

        $iconZoom.hide();


        var runAjax = function(value) {
            if(isPreviewActive) {
                $.ajax({
                    type: "POST",
                    url: params.ajaxUrl,
                    data: { text: value, fieldName: params.fieldName, pageID: params.pageID }
                })
                    .done(function( msg ) {
                        $ajaxContainer.html(msg);
                    });
            }
        };

        var runAjaxTrottled = throttle(runAjax, AJAX_RELOAD_DELAY);

        $textarea.bind('input propertychange', function() {
            runAjaxTrottled(this.value);
        });

        $iconPreview.click(function(e) {
            // prevent header toggle to be fired
            e.preventDefault();
            e.stopImmediatePropagation();

            if (isPreviewActive) {
                $iconZoom.hide();
                $previewWrapper.removeClass(IS_PREVIEW_CLASS);
            } else {
                $iconZoom.show();
                $previewWrapper.addClass(IS_PREVIEW_CLASS);

            }

            isPreviewActive = !isPreviewActive;
            runAjax($textarea[0].value);
        });

        $iconZoom.click(function(e) {
            // prevent header toggle to be fired
            e.preventDefault();
            e.stopImmediatePropagation();



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

        $textareaWrapper.find(ICONS_PARENT_SELECTOR).prepend($iconZoom).prepend($iconPreview);

        $(document).keyup(function(e) {
            if(isZoomActive) {
                if (e.keyCode == 27) { // ESC
                    $iconZoom.click();
                }
            }
        });


        $(window).on('resize', function(){

            if(isZoomActive) {
                applyHeight();
            }

        });


        function applyHeight() {
            $textarea.outerHeight($ajaxContainer.innerHeight());
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