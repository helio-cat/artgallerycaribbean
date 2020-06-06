<?php 

	include 'libs/comm.php';

?>
<!doctype html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="HandheldFriendly" content="True">
<meta name="MobileOptimized" content="320">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta http-equiv="cleartype" content="on">
<title>Gallery of Caribbean Art</title>
<meta name="description" content="The only art gallery in the Caribbean committed to promoting the art of Barbados and the entire Caribbean region">
<link rel="shortcut icon" href="/favicon.ico">
<?php include 'includes/styles.php';?>
</head>
<body>

<?php 

	include 'includes/header.php';
	
	$images = glob('images/carousel/*.[jJ][pP][gG]');
	$images = array_reverse($images);
	    
    echo '<div id="content"><div id="carousel"><ul>';
			
		foreach($images as $image){
			echo '<li style="background-image: url(/' . $image . '?rand=' . rand() . ');"></li>';
		}
			
	echo '</ul></div><ul id="carouselBtns">';
	
		foreach($images as $image){
			echo '<li></li>';
		}
	
	echo '</ul></div>';
	
	include 'includes/footer.php';

?>

<script type="text/javascript" src="/js/imagesloaded.pkgd.min.js"></script>

<script type="text/javascript">
	
	var cbpBGSlideshow = (function() {
		var $slideshow = $('#carousel ul'),
			$items = $('#carousel li'),
			itemsCount = $items.length,
			$buttons = $('#carouselBtns li'),
			current = 0,
			slideshowtime,
			isSlideshowActive = true,
			interval = 10000;

		function initEvents() {
			$buttons.on('click', function() { 
				navigate($(this).index());
				if(isSlideshowActive) { 
					startSlideshow(); 
				}
			});
		}
		function navigate(direction) {
			var $oldItem = $items.eq(current), 
				$oldButton = $buttons.eq(current);
			
			if(jQuery.type(direction) === 'string'){
				if(direction === 'next') {
					current = current < itemsCount - 1 ? ++current : 0;
				}
				else if(direction === 'prev') {
					current = current > 0 ? --current : itemsCount - 1;
				}
			}
			else if(jQuery.type(direction) === 'number'){
				current = direction;
			}
			
			var $newItem = $items.eq(current),
				$newButton = $buttons.eq(current);
			
			$oldItem.css('opacity', 0);
			$newItem.css('opacity', 1);
			
			$oldButton.removeClass('active');
			$newButton.addClass('active');
		}
		function startSlideshow() {
			isSlideshowActive = true;
			clearTimeout(slideshowtime);
			slideshowtime = setTimeout(function() {
				navigate('next');
				startSlideshow();
			}, interval);
		}
		function stopSlideshow() {
			isSlideshowActive = false;
			clearTimeout(slideshowtime);
		}
		function show() {
			$slideshow.imagesLoaded(function() {
				
				$items.eq(current).css('opacity', 1);
				
				$buttons.eq(current).addClass('active');
				
				initEvents();
				
				startSlideshow();
				
			});
		}
		
		return {init : show};
	
	})();
	
	$(function() {
		cbpBGSlideshow.init();
	});
	
</script>
                
</body>
</html>