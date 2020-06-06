<?php 

	include 'libs/comm.php';
	
	$qvars = array(
		'artistId' => '',
		'category' => '',
		'medium' => ''
	);
	
    $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;

    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
	
    $search_string = array();
	
	if(isset($_GET['artistId']) && !empty($_GET['artistId'])){
		$qvars['artistId'] = intval($_GET['artistId']);
	}
	
	if(isset($_GET['category']) && !empty($_GET['category'])){
		$qvars['category'] = strip_tags(trim($_GET['category']));
        $search_string[] = $qvars['category'];
	}
	
	if(isset($_GET['medium']) && !empty($_GET['medium'])){
		$qvars['medium'] = strip_tags(trim($_GET['medium']));
        $search_string[] = $qvars['medium'];
	}
	
	$limit = 40;
	
	$qs = http_build_query($qvars);
	
	/***** gallery items *****/
	
	$items = callAPI(API_ROOT.'items/?key='. APIKEY . '&' . $qs . '&q=stockLocation|Stock&fields=artistId,title,itemId,firstName,lastName,width,height,category,medium,retailPrice,pictures&sortOptions=itemId|desc&offset=' . $offset . '&limit=' . $limit);
	
	$total = $items['total'];
	
	/***** artist list *****/
	
	$artists = callAPI(API_ROOT.'artists/?key='. APIKEY . '&fields=artistId,firstName,lastName&limit=250');
	
	/***** categories *****/
	
	$categories = callAPI(API_ROOT.'categories/?key='. APIKEY . '&fields=category,categoryId');
	
	/***** mediums *****/
	
	$mediums = callAPI(API_ROOT.'mediums/?key='. APIKEY . '&fields=medium,mediumId');
	
    $artist_name = '';

?>
<!doctype html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="HandheldFriendly" content="True">
<meta name="MobileOptimized" content="320">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta http-equiv="cleartype" content="on">
<title>Gallery Of Caribbean Art</title>
<meta name="description" content="The only art gallery in the Caribbean committed to promoting the art of the entire Caribbean region">
<link rel="shortcut icon" href="/favicon.ico">
<?php include 'includes/styles.php';?>
</head>
<body>

<div class="overlay"><em>Loading</em></div>

<div id="lbModal">
    <div id="lbModalBody"></div>
    <div id="lbModalFoot"><span id="lbModalClose"><i class="fas fa-times"></i> close</span></div>
</div>    

