jQuery.medico.calendar = {
		event_date_added: 'medico_calendar_date_added',
		event_date_removed: 'medico_calendar_date_removed',
		event_calendar_reset: 'medico_calendar_reset',
		dates: new Array(),
		
		ready: function() {
			var dates = $(".calendar_date_in_month");
			var length = dates.length;
			var i = 0;
			for(i; i < length; (++i)){
				$(dates[i]).bind('click', $.medico.calendar.click);
			}
			$('#calendar_clear').bind('click', {class:'calendar_date_selected'}, $.medico.calendar.clear);
			delete dates;
			delete length;
			delete i;
		},
		
		click: function(event){
			var target = event.target;
			$(target).toggleClass('calendar_date_selected');
			var index = $.inArray(target, jQuery.medico.calendar.dates);
			if(-1 == index) {
				jQuery.medico.calendar.dates.push(target);
				$(target).trigger(jQuery.medico.calendar.event_date_added);
			} else {
				jQuery.medico.calendar.dates.splice(index,1);
				$(target).trigger(jQuery.medico.calendar.event_date_removed);
				if(0 == jQuery.medico.calendar.dates.length) {
					$(target).trigger(jQuery.medico.calendar.event_calendar_reset);
				}
			}
		},
		
		clear: function(event){
			var targets = $('.' + event.data.class);
			var i = 0;
			var length = targets.length;
			for(i; i < length; (++i)){
				$(targets[i]).removeClass(event.data.class);
			}
			delete targets;
			delete i;
			delete length;
		}
};

jQuery.medico.calendar.requestTimeOff = {
		dates: new Array(),
		month: "",
		form: null,
		
		ready: function(){
			//$("#request_time_off_trigger").colorbox({ width:"50%", maxHeight:"75%"});
			$("#request_time_off_trigger").bind('click', jQuery.medico.calendar.requestTimeOff.click);
			var months = new Array("January", "February", "March", "April","May","June","July","August","September","October","November","December");
			var month = $(".calendar_header").html();
			jQuery.medico.calendar.requestTimeOff.month = ($.inArray(month.split(" ")[0], months) + 1);
			jQuery.medico.calendar.requestTimeOff.year = month.split(" ")[1];
			delete month;
			jQuery.medico.calendar.requestTimeOff.form = $("#day_off_request_message_form");
			$(this).bind(jQuery.medico.calendar.event_calendar_reset, jQuery.medico.calendar.requestTimeOff.reset);
			$(this).bind(jQuery.medico.calendar.event_date_added, jQuery.medico.calendar.requestTimeOff.added);
			$(this).bind(jQuery.medico.calendar.event_date_removed, jQuery.medico.calendar.requestTimeOff.removed);
			$(this).bind("cbox_open", jQuery.medico.calendar.requestTimeOff.cbox_open);
			$("#day_off_request_submit").bind("click", jQuery.medico.calendar.requestTimeOff.ajax_click);
		},
		
		ajax_click: function(event){
			event.preventDefault();
			var success = function(data,text,xml){
				alert("success");
				$.fn.colorbox.close();
			};
			var ajax = new AjaxForm("#day_off_request_message_form", success);
			ajax.submit();
			delete ajax;
			delete success;
		},
		
		click: function(event) {
			event.preventDefault();
			if(0 == jQuery.medico.calendar.dates.length) {
				$("body").animate({scrollTop:0},1000);
				$("#request_time_off_message").toggleClass("hide", false);
				//$(document).mask("#EFEFEF"); // bug, does not work right, will investigate
				$("#request_time_off_message").expose();
			} else {	
			}
		},
		
		added: function(event){
			$("#request_time_off_message").toggleClass("hide", true);
			$("#request_time_off_trigger").colorbox({inline:true, href:"#day_off_request_container", width:"50%", height:"75%"});
			var date = new DateIcon(jQuery.medico.calendar.requestTimeOff.year,
					jQuery.medico.calendar.requestTimeOff.month,
					event.target.firstChild.innerHTML);
			jQuery.medico.calendar.requestTimeOff.dates.push(date);
			delete date;
		},
		
		removed: function(event){
			var target = event.target.firstChild.innerHTML;
			var length = jQuery.medico.calendar.requestTimeOff.dates.length;
			var i = 0;
			for(i; i < length; (++i)) {
				if(jQuery.medico.calendar.requestTimeOff.dates[i].day == target) {
					jQuery.medico.calendar.requestTimeOff.dates.splice(i,1);
				}
			}
			delete target;
			delete length;
			delete i;
		},
		
		cbox_open: function(event){
			var element = $("#day_off_request_icon_container");
			var length = jQuery.medico.calendar.requestTimeOff.dates.length;
			var i = 0;
			var date = null;
			var dateValue = "";
			for(i; i < length; (++i)) {
				date = jQuery.medico.calendar.requestTimeOff.dates[i];
				dateValue += date.date;
				if(i != (length - 1)) {
					dateValue += "|";
				}
				element.append(date.getIcon());
			}
			//var input = $("#day_off_request_days_input");
			if(0 == $("#calendar_dates_data").length) {
				var input = document.createElement("input");
				input.type = "hidden";
				input.name = "dates_data";
				input.id = "calendar_dates_data";
				input.value = dateValue;
				$("#day_off_request_message_form").append(input);
				delete input;
			} else {
				var input = $("#calendar_dates_data");
				input.value = dateValue;
				delete input;
			}
			delete target;
			delete length;
			delete i;
			delete date;
		},
		
		reset: function(event){
			//$.fn.colorbox.suppress(true);
		}
};

(function($){
	/**
	$(document).ready(function(){
		var shiftTrade = new ShiftTrade();
		shiftTrade.ready();
		delete shiftTrade;
	});
	**/

	$(document).ready($.medico.calendar.ready);
	$(document).ready($.medico.calendar.requestTimeOff.ready);
})(jQuery);