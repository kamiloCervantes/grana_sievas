$(document).ready(function(){

	
    
    if (jQuery().accordion) {
	    $(".toggle").each( function () {
	    	if($(this).attr('data-id') == 'closed') {
	    		$(this).accordion({ header: 'h4', collapsible: true, active: false  });
	    	} else {
	    		$(this).accordion({ header: 'h4', collapsible: true});
	    	}
	    });
    }
    
    if (jQuery().tabs) {
    	jQuery("#tabs").tabs({ fx: { opacity: 'show' } });
    	jQuery(".tabs").tabs({ fx: { opacity: 'show' } });
    }
	    
});