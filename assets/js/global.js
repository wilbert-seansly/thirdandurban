/* global twentyseventeenScreenReaderText */
(function( $ ) {

	// Load things we like randomly
	var bsc_tiles_arr = jQuery.parseJSON(bsc_tiles);
	$('.unloadedThing').each(function() {
		var rand = Math.floor(Math.random() * bsc_tiles_arr.length);

		$(this).attr('style', 'background-image:url('+bsc_tiles_arr[rand]['image']+')');
		$(this).find('.block-link').attr('href', bsc_tiles_arr[rand]['link']);
		$(this).find('.title').html(bsc_tiles_arr[rand]['title']);
		$(this).removeClass('unloadedThing');

		// make sure no duplicates
		bsc_tiles_arr = arrayRemove(bsc_tiles_arr, bsc_tiles_arr[rand]);
	});

	// Remove value from array
	function arrayRemove(arr, value) {
	   return arr.filter(function(ele){
	       return ele != value;
	   });
	}

	// Variables and DOM Caching.
	var $body = $( 'body' ),
		$customHeader = $body.find( '.custom-header' ),
		$branding = $customHeader.find( '.site-branding' ),
		$navigation = $body.find( '.navigation-top' ),
		$navWrap = $navigation.find( '.wrap' ),
		$navMenuItem = $navigation.find( '.menu-item' ),
		$menuToggle = $navigation.find( '.menu-toggle' ),
		$menuScrollDown = $body.find( '.menu-scroll-down' ),
		$sidebar = $body.find( '#secondary' ),
		$entryContent = $body.find( '.entry-content' ),
		$formatQuote = $body.find( '.format-quote blockquote' ),
		isFrontPage = $body.hasClass( 'twentyseventeen-front-page' ) || $body.hasClass( 'home blog' ),
		navigationFixedClass = 'site-navigation-fixed',
		navigationHeight,
		navigationOuterHeight,
		navPadding,
		navMenuItemHeight,
		idealNavHeight,
		navIsNotTooTall,
		headerOffset,
		menuTop = 0,
		resizeTimer;

	// Ensure the sticky navigation doesn't cover current focused links.
	$( 'a[href], area[href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), button:not([disabled]), iframe, object, embed, [tabindex], [contenteditable]', '.site-content-contain' ).filter( ':visible' ).focus( function() {
		if ( $navigation.hasClass( 'site-navigation-fixed' ) ) {
			var windowScrollTop = $( window ).scrollTop(),
				fixedNavHeight = $navigation.height(),
				itemScrollTop = $( this ).offset().top,
				offsetDiff = itemScrollTop - windowScrollTop;

			// Account for Admin bar.
			if ( $( '#wpadminbar' ).length ) {
				offsetDiff -= $( '#wpadminbar' ).height();
			}

			if ( offsetDiff < fixedNavHeight ) {
				$( window ).scrollTo( itemScrollTop - ( fixedNavHeight + 50 ), 0 );
			}
		}
	});

	// Set properties of navigation.
	function setNavProps() {
		navigationHeight      = $navigation.height();
		navigationOuterHeight = $navigation.outerHeight();
		navPadding            = parseFloat( $navWrap.css( 'padding-top' ) ) * 2;
		navMenuItemHeight     = $navMenuItem.outerHeight() * 2;
		idealNavHeight        = navPadding + navMenuItemHeight;
		navIsNotTooTall       = navigationHeight <= idealNavHeight;
	}

	// Make navigation 'stick'.
	function adjustScrollClass() {

		// Make sure we're not on a mobile screen.
		if ( 'none' === $menuToggle.css( 'display' ) ) {

			// Make sure the nav isn't taller than two rows.
			if ( navIsNotTooTall ) {

				// When there's a custom header image or video, the header offset includes the height of the navigation.
				if ( isFrontPage && ( $body.hasClass( 'has-header-image' ) || $body.hasClass( 'has-header-video' ) ) ) {
					headerOffset = $customHeader.innerHeight() - navigationOuterHeight;
				} else {
					headerOffset = $customHeader.innerHeight();
				}

				// If the scroll is more than the custom header, set the fixed class.
				if ( $( window ).scrollTop() >= headerOffset ) {
					$navigation.addClass( navigationFixedClass );
				} else {
					$navigation.removeClass( navigationFixedClass );
				}

			} else {

				// Remove 'fixed' class if nav is taller than two rows.
				$navigation.removeClass( navigationFixedClass );
			}
		}
	}

	// Set margins of branding in header.
	function adjustHeaderHeight() {
		if ( 'none' === $menuToggle.css( 'display' ) ) {

			// The margin should be applied to different elements on front-page or home vs interior pages.
			if ( isFrontPage ) {
				$branding.css( 'margin-bottom', navigationOuterHeight );
			} else {
				$customHeader.css( 'margin-bottom', navigationOuterHeight );
			}

		} else {
			$customHeader.css( 'margin-bottom', '0' );
			$branding.css( 'margin-bottom', '0' );
		}
	}

	// Set icon for quotes.
	function setQuotesIcon() {
		$( twentyseventeenScreenReaderText.quote ).prependTo( $formatQuote );
	}

	// Add 'below-entry-meta' class to elements.
	function belowEntryMetaClass( param ) {
		var sidebarPos, sidebarPosBottom;

		if ( ! $body.hasClass( 'has-sidebar' ) || (
			$body.hasClass( 'search' ) ||
			$body.hasClass( 'single-attachment' ) ||
			$body.hasClass( 'error404' ) ||
			$body.hasClass( 'twentyseventeen-front-page' )
		) ) {
			return;
		}

		sidebarPos       = $sidebar.offset();
		sidebarPosBottom = sidebarPos.top + ( $sidebar.height() + 28 );

		$entryContent.find( param ).each( function() {
			var $element = $( this ),
				elementPos = $element.offset(),
				elementPosTop = elementPos.top;

			// Add 'below-entry-meta' to elements below the entry meta.
			if ( elementPosTop > sidebarPosBottom ) {
				$element.addClass( 'below-entry-meta' );
			} else {
				$element.removeClass( 'below-entry-meta' );
			}
		});
	}

	/*
	 * Test if inline SVGs are supported.
	 * @link https://github.com/Modernizr/Modernizr/
	 */
	function supportsInlineSVG() {
		var div = document.createElement( 'div' );
		div.innerHTML = '<svg/>';
		return 'http://www.w3.org/2000/svg' === ( 'undefined' !== typeof SVGRect && div.firstChild && div.firstChild.namespaceURI );
	}

	/**
	 * Test if an iOS device.
	*/
	function checkiOS() {
		return /iPad|iPhone|iPod/.test(navigator.userAgent) && ! window.MSStream;
	}

	/*
	 * Test if background-attachment: fixed is supported.
	 * @link http://stackoverflow.com/questions/14115080/detect-support-for-background-attachment-fixed
	 */
	function supportsFixedBackground() {
		var el = document.createElement('div'),
			isSupported;

		try {
			if ( ! ( 'backgroundAttachment' in el.style ) || checkiOS() ) {
				return false;
			}
			el.style.backgroundAttachment = 'fixed';
			isSupported = ( 'fixed' === el.style.backgroundAttachment );
			return isSupported;
		}
		catch (e) {
			return false;
		}
	}

	// Fire on document ready.
	$( document ).ready( function() {

		// If navigation menu is present on page, setNavProps and adjustScrollClass.
		if ( $navigation.length ) {
			setNavProps();
			adjustScrollClass();
		}

		// If 'Scroll Down' arrow in present on page, calculate scroll offset and bind an event handler to the click event.
		if ( $menuScrollDown.length ) {

			if ( $( 'body' ).hasClass( 'admin-bar' ) ) {
				menuTop -= 32;
			}
			if ( $( 'body' ).hasClass( 'blog' ) ) {
				menuTop -= 30; // The div for latest posts has no space above content, add some to account for this.
			}
			if ( ! $navigation.length ) {
				navigationOuterHeight = 0;
			}

			$menuScrollDown.click( function( e ) {
				e.preventDefault();
				$( window ).scrollTo( '#primary', {
					duration: 600,
					offset: { top: menuTop - navigationOuterHeight }
				});
			});
		}

		adjustHeaderHeight();
		setQuotesIcon();
		if ( true === supportsInlineSVG() ) {
			document.documentElement.className = document.documentElement.className.replace( /(\s*)no-svg(\s*)/, '$1svg$2' );
		}

		if ( true === supportsFixedBackground() ) {
			document.documentElement.className += ' background-fixed';
		}
	});

	// If navigation menu is present on page, adjust it on scroll and screen resize.
	if ( $navigation.length ) {

		// On scroll, we want to stick/unstick the navigation.
		$( window ).on( 'scroll', function() {
			adjustScrollClass();
			adjustHeaderHeight();
		});

		// Also want to make sure the navigation is where it should be on resize.
		$( window ).resize( function() {
			setNavProps();
			setTimeout( adjustScrollClass, 500 );
		});
	}

	$( window ).resize( function() {
		clearTimeout( resizeTimer );
		resizeTimer = setTimeout( function() {
			belowEntryMetaClass( 'blockquote.alignleft, blockquote.alignright' );
		}, 300 );
		setTimeout( adjustHeaderHeight, 1000 );
	});

	// Add header video class after the video is loaded.
	$( document ).on( 'wp-custom-header-video-loaded', function() {
		$body.addClass( 'has-header-video' );
	});

	// Images popup
	if ($('.lightbox').length) {
		$('.lightbox').magnificPopup({
			type: 'inline',
			removalDelay: 160,
			mainClass: 'mfp-fade',
			closeBtnInside: true,
			closeOnContentClick: false,
			gallery: {
				enabled: false,
				preload: [0,1] // Will preload 0 - before current, and 1 after the current image
			}
		});
	}

	if ($('.lightbox-w-slider').length) {
		$('.lightbox-w-slider').magnificPopup({
			type: 'inline',
			removalDelay: 160,
			mainClass: 'mfp-fade',
			closeBtnInside: true,
			closeOnContentClick: false,
			gallery: {
				enabled: false
			},
			callbacks: {
		    open: function() {
					$(".lightbox-slider").owlCarousel({
						autoplay: false,
						loop: false,
						items: 1,
						mouseDrag: true,
						nav: true,
						dots: false,
						navElement: 'div',
						navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
					});
		    },
		    close: function() {
		      // Will fire when popup is closed
		    }
		    // e.t.c.
		  }
		});
	}

	// Top Scroll
	$(window).scroll(function(){
		var $browser_width = $(window).width(),
				$browser_height = $(window).height();
		if ($browser_width > 600) {
			$('.fp-bg').each(function () {
				var $offset = ($(this).offset().top + $(this).outerHeight() - $(window).height() + 75);

				if($(window).scrollTop() > $offset) {
					var opacity = 1 / ($(window).scrollTop() - $offset) * 200;
					var transform = "translate3d(" + ("0px,"+(($(window).scrollTop() - $offset)/3+'px')+",0px)");
					$(this).first('div').css({"transform" : transform, "opacity" : opacity});
				} else {
					$(this).css({"transform" : "none", "opacity" : 1});
				}
			});
		} else {
			$('.fp-bg > div:first-child').css({"transform" : "none", "opacity" : 1});
		}
	});

	$(window).on('resize', function(){
		$(window).scroll();
	});

	// Hide things we like on first two hover
	$('.site-gallery .image:first-child, .site-gallery .image:nth-child(2), .site-gallery .image:nth-child(3)').on({
    mouseenter: function () {
			$('#post-things-that-inspire-us').addClass('hideh1');
    },
    mouseleave: function () {
			$('#post-things-that-inspire-us').removeClass('hideh1');
    }
});


	// Smooth Scroll Anchors
	$('.menu-item  a').addClass('continue');
	smoothScroll.init({
		speed: 1000, // Integer. How fast to complete the scroll in milliseconds
		easing: 'easeInOutCubic', // Easing pattern to use
		updateURL: false, // Boolean. Whether or not to update the URL with the anchor hash on scroll
		offset: 0, // Integer. How far to offset the scrolling anchor location in pixels
		selector: '.continue', // Selector for links (must be a valid CSS selector)
	});

	// Tabs
	$('.post-tabs-nav .tab-title').click(function(event) {
		event.preventDefault();
		var target = $(this).attr('data-target');
		if (!$(this).hasClass('active')) {
			$('.post-tabs-nav .tab-title').removeClass('active');
			$(this).addClass('active');
			$('.post-tab').removeClass('active');
			$(target).addClass('active');
		}
	});

	$('.acc-title').click(function(event) {
		event.preventDefault();
		var target = $(this).attr('data-target');
		if (!$(this).hasClass('active')) {
			$('.acc-title').removeClass('active');
			$(this).addClass('active');
			$('.acc-content').slideUp();
			$(target).slideDown();
		}
	});

	$(".owl-carousel-standard").owlCarousel({
		autoplay: false,
		loop: false,
		items: 3,
		mouseDrag: true,
		nav: true,
		dots: false,
		navElement: 'div',
		navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
			responsive : {
	    // breakpoint from 0 up
	    0 : {
				items: 1,
				slideBy: 1
	    },
	    820 : {
				items: 2,
				slideBy: 2
	    },
	    1300 : {
				items: 3,
				slideBy: 3
	    }
		}
	});

	$(".owl-carousel-4x4").owlCarousel({
		autoplay: false,
		loop: false,
		items: 2,
		slideBy: 2,
		mouseDrag: true,
		nav: true,
		dots: true,
		navContainer: '#owl-carousel-4x4-nav',
		dotsContainer: '#owl-carousel-4x4-dots',
		navElement: 'div',
		navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
		responsive : {
		// breakpoint from 0 up
		0 : {
			items: 1,
			slideBy: 1
		},
		400 : {
			items: 2,
			slideBy: 2
		},
		768 : {
			items: 3,
			slideBy: 3
		}
	}
	});

	$('.owl-next-inner').click(function() {
    $(".owl-carousel-4x4").trigger('next.owl.carousel');
	});

	$('.owl-prev-inner').click(function() {
    $(".owl-carousel-4x4").trigger('prev.owl.carousel');
	});

})( jQuery );

