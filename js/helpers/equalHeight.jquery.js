(function ($, window, document, undefined) {
    /**
     * Equalize the height of the given items.
     * @type {*|jQuery}
     * @return mixed
     * @param options
     * Example: $('.item1, .item2').equalHeight({ container: '.container' });
     */
    $.fn.equalHeight = function (options) {
        var items, settings;
        items = this;
        settings = $.extend({
            container: ''
        }, options);
        if (settings.container) {
            // If a container is set, apply setHeight() for all containers.
            $(settings.container).each(function () {
                var thisC = $(this),
                    getItems = [];
                $.each(items, function (index, value) {
                    getItems.push(thisC.find(value));
                });
                return setHeight(getItems);
            });
        } else {
            // Otherwise we assume that the items exist only once in a page
            // and we apply setHeight() to their first (and unique) instance.
            return setHeight(this);
        }
        // Set the height of all items equal to the highest item.
        function setHeight(getItems) {
            var itemsH = [];
            $(getItems).each(function () {
                itemsH.push($(this).outerHeight());
            });
            var maxH = Math.max.apply(Math, itemsH);
            $(getItems).each(function () {
                return $(this).css('height', maxH);
            });
        }
    }
})(jQuery, window, document);