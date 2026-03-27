jQuery(document).ready(function (a) {
    a.ajaxSetup({ cache: !0 }); a("DIV#tabs span").click(function (b) { a(this).hasClass("active") || (b = a(this).index() + 1, a("DIV#tabs span.active").removeClass("active"), a(this).addClass("active"), a("DIV#tab DIV.cnt.active").removeClass("active"), a("DIV#tab DIV.cnt:nth-child(" + b + ")").addClass("active")) }); a(".accord ul li:has(ul)").addClass("expand").find("ul").hide(); a(".accord ul li.expand>a"); a(".accord ul li.collapse>a").click(function (b) {
        a(this).parent().addClass("expand").removeClass("collapse").find(">ul").slideUp();
        b.stopImmediatePropagation()
    }); a(".accord ul li.expand>a").click(function (b) { a("ul.formlisting").css({ display: "block" }); a(this).parent().addClass("collapse").removeClass("expand").find(">ul").slideDown(); b.stopImmediatePropagation() }); a(".accord ul li.collapse li:not(.collapse)").click(function (a) { a.stopImmediatePropagation() }); a("input[type=button]").each(function () {
        $onclick = a(this).attr("onclick"); void 0 != $onclick && (0 <= $onclick.indexOf("window.print();") || 0 <= $onclick.indexOf("window.print()")) &&
            a(this).addClass("moi-print-btn")
    }); a(window).on('load', function (b) { setTimeout(function () { a("#LoginPortletSecretField").removeAttr("readonly"); a("#LoginPortletSecretField1").removeAttr("readonly") }, 100) })
}); function doHideExp(a) { var b = $(a).attr("class"); "expand" == b ? $(a).attr("class", "collapse") : "collapse" == b && $(a).attr("class", "expand") }
$(document).ready(function () {
    $.ajaxSetup({
        cache: !0
    });

    // Use click instead of hover for better UX and "choosing" behavior
    $("ul.emirates-ul li").click(function (e) {
        e.preventDefault(); // Prevent page reload

        var text = $(this).text();
        // Get the base class (e.g., 'riyadh')
        var fullClass = $(this).attr("class");
        var baseClass = fullClass.replace(" selected", "").replace("selected", "").trim();

        // Update Sidebar Styling
        $("ul.emirates-ul li").removeClass("selected");
        $(this).addClass("selected");

        // Update Map Styling
        $(".region-path").removeClass("selected");
        $("#map-" + baseClass).addClass("selected");

        // Update content
        $("#emirates-mid-block").attr("class", baseClass);
        $(".emirates-title").text(text);

        // Update links list
        $(".emirates-links ul").removeClass("selected").hide();
        $(".emirates-links ul#" + baseClass).addClass("selected").show();
    });

    // Map Click Handler
    $(document).on("click", ".region-path", function () {
        var region = $(this).data("region");
        // Trigger click on corresponding sidebar item
        $("ul.emirates-ul li." + region).click();
    });
});
/* makeactive_emirates and other functions can be removed if they are only for the old hover logic, 
   or kept if used elsewhere. Since the old code used them for hover housekeeping, 
   we can simplify logic directly in the click handler as done above. */
