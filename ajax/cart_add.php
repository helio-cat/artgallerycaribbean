<?php
 
	include '../libs/comm.php';
	
	$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
	
	$code = $cart->add($id);
	$qty = $cart->qty();
	
	$resp = array('code' => $code, 'qty' => $qty);
	
	echo json_encode($resp);
	
?>