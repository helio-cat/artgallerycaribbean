<?php 

	include 'libs/comm.php';
	
	$shopcart_items = $cart->items();
	
	if(empty($shopcart_items)){
		header("Location: gallery.php");
		exit;
	}
	
	$items = array();
	
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
	
	if(empty($items)){
		header("Location: gallery.php");
		exit;
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
<title>Your Gallery of Caribbean Art Shopping Cart Checkout</title>
<meta name="description" content="Your Gallery of Caribbean Art Shopping Cart Checkout">
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

?>

<div id="content"><div id="contentInner">
    
    <h3><i class="fas fa-shopping-cart"></i> Checkout</h3>
    
    <p>Please fill out the order form below and we'll get back to you as soon as possible with purchasing information on the selected pieces and any inquiries you may have. Thank you for your purchase request.</p>
	
	<?php 
		
		echo '<div id="checkoutItems">';
			
			$subtotal = 0;
			
			echo '<i class="highlight">Hover over name to show image</i><br>';
			foreach($items as $result){
				$subtotal += $result['retailPrice'];
				echo '<div>';
					echo '<span class="ipreview" data-src="' . $result['pictures'][0] . '"><i class="far fa-image"></i> ' . $result['title'] . '</span>';
					echo '<em>$' . $result['retailPrice'] . ' BDS</em>';
				echo '</div>';
			}
			
			$subtotal = number_format($subtotal,2);
			
			echo '<p><strong>Subtotal:</strong> $' . $subtotal . ' BDS</p>';
        
        echo '</div>';
    
    ?>
    
    <form id="form1" method="post" action="/checkout.php">
            
        <input type="text" name="fullname" id="fullname" value="" placeholder="Your Name" maxlength="100" required>
        
        <input type="email" name="email" id="email" value="" placeholder="Your Email Address" maxlength="120" required>
        
        <input type="text" name="phone" id="phone" value="" placeholder="Your Phone Number" maxlength="50" required>
        
        <textarea name="message" id="message" placeholder="Add a note (optional)" rows="5"></textarea>
        
        <div style="position: absolute; left: -5000px;" aria-hidden="true">
            <label>Keep this field blank</label>
            <input type="text" name="paperclip" id="paperclip" tabindex="-1" value="">
        </div>
        
        <input type="hidden" name="loadtime" value="<?php echo time();?>">
		<input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>">
        <input type="hidden" name="remote_ip" value="<?php echo $_SERVER['REMOTE_ADDR'];?>">
        
        <div class="fbtn-right"><button type="submit" id="sndbtn"><i class="far fa-envelope"></i> send</button></div>
            
    </form>	
        
</div></div>

<?php 

	include 'includes/footer.php';
		
?>
     
<script type="text/javascript">
	
	var form1 = $('#form1'),
		fullname = $('#fullname'), 
		email = $('#email'), 
		phone = $('#phone'), 
		paperclip = $('#paperclip'),
		sndbtn = $('#sndbtn');
	
	var overlay = $('.overlay'),
		lbModal = $('#lbModal'),
		lbModalClose = $('#lbModalClose'),
		lbModalBody = $('#lbModalBody');
		
	$('.ipreview').imageHoverPreview({w:'200px'});
	
	form1.submit(function(event) {
		
		event.preventDefault();
		
		if(!isEmpty(paperclip.val())) {return false;}
		if(isEmpty(fullname.val())) {alert('Name Required'); fullname.focus(); return false;}
		if(!isEmail(email.val())) {alert('Valid Email Address Required'); email.focus(); return false;}
		if(!isPhone(phone.val())) {alert('Contact Number Required'); phone.focus(); return false;}
		
		sndbtn.prop('disabled', true);
		
		overlay.addClass('spinner').show();
		
		$.ajax({
			method: 'POST',
			url: '/ajax/cart_checkout.php',
			data: form1.serialize()
		}).done(function(resp) {
			
			overlay.removeClass('spinner').empty();
			
			form1[0].reset();
			
			if(resp == '1'){
				window.location.replace('/thank-you.php');
			}
			else{
				lbModalBody.html(resp);
				lbModalClose.click(function() {
					sndbtn.prop('disabled', false);
					lbModal.hide();
					overlay.hide();
				});
				lbModal.center(450).show();
			}
			
		});
		
	});
	
</script>
                
</body>
</html>