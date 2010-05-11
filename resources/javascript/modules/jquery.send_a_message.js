jQuery.createLightbox = function() {
	$("#messages_compose_message_button").colorbox({ inline:true, href:"#send_a_message_container", width:"50%", maxHeight:"75%"});
	
	var wire = function(){ $("#send_a_message_to").autoSuggest("../json/UsersAutoSuggestModule", {minChars: 2, matchCase: false,
				extraParams: "/lookupByUsername"}); };
	
	$(document).bind("cbox_complete", wire);
	
	delete wire;
};

$(document).ready(jQuery.createLightbox);

$(function(){
		$("ul.tabs").tabs("div.panes > div");
});