// Animate WOW (much wow)
wow = new WOW(
	{
	  boxClass:     'animate',
	  animateClass: 'animated',
	  offset:       20,
	  mobile:       true,
	  live:         true
	}
);
wow.init();

// Load more posts
jQuery(function($) {
var request;
$('.see-more').click(function(event){
	event.preventDefault();
	$('body').addClass('saw-more');

	var button = $(this),
			options = {
			'action': 'loadmore',
			'offset': parseInt(button.attr('data-offset'))
		};

		if(request) {
						request.abort();
				}

	request = $.ajax({ // you can also use $.post here
		url : bscloadmore_params.ajaxurl, // AJAX handler
		data : options,
		type : 'POST',
		beforeSend : function ( xhr ) {
			button.css('pointer-events', 'none');
			button.css('opacity', '.35');
			$('.see-more .text').text( 'LOADING...' );
		},
		success : function( data ){
			if( data ) {
				$('.see-more .text').text( 'SEE MORE NEWS' );
				button.css('pointer-events', 'auto');
				button.css('opacity', '');
				button.attr('data-offset', options.offset + 9);

				$('.entry-posts .post-subpage:last-child').after(data);

				$(window).scroll();

				// if ( bscloadmore_params.current_page == bscloadmore_params.max_page )
				// 	button.remove(); // if last page, remove the button

				// you can also fire the "post-load" event here if you use a plugin that requires it
				// $( document.body ).trigger( 'post-load' );
			} else {
				button.remove(); // if no data, remove the button as well
			}
		}
	});
});

});
