$(document).ready(function(){if(/Chrome[\/\s](\d+\.\d+)/.test(navigator.userAgent))if(20>new Number(RegExp.$1))window.location="/portal/js/block.html";else return;if(/Firefox[\/\s](\d+\.\d+)/.test(navigator.userAgent))if(35>new Number(RegExp.$1))window.location="/portal/js/block.html";else return;!(-1!==navigator.userAgent.indexOf("MSIE")||0<navigator.appVersion.indexOf("Trident/"))||!/MSIE (\d+\.\d+);/.test(navigator.userAgent)||9<=new Number(RegExp.$1)||(location="/portal/js/block.html")});
if (window.location.hostname == "jobs.moi.gov.sa" || window.location.hostname == "mncc.org.sa" || window.location.hostname == "www.mncc.org.sa") {
window.open("http://" + window.location.hostname + "/","_self");
}

