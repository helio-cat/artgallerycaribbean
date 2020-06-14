/*!
	Slimbox v2.05 - The ultimate lightweight Lightbox clone for jQuery
	(c) 2007-2013 Christophe Beyls <http://www.digitalia.be>
	MIT-style license.
*/

(function ($) {

	var win = $(window),
		options, images, activeImage = -1,
		activeURL, prevImage, nextImage, compatibleOverlay, middle, centerWidth, centerHeight,
		ie6 = !window.XMLHttpRequest,
		hiddenElements = [],
		documentElement = document.documentElement,

		preload = {},
		preloadPrev = new Image(),
		preloadNext = new Image(),

		overlay, center, centerInfo, image, sizer, prevLink, nextLink, bottomContainer, bottom, caption, number,

		lbTopContainer, addItem, imginfo, itemId, dl, price;

	$(function () {
		$("body").append(
			$([
				overlay = $('<div id="lbOverlay" />').click(close)[0],
				lbTopContainer = $('<div id="lbTopContainer" />')[0],
				center = $('<div id="lbCenter" />')[0],
				centerInfo = $('<div id="lbCenterInfo" />')[0],
				bottomContainer = $('<div id="lbBottomContainer" />')[0]
			]).css("display", "none")
		);

		image = $('<div id="lbImage" />').appendTo(center).append(
			sizer = $('<div style="position: relative;" />').append([
				prevLink = $('<a id="lbPrevLink" href="#" />').click(previous)[0],
				nextLink = $('<a id="lbNextLink" href="#" />').click(next)[0]
			])[0]
		)[0];

		dl = $('<dl />').appendTo(center)[0];

		bottom = $('<div id="lbBottom" />').appendTo(bottomContainer).append([
			$('<a id="lbCloseLink" href="#" />').click(close)[0],
			caption = $('<div id="lbCaption" />')[0],
			number = $('<div id="lbNumber" />')[0],
			$('<div style="clear: both;" />')[0]
		])[0];

		addItem = $('<a>', {
			html: '<i class="fas fa-shopping-cart"></i> Add',
			title: 'Add to your Shopping Cart',
			href: 'javascript:void(0)',
			click: function () {
				addtocart(itemId, price);
				return false;
			}
		}).appendTo(lbTopContainer)[0];

		imginfo = $('<a>', {
			html: '<i class="fas fa-info-circle"></i> Info',
			title: 'Image Info',
			href: 'javascript:void(0)'
		}).appendTo(lbTopContainer)[0];

		if (jQuery.browser.mobile) {
			$(imginfo).click(function () {
				$(dl).slideToggle();
			});
		} else {
			$(imginfo).mouseenter(function () {
				$(dl).slideDown('slow');
			}).mouseleave(function () {
				$(dl).slideUp('slow');
			});
		}

		closeitem = $('<a>', {
			html: '<i class="fas fa-times"></i> Close',
			title: 'Close',
			href: 'javascript:void(0)',
			click: close
		}).appendTo(lbTopContainer)[0];

	});

	$.slimbox = function (_images, startImage, _options) {
		options = $.extend({
			loop: false, // Allows to navigate between first and last images
			overlayOpacity: 0.8, // 1 is opaque, 0 is completely transparent (change the color in the CSS file)
			overlayFadeDuration: 400, // Duration of the overlay fade-in and fade-out animations (in milliseconds)
			resizeDuration: 400, // Duration of each of the box resize animations (in milliseconds)
			resizeEasing: "swing", // "swing" is jQuery's default easing
			initialWidth: 250, // Initial width of the box (in pixels)
			initialHeight: 250, // Initial height of the box (in pixels)
			imageFadeDuration: 400, // Duration of the image fade-in animation (in milliseconds)
			captionAnimationDuration: 400, // Duration of the caption animation (in milliseconds)
			counterText: "Image {x} of {y}", // Translate or change as you wish, or set it to false to disable counter text for image groups
			closeKeys: [27, 88, 67], // Array of keycodes to close Slimbox, default: Esc (27), 'x' (88), 'c' (67)
			previousKeys: [37, 80], // Array of keycodes to navigate to the previous image, default: Left arrow (37), 'p' (80)
			nextKeys: [39, 78] // Array of keycodes to navigate to the next image, default: Right arrow (39), 'n' (78)
		}, _options);

		if (typeof _images == "string") {
			_images = [
				[_images, startImage]
			];
			startImage = 0;
		}

		$("body").addClass('noScroll');

		middle = win.scrollTop() + (win.height() / 2);
		centerWidth = options.initialWidth;
		centerHeight = options.initialHeight;
		$(center).css({
			top: Math.max(0, middle - (centerHeight / 2)),
			width: centerWidth,
			height: centerHeight,
			marginLeft: -centerWidth / 2
		}).show();
		compatibleOverlay = ie6 || (overlay.currentStyle && (overlay.currentStyle.position != "fixed"));
		if (compatibleOverlay) overlay.style.position = "absolute";
		$(overlay).css("opacity", options.overlayOpacity).fadeIn(options.overlayFadeDuration);
		position();
		setup(1);

		images = _images;
		options.loop = options.loop && (images.length > 1);

		$(lbTopContainer).fadeIn().animate({
			top: 0
		}, {
			duration: 'slow',
			queue: false
		});

		return changeImage(startImage);

	};

	/*
		options:	Optional options object, see jQuery.slimbox()
		linkMapper:	Optional function taking a link DOM element and an index as arguments and returning an array containing 2 elements:
				the image URL and the image caption (may contain HTML)
		linksFilter:	Optional function taking a link DOM element and an index as arguments and returning true if the element is part of
				the image collection that will be shown on click, false if not. "this" refers to the element that was clicked.
				This function must always return true when the DOM element argument is "this".
	*/

	$.fn.slimbox = function (_options, linkMapper, linksFilter) {
		linkMapper = linkMapper || function (el) {
			return [$(el).data('img'), el.title, $(el).data('itemid'), $(el).data('artist'), $(el).data('category'), $(el).data('medium'), $(el).data('dimensions'), $(el).data('price')];
		};

		linksFilter = linksFilter || function () {
			return true;
		};

		var links = this;

		return links.unbind("click").click(function () {
			var link = this,
				startIndex = 0,
				filteredLinks, i = 0,
				length;

			filteredLinks = $.grep(links, function (el, i) {
				return linksFilter.call(link, el, i);
			});

			for (length = filteredLinks.length; i < length; ++i) {
				if (filteredLinks[i] == link) startIndex = i;
				filteredLinks[i] = linkMapper(filteredLinks[i], i);
			}

			return $.slimbox(filteredLinks, startIndex, _options);
		});
	};

	function position() {
		var l = win.scrollLeft(),
			w = win.width();
		$([center, bottomContainer]).css("left", l + (w / 2));
		if (compatibleOverlay) $(overlay).css({
			left: l,
			top: win.scrollTop(),
			width: w,
			height: win.height()
		});
	}

	function setup(open) {
		if (open) {
			$("object").add(ie6 ? "select" : "embed").each(function (index, el) {
				hiddenElements[index] = [el, el.style.visibility];
				el.style.visibility = "hidden";
			});
		} else {
			$.each(hiddenElements, function (index, el) {
				el[0].style.visibility = el[1];
			});
			hiddenElements = [];
		}
		var fn = open ? "bind" : "unbind";
		win[fn]("scroll resize", position);
		$(document)[fn]("keydown", keyDown);
	}

	function keyDown(event) {
		var code = event.which,
			fn = $.inArray;
		return (fn(code, options.closeKeys) >= 0) ? close() : (fn(code, options.nextKeys) >= 0) ? next() : (fn(code, options.previousKeys) >= 0) ? previous() : null;
	}

	function previous() {
		return changeImage(prevImage);
	}

	function next() {
		return changeImage(nextImage);
	}

	function changeImage(imageIndex) {
		if (imageIndex >= 0) {
			activeImage = imageIndex;
			imginfoarray = images[activeImage];
			activeURL = imginfoarray[0];
			prevImage = (activeImage || (options.loop ? images.length : 0)) - 1;
			nextImage = ((activeImage + 1) % images.length) || (options.loop ? 0 : -1);

			itemId = imginfoarray[2]; 
			price = imginfoarray[7];
			
			fbq('track', 'ViewContent', {
				value: price,
				currency: 'BBD',
				content_type: 'product',
				content_ids: itemId,
			}); 
		
			stop();
			center.className = "lbLoading";

			preload = new Image();
			preload.onload = animateBox;
			preload.src = activeURL;

			$(dl).html('<dt>Artist:</dt> <dd>' + imginfoarray[3] + '</dd> <dt>Title:</dt> <dd>' + imginfoarray[1] + '</dd> <dt>Category:</dt> <dd>' + imginfoarray[4] + '</dd> <dt>Medium:</dt> <dd>' + imginfoarray[5] + '</dd> <dt>Dimensions:</dt> <dd>' + imginfoarray[6] + '</dd> <dt>Item Number:</dt> <dd>' + imginfoarray[2] + '</dd> <dt>Price:</dt> <dd>$' + imginfoarray[7] + ' BBD</dd>');
		}

		return false;
	}

	function animateBox() {
		center.className = "";

		/* make sure the image won't be bigger than the window */
		var winWidth = $(window).width() - 20;
		var winHeight = $(window).height() - 144;
		var maxSize = (winWidth > winHeight) ? winHeight : winWidth; /* the smaller dimension determines max size */

		/* determine proper w and h for img, based on original image'w dimensions and maxSize */
		var my_w = preload.width;
		var my_h = preload.height;
		if (my_w > my_h) {
			my_h = maxSize * my_h / my_w;
			my_w = maxSize;
		} else {
			my_w = maxSize * my_w / my_h;
			my_h = maxSize;
		}

		if (preload.width > my_w || preload.height > my_h) {
			/* constrain it */
			$(image).css({
				backgroundImage: "url(" + activeURL + ")",
				backgroundSize: my_w + "px " + my_h + "px",
				visibility: "hidden",
				display: ""
			});
			$(sizer).width(my_w);
			$([sizer, prevLink, nextLink]).height(my_h);
		} else {
			$(image).css({
				backgroundImage: "url(" + activeURL + ")",
				backgroundSize: "",
				visibility: "hidden",
				display: ""
			});
			$(sizer).width(preload.width);
			$([sizer, prevLink, nextLink]).height(preload.height);
		}

		$(caption).html(images[activeImage][1] || "");
		$(number).html((((images.length > 1) && options.counterText) || "").replace(/{x}/, activeImage + 1).replace(/{y}/, images.length));

		if (prevImage >= 0) preloadPrev.src = images[prevImage][0];
		if (nextImage >= 0) preloadNext.src = images[nextImage][0];

		centerWidth = image.offsetWidth;
		centerHeight = image.offsetHeight;
		var top = Math.max(0, middle - (centerHeight / 2));
		if (center.offsetHeight != centerHeight) {
			$(center).animate({
				height: centerHeight,
				top: top
			}, options.resizeDuration, options.resizeEasing);
		}
		if (center.offsetWidth != centerWidth) {
			$(center).animate({
				width: centerWidth,
				marginLeft: -centerWidth / 2
			}, options.resizeDuration, options.resizeEasing);
		}
		$(center).queue(function () {
			$(bottomContainer).css({
				width: centerWidth,
				top: top + centerHeight,
				marginLeft: -centerWidth / 2,
				visibility: "hidden",
				display: ""
			});
			$(image).css({
				display: "none",
				visibility: "",
				opacity: ""
			}).fadeIn(options.imageFadeDuration, animateCaption);
		});
	}

	function animateCaption() {
		if (prevImage >= 0) $(prevLink).show();
		if (nextImage >= 0) $(nextLink).show();
		$(bottom).css("marginTop", -bottom.offsetHeight).animate({
			marginTop: 0
		}, options.captionAnimationDuration);
		bottomContainer.style.visibility = "";
	}

	function stop() {
		preload.onload = null;
		preload.src = preloadPrev.src = preloadNext.src = activeURL;
		$([center, image, bottom]).stop(true);
		$([prevLink, nextLink, image, bottomContainer]).hide();
	}

	function close() {
		if (activeImage >= 0) {

			$("body").removeClass('noScroll');

			stop();

			activeImage = prevImage = nextImage = -1;

			$(lbTopContainer).animate({
				top: -60
			}, {
				duration: 'slow',
				queue: false
			});

			$(dl).hide();
			$(center).hide();
			$(overlay).stop().fadeOut(options.overlayFadeDuration, setup);

		}

		return false;
	}

})(jQuery);