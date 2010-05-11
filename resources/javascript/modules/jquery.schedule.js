jQuery.createLightbox = function() {
	$("#propose_shift_trade_trigger").colorbox({inline:true, href:"#shift_trade_request_container", width:"50%"});
	$("#view_proposed_shift_trades_trigger").colorbox({ width:"50%", maxHeight:"75%"});
	$("#request_time_off_trigger").colorbox({ width:"50%", maxHeight:"75%"});
	
	/***
	var toggle_lightbox = function(event){
      // $(event.data.sender).toggleClass("hide");
		if(("#" + this.id) == event.data.sender){
			$(this).toggleClass("hide");
		}
	};
	
	$("#shift_trade_request_container").bind('cbox_load', { sender: "#shift_trade_request_container" }, toggle_lightbox);
	$("#shift_trade_request_container").bind('cbox_cleanup', { sender: "#shift_trade_request_container" }, toggle_lightbox);
	
	delete toggle_lightbox;
	**/
};

//$(document).ready(jQuery.createVisualQueue);
$(document).ready(jQuery.createLightbox);