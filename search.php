<?php 

	include 'libs/comm.php';
	
	/***** artist list *****/
	
	$artists = callAPI(API_ROOT.'artists/?key='. APIKEY . '&fields=artistId,firstName,lastName&limit=250');
	
	/***** categories *****/
	
	$categories = callAPI(API_ROOT.'categories/?key='. APIKEY . '&fields=category,categoryId');
	
	/***** mediums *****/
	
	$mediums = callAPI(API_ROOT.'mediums/?key='. APIKEY . '&fields=medium,mediumId');
	
?>
<!doctype html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="HandheldFriendly" content="True">
<meta name="MobileOptimized" content="320">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta http-equiv="cleartype" content="on">
<title>Search the Gallery of Caribbean Art</title>
<meta name="description" content="Search the Gallery of Caribbean Art and find the perfect artwork">
<link rel="shortcut icon" href="/favicon.ico">
<?php include 'includes/styles.php';?>
</head>
<body>

<?php 

	include 'includes/header.php';

?>

<div id="content"><div id="contentInner">
    
    <div class="textcenter">
    
        <h1>Search</h1>
        
        <p>Choose one or more search criteria</p>
    
    </div>
    
    <form id="form1" method="get" action="/search-results.php">
    
        <div class="col2">
            <div class="col2Left">
                
			<?php 
			
				echo '<select name="artistId" id="artistId">';
				echo '<option value="" disabled selected>Select Artist</option>';
				foreach($artists['artists'] as $artist){
					$fname = empty($artist['firstName']) ? '' : $artist['firstName'];
					$lname = empty($artist['lastName']) ? '' : $artist['lastName'] . ', ';
					echo '<option value="' . $artist['artistId'] . '">' . $lname . $fname . '</option>';
				}
				echo '</select>';
				
				echo '<select name="category" id="category">';
				echo '<option value="" disabled selected>Select Category</option>';
				foreach($categories['categories'] as $category){
					echo '<option value="' . $category['categoryId'] . '">' . trim($category['category']) . '</option>';
				}
				echo '</select>';
				
				echo '<select name="medium" id="medium">';
				echo '<option value="" disabled selected>Select Medium</option>';
				foreach($mediums['mediums'] as $medium){
					echo '<option value="' . $medium['mediumId'] . '">' . trim($medium['medium']) . '</option>';
				}
				echo '</select>';
			
			?>
        
            </div>
        </div>
        
        <div class="col2">
            <div class="col2Right">
                
                <input type="text" name="itemId" id="itemId" value="" placeholder="Item number" maxlength="20"> 
                
                <input type="text" name="title" id="title" value="" placeholder="Title" maxlength="80">    
                
                <input type="text" name="subject" id="subject" value="" placeholder="Subject" maxlength="80">
            
                <div class="fcol">
                	<div class="fcol-linner"><input type="text" name="minprice" id="minprice" value="" placeholder="Minimum Price"></div>
                </div>
                
                <div class="fcol">
                	<div class="fcol-rinner"><input type="text" name="maxprice" id="maxprice" value="" placeholder="Maximum Price"></div>
                </div>
                
            </div>
        </div>
        
        <div class="clear"></div>
        
        <input type="hidden" name="page" value="1">
        <input type="hidden" name="offset" value="0">
        
        <div class="fbtn-center"><button type="submit" id="sndbtn"><i class="fas fa-search"></i> Search</button></div>
    
    </form>
        
</div></div>

<?php 

	include 'includes/footer.php';
		
?>
               
</body>
</html>