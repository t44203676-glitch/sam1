var format = "dd/mm/yyyy";
var maxDatecal = 0;
var minDatecal = 0;
var resourcesUrlPrefix='', TimeVal,	varhijri_input = new Array(), TimeValstatus, HoursClocks;
var mainelement='', MainInputvalue,maininputhijri, inputsplithijri,onlytime;
function initCalendarssingle(inputHijri,resourcesUrlPrefixx,locale,isHijri,formatp,minDate,maxDate, times, timesingle, HoursClock)
{
var calendar;
var calendar_gregorian;
var hijri_input ='hijri_input' ;
var gregorian_input = 'gregorian_input';
var TimeVal1 = times=="time";
TimeValstatus = timesingle==true;
if(TimeValstatus == true)
{
$("#"+inputHijri).attr("rel", "timesingle");
}

if(TimeVal1 == true)
{
$("#"+inputHijri).attr("data", true);
}

$("#"+inputHijri).attr("title", HoursClock);



var DefaultTime = '', Hours='', am='';
	if(formatp!=null)
	{
		format = formatp;
	}
	//try{
	if(locale!= null && locale == 'ar')
	{
		(function($)
		{
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
	//}catch(e){alert(e);}
	if(inputHijri != null)
	{
		hijri_input = inputHijri;
	}
	if(resourcesUrlPrefixx != null)
	{
		if(timesingle == true)
		{
			resourcesUrlPrefix = resourcesUrlPrefixx+"time.png";
		}
		else
		{
			resourcesUrlPrefix = resourcesUrlPrefixx+"calendar-green.gif";
		}
		
	}
//varhijri_input.push($('#'+hijri_input));

	if(isHijri)
	{
		calendar = $.calendars.instance('ummalqura','ar');
		var date = calendar.today();
		maxDatecal = maxDate.split("");
		minDatecal = minDate.split("");
		var maxYearsNum = parseInt(maxDate.split(maxDatecal[maxDatecal.length-1])[0]);
		var minYearsNum = parseInt(minDate.split(maxDatecal[maxDatecal.length-1])[0]);
		var yearRangemax =date.newDate().add(maxYearsNum, maxDatecal[maxDatecal.length-1]).year();
		var yearRangemin =date.newDate().add(minYearsNum, maxDatecal[maxDatecal.length-1]).year();
		var rangeStr = yearRangemin+":"+yearRangemax;
		$('#'+hijri_input).calendarsPicker();
		 var current = $('#'+hijri_input).calendarsPicker('options', 'all');
		 $('#'+hijri_input).calendarsPicker('option', {minDate:minDate,maxDate:maxDate,yearRange:rangeStr,defaultDate:+0,dateFormat:format,showTrigger: '<img src="'+resourcesUrlPrefix+'" alt="Popup" class="trigger">',
		 calendar: calendar,onSelect: function(){


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
				if(Boolean($('#'+maininputhijri).val().length > 5) == true)
				{	
					if(!localStorage.getItem("TimeFlag"))
					{
					$('#'+maininputhijri).val($('#'+maininputhijri).val()+DefaultTime);
					localStorage.removeItem("CTime");
					}
					else
					{
					$('#'+maininputhijri).val($('#'+maininputhijri).val()+localStorage.getItem("CTime"));
					localStorage.removeItem("TimeFlag");
					localStorage.removeItem("CTime");
					}					
					
				}		 	
		 }}).calendarsPicker('option',{onSelect: current.onSelect, onChangeMonthYear: current.onChangeMonthYear});
					
	}
	else
	{
		calendar_gregorian = $.calendars.instance('gregorian','ar');
		var date2 = calendar_gregorian.today();
		maxDatecal = maxDate.split("");
		minDatecal = minDate.split("");
		var maxYearsNumGreg = parseInt(maxDate.split(maxDatecal[maxDatecal.length-1])[0]);
		var minYearsNumGreg = parseInt(minDate.split(minDatecal[minDatecal.length-1])[0]);
		var yearRangemaxGreg =date2.newDate().add(maxYearsNumGreg,maxDatecal[maxDatecal.length-1]).year();
		var yearRangeminGreg =date2.newDate().add(minYearsNumGreg,minDatecal[minDatecal.length-1]).year();
		var rangeStrGreg = yearRangeminGreg+":"+yearRangemaxGreg;
		$('#'+inputHijri).calendarsPicker();
		 var current_greg = $('#'+inputHijri).calendarsPicker('options', 'all');
		 $('#'+inputHijri).calendarsPicker('option', {minDate:minDate,maxDate:maxDate,yearRange:rangeStrGreg,defaultDate:0,dateFormat:format,showTrigger: '<img src="'+resourcesUrlPrefix+'" alt="Popup" class="trigger">',
		 calendar: calendar_gregorian,onSelect: function(){

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
				if(Boolean($('#'+maininputhijri).val().length >5) == true)
				{	
					if(!localStorage.getItem("TimeFlag"))
					{
					$('#'+maininputhijri).val($('#'+maininputhijri).val()+DefaultTime);
					localStorage.removeItem("CTime");
					}
					else
					{
					$('#'+maininputhijri).val($('#'+maininputhijri).val()+localStorage.getItem("CTime"));
					localStorage.removeItem("TimeFlag");
					localStorage.removeItem("CTime");
					}					
					
				}

		 }}).calendarsPicker('option',{onSelect: current_greg.onSelect, onChangeMonthYear: current_greg.onChangeMonthYear});
	}
}