function doKeyPressLogin(a) { "undefined" == typeof a && window.event && (a = window.event); 13 == a.keyCode && (a = document.getElementById("LoginPortletSecretField"), a.value = base64_encode(a.value), document.getElementById("loginFormId").submit()) }
$(document).ready(function () {
    $("#LoginPortletFormSubmit").click(function () {
        function a() {
            $(".inptTextLoginpopup").focus(function () {
                $(this).removeAttr("style"); "LoginPortletIDField1" == $(this).attr("id") && ("ar" == $("html").attr("lang") ? $(this).attr("placeholder", "\ue800 \u0627\u0633\u0645 \u0627\u0644\u0645\u0633\u062a\u062e\u062f\u0645 \u0623\u0648 \u0631\u0642\u0645 \u0627\u0644\u0647\u0648\u064a\u0629") : $(this).attr("placeholder", "\ue800 Username or ID Number")); "LoginPortletSecretField1" == $(this).attr("id") &&
                    ("ar" == $("html").attr("lang") ? $(this).attr("placeholder", "\ue818 \u0643\u0644\u0645\u0629 \u0627\u0644\u0645\u0631\u0648\u0631") : $(this).attr("placeholder", "\ue818 Password")); "kapcha_value" == $(this).attr("name") && ("ar" == $("html").attr("lang") ? $(this).attr("placeholder", "\ue85f \u0627\u0644\u0631\u0645\u0632 \u0627\u0644\u0645\u0631\u0626\u064a") : $(this).attr("placeholder", "\ue85f Enter Image Code"))
            })
        } function b() {
            $(".inptTextLoginpopup").focus(function () {
                $(this).removeAttr("style"); "Please enter ID / user name" ==
                    $(this).val() ? ($(this).val(""), $(this).removeClass("placeholder")) : "Please enter your password" == $(this).val() ? ($(this).val(""), $(this).removeClass("placeholder")) : "Please Enter image code" == $(this).val() && ($(this).val(""), $(this).removeClass("placeholder"))
            })
        } function f() {
            !1 === d ? ($("#LoginPortletIDField1").attr("style", "border: 1px solid rgb(207, 0, 0) !important;background: rgb(255, 225, 225) none repeat scroll 0% 0% !important;"), "ar" == $("html").attr("lang") ? $("#LoginPortletIDField1").attr("placeholder",
                "\u0623\u062f\u062e\u0644 \u0627\u0644\u0647\u0648\u064a\u0629 \u0623\u0648 \u0627\u0633\u0645 \u0627\u0644\u0645\u0633\u062a\u062e\u062f\u0645") : $("#LoginPortletIDField1").attr("placeholder", "Please enter ID / user name")) : $("#LoginPortletIDField1").removeAttr("style"); !1 === e ? ($("#LoginPortletSecretField1").attr("style", "border: 1px solid rgb(207, 0, 0) !important;background: rgb(255, 225, 225) none repeat scroll 0% 0% !important;"), "ar" == $("html").attr("lang") ? $("#LoginPortletSecretField1").attr("placeholder",
                    "\u0623\u062f\u062e\u0644 \u0643\u0644\u0645\u0629 \u0627\u0644\u0645\u0631\u0648\u0631") : $("#LoginPortletSecretField1").attr("placeholder", "Please enter your password")) : $("#LoginPortletSecretField1").removeAttr("style"); !1 === c ? ($(".captchaInput").attr("style", "border: 1px solid rgb(207, 0, 0) !important;background: rgb(255, 225, 225) none repeat scroll 0% 0% !important;"), "ar" == $("html").attr("lang") ? $(".captchaInput").attr("placeholder", "\u0623\u062f\u062e\u0644 \u0627\u0644\u0631\u0645\u0632 \u0627\u0644\u0645\u0631\u0626\u064a") :
                        $(".captchaInput").attr("placeholder", "Please Enter image code")) : $(".captchaInput").removeAttr("style")
        } var e = !1, d = !1, c = !1; if (-1 !== navigator.userAgent.indexOf("MSIE") || 0 < navigator.appVersion.indexOf("Trident/")) { var g = /MSIE (\d+\.\d+);/; g.test(navigator.userAgent) ? 9 == new Number(RegExp.$1) ? b() : a() : a() } else a(); d = 0 === $("#LoginPortletIDField1").val().length || "\ue800 Username or ID Number" == $("#LoginPortletIDField1").val() || "\ue800 \u0627\u0633\u0645 \u0627\u0644\u0645\u0633\u062a\u062e\u062f\u0645 \u0623\u0648 \u0631\u0642\u0645 \u0627\u0644\u0647\u0648\u064a\u0629" ==
            $("#LoginPortletIDField1").val() || "Please enter ID / user name" == $("#LoginPortletIDField1").val() || "\u0623\u062f\u062e\u0644 \u0627\u0644\u0647\u0648\u064a\u0629 \u0623\u0648 \u0627\u0633\u0645 \u0627\u0644\u0645\u0633\u062a\u062e\u062f\u0645" == $("#LoginPortletIDField1").val() ? !1 : !0; e = 0 == $("#LoginPortletSecretField1").val().length || "\ue818 Password" == $("#LoginPortletSecretField1").val() || "\ue818 \u0643\u0644\u0645\u0629 \u0627\u0644\u0645\u0631\u0648\u0631" == $("#LoginPortletSecretField1").val() ||
                "Please enter your password" == $("#LoginPortletSecretField1").val() || "\u0623\u062f\u062e\u0644 \u0643\u0644\u0645\u0629 \u0627\u0644\u0645\u0631\u0648\u0631" == $("#LoginPortletSecretField1").val() ? !1 : !0; c = 0 == $(".captchaInput").val().length || "\ue85f Enter Image Code" == $(".captchaInput").val() || "\ue85f \u0627\u0644\u0631\u0645\u0632 \u0627\u0644\u0645\u0631\u0626\u064a" == $(".captchaInput").val() || "Please Enter image code" == $(".captchaInput").val() || "\u0623\u062f\u062e\u0644 \u0627\u0644\u0631\u0645\u0632 \u0627\u0644\u0645\u0631\u0626\u064a" ==
                    $(".captchaInput").val() ? !1 : !0; -1 !== navigator.userAgent.indexOf("MSIE") || 0 < navigator.appVersion.indexOf("Trident/") ? (g = /MSIE (\d+\.\d+);/, g.test(navigator.userAgent) ? 9 == new Number(RegExp.$1) ? (!1 === d ? ($("#LoginPortletIDField1").attr("style", "border: 1px solid rgb(207, 0, 0) !important;background: rgb(255, 225, 225) none repeat scroll 0% 0% !important;"), "ar" == $("html").attr("lang") ? $("#LoginPortletIDField1").attr("value", "\u0623\u062f\u062e\u0644 \u0627\u0644\u0647\u0648\u064a\u0629 \u0623\u0648 \u0627\u0633\u0645 \u0627\u0644\u0645\u0633\u062a\u062e\u062f\u0645") :
                        $("#LoginPortletIDField1").attr("value", "Please enter ID / user name")) : $("#LoginPortletIDField1").removeAttr("style"), !1 === e ? ($("#LoginPortletSecretField1").attr("style", "border: 1px solid rgb(207, 0, 0) !important;background: rgb(255, 225, 225) none repeat scroll 0% 0% !important;"), "ar" == $("html").attr("lang") ? $("#LoginPortletSecretField1").attr("value", "\u0623\u062f\u062e\u0644 \u0643\u0644\u0645\u0629 \u0627\u0644\u0645\u0631\u0648\u0631") : $("#LoginPortletSecretField1").attr("value", "Please enter your password")) :
                            $("#LoginPortletSecretField1").removeAttr("style"), !1 === c ? ($(".captchaInput").attr("style", "border: 1px solid rgb(207, 0, 0) !important;background: rgb(255, 225, 225) none repeat scroll 0% 0% !important;"), "ar" == $("html").attr("lang") ? $(".captchaInput").attr("value", "\u0623\u062f\u062e\u0644 \u0627\u0644\u0631\u0645\u0632 \u0627\u0644\u0645\u0631\u0626\u064a") : $(".captchaInput").attr("value", "Please Enter image code")) : $(".captchaInput").removeAttr("style")) : f() : f()) : f(); return !0 === e && !0 === d && !0 ===
                                c ? !0 : !1
    })
});
function doSubmitLogin() {
    var a = !1; -1 !== navigator.userAgent.indexOf("MSIE") || 0 < navigator.appVersion.indexOf("Trident/") ? /MSIE (\d+\.\d+);/.test(navigator.userAgent) && 9 == new Number(RegExp.$1) && ($(".inptTextLogin").blur(function () {
        "LoginPortletIDField" == $(this).attr("name") && ("ar" == $("html").attr("lang") ? $(this).attr("placeholder", "\ue800 \u0631\u0642\u0645 \u0627\u0644\u0647\u0648\u064a\u0629") : $(this).attr("placeholder", "\ue800 ID Number")); "LoginPortletSecretField" == $(this).attr("id") && ("ar" == $("html").attr("lang") ?
            $(this).attr("placeholder", "\ue818 \u0643\u0644\u0645\u0629 \u0627\u0644\u0645\u0631\u0648\u0631") : $(this).attr("placeholder", "\ue818 Password"))
    }), $(".inptTextLogin").focus(function () { $(this).removeAttr("style"); $(this).val("") })) : $(".inptTextLogin").focus(function () {
        $(this).removeAttr("style"); "LoginPortletIDField" == $(this).attr("name") && ("ar" == $("html").attr("lang") ? $(this).attr("placeholder", "\ue800 \u0631\u0642\u0645 \u0627\u0644\u0647\u0648\u064a\u0629") : $(this).attr("placeholder", "\ue800 ID Number"));
        "LoginPortletSecretField" == $(this).attr("id") && ("ar" == $("html").attr("lang") ? $(this).attr("placeholder", "\ue818 \u0643\u0644\u0645\u0629 \u0627\u0644\u0645\u0631\u0648\u0631") : $(this).attr("placeholder", "\ue818 Password"))
    }); 0 === $("input[name='LoginPortletIDField']").val().length || "\ue800 ID Number" == $("input[name='LoginPortletIDField']").val() ? ($("input[name='LoginPortletIDField']").attr("style", "border: 1px solid rgb(207, 0, 0) !important;background: rgb(255, 225, 225) none repeat scroll 0% 0% !important;font-size:9px;height:23px;"),
        "ar" == $("html").attr("lang") ? $("input[name='LoginPortletIDField']").attr("placeholder", "\u0623\u062f\u062e\u0644 \u0627\u0644\u0647\u0648\u064a\u0629 \u0623\u0648 \u0627\u0633\u0645 \u0627\u0644\u0645\u0633\u062a\u062e\u062f\u0645") : $("input[name='LoginPortletIDField']").attr("placeholder", "Please enter ID / user name")) : ($("input[name='LoginPortletIDField']").removeAttr("style"), a = !0); 0 == $("#LoginPortletSecretField").val().length || "\ue818 Password" == $("#LoginPortletSecretField").val() ? ($("#LoginPortletSecretField").attr("style",
            "border: 1px solid rgb(207, 0, 0) !important;background: rgb(255, 225, 225) none repeat scroll 0% 0% !important;font-size:9px;height:23px;"), "ar" == $("html").attr("lang") ? $("#LoginPortletSecretField").attr("placeholder", "\u0623\u062f\u062e\u0644 \u0643\u0644\u0645\u0629 \u0627\u0644\u0645\u0631\u0648\u0631") : $("#LoginPortletSecretField").attr("placeholder", "Please enter your password ")) : ($("#LoginPortletSecretField").removeAttr("style"), !0 === a && ($("#LoginPortletSecretField").val(base64_encode($("#LoginPortletSecretField").val())),
                document.getElementById("loginFormId").submit()))
}
function base64_encode(a) {
    var b, f, e, d, c = 0, g = 0, h = []; if (!a) return a; a = unescape(encodeURIComponent(a)); do b = a.charCodeAt(c++), f = a.charCodeAt(c++), e = a.charCodeAt(c++), d = b << 16 | f << 8 | e, b = d >> 18 & 63, f = d >> 12 & 63, e = d >> 6 & 63, d &= 63, h[g++] = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=".charAt(b) + "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=".charAt(f) + "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=".charAt(e) + "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=".charAt(d);
    while (c < a.length); c = h.join(""); a = a.length % 3; return (a ? c.slice(0, a - 3) : c) + "===".slice(a || 3)
} function doSubmitLoginMobile() { document.getElementById("loginFormIdMobile").submit() } function closeCanvas() { $("nav#menu").data("mmenu").close(); setTimeout(function () { commoninitPopup("login-box2") }, 500) } $(document).ready(function () { $("input[type='password']").bind({ paste: function () { console.log("paste behaviour detected!"); return !1 } }) });
