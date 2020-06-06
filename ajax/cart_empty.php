<?php
 
	include '../libs/comm.php';
	
	unset($_COOKIE[$cart->cookie_name]);
	setcookie($cart->cookie_name, '', time() + (86400 * 30), '/');
	$_COOKIE[$cart->cookie_name] = '';
	
	echo '<div>Your Shopping Cart is Empty</div>';
	
?>