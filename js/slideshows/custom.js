(function ($, window) {
    /**
     * A custom Slideshow.
     * @type {*|jQuery}
     * @return mixed
     * @param options
     * Example: $('#slideshow').ddSlideshow({ speed: 2000, 'pause': '.elem-to-pause-on-hover' });
     */
    "use strict";
    $.fn.ddSlideshow = function (options) {
        var slideshow, settings;
        slideshow = this;
        settings = $.extend({
            'speed': 4000,
            'pause': false
        }, options);

        var controls = slideshow.find('.controls'),
            slides = slideshow.find('.slides'),
            timer = 0,
            speed = settings.speed;

        $(window).on('load', function () {
            setTimer();
            controls.find('li').on('click', function () {
                clearInterval(timer);
                var cId = $(this).attr('data-slide');
                controls.find('li').removeClass('active');
                $(this).addClass('active');
                slides.find('li').removeClass('active');
                slides.find('#' + cId).addClass('active');
                setTimer();
            });

            if (settings.pause !== false) {
                $(settings.pause).on('mouseenter', function () {
                    clearInterval(timer);
                }).on('mouseleave', function () {
                    setTimer();
                });
            }

        });

        function setTimer() {
            timer = setInterval(function () {
                animateSlides();
            }, speed);
        }

        function animateSlides() {
            var activeSlides = slides.find('.active'),
                activeControls = controls.find('.active');
            activeSlides.removeClass('active');
            activeControls.removeClass('active');
            if (activeSlides.next('li').length) {
                activeSlides.next('li').addClass('active');
                activeControls.next('li').addClass('active');
            } else {
                slides.find('li:first').addClass('active');
                controls.find('li:first').addClass('active');
            }
        }
    }
})(jQuery, window, document);