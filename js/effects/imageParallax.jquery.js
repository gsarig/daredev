/**
 * Created by George on 6/23/2016.
 */
jQuery(document).ready(function ($) {
    /**
     * Parallax effect on header scroll.
     * @type {*|jQuery}
     * @return mixed
     * @param options
     * Example: $('#slideshow').ddSlideshow({ speed: 2000, 'pause': '.elem-to-pause-on-hover' });
     */
    "use strict";
    $('.daredev-parallax-wrapper').each(function () {
        var parallax_section = $(this);
        var parallax_speed = parseFloat(parallax_section.attr('data-bgspeed'));
        if (parallax_speed == -1) {
            parallax_section.css('background-attachment', 'fixed');
            parallax_section.css('background-position', 'center center');
            return;
        }

        $(window).scroll(function () {
            // if in area of interest
            if (( $(window).scrollTop() + $(window).height() > parallax_section.offset().top ) &&
                ( $(window).scrollTop() < parallax_section.offset().top + parallax_section.outerHeight() )) {

                var scroll_pos = 0;
                if ($(window).height() > parallax_section.offset().top) {
                    scroll_pos = $(window).scrollTop();
                } else {
                    scroll_pos = $(window).scrollTop() + $(window).height() - parallax_section.offset().top;
                }
                parallax_section.css('background-position', 'center ' + (-scroll_pos * parallax_speed) + 'px');
            }
        });
    });
});