<?php 

	include 'includes/header.php';

	echo '<div id="content">';
		
		echo '<div id="pageHead">';
    
            echo '<h1>Gallery of Caribbean Art</h1>';
		
			echo '<p>';
				
				echo '<select name="artistList" onchange="onSelectRedirect(this)">';
				echo '<option value="/gallery.php" disabled selected>Filter by Artist</option>';
				echo '<option value="/gallery.php?' . removeQueryStringVar($qvars, 'artistId') . '&artistId=">None</option>';
				foreach($artists['artists'] as $result){
                    $fname = $lname = $ffname = $llname = $selected = '';
                    
                    if(!empty($result['firstName'])){
                        $fname = $result['firstName'];
                        $ffname = $result['firstName'] . ' ';
                    }
                    
                    if(!empty($result['lastName'])){
                        $lname = $result['lastName'] . ', ';
                        $llname = $result['lastName'];
                    }
					
                    if($result['artistId'] == $qvars['artistId']){
                        $selected = ' selected';
                        $artist_name = $ffname . $llname;
                    }
                    
                    echo '<option value="/gallery.php?' . removeQueryStringVar($qvars, 'artistId') . '&artistId=' . $result['artistId'] . '"' . $selected . '>' . $lname . $fname . '</option>';
				}
				echo '</select>';
				
				echo '<select name="category" onchange="onSelectRedirect(this)">';
				echo '<option value="/gallery.php" disabled selected>Filter by Category</option>';
				echo '<option value="/gallery.php?' . removeQueryStringVar($qvars, 'category') . '&category=">None</option>';
				foreach($categories['categories'] as $category){
					$_cat = trim($category['category']);
                    $selected = $_cat == $qvars['category'] ? ' selected' : '';
					echo '<option value="/gallery.php?' . removeQueryStringVar($qvars, 'category') . '&category=' . urlencode($_cat) . '"' . $selected . '>' . $_cat . '</option>';
				}
				echo '</select>';
				
				echo '<select name="medium" onchange="onSelectRedirect(this)">';
				echo '<option value="/gallery.php" disabled selected>Filter by Medium</option>';
				echo '<option value="/gallery.php?' . removeQueryStringVar($qvars, 'medium') . '&medium=">None</option>';
				foreach($mediums['mediums'] as $medium){
					$_med = trim($medium['medium']);
                    $selected = $_med == $qvars['medium'] ? ' selected' : '';
					echo '<option value="/gallery.php?' . removeQueryStringVar($qvars, 'medium') . '&medium=' . urlencode($_med) . '"' . $selected . '>' . $_med . '</option>';
				}
				echo '</select>';
				
			echo '</p>';
			
            if(!empty($artist_name)){
                echo '<h3>' . $artist_name . '</h3>';
            }
    
            if(!empty($search_string)){
                echo '<h3>' . implode(', ', $search_string) . '</h3>';
            }
			
			echo '<p>' . $total . ' Pieces shown from newest to oldest</p>';
		
		echo '</div>';
		
		if($items['total'] != 0){
		
			echo '<div id="galleryItems">';
			
				echo '<ul>';
				foreach($items['items'] as $result){				
					$fname = empty($result['firstName']) ? '' : $result['firstName'];
					$lname = empty($result['lastName']) ? '' : $result['lastName'] . ', ';
					
					$fullname = $lname . $fname;
					
					if(!empty($result['pictures'])){
					
						$dimentions = 'W' . round($result['width']) . 'in x H' . round($result['height']) . 'in';
						
						echo '<li>';
							
							echo '<dl>';
							
								echo '<dt>Artist:</dt> <dd>' . $fullname . '</dd>';
								echo '<dt>Title:</dt> <dd>' . $result['title'] . '</dd>';
								echo '<dt>Category:</dt> <dd>' . $result['category'] . '</dd>';
								echo '<dt>Medium:</dt> <dd>' . $result['medium'] . '</dd>';
								echo '<dt>Dimensions:</dt> <dd>' . $dimentions . '</dd>';
								echo '<dt>Item Number:</dt> <dd>' . $result['itemId'] . '</dd>';
								echo '<dt>Price:</dt> <dd>$' . $result['retailPrice'] . ' BBD</dd>';			
							
							echo '</dl>';
							
							echo '<a href="/item.php?itemId=' . $result['itemId'] . '" 
							data-img="' . $result['pictures'][0] . '" 
							data-artist="' . $fullname . '" 
							data-category="' . $result['category'] . '" 
							data-medium="' . $result['medium'] . '" 
							data-dimensions="' . $dimentions . '" 
							data-itemid="' . $result['itemId'] . '" 
							data-price="' . $result['retailPrice'] . '" 
							rel="lightbox-photos" 
							title="' . $result['title'] . '">
							<img src="' . $result['pictures'][0] . '"></a>';
							
							echo '<span><em id="showinfo"><i class="fas fa-info-circle"></i> info</em> <em id="addtocart" data-itemid2="' . $result['itemId'] . '"><i class="fas fa-shopping-cart"></i> add</em></span>';
							
						echo '</li>';
						
					}
				}
				echo '</ul>';
			
			echo '</div>';
			
			echo '<div class="clear"></div>';
			
			echo paginationLinks($page, $total, $limit, 3, '/gallery.php?' . $qs, '&page=', '&offset=');
		
		}
		else{
			echo '<p class="textcenter">Sorry no search results returned</p>';
		}
		
	echo '</div>';

	include 'includes/footer.php';
		
?>
    
<script type="text/javascript" src="/js/slimbox2-cart.js"></script>

<script type="text/javascript">
	
	var overlay = $('.overlay'),
		lbModal = $('#lbModal'),
		lbModalClose = $('#lbModalClose'),
		lbModalBody = $('#lbModalBody');
		
	var	cartQty = $('#cartQty'),
		$items = $('#galleryItems li'),
        pleaseNote = $('#pleaseNote');
	
    $("a[rel^='lightbox']").slimbox({}, null, function(el) {
        return (this == el) || ((this.rel.length > 8) && (this.rel == el.rel));
    });
	
    if(jQuery.browser.mobile){
                
        $items.each(function () {
            var $self = $(this);

            $('span em#showinfo', this).click(function(){ 
                $('dl', $self).slideToggle();
            });
        });
        
    }
    else{
        $items.each(function () {
            var $self = $(this);

            $('span em#addtocart', this).click(function(){ 
                addtocart($(this).data('itemid2'));
            });
			
			$('span em#showinfo', this).mouseenter(function(){ 
                $('dl', $self).slideDown('slow');
            }).mouseleave(function(){
                $('dl', $self).slideUp('slow');
            });
        });
    }
    
    function addtocart(id){
		
		overlay.addClass('spinner').show();
		
		$.ajax({
			type: 'POST',
			url: '/ajax/cart_add.php',
			dataType: 'json',
			data: {id: id}
		}).done(function(resp) {
			overlay.removeClass('spinner').empty();
			
			switch (resp.code) {
				case 0: 
					lbModalBody.html('<div>Item already in your Shopping Cart<br><br><i class="fas fa-shopping-cart"></i> <a href="/cart.php" title="">view cart</a></div>');
				break;
				case 1: 
					lbModalBody.html('<div><h4>Success!</h4> Item added to your Shopping Cart<br><br><i class="fas fa-shopping-cart"></i> <a href="/cart.php" title="">view cart</a></div>');
					
					cartQty.text(resp.qty);
				break;
			}
			
			lbModalClose.click(function() {
				lbModal.hide();
				overlay.hide();
			});
			lbModal.center(450).show();
			
		});
		
	}
	
</script>
                
</body>
</html>