/* begin cufon replacer */

Cufon.replace('h1, h2, h3, h4, h5, h6, .dropcap, #midCaption p');
Cufon.replace('.intro_text, .intro_image', { textShadow: '1px 1px #fff'});
Cufon.replace('.page_top_title, .page_top_desc', { textShadow: '1px 1px #444'});

/* end cufon replacer */

var $featured_content = jQuery('#featured #slides'),
	et_featured_slider_auto = jQuery("meta[name=et_featured_slider_auto]").attr('content'),
	et_featured_auto_speed = jQuery("meta[name=et_featured_auto_speed]").attr('content'),
	et_featured_slider_pause = jQuery("meta[name=et_featured_slider_pause]").attr('content');
	
if ($featured_content.length){
	var $featured_controllers = jQuery('#controllers ul li'),
		$featured_controllers_links = $featured_controllers.find('a'),
		$et_active_arrow = jQuery('div#active_item'),
		et_featured_options = {
			timeout: 0,
			speed: 500,
			cleartypeNoBg: true,
			prev:   '#featured a#left-arrow', 
			next:   '#featured a#right-arrow',
			before: function (currSlideElement, nextSlideElement, options, forwardFlag) { 
				var $et_active_slide = jQuery(nextSlideElement),
					et_active_order = $et_active_slide.prevAll().length;
					
				$featured_controllers_links.removeClass('active').eq(et_active_order).addClass('active');
				
				$et_active_arrow.animate({"left": $featured_controllers.eq(et_active_order).find('div').position().left+37}, "slow");
			}
		}
		
	if ( et_featured_slider_auto == 1 ) et_featured_options.timeout = et_featured_auto_speed;
	if ( et_featured_slider_pause == 1 ) et_featured_options.pause = 1;
		
	$featured_content.cycle( et_featured_options );
		
	$featured_controllers_links.click(function(){
		et_ordernumber = jQuery(this).parent().parent('li').prevAll().length;
		$featured_content.cycle( et_ordernumber );
		return false;
	}).hover(function(){
		jQuery(this).fadeTo('fast',.7);
	}, function(){
		jQuery(this).fadeTo('fast',1);
	});
}

jQuery(document).ready(function() {
	
	$("a[rel^='prettyPhoto'], a[href$=jpg], a[href$=png], a[href$=gif], a[href$=jpeg]").prettyPhoto({ "slideshow": 5000, "overlay_gallery": false, "deeplinking": false, "show_title": false });
	$('a[href$="bodytop"]').click( function(s) {
		$.scrollTo( $('#bodytop'), {speed:1000}, {easing:'easeInQuad'} );
		s.preventDefault();
	});
});