$(window).on("load", function () {
    // Small delay to ensure everything is settled
    setTimeout(function () {
        var $slides = $("#slides");
        if ($slides.length > 0 && $slides.find("img").length > 0) {
            try {
                $slides.slidesjs({
                    width: 940,
                    height: 371,
                    navigation: false,
                    pagination: false,
                    play: {
                        active: false,
                        auto: true,
                        interval: 5000,
                        swap: true,
                        pauseOnHover: true,
                        restartDelay: 1000
                    }
                });
            } catch (e) {
                console.log("SlidesJS init suppressed:", e.message);
            }
        }
    }, 500);
});
