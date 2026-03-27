/**
 * Auto-rotation script for sliders, news, and events
 * Automatically rotates content every few seconds
 */

(function ($) {
    'use strict';

    // Configuration
    var config = {
        sliderInterval: 5000,      // 5 seconds for main slider
        newsInterval: 8000,        // 8 seconds for news ticker
        eventsInterval: 10000      // 10 seconds for events
    };

    var timers = {
        slider: null,
        news: null,
        events: null
    };

    /**
     * Auto-rotate main image slider
     */
    function autoRotateSlider() {
        var $slides = $('.ctl00_ContentPlaceHolder1_Rotator_jqImageList li');
        var $links = $('.ctl00_ContentPlaceHolder1_Rotator_jqImageLinks li a');
        var currentIndex = 0;
        var totalSlides = $slides.length;

        if (totalSlides <= 1) return;

        // Show first slide
        $slides.hide().eq(0).fadeIn(600);
        $links.removeClass('ctl00_ContentPlaceHolder1_Rotator_selected').eq(0).addClass('ctl00_ContentPlaceHolder1_Rotator_selected');

        // Auto-rotate function
        function rotate() {
            var nextIndex = (currentIndex + 1) % totalSlides;

            $slides.eq(currentIndex).fadeOut(600, function () {
                $slides.eq(nextIndex).fadeIn(600);
            });

            $links.removeClass('ctl00_ContentPlaceHolder1_Rotator_selected')
                .eq(nextIndex).addClass('ctl00_ContentPlaceHolder1_Rotator_selected');

            currentIndex = nextIndex;
        }

        // Start auto-rotation
        timers.slider = setInterval(rotate, config.sliderInterval);

        // Manual navigation
        $links.on('click', function (e) {
            e.preventDefault();
            clearInterval(timers.slider);

            var clickedIndex = $(this).parent().index();

            $slides.eq(currentIndex).fadeOut(600, function () {
                $slides.eq(clickedIndex).fadeIn(600);
            });

            $links.removeClass('ctl00_ContentPlaceHolder1_Rotator_selected');
            $(this).addClass('ctl00_ContentPlaceHolder1_Rotator_selected');

            currentIndex = clickedIndex;

            // Restart auto-rotation after 3 seconds
            setTimeout(function () {
                timers.slider = setInterval(rotate, config.sliderInterval);
            }, 3000);
        });

        // Pause on hover
        $('.jqImageRotator').hover(
            function () {
                clearInterval(timers.slider);
            },
            function () {
                timers.slider = setInterval(rotate, config.sliderInterval);
            }
        );
    }

    /**
     * Auto-rotate news ticker
     */
    function autoRotateNews() {
        var $newsItems = $('.news-ticker li');
        var totalNews = $newsItems.length;

        if (totalNews <= 1) return;

        $newsItems.hide().eq(0).fadeIn(600);
        $('#current').text('1');
        $('#total').text(totalNews);

        var currentIndex = 0;

        function rotateNews() {
            var nextIndex = (currentIndex + 1) % totalNews;

            $newsItems.eq(currentIndex).fadeOut(600, function () {
                $newsItems.eq(nextIndex).fadeIn(600);
                $('#current').text(nextIndex + 1);
            });

            currentIndex = nextIndex;
        }

        // Start auto-rotation
        timers.news = setInterval(rotateNews, config.newsInterval);

        // Manual controls
        $('.control_prev').on('click', function (e) {
            e.preventDefault();
            clearInterval(timers.news);

            var prevIndex = (currentIndex - 1 + totalNews) % totalNews;

            $newsItems.eq(currentIndex).fadeOut(300, function () {
                $newsItems.eq(prevIndex).fadeIn(300);
                $('#current').text(prevIndex + 1);
            });

            currentIndex = prevIndex;

            setTimeout(function () {
                timers.news = setInterval(rotateNews, config.newsInterval);
            }, 3000);
        });

        $('.control_next').on('click', function (e) {
            e.preventDefault();
            clearInterval(timers.news);

            rotateNews();

            setTimeout(function () {
                timers.news = setInterval(rotateNews, config.newsInterval);
            }, 3000);
        });

        // Pause on hover
        $('.news-ticker').hover(
            function () {
                clearInterval(timers.news);
            },
            function () {
                timers.news = setInterval(rotateNews, config.newsInterval);
            }
        );
    }

    /**
     * Auto-rotate events
     */
    function autoRotateEvents() {
        var $eventBoxes = $('.news-list.events_box .events_box_body');
        var totalEvents = $eventBoxes.length;

        if (totalEvents <= 1) return;

        $eventBoxes.hide().eq(0).fadeIn(600);
        $('#event_current').text('1');
        $('#event_total').text(totalEvents);

        var currentIndex = 0;

        function rotateEvents() {
            var nextIndex = (currentIndex + 1) % totalEvents;

            $eventBoxes.eq(currentIndex).fadeOut(600, function () {
                $eventBoxes.eq(nextIndex).fadeIn(600);
                $('#event_current').text(nextIndex + 1);
            });

            currentIndex = nextIndex;
        }

        // Start auto-rotation
        timers.events = setInterval(rotateEvents, config.eventsInterval);

        // Manual controls
        $('.control_prev_events').on('click', function (e) {
            e.preventDefault();
            clearInterval(timers.events);

            var prevIndex = (currentIndex - 1 + totalEvents) % totalEvents;

            $eventBoxes.eq(currentIndex).fadeOut(300, function () {
                $eventBoxes.eq(prevIndex).fadeIn(300);
                $('#event_current').text(prevIndex + 1);
            });

            currentIndex = prevIndex;

            setTimeout(function () {
                timers.events = setInterval(rotateEvents, config.eventsInterval);
            }, 3000);
        });

        $('.control_next_events').on('click', function (e) {
            e.preventDefault();
            clearInterval(timers.events);

            rotateEvents();

            setTimeout(function () {
                timers.events = setInterval(rotateEvents, config.eventsInterval);
            }, 3000);
        });

        // Pause on hover
        $('.news-list.events_box').hover(
            function () {
                clearInterval(timers.events);
            },
            function () {
                timers.events = setInterval(rotateEvents, config.eventsInterval);
            }
        );
    }

    /**
     * Initialize all auto-rotation features
     */
    function init() {
        // Wait for DOM to be ready
        $(document).ready(function () {
            // Small delay to ensure all elements are loaded
            setTimeout(function () {
                autoRotateSlider();
                autoRotateNews();
                autoRotateEvents();
            }, 500);
        });
    }

    // Start initialization
    init();

})(jQuery);
