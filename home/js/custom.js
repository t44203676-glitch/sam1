var domainOrgin = document.location.origin, domainOrgin = window.location.origin ? document.location.origin : window.location.protocol + "//" + window.location.hostname + (window.location.port ? ":" + window.location.port : ""); function emailForm() { win = window.open("mailto:?subject=Portal - www.moi.gov.sa&body=I would like to refer this Portal Page to you. You can visit the page in the following link: " + document.referrer, "emailWindow"); setTimeout(function () { win && win.open && !win.closed && win.close() }, 20) }
function displayElement(a) { var b = document.getElementById(a).style.display; "none" == b || "" == b ? document.getElementById(a).style.display = "block" : document.getElementById(a).style.display = "none" }
$(document).ready(function (a) {
    0 != $("#mobile_number").length && (document.querySelector("#mobile_number"), $("#mobile_number").bind("keyup", function (a) { a = parseInt($("#mobile_number").attr("maxlength")); $(this).val().length > a && $(this).val($(this).val().substr(0, a)) })); 0 != $("#otp").length && (document.querySelector("#otp"), $("#otp").bind("keyup", function (a) { a = parseInt($("#otp").attr("maxlength")); $(this).val().length > a && $(this).val($(this).val().substr(0, a)) })); 0 != $(".customTooltipContainer").length && 0 !=
        $("#d_sim:checked").length && $(".customTooltipContainer").css("display", "inline-block"); "en" == $("html").attr("lang") ? $(".customTooltipContainer .tooltip-header").find("span").text("If you do not know the SIM Card Number(MSISDN), please contact your service provider.") : $(".customTooltipContainer .tooltip-header").find("span").text("\u00d8\u00a5\u00d8\u00b0\u00d8\u00a7 \u00d9\u0084\u00d9\u0085 \u00d8\u00aa\u00d8\u00b9\u00d8\u00b1\u00d9\u0081 \u00d8\u00b1\u00d9\u0082\u00d9\u0085 \u00d9\u0087\u00d8\u00a7\u00d8\u00aa\u00d9\u0081 \u00d8\u00a7\u00d9\u0084\u00d8\u00b4\u00d8\u00b1\u00d9\u008a\u00d8\u00ad\u00d8\u00a9\u00d8\u008c(MSISDN) \u00d9\u0081\u00d8\u00b6\u00d9\u0084\u00d8\u00a7 \u00d8\u00a7\u00d9\u0084\u00d8\u00aa\u00d9\u0088\u00d8\u00a7\u00d8\u00b5\u00d9\u0084 \u00d9\u0085\u00d8\u00b9 \u00d9\u0085\u00d8\u00b2\u00d9\u0088\u00d8\u00af \u00d8\u00a7\u00d9\u0084\u00d8\u00ae\u00d8\u00af\u00d9\u0085\u00d8\u00a9 \u00d9\u0084\u00d9\u0084\u00d8\u00ad\u00d8\u00b5\u00d9\u0088\u00d9\u0084 \u00d8\u00b9\u00d9\u0084\u00d9\u0089 \u00d8\u00a7\u00d9\u0084\u00d8\u00b1\u00d9\u0082\u00d9\u0085");
    $('input[type="radio"]').bind("click", function () { "D" == $('input[name="simSelection"]:checked').val() ? ($(".customTooltipContainer").css("display", "inline-block"), $(".custom-tooltip").show(), $("#mobile_number").attr("placeholder", "831XXXXXXXXX")) : ($(".customTooltipContainer").hide(), $("#mobile_number").attr("placeholder", "05XXXXXXXX")) }); $(".close-tooltip").bind("click", function () { $(".custom-tooltip").hide(); return !1 }); $(".custom-tooltip-link").bind("click", function () { $(".custom-tooltip").toggle(); return !1 });
    $(".page_body").prepend('<div id="toolsList" class="sharePost_new noPrint"><a href="javascript:shareLink(\'Facebook\');" class="facebook">Facebook</a><a href="javascript:shareLink(\'Google\');" class="google">Google</a></div>')
}); $("#myID\\.entry\\[1\\]").css("border", "3px solid red");
function shareLink(a) { var b = parent.window.location.href; "Delicious" == a && window.open("http://del.icio.us/post?url=" + b + ";title=title"); "Digg" == a && window.open("http://digg.com/submit?phase=2&amp;url=" + b + ""); "Facebook" == a && window.open("http://www.facebook.com/sharer.php?u=" + b + "&amp;t=title"); "Google" == a && window.open("http://www.google.com/bookmarks/mark?op=edit&amp;bkmk=" + b + "&amp;title=title") }
(function (a) {
    a.fn.hoverIntent = function (b, c, f) {
        var e = { interval: 100, sensitivity: 2, timeout: 0 }, e = "object" === typeof b ? a.extend(e, b) : a.isFunction(c) ? a.extend(e, { over: b, out: c, selector: f }) : a.extend(e, { over: b, out: b, selector: c }), g, d, h, k, l = function (a) { g = a.pageX; d = a.pageY }, m = function (b, c) {
            c.hoverIntent_t = clearTimeout(c.hoverIntent_t); if (Math.abs(h - g) + Math.abs(k - d) < e.sensitivity) return a(c).off("mousemove.hoverIntent", l), c.hoverIntent_s = 1, e.over.apply(c, [b]); h = g; k = d; c.hoverIntent_t = setTimeout(function () { m(b, c) },
                e.interval)
        }; b = function (b) { var c = jQuery.extend({}, b), d = this; d.hoverIntent_t && (d.hoverIntent_t = clearTimeout(d.hoverIntent_t)); "mouseenter" == b.type ? (h = c.pageX, k = c.pageY, a(d).on("mousemove.hoverIntent", l), 1 != d.hoverIntent_s && (d.hoverIntent_t = setTimeout(function () { m(c, d) }, e.interval))) : (a(d).off("mousemove.hoverIntent", l), 1 == d.hoverIntent_s && (d.hoverIntent_t = setTimeout(function () { d.hoverIntent_t = clearTimeout(d.hoverIntent_t); d.hoverIntent_s = 0; e.out.apply(d, [c]) }, e.timeout))) }; return this.on({
            "mouseenter.hoverIntent": b,
            "mouseleave.hoverIntent": b
        }, e.selector)
    }
})(jQuery);
$(document).ready(function () {
    var a, b, c, f, e, g = 0; $(".topnav > ul > li").hover(function (d) { try { clearTimeout(timeout) } catch (c) { } a = $(this); b = a.children("div"); b.slideDown(); a.addClass("active") }, function () { timeout = setTimeout(function () { b.slideUp(); a.removeClass("active") }, 800) }); $("ul.nav-tabs").each(function () {
        var a, b, c = $(this).find("a"); a = $(c.filter('[href="' + location.hash + '"]')[0] || c[0]); a.parent().addClass("active"); b = $(a.attr("href")); b.addClass("active"); c.not(a).each(function () { $($(this).attr("href")).hide().parent().removeClass("active") });
        $(this).on("click", "a", function (c) { a.parent().removeClass("active"); b.hide().removeClass("active"); a = $(this); b = $($(this).attr("href")); a.parent().addClass("active"); b.show().addClass("active"); c.preventDefault() })
    }); $(".week-day").on("click", "li", function () { var a = $(this), b = a.find("a > span > em"), c = a.find("a > span"); a.toggleClass("selected"); 0 < b.length ? b.remove() : c.append("<em>selected</em>") }); f = $(".branch-address"); f.eq(0).show(); $(".branch-list").on("mouseenter", "li", function () {
        c = $(this); e = parseInt(c.index()) +
            1; c.addClass("hover"); f.hide(); f.eq(e).show(); g = $("input[name=service_branch]:checked").val()
    }).on("mouseleave", "li", function () { $branch_opted = $("input[name=service_branch]:checked").val(); g = "" != $branch_opted && "undefined" != typeof $branch_opted ? $branch_opted : 0; c.removeClass("hover"); f.hide(); f.eq(g).show() }); $(".branch-list").on("click", "input[name=service_branch]", function () { var a = $(this); $(".branch-list ul li").removeClass("selected"); a.closest("li").addClass("selected") }); $(".collapse-head").click(function () {
        1 ==
            $(this).next().is(":hidden") ? ($(this).addClass("on"), $(this).next().slideDown("normal")) : ($(this).next().slideUp("normal"), $(this).removeClass("on"))
    }); $(".collapse-head").mouseover(function () { $(this).addClass("over") }).mouseout(function () { $(this).removeClass("over") }); $("div#mySliderTabs").sliderTabs({}); $(".nav-holder > ul > li").hoverIntent({
        sensitivity: 7, interval: 50, over: function () { $(".megamenu", this).slideDown(220); $(this).addClass("hovered") }, timeout: 50, out: function () {
            $(".megamenu", this).hide();
            $(this).removeClass("hovered")
        }
    }); $(".service-toogle").on("click", function (a) { a.stopImmediatePropagation(); $(this); $(".service-container"); $(this).parent().find("a").hasClass("highlight") ? ($(this).parent().parent().parent().removeClass("toggle-container"), $(this).parent().find("a").removeClass("highlight")) : ($(this).parent().parent().parent().addClass("toggle-container"), $(this).parent().find("a").addClass("highlight")) }); $(".all-services").click(function () {
        $(this).find("a").hasClass("highlight") ?
            ($(this).parent().parent().removeClass("toggle-container"), $(this).find("a").removeClass("highlight")) : ($(this).parent().parent().addClass("toggle-container"), $(this).find("a").addClass("highlight"))
    })
}); function showServices(a) { "" != a ? $("#sector").show() : $("#sector").hide() }
$(document).ready(function () {
    $("input[type='password']").onpaste = function (a) { a.preventDefault() }; $(".dropdown  dt  a").click(function () { var a = $(this); a.parent().parent().parent().find("ul").not(a.parent().parent().find("ul")).hide(); a.parent().parent().find("ul").toggle() }); $(".ui-slider-tabs-list li").click(function () { $(this).parent().parent().parent().parent().parent().parent().addClass("toggle-container").find("a").addClass("highlight"); $(this).find("div:first").find("a").addClass("highlight") });
    $(".dropdown dd ul li a").click(function () { var a = $(this), b = a.html(); a.parent().parent().parent().prev().find("a span").html(b); a.closest("dd > ul").hide() }); $(document).bind("click", function (a) { $(a.target).parents().hasClass("dropdown") || $(".dropdown dd ul").hide() }); $(".steps li a").hover(function () { $(this).parent().toggleClass("hover") }); $(".tab-content .collapse-box ,.news-list li, .all-services, .search-box .search-btn, .video-block, .wide-blocks .service-block, .eservices-block .service-block").hover(function () { $(this).toggleClass("hover") });
    $(".nav-items,.nav-more ,.navbar-toggle ,.search-toggle ,.login-link ,.collapse-block .collapse-toggle ").click(function () { $(this).parent().toggleClass("selected") }); $(".service-group h4 a ,.all-service h4 a ").click(function () { $(this).parent().parent().toggleClass("selected") })
});
$(window).on("load", function () { $(document).ready(function () { function a() { "" === $(this).val() && $(this).val($(this).attr("placeholder")).addClass("placeholder") } function b() { $(this).val() === $(this).attr("placeholder") && $(this).val("").removeClass("placeholder") } "placeholder" in $("<input>")[0] || ($("input[placeholder], textarea[placeholder]").blur(a).focus(b).each(a), $("form").submit(function () { $(this).find("input[placeholder], textarea[placeholder]").each(b) })) }) });
$(window).on("load", function () {
    var a;
    function processLogos(b) {
        a = b; if (!a) return;
        var bURL = (typeof BASE_URL !== 'undefined' ? BASE_URL : '');
        $("img.vehicle-logo").each(function () {
            var alt = $(this).attr("alt");
            var code = void 0;
            for (var i = 0; i < a.length; i++) { if (a[i].name == alt) { code = a[i].code; break; } }
            if (code !== void 0) {
                $(this).attr("src", bURL + "portal/images/AppAssets/vehiclesLogos/" + code + ".imageset/" + code + ".png").css("visibility", "visible");
            } else {
                $(this).attr("src", bURL + "portal/images/AppAssets/vehiclesLogos/defaultVehicle.png").css("visibility", "visible");
            }
        });
    }
    // Use BASE_URL from header
    var jsonPath = (typeof BASE_URL !== 'undefined' ? BASE_URL : '') + "portal/js/vc-logos.json";

    $.ajax({
        url: jsonPath,
        dataType: 'json',
        success: processLogos,
        error: function () {
            console.error("Failed to load vc-logos.json from: " + jsonPath);
        }
    });
});
function callLogout() { Object.keys(localStorage).forEach(function (a) { /^DataTables_Absher_*/.test(a) && localStorage.removeItem(a) }); document.getElementById("logoutFormId").submit() } var CalendarYear = function () { }; CalendarYear.prototype.hijri = function () { var a = (new Date).getDate(), b = (new Date).getMonth() + 1, c = (new Date).getFullYear(), a = getTodayHijriDate(c, b, a).split(" "); return a[a.length - 1] }; CalendarYear.prototype.gregorian = function () { return (new Date).getFullYear() }; var copyrightYear = new CalendarYear;
