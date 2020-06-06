<?php 

	include 'libs/comm.php';
		
	$artistId = (isset($_GET['artistId'])) ? intval($_GET['artistId']) : '';
	
	if (empty($artistId)){
		header("Location: artists.php");
		exit;
	}
	
	/* artist */
	
	$artist = callAPI(API_ROOT.'artists/?key='. APIKEY . '&artistId=' . $artistId . '&fields=artistId,firstName,lastName');
	
	if ($artist['total'] == 0){
		header("Location: artists.php");
		exit;
	}
	
	$artist_info = $artist['artists'][0];
	
	$fname = ($artist_info['firstName'] == '') ? '' : $artist_info['firstName'];
	$lname = ($artist_info['lastName'] == '') ? '' : ' ' . $artist_info['lastName'];
	
	$fullname = $fname . $lname;
	
	/* artists */
	
	$artists = callAPI(API_ROOT.'artists/?key='. APIKEY . '&fields=artistId,firstName,lastName,itemPicture&limit=250');
	
	/* portfolio */
	
	$portfolio = callAPI(API_ROOT.'items/?key='. APIKEY . '&artistId=' . $artistId . '&q=stockLocation|Stock&fields=title,medium,category,width,height,itemId,retailPrice,pictures&limit=100');
	
?>
<!doctype html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="HandheldFriendly" content="True">
<meta name="MobileOptimized" content="320">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta http-equiv="cleartype" content="on">
<title><?php echo $fullname;?> Portfolio</title>
<meta name="description" content="The portfolio of <?php echo $fullname;?>">
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
	
		echo '<div id="pageHead">';
		
			echo '<h1>' . $fullname . '</h1>';
			
			if($artists['total'] != 0){
			
				echo '<p><select name="artistList" onchange="onSelectRedirect(this)">';
				foreach($artists['artists'] as $result){
					$fname = empty($result['firstName']) ? '' : $result['firstName'];
					$lname = empty($result['lastName']) ? '' : $result['lastName'] . ', ';
					
					$selected = $result['artistId'] == $artistId ? ' selected' : '';
					
					echo '<option value="/portfolio.php?artistId=' . $result['artistId'] . '"' . $selected . '>' . $lname . $fname . '</option>';
				}
				echo '</select></p>';
			
			}
		
		echo '</div>';
	
		if($portfolio['total'] != 0){
		
			echo '<div class="textcenter">';
			
				echo '<p>Prices shown in Barbados Dollars (BBD), $1 USD = $2 BBD</p>';
				
				echo '<p>' . $portfolio['total'] . ' Pieces</p>';
			
			echo '</div>';
			
			echo '<div id="portfolio">';
			
				foreach($portfolio['items'] as $result){
					
					if(!empty($result['pictures'])){
						echo '<div>';
						
							$dimentions = 'W ' . round($result['width']) . 'in x H ' . round($result['height']) . 'in';
							
							echo '<p><a href="/item.php?itemId=' . $result['itemId'] . '" 
										data-img="' . $result['pictures'][0] . '" 
										data-artist="' . $fullname . '" 
										data-category="' . $result['category'] . '" 
										data-medium="' . $result['medium'] . '" 
										data-dimensions="' . $dimentions . '" 
										data-itemid="' . $result['itemId'] . '" 
										data-price="' . $result['retailPrice'] . '" 
										rel="lightbox-photos" 
										title="' . $result['title'] . '">
										<img src="' . $result['pictures'][0] . '" alt=""></a></p>';
							
							echo '<ul>';
								
								echo '<li><strong>Title:</strong> ' . $result['title'] . '</li>';
								echo '<li><strong>Category:</strong> ' . $result['category'] . '</li>';
								echo '<li><strong>Medium:</strong> ' . $result['medium'] . '</li>';
								echo '<li><strong>Dimensions:</strong> ' . $dimentions . '</li>';
								echo '<li><strong>Item Number:</strong> ' . $result['itemId'] . '</li>';
								echo '<li><strong>Price:</strong> $' . $result['retailPrice'] . ' BBD</li>';
								
								echo '<li><a href="javascript:void(0)" class="cartbtn" data-itemid="' . $result['itemId'] . '" title="add this item to your shopping cart"><span><i class="fas fa-shopping-cart"></i></span> add to cart</a></li>';
								
							echo '</ul>';
						
						echo '</div>';
					}
					
				}
			
			echo '</div>';
		
		}
		else{
			echo '<p class="textcenter">Sorry, no Pieces found for ' . $fullname . '</p>';	
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
		
    var cartQty = $('#cartQty'),
        cartbtn = $('.cartbtn');
	
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
	
	cartbtn.click(function () {
		
		var itemid = $(this).data('itemid');
        
        addtocart(itemid);
			
	});
	
</script>
                
</body>
</html>