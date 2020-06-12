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



$items = callAPI(API_ROOT.'items/?key='. APIKEY . '&' . $qs . '&q=stockLocation|Stock&fields=artistId,title,itemId,firstName,lastName,width,height,category,medium,retailPrice,pictures&sortOptions=itemId|desc&offset=' . $offset . '&limit=' . $limit);


echo '<pre>';
print_r($items);
echo '</pre>';


?>