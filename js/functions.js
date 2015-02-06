/**
 * Mega Menu
 */
/*jQuery(function ($) {
    function hoverIn() {
        var a = jQuery(this);
        var nav = a.closest('.nav-menu');
        var mega = a.find('.mega-menu');
        var offset = rightSide(nav) - leftSide(a);
        		
		mega.width(Math.min(rightSide(nav), columns(mega)*240));
       	mega.css('left', Math.min(0, offset - mega.width()+100));
		
		console.log(columns(mega));
    }
    function hoverOut() {
    }
    function columns(mega) {
        var columns = 0;
        mega.children('.mega-menu-row').each(function () {
            columns = Math.max(columns, jQuery(this).children('.menu-item').length);
        });
        return columns;
    }
    function leftSide(elem) {
        return elem.offset().left;
    }
    function rightSide(elem) {
        return elem.offset().left + elem.outerWidth();
    }
    jQuery('.menu-item-has-mega-menu').hover(hoverIn, hoverOut);
	
	jQuery('.blog-masonry').isotope({
		itemSelector: '.post-item',
		masonry: {
		  columnWidth: '.post-item'
		}
	});
});*/

// Enable Fixed Menu Through jQuery
jQuery(function( jQuery ) {
	var starting_position = jQuery('.ult-fixed-menu').outerHeight( true );
	jQuery(window).scroll(function() {
		var yPos = ( jQuery(window).scrollTop() );
		if( yPos > starting_position && window.innerWidth > 768 ) { 
			jQuery(".ult-fixed-menu").addClass("ult-sticky-menu");
		} else {
			jQuery(".ult-fixed-menu").removeClass("ult-sticky-menu");
		}
	});
});

// Aissign Top Padding When Transparent Menu Is Set
jQuery(document).ready(function() {
	var header_height = jQuery('.ult-fixed-menu').outerHeight( true );
	if( window.innerWidth > 768 ) {
		jQuery("body #main").css('padding-top',header_height);
		jQuery(".ultimate-page-header").css('padding-top',header_height);
	}
	else {
		jQuery("body #main").css('padding-top',0);
		jQuery(".ultimate-page-header").css('padding-top',0);
	}
});
jQuery(window).on('resize',function() {
	var header_height = jQuery('.ult-fixed-menu').outerHeight( true );
	if( window.innerWidth > 768 ) {
		jQuery("body #main").css('padding-top',header_height);
		jQuery(".ultimate-page-header").css('padding-top',header_height);
	}
	else {
		jQuery("body #main").css('padding-top',0);
		jQuery(".ultimate-page-header").css('padding-top',0);
	}
});


// Arrange Website Name & Description, Vertically Center
/*
jQuery(document).ready(function() {
	var header_height1 = jQuery('.ult-main-menu-container').outerHeight( true );
	if( window.innerWidth > 768 ) {
		jQuery(".site-title").css('line-height',header_height1 + "px");
	}
	else {
		jQuery(".site-title").css('line-height',0);
	}
});
jQuery(window).on('resize',function() {
	var header_height1 = jQuery('.ult-main-menu-container').outerHeight( true );
	if( window.innerWidth > 768 ) {
		jQuery(".site-title").css('line-height',header_height1 + "px");
	}
	else {
		jQuery(".site-title").css('line-height',0);
	}
});
*/


// Assign Browser Width to Row - If Front Page Widget Area has Featured Image
jQuery(document).ready(function() {
	var browser_width = jQuery('#page').outerWidth( true );
	var front_widget_offset = jQuery("#content").offset();
	var front_widget_styles = {
      "width": browser_width,
      "left": - front_widget_offset.left,
    };
	jQuery(".widget-thumbnail").css( front_widget_styles );
});
jQuery(window).on('resize',function() {
	var browser_width = jQuery('#page').outerWidth( true );
	var front_widget_offset = jQuery("#content").offset();
	var front_widget_styles = {
      "width": browser_width,
      "left": - front_widget_offset.left,
    };
	jQuery(".widget-thumbnail").css( front_widget_styles );
});


// Random Class
var colorClasses = ['blueviolet', 'aliceblue', 'antiquewhite', 'aquamarine', 'beige', 'turquoise', 'thistle', 'skyblue'];
jQuery(".blog-masonry .post").each(function(e){
    classIndex = Math.floor(Math.random() * colorClasses.length);
    jQuery(this).addClass(colorClasses[classIndex]);
});

