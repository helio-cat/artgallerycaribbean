<?php 

	include 'libs/comm.php';
	
	$artists = callAPI(API_ROOT.'artists/?key='. APIKEY . '&fields=artistId,firstName,lastName,itemPicture&limit=250');

?>
<!doctype html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="HandheldFriendly" content="True">
<meta name="MobileOptimized" content="320">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta http-equiv="cleartype" content="on">
<title>Gallery of Caribbean Art - Caribbean Artists</title>
<meta name="description" content="The only art gallery in the Caribbean committed to promoting the art of the entire Caribbean region">
<link rel="shortcut icon" href="/favicon.ico">
<?php include 'includes/styles.php';?>
</head>
<body>

<?php 

	include 'includes/header.php';

	echo '<div id="content">';
	
		if($artists['total'] == 0){
			
			echo '<div class="textcenter">';
			
				echo '<h1>Caribbean Artists</h1>';
				echo '<p>Sorry, no artists found.</p>';
				
			echo '</div>';	
			
		}
		else{
			
			echo '<div id="pageHead">';
			
				echo '<h1>Caribbean Artists</h1>';
				
				echo '<p><select name="artistList" onchange="onSelectRedirect(this)">';
				echo '<option value="/artists.php" disabled selected>Select Artist</option>';
				foreach($artists['artists'] as $result){
					$fname = empty($result['firstName']) ? '' : $result['firstName'];
					$lname = empty($result['lastName']) ? '' : $result['lastName'] . ', ';
					echo '<option value="/portfolio.php?artistId=' . $result['artistId'] . '">' . $lname . $fname . '</option>';
				}
				echo '</select></p>';
			
			echo '</div>';
			
			echo '<div id="artistGallery">';
			
				echo '<ul>';
				
					foreach($artists['artists'] as $result){
						$fname = empty($result['firstName']) ? '' : $result['firstName'];
						$lname = empty($result['lastName']) ? '' : $result['lastName'] . ', ';
						
						$full_name = $lname . $fname;
						
						if(empty($result['itemPicture'])){
							
							echo '<li>&nbsp;</li>';
							
						}
						else{
							
							echo '<li>';
							
								echo '<img src="' . $result['itemPicture'] . '">';
								
								echo '<span>';
								
									echo '<a href="/portfolio.php?artistId=' . $result['artistId'] . '" title="View Portfolio">' . $full_name . '</a>';
								
								echo '</span>';
							
							echo '</li>';
							
						}
						
					}
					
				echo '</ul>';
			
			echo '</div>';
			
			echo '<div class="clear"></div>';
			
		}
	
	echo '</div>';

	include 'includes/footer.php';
		
?>
               
</body>
</html>