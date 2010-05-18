// application js for iScheduler
jQuery.medico = {};
Medico = {};
function AjaxForm(formId, success) {
	if(!(this instanceof AjaxForm)) {
		return new AjaxForm(formId,callback);
	}
	if("string" == typeof formId) {
		this.form = $(formId);
	}
	if("object" == typeof formId) {
		this.form = formId;
	}
	this.url = this.form.attr("action");
	this.method = this.form.attr("method");
	this.success = success;
};

AjaxForm.prototype.submit = function() {
	$.ajax({
		type: this.method,
		url: this.url,
		data: this.form.serialize(),
		success: this.success});
};

function DateIcon(year, month, day) {
	if(!(this instanceof DateIcon)) {
		 return new DateIcon(month, day); 
	}
	
	this.date_container_class = "calendar_icon_block";
	this.date_month_class = "calendar_icon_month";
	this.date_day_class = "calendar_icon_day";
	this.month = (month < 10) ? "0" + month : month;
	this.day = (day < 10) ? "0" + day : day;
	this.year = year;
	this.date = this.year + "-" + this.month + "-" + this.day;
	this.months = new Array("January", "February", "March", "April","May","June","July","August","September","October","November","December");
	
	this.calendar_container = document.createElement("div");
	$(this.calendar_container).addClass(this.date_container_class);
	this.calendar_container.id = "calendar_container_" + this.date;
	
	this.calendar_month = document.createElement("div");
	$(this.calendar_month).addClass(this.date_month_class);
	this.calendar_month.id = "calendar_month_" + this.date;
	this.calendar_month.innerHTML = this.months[this.month - 1];
	this.calendar_container.appendChild(this.calendar_month);
	
	this.calendar_day = document.createElement("div");
	$(this.calendar_day).addClass(this.date_day_class);
	this.calendar_day.id = "calendar_day_" + this.date;
	this.calendar_day.innerHTML = this.day;
	this.calendar_container.appendChild(this.calendar_day);
};

DateIcon.prototype = {
	getIcon: function() {
		return this.calendar_container;
	}
};