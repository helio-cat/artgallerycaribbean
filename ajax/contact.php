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
			echo '<div><h4>Error!</h4> Message not sent (4)</div>';
			exit;
		}
		
		$loadtime = isset($_POST['loadtime']) ? intval($_POST['loadtime']) : 0;
		
		$totaltime = (time() - $loadtime);
		
		if($totaltime < 5){
		   echo '<div><h4>Error!</h4> Message not sent (5)</div>';
		   exit;
		}		
		
		$paperclip = isset($_POST['paperclip']) ? strip_tags(trim($_POST['paperclip'])) : '';
		
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
			if(empty($message)){ $errors[] = 'Message Required'; }
			
			if(empty($errors)){ 
				
				$text  = "Website Contact Form\n\n";
				$text .= "Name: " . $fullname . "\n";
				$text .= "Email Address: " . $email . "\n";
				$text .= "Phone: " . $phone . "\n";
				$text .= "Comments: \n" . $message;
				$text .= "\n\n---------------------------------------------------------------------------\n\n";
				$text .= "Remote IP: " . $remote_ip . "\n";
				
				$subject = 'Website contact from ' . $fullname;
				
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
					echo '<h4>Message Sent</h4><p>Thank you ' . $fullname . ' for contacting us,  
					we\'ll get back to you as soon as possible at the email address provided <strong>' . $email . '</strong>. 
					If you do not see our email in your inbox please check your Junk/Spam folder. Thank you.</p>';
				}
				else{
					echo '<h4>Error! message not sent</h4>
					<p>An error occurred and your message was not sent. 
					We are very sorry and regret any inconvenience this has caused. 
					Please try again later or call us at ' . CONTACT_PHONE . '. Thank you.</p>';
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
			echo '<div><h4>Error!</h4> Message not sent (6)</div>';
		}
	
	}
	else{
		echo '<div><h4>Error!</h4> Message not sent (1)</div>';
	}

?>
