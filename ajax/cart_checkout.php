<?php 

	include '../libs/comm.php';
	
	if(isAjax()){
		
        $stoken = isset($_SESSION['token']) ? $_SESSION['token'] : ''; 
        $ptoken = isset($_POST['token']) ? strip_tags(trim($_POST['token'])) : '';
        
        if (empty($ptoken)) {
           echo '<div><h4>Error!</h4> Message not sent (2)</div>';
           exit;
        }
            
        if (!hash_equals($stoken, $ptoken)) {
           echo '<div><h4>Error!</h4> Message not sent (3)</div>';
           exit;
        } 
		
		$pattern = '/0x0A|%0A|0x0D|%0D|Content-Type:|Bcc:|Cc:/i';
		$suspect = false;
		
		isSuspect($_POST, $pattern, $suspect);
		
		if($suspect){
			echo '<div><h4>Error!</h4> Request not sent (4)</div>';
			exit;
		}
		
		$loadtime = isset($_POST['loadtime']) ? intval($_POST['loadtime']) : 0;
		
		$totaltime = (time() - $loadtime);
		
		if($totaltime < 5){
		   echo '<div></h4>Error!</h4> Request not sent (5)</div>';
		   exit;
		}		
		
		$paperclip = isset($_POST['paperclip']) ? $_POST['paperclip'] : '';
		
		if(empty($paperclip)){
						
			$fullname = isset($_POST['fullname']) ? strip_tags(trim($_POST['fullname'])) : '';
			$email = isset($_POST['email']) ? strip_tags(trim($_POST['email'])) : '';
			$phone = isset($_POST['phone']) ? strip_tags(trim($_POST['phone'])) : '';
			$message = isset($_POST['message']) ? trim($_POST['message']) : '';
		
			$remote_ip = isset($_POST['remote_ip']) ? strip_tags(trim($_POST['remote_ip'])) : 'unknown';
			
			$errors = array();
			
			if(empty($fullname)){ $errors[] = 'Name Required'; }
			if(!isEmail($email)){ $errors[] = 'Email Required or Invalid'; }
			if(!isPhone($phone)){ $errors[] = 'Phone Required'; }
			
			if(empty($errors)){ 
				
				$shopcart_items = $cart->items();
				
				if(empty($shopcart_items)){
					echo '<div><h4>Error!</h4> Request not sent</div>';
				}
				else{
					
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
					
					$orderno = token(4);
                    $orderdate = date('l jS \of F Y, g:i A');
					
					$text  = "Website Purchase Request\n";
					
					$text .= $orderdate;
					
					$text .= "\n\nOrder Number: " . $orderno;
					
					$text .= "\n\n===========================================================\n";
					$text .= "Contact Information";
					$text .= "\n===========================================================\n\n";
					
					$text .= "Name: " . $fullname . "\n";
					$text .= "Email Address: " . $email . "\n";
					$text .= "Phone: " . $phone;
					
					$text .= "\n\n===========================================================\n";
					$text .= "Message";
					$text .= "\n===========================================================\n\n";
					
					$text .= !empty($message) ? $message : "Message not supplied";
					
					$text .= "\n\n===========================================================\n";
					$text .= "Requested Artwork";
					$text .= "\n===========================================================\n";
					
					$subtotal = 0;
					
					foreach($items as $result){
							
						$subtotal += $result['retailPrice'];
						
						$fname = empty($result['firstName']) ? '' : $result['firstName'];
						$lname = empty($result['lastName']) ? '' : ' ' . $result['lastName'];
						
						$text .= "\nTitle: " . $result['title'];
						$text .= "\nArtist: " . $fname . $lname;
						$text .= "\nItem Number: " . $result['itemId'];
						$text .= "\nPrice: $" . $result['retailPrice'] . " BBD\n";
						$text .= "\nView: http://artgallerycaribbean.com/item.php?itemId=" . $result['itemId'] . " \n";
						
						$text .= "----------------------------------------------------------------------------";
												
					}
					
					$subtotal = number_format($subtotal,2);
					
					$text .= "\n\nSubtotal: $" . $subtotal . " BBD";
					
					$text .= "\n\n----------------------------------------------------------------------------\n\n";
				
					$text .= "Remote IP: " . $remote_ip . "\n";
					
					$resp = 1;					
					
					$subject = 'Website request from ' . $fullname;
					
					$to = $from = CONTACT_EMAIL;
					
					require_once('aws.inc.php');
					require_once('ses.php');
					
					$creds = getAWScredentials();
					
					$ses = new SimpleEmailService($creds['id'], $creds['key']);
					
					$m = new SimpleEmailServiceMessage();
					$m->addTo($to);
					$m->setFrom($from);
					$m->setSubject($subject);
					$m->setMessageFromString($text);
					
					if($ses->sendEmail($m)){
						$_SESSION['ckout']['orderno'] = $orderno;
						$_SESSION['ckout']['orderdate'] = $orderdate;
						$_SESSION['ckout']['fullname'] = $fullname;
						$_SESSION['ckout']['email'] = $email;
						$_SESSION['ckout']['phone'] = $phone;
						$_SESSION['ckout']['message'] = $message;
						
						$resp = 1;
					}
					else{
						$resp = '<h4>Error! inquiry not sent</h4>
						<p>An error occurred and your inquiry was not sent. 
						We are very sorry and regret any inconvenience this has caused.  
						Please try again later or email us at ' .  obfuse(CONTACT_EMAIL) . '. 
						Thank you for your understanding and patience.</p>';
					}
					
					echo $resp;
				
				}
				
			}
			else{
				echo '<h4>Error!</h4><p>Some errors occurred, please correct them below</p><ul>';
				foreach ($errors as $error){ 
					echo '<li>' . $error . '</li>'; 
				}
				echo '</ul>';
			}
		
		}
		else{
			echo '<div><h4>Error!</h4> Request not sent</div>';
		}
	
	}
	else{
		echo '<div><h4>Error!</h4> Request not sent</div>';
	}

?>
