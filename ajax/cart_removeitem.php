<?php
 
	include '../libs/comm.php';
	
	$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
	
	$resp = $cart->remove($id);
	
	echo $resp;
	
?>