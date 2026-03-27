var resourcesUrlPrefix='', TimeVal,format = "dd/mm/yyyy",vargregorian_input='',varhijri_input = '',TimeValstatus,HoursClocks;
function initCalendars(inputHijri,inputGreg,resourcesUrlPrefixx,locale,isHijriOutputOnly,formatp,minDate,maxDate,yearRangeGregorian,yearRangeHijra, times, timesingle, HoursClock)
{
var calendar;
var calendar_gregorian;
var hijri_input ='hijri_input' ;
var gregorian_input = 'gregorian_input';

if(arguments.length === 3)
{
	var a = inputHijri,b= "/portal/individuals/portlet_resources/img/",c='en',d=false,e='dd/mm/yyyy',f='0y',g='0y',h=inputGreg,i=true,j=resourcesUrlPrefixx;
	initCalendarssingle(a,b,c,d,e,f,g,h,i,j);
} else if(arguments.length === 10)
{
	var inputGreg = "/portal/individuals/portlet_resources/img/";
	initCalendarssingle(inputHijri,inputGreg,resourcesUrlPrefixx,locale,isHijriOutputOnly,formatp,minDate,maxDate,yearRangeGregorian,yearRangeHijra)
} else if(arguments.length === 7)
{
	var inputGreg = "/portal/individuals/portlet_resources/img/";
	initCalendarssingle(inputHijri,inputGreg,resourcesUrlPrefixx,locale,isHijriOutputOnly,formatp,minDate)
}
else
{

var TimeVal1 = yearRangeGregorian=="time" || yearRangeHijra=="time" || times=="time";
TimeValstatus = yearRangeHijra==true || times==true || timesingle==true;
HoursClocks = times=="12" || timesingle=="12" || HoursClock=="12";
if(HoursClocks == true){
$("#"+inputHijri).attr("title", "12");
$("#"+inputGreg).attr("title", "12");	
}
else
{
	$("#"+inputHijri).attr("title", "24");
	$("#"+inputGreg).attr("title", "24");	
}
if(TimeVal1 == true)
{
$("#"+inputHijri).attr("data", true);
$("#"+inputGreg).attr("data", true);	
}
var DefaultTime = '', Hours='', am='';
if(arguments.length === 7)
{
	var inputGreg = "/portal/individuals/portlet_resources/img/";
	initCalendarssingle(inputHijri,inputGreg,resourcesUrlPrefixx,locale,isHijriOutputOnly,formatp,minDate)
}
else
{
	var resourcesUrlPrefixx = "/portal/individuals/portlet_resources/img/";
	if(formatp != null)
	{
		format = formatp;
	}
	if(minDate == null)
	{
		minDate = '-5y';
	}	
	if(maxDate == null)
	{
		maxDate = '+5y';
	}
	try{
	if(locale!= null && locale == 'ar')
	{
		(function($){
			$.calendars.calendars.ummalqura.prototype.regionalOptions['ar'] = {
				name: 'UmmAlQura', // The calendar name
				epochs: ['BAM', 'AM'],
				monthNames: ['محرّم', 'صفر', 'ربيع الأول', 'ربيع الثاني', 'جمادى الاول', 'جمادى الآخر', 'رجب', 'شعبان', 'رمضان', 'شوّال', 'ذو القعدة', 'ذو الحجة'],
				monthNamesShort: ['محرّم', 'صفر', 'ربيع الأول', 'ربيع الثاني', 'جمادى الاول', 'جمادى الآخر', 'رجب', 'شعبان', 'رمضان', 'شوّال', 'ذو القعدة', 'ذو الحجة'],
				dayNames: ['الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'],
				dayNamesMin: ['الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'],
				dayNamesShort: ['الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'],
				digits: $.calendars.substituteDigits(['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩']),
				dateFormat: 'yyyy/mm/dd', // See format options on BaseCalendar.formatDate
				firstDay: 6, // The first day of the week, Sat = 0, Sun = 1, ...
				isRTL: true // True if right-to-left language, false if left-to-right
			};
			
			$.calendars.calendars.gregorian.prototype.regionalOptions['ar'] = {
				name: 'Gregorian',
				epochs: ['BCE', 'CE'],
				monthNames: ['يناير', 'فبراير', 'مارس', 'أبريل ', 'مايو','يونيو', 'يوليو ',
						'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'],				
				monthNamesShort: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
				dayNames: ['الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'],
				dayNamesShort: ['الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'],
				dayNamesMin: ['الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'],
				digits: $.calendars.substituteDigits(['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩']),
				dateFormat: 'dd/mm/yyyy',
				firstDay: 6,
				isRTL: true
			};
			$.calendarsPicker.regionalOptions['ar'] = {
				renderer: $.calendarsPicker.defaultRenderer,
				prevText: '&#x3c;السابق', prevStatus: 'عرض الشهر السابق',
				prevJumpText: '&#x3c;&#x3c;', prevJumpStatus: '',
				nextText: 'التالي&#x3e;', nextStatus: 'عرض الشهر القادم',
				nextJumpText: '&#x3e;&#x3e;', nextJumpStatus: '',
				currentText: 'اليوم', currentStatus: 'عرض الشهر الحالي',
				todayText: 'اليوم', todayStatus: 'عرض الشهر الحالي',
				clearText: 'مسح', clearStatus: 'امسح التاريخ الحالي',
				closeText: 'إغلاق', closeStatus: 'إغلاق بدون حفظ',
				yearStatus: 'عرض سنة آخرى', monthStatus: 'عرض شهر آخر',
				weekText: 'أسبوع', weekStatus: 'أسبوع السنة',
				dayStatus: 'اختر D, M d', defaultStatus: 'اختر يوم',
				isRTL: true
			};
			$.calendarsPicker.setDefaults($.calendarsPicker.regionalOptions['ar']);
		})(jQuery);
	}
	}catch(e){alert(e);}


	if(inputHijri != null)
	{
		hijri_input = inputHijri;
	}
	if(inputGreg != null)
	{
		gregorian_input = inputGreg;
	}
	if(resourcesUrlPrefixx != null)
	{
		resourcesUrlPrefix = resourcesUrlPrefixx;
	}
	vargregorian_input = $('#'+gregorian_input);
	varhijri_input = $('#'+hijri_input);
// initi
	calendar = $.calendars.instance('ummalqura','ar');
	calendar_gregorian = $.calendars.instance('gregorian','ar');	

	if(!isHijriOutputOnly)
	{
	var date = calendar.today();
	var maxYearsNum = parseInt(maxDate.split('y')[0]);
	var minYearsNum = parseInt(minDate.split('y')[0]);
	var yearRangemax =date.newDate().add(maxYearsNum, "y").year();
	var yearRangemin =date.newDate().add(minYearsNum,"y").year();
	var rangeStr = yearRangemin+":"+yearRangemax;
	$('#'+hijri_input).calendarsPicker();
	var current = $('#'+hijri_input).calendarsPicker('options', 'all');
	$('#'+hijri_input).calendarsPicker('option', {minDate:minDate,maxDate:maxDate,yearRange:rangeStr,defaultDate:+0,dateFormat:format,showTrigger: '<img src="'+resourcesUrlPrefix+'calendar-green.gif" alt="Popup" class="trigger">', calendar: calendar, 
	
	onSelect: function(){ 
		try
			{	
		var date = calendar.parseDate(format, $('#'+hijri_input).val()), 
		jdd =  calendar.toJD(date), 
		gregDate = calendar_gregorian.fromJD( jdd);


	$('#'+gregorian_input).val(gregDate.formatDate("dd/mm/yyyy"));

				if(TimeVal == true){
					var T = new Date();
					var Minuts = T.getMinutes(),Hours;
					if(HoursClocks == true){
						if(T.getHours()>=12){
						Hours = T.getHours()-12;
						if(T.getHours() == 12){	Hours = T.getHours();}
						if($("html").attr("lang") == "en"){ am = "PM"; }else{am = "م";}
						}
						else{
						Hours = T.getHours();
						if($("html").attr("lang") == "en"){ am = "AM"; }else{am = "ص";}
						}

					if(Hours <= "9"){Hours = "0"+Hours;}
					if(Minuts <= "9"){Minuts = "0"+Minuts;}
						DefaultTime = " "+Hours+":"+Minuts+" "+am;
					}
					else
					{
					Hours = T.getHours();
					if(Hours <= "9"){Hours = "0"+Hours;}
					if(Minuts <= "9"){Minuts = "0"+Minuts;}
						DefaultTime = " "+Hours+":"+Minuts;
					}
					
				}	
				if($('#'+hijri_input).val().length ==10 || $('#'+gregorian_input).val().length ==10)
				{	
					if(!localStorage.getItem("TimeFlag"))
					{
					$('#'+gregorian_input).val($('#'+gregorian_input).val()+DefaultTime);
					$('#'+hijri_input).val($('#'+hijri_input).val()+DefaultTime);
					}
					else
					{
					$('#'+gregorian_input).val($('#'+gregorian_input).val()+localStorage.getItem("CTime"));
					$('#'+hijri_input).val($('#'+hijri_input).val()+localStorage.getItem("CTime"));
					localStorage.removeItem("TimeFlag");
					localStorage.removeItem("CTime");
					}					
					
				}
	}
			catch(e){}
	}}).calendarsPicker('option', {onSelect: current.onSelect, onChangeMonthYear: current.onChangeMonthYear}); 
	}
	else
	{
	$('#'+hijri_input).attr('disabled','disabled');
	}
	var date2 = calendar_gregorian.today();
	var maxYearsNumGreg = parseInt(maxDate.split('y')[0]);
	var minYearsNumGreg = parseInt(minDate.split('y')[0]);
	var yearRangemaxGreg =date2.newDate().add(maxYearsNumGreg,"y").year();
	var yearRangeminGreg =date2.newDate().add(minYearsNumGreg,"y").year();
	var rangeStrGreg = yearRangeminGreg+":"+yearRangemaxGreg;
	$('#'+gregorian_input).calendarsPicker();
	var current_greg = $('#'+gregorian_input).calendarsPicker('options', 'all'); 
	$('#'+gregorian_input).calendarsPicker('option',
	{
		minDate:minDate,maxDate:maxDate,yearRange:rangeStrGreg,defaultDate:0,dateFormat:format,showTrigger: '<img src="'+resourcesUrlPrefix+'calendar-green.gif" alt="Popup" class="trigger">',
		calendar: calendar_gregorian,onSelect: function()
		{
			try
			{
				var date = calendar_gregorian.parseDate(format, $('#'+gregorian_input).val());
				var jdd =  calendar_gregorian.toJD(date);
				var gregDate = calendar.fromJD( jdd);
				$('#'+hijri_input).val(gregDate.formatDate("dd/mm/yyyy"));
				if(TimeVal == true){
					var T = new Date();
					var Minuts = T.getMinutes(),Hours;
					if(HoursClocks == true){
						if(T.getHours()>=12){
						Hours = T.getHours()-12;
						if(T.getHours() == 12){	Hours = T.getHours();}
						if($("html").attr("lang") == "en"){ am = "PM"; }else{am = "م";}
						}
						else{
						Hours = T.getHours();
						if($("html").attr("lang") == "en"){ am = "AM"; }else{am = "ص";}
						}
					if(Hours <= "9"){Hours = "0"+Hours;}
					if(Minuts <= "9"){Minuts = "0"+Minuts;}
						DefaultTime = " "+Hours+":"+Minuts+" "+am;
					}
					else
					{
					Hours = T.getHours();
					if(Hours <= "9"){Hours = "0"+Hours;}
					if(Minuts <= "9"){Minuts = "0"+Minuts;}
						DefaultTime = " "+Hours+":"+Minuts;
					}
					
				}
				if($('#'+hijri_input).val().length ==10 || $('#'+gregorian_input).val().length ==10)
				{
					if(!localStorage.getItem("TimeFlag"))
					{
					$('#'+hijri_input).val($('#'+hijri_input).val()+DefaultTime);
					$('#'+gregorian_input).val($('#'+gregorian_input).val()+DefaultTime);
					}
					else{
					$('#'+hijri_input).val($('#'+hijri_input).val()+localStorage.getItem("CTime"));
					$('#'+gregorian_input).val($('#'+gregorian_input).val()+localStorage.getItem("CTime"));	
					localStorage.removeItem("TimeFlag");
					localStorage.removeItem("CTime");
						
					}
					
				}
			}
			catch(e){}
		} 
    }).calendarsPicker('option',{onSelect: current_greg.onSelect,onChangeMonthYear: current_greg.onChangeMonthYear}); 
}    
}
}