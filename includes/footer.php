<?php 
	
	echo '<div id="footer">
	
            <div id="footerInner">
		
			<div class="footer-col">
			
				<div class="footer-col-inner">
					
					<h4>Links</h4>
					
					<ul id="footerContactLinks">';
                       
                        foreach($menu as $key => $val){
                            $class = basename($val[0]) == $current_page ? ' class="active"' : '';
                            echo '<li><a href="/' . $val[0] . '" title="' . $val[1] . '"' . $class . '>' . $key . '</a></li>';
                        } 
                        
                        $privacyLinkState = 'privacy.php' == $current_page ? ' class="active"' : '';
                        echo '<li><a href="/privacy.php" title="Our Privacy Policy"' . $privacyLinkState . '>Privacy</a></li>
                       
					</ul>
					
				</div>
			
			</div>
			
			<div class="footer-col">
			
				<div class="footer-col-inner">
				
					<h4>Contact</h4>
				
					<p>' . str_replace(',', '<br>', CONTACT_ADDRESS) . '</p>
                    
                    <p>' . str_replace(',', '<br>', OPENING_HOURS) . '</p>
					
					<p><i class="fas fa-phone"></i> ' . str_replace(',', '<br>', CONTACT_PHONE) . '<br>
					
					<i class="fas fa-at"></i> ' . obfuse(CONTACT_EMAIL) . '</p>
				
				</div>
				
			</div>
			
			<div class="footer-col">
			
				<div class="footer-col-inner">
				
					<h4>Follow us</h4>
				
					<ul id="footerSocialLinks">
                        <li><a href="https://www.facebook.com/artgallerycaribbean" target="_blank" title="Gallery of Caribbean Art Facebook Page"><i class="fab fa-facebook"></i></a></li>
                        <li><a href="https://twitter.com/caribbeanart0" target="_blank" title="Gallery of Caribbean Art Twitter Page"><i class="fab fa-twitter-square"></i></a></li>  
                        <li><a href="https://www.instagram.com/artgallerycaribbean/" target="_blank" title="Gallery of Caribbean Art Instagram Page"><i class="fab fa-instagram"></i></a></li>
					</ul>
					
					<h4>About</h4>
					
					<p>Gallery of Caribbean Art is the only gallery in the region committed to promoting the art of the entire Caribbean 
                    from Haiti and Cuba in the north through Jamaica, Barbados and Guyana in the south.</p>
					
				</div>
			
			</div>
		
		</div>
        
	
	</div>
    
    <div id="footerCaption">
        
        <p>&copy;' . date('Y') . ' <a href="/" title="Gallery of Caribbean Art">' . HOST_NAME . '</a></p>
        
        <p>				<?php include_once("mm-link.php"); ?>
</p>
        
    </div>';

?>

<script type="text/javascript">
    
	if(jQuery.browser.mobile){
		
        $('#pull').click(function() {
			$('#headerRight ul').slideToggle();
		});
    
        if ($(window).width() < 575) {
            
            $(window).scroll(function() {
                if ($('#headerRight ul').is(":visible")){
                    $('#headerRight ul').slideUp();
                }
            });
            
        }
    
	}

</script>
