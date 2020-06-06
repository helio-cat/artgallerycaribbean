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
<title>About the Gallery of Caribbean Art</title>
<meta name="description" content="Visit the Gallery of Caribbean Art for the finest collection of art from Barbados and the Caribbean">
<link rel="shortcut icon" href="/favicon.ico">
<?php include 'includes/styles.php';?>
</head>
<body>

<?php 
	
	include 'includes/header.php';

	echo '<div id="content"><div id="contentInner">';
	
		include 'html/about.html';
	
	echo '</div></div>';
		
	include 'includes/footer.php';
    
?>

</body>
</html>