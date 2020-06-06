<?php 

	include 'libs/comm.php';
	
	$artistId = isset($_GET['artistId']) ? intval($_GET['artistId']) : '';
	$category = isset($_GET['category']) ? intval($_GET['category']) : '';
	$medium = isset($_GET['medium']) ? intval($_GET['medium']) : '';
	$itemId = isset($_GET['itemId']) ? strip_tags(trim($_GET['itemId'])) : '';
	$title = isset($_GET['title']) ? strip_tags(trim($_GET['title'])) : '';
	$subject = isset($_GET['subject']) ? strip_tags(trim($_GET['subject'])) : '';
	$minprice = isset($_GET['minprice']) ? intval($_GET['minprice']) : '';
	$maxprice = isset($_GET['maxprice']) ? intval($_GET['maxprice']) : '';
	
	$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
	
	$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
	
	$limit = 40;
	
	$equals = array();
	if(!empty($itemId)){ $equals[] = '&itemId=' . $itemId; }
	if(!empty($artistId)){ $equals[] = '&artistId=' . $artistId; }
	if(!empty($category)){ $equals[] = '&category=' . $category; }
	if(!empty($medium)){ $equals[] = '&medium=' . $medium; }
	
	$like = array();
	$like[] = 'stockLocation|Stock';
	if(!empty($title)){ $like[] = 'title|' . $title; }
	if(!empty($subject)){ $like[] = 'subject|' . $subject; }
	
	$compare = array();
	if(!empty($minprice)){ $compare[] = 'retailPrice|' . $minprice . '|>'; }
	if(!empty($maxprice)){ $compare[] = 'retailPrice|' . $maxprice . '|<'; }
	
	$qs = !empty($equals) ? implode('', $equals) : '';
	$qs2 = '&q=' . implode(',', $like);
	$qs3 = !empty($compare) ? '&compare=' . implode(',', $compare) : '';
	
	$items = callAPI(API_ROOT.'items/?key='. APIKEY . $qs . $qs2 . $qs3 . '&fields=artistId,title,itemId,firstName,lastName,category,medium,retailPrice,width,height,pictures&sortOptions=itemId|desc&offset=' . $offset . '&limit=' . $limit);
		
	$query_string = http_build_query($_GET);
	
	$total = $items['total'];

?>
<!doctype html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="HandheldFriendly" content="True">
<meta name="MobileOptimized" content="320">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta http-equiv="cleartype" content="on">
<title>Search Results</title>
<meta name="description" content="Search Results">
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
		
		echo '<h1 class="textcenter">Search Results</h1>';
		
		if(!empty($items['items'])){
		
			echo '<div id="pageHead"><p>Total: ' . $total . '</p></div>';
            
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
			
			echo paginationLinks($page, $total, $limit, 3, '/search-results.php?' . $query_string, '&page=', '&offset=');
		
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
		$items = $('#galleryItems li');
	
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
			lbModal.center(350).show();
			
		});
		
	}
	
</script>
                
</body>
</html>