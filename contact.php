<?php 

	include 'libs/comm.php';

?>
<!doctype html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="HandheldFriendly" content="True">
<meta name="MobileOptimized" content="320">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta http-equiv="cleartype" content="on">
<title>Contact the Gallery of Caribbean Art</title>
<meta name="description" content="The Gallery of Caribbean Art contact page">
<link rel="shortcut icon" href="/favicon.ico">
<?php include 'includes/styles.php';?>
</head>
<body>

<div class="overlay"><em>Loading</em></div>

<div id="lbModal">
    <div id="lbModalBody"></div>
    <div id="lbModalFoot"><span id="lbModalClose"><i class="fas fa-times"></i> close</span></div>
</div>

<?php include 'includes/header.php';?>

<div id="content"><div id="contentInner">
    
    <h1>Contact us</h1>
    
    <div class="col2">
        <div class="col2Left">
            
            <?php 
            
                echo '<div id="formHeader"><p>' . str_replace(',', '<br>', CONTACT_ADDRESS) . '</p>';
                echo '<p>' . OPENING_HOURS . '</p>';
                echo '<p><i class="fas fa-phone"></i> ' . CONTACT_PHONE . ' <br><i class="fas fa-at"></i> ' . obfuse(CONTACT_EMAIL) . '</p></div>';
            
            ?>
            
            <form id="form1" method="post" action="/contact.php">
                
                <fieldset>
                    
                   <legend>Contact Form</legend> 
                    
                    <input type="text" name="fullname" id="fullname" value="" placeholder="Your Name" maxlength="100" required>
                    
                    <input type="email" name="email" id="email" value="" placeholder="Your Email Address" maxlength="120" required>
                    
                    <input type="text" name="phone" id="phone" value="" placeholder="Your Phone Number" maxlength="50" required>
                    
                    <textarea name="message" id="message" placeholder="Your Message" rows="5" required></textarea>
                    
                    <div style="position: absolute; left: -5000px;" aria-hidden="true">
                        <label>Keep this field blank</label>
                        <input type="text" name="paperclip" id="paperclip" tabindex="-1" value="">
                    </div>
                    
                    <input type="hidden" name="loadtime" value="<?php echo time();?>">
					<input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>">
                    <input type="hidden" name="remote_ip" value="<?php echo $_SERVER['REMOTE_ADDR'];?>">
                    
                    <div class="fbtn-right"><button type="submit" id="sndbtn"><i class="far fa-envelope"></i> send</button></div>
                    
                </fieldset>
                
            </form>
    
        </div>
    </div>
    
    <div class="col2">
        <div class="col2Right">
            
            <div class="google-maps">
                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d10984.588921953182!2d-59.638265478416464!3d13.247978631775496!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x85b01953f7e0d875!2sGallery+of+Caribbean+Art!5e0!3m2!1sen!2s!4v1436829085851" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
            </div>
            
        </div>
    </div>
    
    <div class="clear"></div>

</div></div>   

<?php include 'includes/footer.php';?>

<script type="text/javascript">

	var form1 = $('#form1'),
		fullname = $('#fullname'), 
		email = $('#email'), 
		phone = $('#phone'), 
		message = $('#message'),
		paperclip = $('#paperclip'),
		sndbtn = $('#sndbtn');
		
	var overlay = $('.overlay'),
        lbModal = $('#lbModal'),
		lbModalClose = $('#lbModalClose'),
		lbModalBody = $('#lbModalBody');

	form1.submit(function(event) {
		
		event.preventDefault();
		
		if(!isEmpty(paperclip.val())) {return false;}
		if(isEmpty(fullname.val())) {alert('Name Required'); fullname.focus(); return false;}
		if(!isEmail(email.val())) {alert('Valid Email Address Required'); email.focus(); return false;}
		if(!isPhone(phone.val())) {alert('Contact Number Required'); phone.focus(); return false;}
		if(isEmpty(message.val())) {alert('Message Required'); message.focus(); return false;}
		
		sndbtn.prop('disabled', true);
		
		overlay.addClass('spinner').show();
		
		$.ajax({
			method: 'POST',
			url: '/ajax/contact.php',
			data: form1.serialize()
		}).done(function(msg) {
			overlay.removeClass('spinner').empty();
			
			form1[0].reset();
			
			lbModalBody.html(msg);
			lbModalClose.click(function() {
				sndbtn.prop('disabled', false);
				lbModal.hide();
				overlay.hide();
				window.location.replace('/');
			});
			lbModal.center(450).show();
		});
		
	});

</script>

</body>
</html>