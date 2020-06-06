<?php 

	include 'libs/comm.php';
	
	$shopcart_items = $cart->items();
	
	$items = array();
	
	if(!empty($shopcart_items)){
		
		$ids = array();
		
		foreach($shopcart_items as $id => $val){
			array_push($ids, $id);
		}
		
		$str = implode(',', $ids);
		
		$_items = callAPI(API_ROOT.'items/?key='. APIKEY . '&itemId=' . $str . '&fields=title,firstName,lastName,artistId,medium,category,width,height,itemId,retailPrice,pictures');
		
		foreach($_items['items'] as $val){
			if(in_array($val['itemId'], $ids)){
				$items[] = $val;
			}
		}
	
	}
	
?>
<!doctype html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="HandheldFriendly" content="True">
<meta name="MobileOptimized" content="320">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta http-equiv="cleartype" content="on">
<title>Your Gallery of Caribbean Art Shopping Cart</title>
<meta name="description" content="Your Gallery of Caribbean Art Shopping Cart">
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
	
		if(!empty($items)){
			
			$subtotal = 0;
			
			echo '<div id="cartItems">';
            
                echo '<h3><i class="fas fa-shopping-cart"></i> Your Shopping Cart</h3>';
			
                foreach($items as $result){

                    if(!empty($result['pictures'])){

                        echo '<div>';

                            $subtotal += $result['retailPrice'];

                            $fname = empty($result['firstName']) ? '' : $result['firstName'];
                            $lname = empty($result['lastName']) ? '' : ' ' . $result['lastName'];

                            $fullname = $fname . $lname;

                            $dimentions = 'W ' . round($result['width']) . 'in x H ' . round($result['height']) . 'in';

                            echo '<p><a href="' . $result['pictures'][0] . '" rel="lightbox-photos" title="' . $result['title'] . '"><img src="' . $result['pictures'][0] . '" alt=""></a></p>';

                            echo '<ul>';

                                echo '<li><strong>Artist:</strong> <a href="/portfolio.php?artistId=' . $result['artistId'] . '" title="View Portfolio">' . $fullname . '</a></li>';
                                echo '<li><strong>Title:</strong> ' . $result['title'] . '</li>';
                                echo '<li><strong>Category:</strong> ' . $result['category'] . '</li>';
                                echo '<li><strong>Medium:</strong> ' . $result['medium'] . '</li>';
                                echo '<li><strong>Dimensions:</strong> ' . $dimentions . '</li>';
                                echo '<li><strong>Item Number:</strong> ' . $result['itemId'] . '</li>';
                                echo '<li><strong>Price:</strong> $' . $result['retailPrice'] . ' BBD<br></li>';
                                echo '<li><a href="javascript:void(0)" data-itemid="' . $result['itemId'] . '" class="removeitem" title="">remove item</a></li>';

                            echo '</ul>';

                        echo '</div>';

                    }

                }
			
			echo '</div>';
			
			$subtotal = number_format($subtotal,2);
			
			echo '<div id="cartItemsFoot">';
			
				echo '<p><strong>Subtotal:</strong> $' . $subtotal . ' BBD</p>';
				
				echo '<ul>';
					echo '<li><a href="/gallery.php" title=""><span><i class="fas fa-caret-left"></i></span> continue browsing</a></li>';
					echo '<li><a href="javascript:void(0)" title="" id="emptyCart"><span><i class="fas fa-trash-alt"></i></span> empty cart</a></li>';
					echo '<li><a href="/checkout.php" title=""><span><i class="fas fa-check"></i></span> continue to checkout</a></li>';
				echo '</ul>';
			
			echo '</div>';
				
		}
		else{
			echo '<div class="textcenter"><h3><i class="fas fa-shopping-cart"></i> Your Shopping Cart is Empty</h3>
			<p><a href="/gallery.php" class="button large green" title="Visit the Gallery">continue browsing</a></p></div>';
		}
	
	echo '</div>';
	
	include 'includes/footer.php';
		
?>
      
<script type="text/javascript" src="/js/slimbox2.js"></script>
      
<script type="text/javascript">
	
	var emptyCart = $('#emptyCart'),
		removeitem = $('.removeitem');
		
	var overlay = $('.overlay'),
		lbModal = $('#lbModal'),
		lbModalClose = $('#lbModalClose'),
		lbModalBody = $('#lbModalBody');
		
	removeitem.click(function(el) {
		
		if(confirm('Remove Item??')){
		
			var self = $(this);
			
			overlay.addClass('spinner').show();
			
			$.ajax({
				method: 'POST',
				url: '/ajax/cart_removeitem.php',
				data: {id: self.data('itemid')}
			}).done(function(resp) {
				overlay.removeClass('spinner').empty();
				
				switch (resp) {
					case '0': 
						lbModalBody.html('<div>Item not found</div>');
						lbModalClose.click(function() {
							lbModal.hide();
							overlay.hide();
						});
						lbModal.center(250).show();
					break;
					case '1': 
						window.location.reload();
					break;
				}
			});
		
		}
		
	});
	
	emptyCart.click(function () {
		
		if(confirm('Empty Cart??')){
			overlay.addClass('spinner').show();
			
			$.ajax({        
				type: 'POST',
				url: '/ajax/cart_empty.php'
			}).done(function() { 
				window.location.replace('/gallery.php');
			});
		}
		
	});
	
</script>
                
</body>
</html>