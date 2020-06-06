<?php 

	include 'libs/comm.php';
	
	$events = callAPI(API_ROOT.'events/?key='. APIKEY . '&sortOptions=startDate|asc');
	
?>
<!doctype html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="HandheldFriendly" content="True">
<meta name="MobileOptimized" content="320">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta http-equiv="cleartype" content="on">
<title>Gallery of Caribbean Art Upcoming Events</title>
<meta name="description" content="Gallery of Caribbean Art Upcoming Events, Shows and Exhibitions">
<link rel="shortcut icon" href="/favicon.ico">
<?php include 'includes/styles.php';?>
</head>
<body>

<div class="overlay"><em>Loading</em></div>

<div id="lbModal">
    <div id="lbModalBody"></div>
    <div id="lbModalFoot"><span id="lbModalClose"><i class="fas fa-times"></i> close</span></div>
</div>    

<?php 

	include 'includes/header.php';
    
	echo '<div id="content">';
    
		if($events['total'] == 0){
			
			echo '<div class="textcenter">';
				echo '<h1>Art Exhibitions and Events</h1>';
				echo '<p>Sorry, no Exhibitions or Events found.</p>';
			echo '</div>';	
			
		}
		else{
			
			echo '<div id="eventsHead">';
			
				echo '<h1>Art Exhibitions and Events</h1>';
				
				echo '<h3>The Gallery Of Caribbean Art is pleased to announce the upcoming Art Exhibitions</h3>';
			
			echo '</div>';
			
			echo '<div class="divider"></div>';
			
			foreach($events['events'] as $i => $event){
				
				$event_items = callAPI(API_ROOT.'items/?key='. APIKEY . '&eventId=' . $event['eventId'] . '&q=stockLocation|Stock&fields=title,itemId,firstName,lastName,width,height,category,medium,retailPrice,pictures&limit=70');
				
				echo '<div class="eventsBody">' . $event['body'] . '</div>';
				
				if($event_items['total'] != 0){
					
					echo '<div class="eventsGallery">';
					
						echo '<ul>';
					
							foreach($event_items['items'] as $result){
								
								if(!empty($result['pictures'])){
									
									$fname = empty($result['firstName']) ? '' : $result['firstName'];
									$lname = empty($result['lastName']) ? '' : $result['lastName'] . ', ';
									
									$fullname = $lname . $fname;
									
									$dimentions = 'W' . round($result['width']) . 'in x H' . round($result['height']) . 'in';
									
									echo '<li>';
										
										echo '<a href="/item.php?itemId=' . $result['itemId'] . '" 
										data-img="' . $result['pictures'][0] . '" 
										data-artist="' . $fullname . '" 
										data-category="' . $result['category'] . '" 
										data-medium="' . $result['medium'] . '" 
										data-dimensions="' . $dimentions . '" 
										data-itemid="' . $result['itemId'] . '" 
										data-price="' . $result['retailPrice'] . '" 
										rel="lightbox-photos" 
										title="' . $result['title'] . '">
										<img src="' . $result['pictures'][0] . '"></a>';
									
									echo '</li>';
								
								}
								
							}
						
						echo '</ul>';
					
					echo '</div>';
					
				}
				
				if ($i < $events['total'] - 1){
					echo '<div class="divider"></div>';
				}
				
			}
			
		}
    
	echo '</div>';
	
	include 'includes/footer.php';
		
?>

<script type="text/javascript" src="/js/slimbox2-cart.js"></script>

<script type="text/javascript">

	var overlay = $('.overlay'),
		lbModal = $('#lbModal'),
		lbModalClose = $('#lbModalClose'),
		lbModalBody = $('#lbModalBody');
		
	var	cartQty = $('#cartQty');
	
    $("a[rel^='lightbox']").slimbox({}, null, function(el) {
        return (this == el) || ((this.rel.length > 8) && (this.rel == el.rel));
    });	
    
    function addtocart(id){
		
		overlay.addClass('spinner').show();
		
		$.ajax({
			type: 'POST',
			url: '/ajax/cart_add.php',
			dataType: 'json',
			data: {id: id}
		}).done(function(resp) {
			overlay.removeClass('spinner').empty();
			
			switch (resp.code) {
				case 0: 
					lbModalBody.html('<div>Item already in your Shopping Cart<br><br><i class="fas fa-shopping-cart"></i> <a href="/cart.php" title="">view cart</a></div>');
				break;
				case 1: 
					lbModalBody.html('<div><h4>Success!</h4> Item added to your Shopping Cart<br><br><i class="fas fa-shopping-cart"></i> <a href="/cart.php" title="">view cart</a></div>');
					
					cartQty.text(resp.qty);
				break;
			}
			
			lbModalClose.click(function() {
				lbModal.hide();
				overlay.hide();
			});
			lbModal.center(350).show();
			
		});
		
	}

</script>
                
</body>
</html>