createLightbox = function() {
	$("ul.tabs").tabs("div.panes > div");
	$("#messages_compose_message_button").colorbox({ inline:true, href:"#send_a_message_container", maxWidth:"75%", height:"75%"});
	
	var wire = function(){ $("#send_a_message_to").autoSuggest("../json/UsersAutoSuggestModule", {minChars: 2, matchCase: false,
				extraParams: "/lookupByUsername"}); };
	
	$(document).bind("cbox_complete", wire);
	
	var click = function(event){
		event.preventDefault();
		var form = $("#send_a_message_form");
		var url = form.attr("action");
		var data =	form.serialize();
		var success = function(data,text,xml){
			$("#messages").fadeOut("slow");
			$("#messages").load("../ajax/MessagesModule #messages");
			//$("ul.tabs").tabs("div.panes > div");
			$("#messages").fadeIn("slow");
			$.fn.colorbox.close();
		};
		
		$.ajax({
			type: "POST",
			url: url,
			data: data,
			success: success});
		delete form;
		delete url;
		delete data;
		delete success;
	};
	$("#send_a_message_button").bind('click', click);
//	$.ajax({
//		  type: 'POST',
//		  url: url,
//		  data: data,
//		  success: success
//		  dataType: dataType
//		});

	delete wire;
	delete click;
};

$(document).ready(createLightbox);