$(document).ready(function () {
    const $drawer = $('#mobileDrawer');
    const $overlay = $('.mobile-menu-overlay');
    const $toggleBarBtn = $('#mobileMenuOpen'); // Corrected ID for full-width bar
    const $backBtn = $('#menuBackBtn');
    const $currentTitle = $('#menuCurrentTitle');

    // Breadcrumb / Navigation Stack
    let navStack = [{ id: 'main-level', title: 'القائمة' }];

    function updateHeader() {
        const current = navStack[navStack.length - 1];
        $currentTitle.text(current.title);

        if (navStack.length > 1) {
            $backBtn.removeClass('hidden');
        } else {
            $backBtn.addClass('hidden');
        }
    }

    // Toggle Drawer
    function openDrawer() {
        $drawer.addClass('active');
        $overlay.addClass('active');
        $('body').addClass('menu-open');
    }

    function closeDrawer() {
        $drawer.removeClass('active');
        $overlay.removeClass('active');
        $('body').removeClass('menu-open');

        // Reset navigation to main level after a delay
        setTimeout(() => {
            $('.menu-level').removeClass('active parent-out');
            $('#main-level').addClass('active');
            navStack = [{ id: 'main-level', title: 'القائمة' }];
            updateHeader();
        }, 400);
    }

    $toggleBarBtn.on('click', openDrawer);
    $overlay.on('click', closeDrawer);

    // Drill-Down Logic
    $(document).on('click', '.menu-link.has-submenu', function (e) {
        e.preventDefault();
        const targetId = $(this).data('target');
        const nextTitle = $(this).data('title');
        const currentLevelId = navStack[navStack.length - 1].id;

        // Animate transition
        $(`#${currentLevelId}`).addClass('parent-out').removeClass('active');
        $(`#${targetId}`).addClass('active');

        // Update stack
        navStack.push({ id: targetId, title: nextTitle });
        updateHeader();
    });

    // Back Logic
    $backBtn.on('click', function () {
        if (navStack.length > 1) {
            const current = navStack.pop();
            const parent = navStack[navStack.length - 1];

            $(`#${current.id}`).removeClass('active');
            $(`#${parent.id}`).removeClass('parent-out').addClass('active');

            updateHeader();
        }
    });

    // Handle home button inside drawer (close drawer and navigate)
    $('.btn-home-circle').on('click', function (e) {
        closeDrawer();
    });

    // Handle Window Resize
    $(window).on('resize', function () {
        if ($(window).width() >= 768) {
            if ($('body').hasClass('menu-open')) {
                closeDrawer();
            }
        }
    });
});
