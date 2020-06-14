<?php 

	include 'libs/comm.php';
	
	$shopcart_items = $cart->items();
	
	if(empty($shopcart_items)){
		if(isset($_SESSION['ckout'])){ 
			$_SESSION['ckout'] = array();
		}
		header("Location: gallery.php");
		exit;
	}
	
	$ckout = array();
	
	if(isset($_SESSION['ckout']) && !empty($_SESSION['ckout'])) {
		$ckout = $_SESSION['ckout'];
	}
	else{
		header("Location: gallery.php");
		exit;
	}
	
	$ids = array();
	
	foreach($shopcart_items as $id => $val){
		array_push($ids, $id);
	}
	
	$str = implode(',', $ids);
	
	$_items = callAPI(API_ROOT.'items/?key='. APIKEY . '&itemId=' . $str . '&fields=title,firstName,lastName,artistId,medium,category,width,height,itemId,retailPrice,pictures');
		
	$items = array();
		
	foreach($_items['items'] as $val){
		if(in_array($val['itemId'], $ids)){
			$items[] = $val;
		}
	}
	
	$cart->emptyCart();
	$_SESSION['ckout'] = array();
	
	$piece = 'piece';
	
	if($items['total'] > 1){
		$piece = 'pieces';
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
<title>Thank you for your order request</title>
<meta name="description" content="Thank you for your order request, we'll be getting back to you as soon as possible with information on purchasing">
<link rel="shortcut icon" href="/favicon.ico">
<?php include 'includes/styles.php';?>
</head>
<body>

<?php 

	include 'includes/header.php';

?>

<div id="content"><div id="contentInner">
    
    <h1>Thank you for your order request</h1>
    
    <p>Thank you <?php echo $ckout['fullname'];?> for your order request, we'll be getting 
    back to you as soon as possible with purchasing information and any inquiries you may have. Please find below, details of your order.</p>
	
    <p><a href="javascript:void(0)" title="" class="button" onclick="printArea('printArea',800,600);"><i class="fas fa-print"></i> Print</a></p>
    
    <div id="printArea">
        
        <?php 
			
            echo '<h4>Inquiry Details</h4>';

            echo '<p>' . $ckout['orderdate'] . '</p>';
        
            echo '<p><strong>Order Number:</strong> ' . $ckout['orderno'] . '</p>';

            echo '<p><strong>Name:</strong> ' . $ckout['fullname'] . '<br>';
            echo '<strong>Email:</strong> ' . $ckout['email'] . '<br>';
            echo '<strong>Phone:</strong> ' . $ckout['phone'] . '</p>';

            echo '<p><strong>Message:</strong> ' . (empty($ckout['message']) ? 'not supplied' : $ckout['message'])  . '</p>';

            echo '<h4>Selected ' . $piece . '</h4>';
            
            $subtotal = 0;
			$subtotal2 = 0;
            
			echo '<div id="cartItems">';
			
			foreach($items as $result){
                echo '<div>';
                
                    $subtotal += $result['retailPrice'];
					$subtotal2 += $result['retailPrice'];
                    
                    $fname = empty($result['firstName']) ? '' : $result['firstName'];
                    $lname = empty($result['lastName']) ? '' : ' ' . $result['lastName'];
                    
                    $fullname = $fname . $lname;
                    
                    $img_str = '<img src="/images/files/noimage.jpg" alt="">';
					
					if(!empty($result['pictures'])){
                        $img_str = '<img src="' . $result['pictures'][0] . '" alt="">';
                    }
                    
                    echo '<p>' . $img_str . '</p>';
                    
                    echo '<ul>';
                        
                        echo '<li><strong>Artist:</strong> ' . $fullname . '</li>';
                        echo '<li><strong>Title:</strong> ' . $result['title'] . '</li>';
						echo '<li><strong>Category:</strong> ' . $result['category'] . '</li>';
                        echo '<li><strong>Medium:</strong> ' . $result['medium'] . '</li>';
                        echo '<li><strong>Dimensions:</strong> W ' . round($result['width']) . 'in x H ' . round($result['height']) . 'in</li>';
                        echo '<li><strong>Item Number:</strong> ' . $result['itemId'] . '</li>';
                        echo '<li><strong>Price:</strong> $' . $result['retailPrice'] . ' BDD</li>';
                        
                    echo '</ul>';
                
                echo '</div>';
            }
			
			echo '</div>';
            
            $subtotal = number_format($subtotal,2);
			$subtotal2 = number_format($subtotal2,2,'.','');
            
            echo '<div id="cartItemsFoot">';
        
                echo '<p><strong>Subtotal:</strong> $' . $subtotal . ' BDD</p>';
        
            echo '</div>';
            
        ?>
    
    </div>
    
    <p class="textcenter"><a href="javascript:void(0)" title="" class="button" onclick="printArea('printArea',800,600);"><i class="fas fa-print"></i> Print</a></p>
        
</div></div>

<?php 

	include 'includes/footer.php';
		
?>

<script type="text/javascript">
    
    fbq('track', 'Purchase', {
    value: '<?php echo $subtotal2;?>',
    currency: 'BBD',
    content_type: 'product'
    contents: [
    {
        id: '<?php echo $ckout["orderno"];?>',
        quantity: '1'
    }],
    });
   
</script>
     
</body>
</html>