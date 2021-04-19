jQuery(document).ready(function() {
	/* Accordion Portfolio */
	$( ".accordion" ).accordion({ header: '.modern-title', autoHeight: false });
	/* Portfolio */
	var enable_image_hover = function(image){
		if(image.is(".portfolio")){
			image.hover(function(){
					jQuery(".image_overlay",this).css("visibility", "visible");
				},function(){
					jQuery(".image_overlay",this).css("visibility", "hidden");
				}).children('img').after('<span class="image_overlay"></span>');
			}else{
				image.hover(function(){
					jQuery(".image_overlay",this).animate({
						opacity: '1'
					},"fast");
				},function(){
					jQuery(".image_overlay",this).animate({
						opacity: '0'
					},"fast");
			})
			.children('img').after(jQuery('<span class="image_overlay"></span>').css({opacity: '0',visibility:'visible'}));
		}	
	}
	
	jQuery('#portfolio-control a').each( 
		function () {
			jQuery(this).click(
				function(e) {
					jQuery('#portfolio-control a').removeClass('active');
					jQuery(this).addClass('active');
					e.preventDefault();
				}
			);

		}
	);
	$('.post-thumb img').hover(function(){
		var image = jQuery(this).closest('.image_frame').removeClass('preloading').children("a");
		enable_image_hover(image);
		
	});
	$('.accordion img').hover(function(){	
		$(this).addClass('currentitem');
		$('.currentitem').stop().animate({ opacity: 0.7 }, 300);	
	}, function(){
		 $('.currentitem').stop().animate({ opacity: 1 }, 300);
		 $('.currentitem').removeClass('currentitem');
	});
	(function($) {
		$.fn.sorted = function(customOptions) {
			var options = {
				reversed: false,
				by: function(a) {
					return a.text();
				}
			};
			$.extend(options, customOptions);
		
			$data = $(this);
			arr = $data.get();
			arr.sort(function(a, b) {
				
				var valA = options.by($(a));
				var valB = options.by($(b));
				if (options.reversed) {
					return (valA < valB) ? 1 : (valA > valB) ? -1 : 0;				
				} else {		
					return (valA < valB) ? -1 : (valA > valB) ? 1 : 0;	
				}
			});
			return $(arr);
		};
	
	})(jQuery);

	$(function() {
	  var read_button = function(class_names) {
		var r = {
		  selected: false,
		  type: 0
		};
		for (var i=0; i < class_names.length; i++) {
		  if (class_names[i].indexOf('selected-') == 0) {
			r.selected = true;
		  }
		  if (class_names[i].indexOf('segment-') == 0) {
			r.segment = class_names[i].split('-')[1];
		  }
		};
		return r;
	  };
	  
	  var determine_sort = function($buttons) {
		var $selected = $buttons.parent().filter('[class*="selected-"]');
		return $selected.find('a').attr('data-value');
	  };
	  
	  var determine_kind = function($buttons) {
		var $selected = $buttons.parent().filter('[class*="selected-"]');
		return $selected.find('a').attr('data-value');
	  };
	  
	  var $preferences = {
		duration: 600,
		easing: 'easeInOutQuad',
		adjustHeight: 'dynamic'
	  };
	  
	  var $list = $('#columns');
	  var $data = $list.clone();
	  
	  var $controls = $('#portfolio-control');
	  
	  $controls.each(function(i) {
		
		var $control = $(this);
		var $buttons = $control.find('a');
		
		$buttons.bind('click', function(e) {
		  
		  var $button = $(this);
		  var $button_container = $button.parent();
		  var button_properties = read_button($button_container.attr('class').split(' '));      
		  var selected = button_properties.selected;
		  var button_segment = button_properties.segment;
	
		  if (!selected) {
	
			$buttons.parent().removeClass();
			$button_container.addClass('selected-' + button_segment);
			
			var sorting_type = determine_sort($controls.eq(1).find('a'));
			var sorting_kind = determine_kind($controls.eq(0).find('a'));
			
			if (sorting_kind == 'all') {
			  var $filtered_data = $data.find('li');
			} else {
			  var $filtered_data = $data.find('li.' + sorting_kind);
			}
			
			var $sorted_data = $filtered_data.sorted({
				by: function(v) {
					return $(v).find('strong').text().toLowerCase();
				}
			});
			
			$list.quicksand($sorted_data, $preferences, function () {
				$('.post-thumb img').hover(function(){
					var image = jQuery(this).closest('.image_frame').removeClass('preloading').children("a");
					enable_image_hover(image);
					
				});
				$("a[rel^='prettyPhoto']").prettyPhoto({ "slideshow": 5000, "overlay_gallery": false, "deeplinking": false, "show_title": false });
			});
			
		  }
		  
		  e.preventDefault();
		});
		
	  }); 
	
	});
	jQuery(".classic").preloader({
		delay:200,
		imgSelector:'.post-thumb img',
		beforeShow:function(){
			jQuery(this).closest('.image_frame').addClass('preloading');
		},
		afterShow:function(){
			var image = jQuery(this).closest('.image_frame').removeClass('preloading').children("a");
			enable_image_hover(image);
		}
	});
});

/* Preloader */
(function($) {

	$.fn.preloader = function(options) {
		var settings = $.extend({}, $.fn.preloader.defaults, options);


		return this.each(function() {
			settings.beforeShowAll.call(this);
			var imageHolder = $(this);
			
			var images = imageHolder.find(settings.imgSelector).css({opacity:0, visibility:'hidden'});	
			var count = images.length;
			var showImage = function(image,imageHolder){
				if(image.data.source != undefined){
					imageHolder = image.data.holder;
					image = image.data.source;	
				};
				
				count --;
				if(settings.delay <= 0){
					image.css('visibility','visible').animate({opacity:1}, settings.animSpeed, function(){settings.afterShow.call(this)});
				}
				if(count == 0){
					imageHolder.removeData('count');
					if(settings.delay <= 0){
						settings.afterShowAll.call(this);
					}else{
						if(settings.gradualDelay){
							images.each(function(i,e){
								var image = $(this);
								setTimeout(function(){
									image.css('visibility','visible').animate({opacity:1}, settings.animSpeed, function(){settings.afterShow.call(this)});
								},settings.delay*(i+1));
							});
							setTimeout(function(){settings.afterShowAll.call(imageHolder[0])}, settings.delay*images.length+settings.animSpeed);
						}else{
							setTimeout(function(){
								images.each(function(i,e){
									$(this).css('visibility','visible').animate({opacity:1}, settings.animSpeed, function(){settings.afterShow.call(this)});
								});
								setTimeout(function(){settings.afterShowAll.call(imageHolder[0])}, settings.animSpeed);
							}, settings.delay);
						}
					}
				}
			};
			

			images.each(function(i){
				settings.beforeShow.call(this);
				
				image = $(this);
				
				if(this.complete==true){
					showImage(image,imageHolder);
				}else{
					image.bind('error load',{source:image,holder:imageHolder}, showImage);
					if($.browser.opera){
						image.trigger("load");//for hidden image
					}
				}
			});
		});
	};


	//Default settings
	$.fn.preloader.defaults = {
		delay:1000,
		gradualDelay:true,
		imgSelector:'.portfolio',
		animSpeed:500,
		beforeShowAll: function(){},
		beforeShow: function(){},
		afterShow: function(){},
		afterShowAll: function(){}
	};
})(jQuery);