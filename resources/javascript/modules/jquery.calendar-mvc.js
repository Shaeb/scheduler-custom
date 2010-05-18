Medico.Calendar = {
	namespace: "Medico.Calendar"
};
Medico.Calendar.Models = {
	namespace: "Medico.Calendar.Models"
};
Medico.Calendar.Views = {
	namespace: "Medico.Calendar.Views"
};
Medico.Calendar.Controllers = {
	namespace: "Medico.Calendar.Controllers"
};

jQuery.medico.calendar = {
		event_date_added: 'medico_calendar_date_added',
		event_date_removed: 'medico_calendar_date_removed',
		event_calendar_reset: 'medico_calendar_reset',
		dates: new Array(),
		
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

Medico.Calendar.Views.Interface = {
	namespace: Medico.Calendar.Views.namespace + ".Interface",
	event_calendar_date_clicked: "event_calendar_date_clicked",
	events: {
		calendar_date_clicked: "event_calendar_date_clicked"
	},
	classes: {
		date_selected: 'calendar_date_selected',
		date_in_month: 'calendar_date_in_month'
	},
	
	ready: function(){
		var dates = $("." + this.classes.date_in_month);
		var length = dates.length;
		var i = 0;
		for(i; i < length; (++i)){
			$(dates[i]).bind('click', $.proxy(Medico.Calendar.Views.Interface.click, Medico.Calendar.Views.Interface));
		}
		//$('#calendar_clear').bind('click', {class:'calendar_date_selected'}, $.medico.calendar.clear);
		delete dates;
		delete length;
		delete i;
	},
	
	click: function(event){
		var target = event.target;
		var added = $(target).hasClass(this.classes.date_selected);
		$(target).toggleClass(this.classes.date_selected);
		var trigger = jQuery.Event(this.events.calendar_date_clicked);
		trigger.element = target;
		trigger.sender = this.namespace;
		trigger.wasAdded = !added;
		$(this).trigger(trigger);
		delete target;
		delete trigger;
		delete added;
	}
};

Medico.Calendar.Views.RequestTimeOff = {
	namespace: "Medico.Calendar.Views.RequestTimeOff",
	dateList: null,
	form: null,
	messageElement: null,
	messageResultMessage: "Your time off request has been sent to management.  Once approved or reject, you will be automatically notified.",
	messageResultElement: null,
	linkTrigger: null,
	modal: "",
	input: null,
	dateIconContainer: null,
	
	ready: function(){
		this.dateList = Medico.Calendar.Controllers.DateIconList;
		this.messageElement = $("#request_time_off_message");
		this.form = $("#day_off_request_message_form");
		this.linkTrigger = $("#request_time_off_trigger");
		this.modal = "#day_off_request_container";
		this.dateIconContainer = $("#day_off_request_icon_container");
	
		this.input = document.createElement("input");
		this.input.type = "hidden";
		this.input.name = "dates_data";
		this.input.id = "calendar_dates_data";
		$(this.form).append(this.input);
		
		this.messageResultElement = document.createElement("div");
		this.messageResultElement.id = "request_time_off_message_result";
		$(this.messageResultElement).hide();
		$(this.messageResultElement).addClass("greyBorders");
		this.messageResultElement.innerHTML = this.messageResultMessage;
		$("#dynamic_instructional_text").append(this.messageResultElement);
		
		$(this.linkTrigger).bind('click', $.proxy(this.click, this));
		$(this).bind("cbox_open", $.proxy(this.cbox_open, this));
		$("#day_off_request_submit").bind("click", $.proxy(this.ajax_click, this));
		$(Medico.Calendar.Controllers.DateIconList).bind(
				Medico.Calendar.Controllers.DateIconList.events.date_updated,
				$.proxy(this.date_updated, this));
	},

	ajax_click: function(event){
		event.preventDefault();
		var ajax = new AjaxForm("#" + this.form.attr("id"), $.proxy(this.ajax_success, this));
		ajax.submit();
		delete ajax;
	},
	
	ajax_success: function(data,text,xml){
		$.fn.colorbox.close();
		if("success" == text) {
			// later on: will flash a message sent messages
			$("body").animate({scrollTop:0},1000);
			$(this.messageResultElement).show("slow");
			$(this.messageResultElement).expose();
			$(this.messageResultElement).effect("highlight", {}, 3000);
		}
	},
	
	cbox_open: function(event){
	},
	
	click: function(event){
		event.preventDefault();
		if(0 == this.dateList.dates.length) {
			this.revealInstructions();
		} else {
			this.prepareAndDisplay();
		}
	},
	
	date_updated: function(event){
//		alert(event.list.length);
	},
	
	// specific interface functionality
	revealInstructions: function(){
		$("body").animate({scrollTop:0},0);
		$(this.messageElement).toggleClass("hide", false);
		//$(document).mask("#EFEFEF"); // bug, does not work right, will investigate
		$(this.messageElement).expose();
		$(this.messageElement).effect("highlight", {}, 4000);
	},
	
	prepareAndDisplay: function(){
		var length = this.dateList.dates.length;
		var i = 0;
		var date = null;
		var dateValue = "";
		for(i; i < length; (++i)) {
			date = this.dateList.dates[i];
			dateValue += date.date;
			if(i != (length - 1)) {
				dateValue += "|";
			}
			$(this.dateIconContainer).append(date.getIcon());
		}
		this.input.value = dateValue;
		$(this.messageElement).hide("slow");
		$.fn.colorbox({ inline:true, href:this.modal, width:"50%", height:"75%", open: true } );
		delete length;
		delete i;
		delete date;
		delete dateValue;
	}
};

Medico.Calendar.Views.ShiftTrade = {
	namespace: "Medico.Calendar.Views.ShiftTrade",
	dateList: null,
	elements: {
		trigger: null,
		instructions: null,
		modal: null,
		tradingIconContainer: null,
		tradingForIconContainer: null,
		inputTrading: null,
		inputTradingFor: null,
		form: null,
		submit: null,
		success: null,
		matchContainer: null
	},
	events: {
	},
	classes: {
	},
	
	ready: function(){
		this.dateList = Medico.Calendar.Controllers.DateIconList;
		this.elements.trigger = $("#propose_shift_trade_trigger");
		this.elements.instructions = $("#shift_trade_message");
		this.elements.modal = $("#shift_trade_request_container");
		this.elements.tradingIconContainer = $("#shift_trade_trading_icon_container");
		this.elements.tradingForIconContainer = $("#shift_trade_trading_for_icon_container");
		this.elements.form = $("#shift_trade_proposal_form");
		this.elements.submit = $("#propose_shift_trade_submit");
		this.elements.success = $("#shift_trade_success_message");
		this.elements.matchContainer = $("#shift_trade_match_container");
		
		var input = document.createElement("input");
		input.type = "hidden";
		input.name = "shift_trade_date_trading";
		input.id = input.name;
		this.elements.inputTrading = input;
		$(this.elements.form).append(this.elements.inputTrading);
		
		input = document.createElement("input");
		input.type = "hidden";
		input.name = "shift_trade_date_trading_for";
		input.id = input.name;
		this.elements.inputTradingFor = input;
		$(this.elements.form).append(this.elements.inputTradingFor);
		
		delete input;

		$(this.elements.tradingIconContainer).css("height", "80px");
		$(this.elements.tradingForIconContainer).css("height", "80px");
		
		$(this.elements.trigger).bind("click", $.proxy(this.click, this));
		$(this.elements.submit).bind("click", $.proxy(this.ajax_click, this));
		$(this).bind("cbox_closed", {interface: this.namespace }, $.proxy(this.cbox_closed, this));
	},
	
	ajax_click: function(event){
		event.preventDefault();
		var ajax = new AjaxForm(this.elements.form, $.proxy(this.ajax_success, this));
		ajax.submit();
		delete ajax;
	},

	ajax_success: function(data,text,xml){
		$.fn.colorbox.close();
		if("success" == text) {
			// later on: will flash a message sent messages
			$("body").animate({scrollTop:0},1000);
			$(this.elements.success).show("slow");
			$(this.elements.success).expose();
			$(this.elements.success).effect("highlight", {}, 3000);
		}
	},
	
	cbox_closed: function(event){
		/*****
		if(event.data.interface == this.namespace ) {
			$(this.elements.tradingIconContainer).html("");
			$(this.elements.tradingForIconContainer).html("");
			$("body").animate({scrollTop:0},1000);
			$("#request_time_off_message_result").show("slow");
			$("#request_time_off_message_result").expose();
			$("#request_time_off_message_result").effect("highlight", {}, 3000);
		}
		*****/
	},
	
	click: function(event){
		event.preventDefault();
		if(0 == this.dateList.dates.length) {
			this.revealInstructions();
		} else {
			if(2 == this.dateList.dates.length) {
				this.prepareAndDisplay();
			}
		}
	},
	
	revealInstructions: function(){
		$("body").animate({scrollTop:0},0);
		$(this.elements.instructions).toggleClass("hide", false);
		//$(document).mask("#EFEFEF"); // bug, does not work right, will investigate
		$(this.elements.instructions).expose();
		$(this.elements.instructions).effect("highlight", {}, 4000);
	},
	
	prepareAndDisplay: function(){
		var length = this.dateList.dates.length;
		if(2 != length) {
			return false;
		}
		$(this.elements.tradingIconContainer).append(this.dateList.dates[0].getIcon());
		$(this.elements.tradingForIconContainer).append(this.dateList.dates[1].getIcon());
		$(this.elements.instructions).hide("slow");
		$.fn.colorbox({ inline:true, href:this.elements.modal, width:"50%", height:"75%", open: true } );
		
		this.elements.inputTrading.value = this.dateList.dates[0].date;
		this.elements.inputTradingFor.value = this.dateList.dates[1].date;
		
		$.ajax({
			type: "POST",
			url: "../ajax/FindUsersShiftTradeModule",
			data: {
				shift_trade_date_trading: this.elements.inputTrading.value,
				shift_trade_date_trading_for: this.elements.inputTradingFor.value,
				format: "RAW"
			},
			dataType: "html",
			context: this,
			success: this.loadMatchList});
		
		delete length;
	},
	
	loadMatchList: function(data, text, xml){
		$(this.elements.matchContainer).html(data);
	}
};

Medico.Calendar.Views.ViewShiftTrades = {
	namespace: "Medico.Calendar.Views.ViewShiftTrades",
	elements: {
		trigger: null,
		modal: null,
		submitButtons: null,
		message: null
	},
	ready: function(){
		this.elements.trigger = $("#view_proposed_shift_trades_trigger");
		this.elements.modal = $("#view_proposed_trades_container");
		this.elements.submitButtons = $(".shift_trade_buttons");
		this.elements.message = $("#shift_trade_proposal_success_message");
		
		$(this.elements.trigger).bind("click", $.proxy(this.click, this));
		$(this.elements.submitButtons).bind("click", $.proxy(this.submit, this));
	},
	click: function(event){
		event.preventDefault();
		$.fn.colorbox({ inline:true, href:this.elements.modal, width:"50%", height:"75%", open: true } );
	},
	submit: function(event){
		event.preventDefault();
		var form = $(event.target).parents("form");
		var input = $(form).children("input[name|=shift_trade_action]")[0];
		input.value = event.target.name;
		var ajax = new AjaxForm(form, $.proxy(this.ajax_success, this));
		ajax.submit();
		delete form;
		delete input;
		delete ajax;
	},
	
	ajax_success: function(data,text,xml){
		$.fn.colorbox.close();
		if("success" == text) {
			// later on: will flash a message sent messages
			$("body").animate({scrollTop:0},1000);
			$(this.elements.message).show("slow");
			$(this.elements.message).expose();
			$(this.elements.message).effect("highlight", {}, 3000);
		}
	}
};

Medico.Calendar.Controllers.DateIconList = {
	namespace: Medico.Calendar.Controllers.namespace + ".DateIconList",
	event_date_added: "event_date_added",
	events: {
		date_updated: "event_date_updated"
	},
	dates: new Array(),
	year: "",
	month: "",
	day: "",
	months: new Array("January", "February", "March", "April","May","June","July","August","September","October","November","December"),
	event_updated: "event_updated",
	
	ready: function(){
		var month = $(".calendar_header").html();
		this.month = ($.inArray(month.split(" ")[0], this.months) + 1);
		this.year = month.split(" ")[1];
		delete month;
		$(Medico.Calendar.Views.Interface).bind(Medico.Calendar.Views.Interface.events.calendar_date_clicked, $.proxy(this.click_add, this));
	},
	
	click_add: function(event){
		if(event.wasAdded) {
			this.dates.push(new DateIcon(this.year, this.month, event.element.firstChild.innerHTML));
			this.trigger_updated();
		} else {
			this.click_remove(event);
		}
	},
	
	click_remove: function(event){
		var day = event.element.firstChild.innerHTML;
		var length = this.dates.length;
		var i = 0;
		
		for(i; i < length; (++i)) {
			if(day == this.dates[i].day) {
				this.dates.splice(i, 1);
				this.trigger_updated();
			}
		}
		
	},
	
	trigger_updated: function(){
		var event = jQuery.Event(this.events.date_updated);
		event.list = this.dates;
		$(this).trigger(event);
		delete event;
	}
};

(function($){
	$(document).ready($.proxy(Medico.Calendar.Views.ViewShiftTrades.ready, Medico.Calendar.Views.ViewShiftTrades));
	$(document).ready($.proxy(Medico.Calendar.Views.RequestTimeOff.ready, Medico.Calendar.Views.RequestTimeOff));
	$(document).ready($.proxy(Medico.Calendar.Views.ShiftTrade.ready, Medico.Calendar.Views.ShiftTrade));
	$(document).ready($.proxy(Medico.Calendar.Views.Interface.ready, Medico.Calendar.Views.Interface));
	$(document).ready($.proxy(Medico.Calendar.Controllers.DateIconList.ready, Medico.Calendar.Controllers.DateIconList));
})(jQuery);