(function ($, window, document, undefined) {
    /**
     * Toggle text
     * Use it like this:
     * $('#selector').toggleText('text1', 'text2');
     * @param a
     * @param b
     * @returns {jQuery}
     */
    $.fn.toggleText = function (a, b) {
        var that = this;
        if (that.text() != a && that.text() != b) {
            that.text(a);
        }
        else if (that.text() == a) {
            that.text(b);
        }
        else if (that.text() == b) {
            that.text(a);
        }
        return this;
    }
})(jQuery, window, document);