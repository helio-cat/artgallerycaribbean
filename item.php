<?php 

	include 'libs/comm.php';
	
    $itemId = (isset($_GET['itemId'])) ? intval($_GET['itemId']) : '';
	
	if (empty($itemId)){
		header("Location: gallery.php");
		exit;
	}
	
	$item = callAPI(API_ROOT.'items/?key='. APIKEY . '&itemId=' . $itemId . '&fields=title,itemId,firstName,lastName,width,height,category,medium,retailPrice,pictures');
	
	if (empty($item['items'])){
		header("Location: gallery.php");
		exit;
	}
	
	$selitem = $item['items'][0];
	
	$fname = empty($selitem['firstName']) ? '' : $selitem['firstName'];
	$lname = empty($selitem['lastName']) ? '' : ' ' . $selitem['lastName'];
	
	$fullname = $fname . $lname;
	
?>
<!doctype html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="HandheldFriendly" content="True">
<meta name="MobileOptimized" content="320">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta http-equiv="cleartype" content="on">
<title><?php echo $selitem['title'] . ' by ' . $fullname;?></title>
<meta name="description" content="Contact the Gallery of Caribbean Art to request purchase information">
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
	
		echo '<div id="item">';
		
			echo '<h1>' . $selitem['title'] . '</h1>';
			
			echo '<h4>' . $fullname . '</h4>';
		
			echo '<p>';
			if(empty($selitem['pictures'])){
				echo '<img src="/images/files/noimage.jpg" alt="">';
			}
			else{
				echo '<img src="' . $selitem['pictures'][0] . '" alt="">';
			}
			echo '</p>';
			
			echo '<p>Prices shown in Barbados Dollars (BDS), $1.00 US = $2.00 BDS</p>';
			
			echo '<ul>';
				
				echo '<li><strong>Title:</strong><em>' . $selitem['title'] . '</em></li>';
				echo '<li><strong>Category:</strong><em>' . $selitem['category'] . '</em></li>';
				echo '<li><strong>Medium:</strong><em>' . $selitem['medium'] . '</em></li>';
				echo '<li><strong>Dimensions:</strong><em>W ' . round($selitem['width']) . 'in x H ' . round($selitem['height']) . 'in</em></li>';
				echo '<li><strong>Item Number:</strong><em>' . $selitem['itemId'] . '</em></li>';
				echo '<li><strong>Price:</strong><em>$' . $selitem['retailPrice'] . ' BDS</em></li>';
			echo '</ul>';
    
            echo '<p class="textcenter"><a href="javascript:void(0)" data-itemid="' . $selitem['itemId'] . '" class="cartbtn" id="additem" title="Add to your Shopping Bag">
				<span><i class="fas fa-shopping-cart"></i></span> add</a></p>';
								
		echo '</div>';
	
	echo '</div>';
            
	include 'includes/footer.php';
		
?>
     
<script type="text/javascript">
	
	var additem = $('#additem'),
		overlay = $('.overlay'),
		lbModal = $('#lbModal'),
		lbModalClose = $('#lbModalClose'),
		lbModalBody = $('#lbModalBody'),
		
		cartQty = $('#cartQty');
	
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
					lbModalBody.html('<div>Item added to your Shopping Cart<br><br><i class="fas fa-shopping-cart"></i> <a href="/cart.php" title="">view cart</a></div>');
					
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
	
	additem.click(function() {
		var self = $(this);
		addtocart(self.data('itemid'));
	});
	
</script>
                
</body>
</html>