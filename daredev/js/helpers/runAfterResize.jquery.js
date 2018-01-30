(function ($, window, document, undefined) {
    /**
     * Trigger a callback after window resize is finished.
     * It runs only on width resize, to prevent accidental
     * executions during scroll on mobile browsers.
     * @type {*|jQuery}
     * @param function callback
     * @param string uniqueId
     * @param integer delay
     * @return mixed
     * Typical Example: $.runAfterResize( functionToExecute );
     * Full Example: $.runAfterResize( functionToExecute, 'some_unique_id', 600 );
     */
    $.extend({
        runAfterResize: function (callback, uniqueId, delay) {
            uniqueId = uniqueId || 'some unique string';
            delay = delay || 300;
            var width = $(window).width();
            $(window).on('resize', function () {
                if ($(window).width() != width) {
                    waitForFinalEvent(function () {
                        callback();
                    }, delay, uniqueId);
                }
            });

            var waitForFinalEvent = (function () {
                var timers = {};
                return function (callback, ms, uniqueId) {
                    if (!uniqueId) {
                        uniqueId = 'Don\'t call this twice without a uniqueId';
                    }
                    if (timers[uniqueId]) {
                        clearTimeout(timers[uniqueId]);
                    }
                    timers[uniqueId] = setTimeout(callback, ms);
                };
            })();
        }
    });
})(jQuery, window, document);