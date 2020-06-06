<?php 
include("block-hack.php"); // prevent hackers from injecting API calls

    date_default_timezone_set('America/Barbados');

	define("API_ROOT","https://masterpiece-api.com/v2/");
    define('APIKEY', 'b3033468-5c56-4816-aa43-51cce5867d61');
	
    define('HOST_NAME', 'artgallerycaribbean.com');
    define('HOST_KEY', 'AYuGvjkyQJssCKqP');
	
	define('CONTACT_ADDRESS', '<strong>Gallery Of Caribbean Art</strong><br>Northern Business Centre, Queen\'s Street, Speightstown, St. Peter, Barbados');
	define('CONTACT_PHONE', '(246) 419-0858');
	define('CONTACT_EMAIL', 'artgallerycaribbean@caribsurf.com');
	
	define('OPENING_HOURS', '<strong>Opening Hours</strong><br>Monday to Friday 10am to 4pm, Saturday 10am to 2pm');
	
    /********************* Secure Session Start *********************/

    ini_set('session.use_only_cookies',1);
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams['lifetime'], $cookieParams['path'], $cookieParams['domain'], false, true);
    session_name(HOST_KEY);
    session_start(); 
    session_regenerate_id();  

    /********************* Session Token *********************/

    if (empty($_SESSION['token'])) {
        if (function_exists('mcrypt_create_iv')) {
            $_SESSION['token'] = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
        }
        else {
            $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
        }
    }

    /********************* functions *********************/
	
	function removeQueryStringVar($arr, $key) {
		unset($arr[$key]);
		$url = http_build_query($arr);
		return $url;
	}	
	
	function callAPI($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
			
		$result = curl_exec($ch);
		
		curl_close($ch);
		
		return json_decode($result, true);
	}
	
	function redirect($url, $statusCode = 302){
		header('Location: ' . $url, true, $statusCode);
		exit();
	}	
	
	function isAjax(){ 
		return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));
	}
	
    function isSuspect($val, $pattern, &$suspect) {
		if (is_array($val)) {
			foreach ($val as $item) { isSuspect($item, $pattern, $suspect); }
		} 
		else {
			if (preg_match($pattern, $val)) { $suspect = true; }
		}
	}	
	
	function isEmail($str){
		return preg_match("/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)+/", $str);
	}
	
	function isPhone($str){
		return preg_match("/^[0-9-\s+]{5,16}$/", $str);
	}
	
	function obfuse($email){ 
	
		$character_set = '+-.0123456789@ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz';
		
		$key = str_shuffle($character_set); 
		
		$cipher_text = ''; 
		
		$id = 'e'.rand(1,999999999);
		
		for ($i=0; $i<strlen($email); $i+=1) { 
			$cipher_text.= $key[strpos($character_set,$email[$i])]; 
		}
		
		$script = 'var a="'.$key.'";var b=a.split("").sort().join("");var c="'.$cipher_text.'";var d="";';
		$script.= 'for(var e=0;e<c.length;e++)d+=b.charAt(a.indexOf(c.charAt(e)));';
		$script.= 'document.getElementById("'.$id.'").innerHTML="<a href=\\"mailto:"+d+"\\">"+d+"</a>"';
		$script = '<script type="text/javascript">/*<![CDATA[*/'.$script.'/*]]>*/</script>';
		
		return '<span id="'.$id.'">[javascript protected email address]</span>'.$script;
	
	}
	
	function token($type = 1, $length = 8){
		switch($type){
			case 1: $salt = 'ABCDEFGHJKMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789'; break;
			case 2: $salt = 'ABCDEFGHJKMNPRSTUVWXYZ23456789'; break;
			case 3: $salt = 'ABCDEFGHJKMNPRSTUVWXYZ'; break;
			case 4: $salt = '23456789'; break;
			case 5: $salt = '1234567890'; break;
		}
	
		$crypto_rand_secure = function ($min, $max) {
			$range = $max - $min;
			if ($range < 0) return $min;
			$log    = log($range, 2);
			$bytes  = (int) ($log / 8) + 1; 
			$bits   = (int) $log + 1; 
			$filter = (int) (1 << $bits) - 1; 
			do {
				$rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
				$rnd = $rnd & $filter;
			} while ($rnd >= $range);
			return $min + $rnd;
		};
	
		$token = '';
		$max   = strlen($salt);
		for ($i = 0; $i < $length; $i++) {
			$token .= $salt[$crypto_rand_secure(0, $max)];
		}
		return $token;
	}
	
	function paginationLinks($page, $total, $limit, $adjacents, $url, $qs, $qs_offset){		
		$prev = $page - 1;
		$next = $page + 1;
		$lastpage = ceil($total/$limit);
		$lpm1 = $lastpage - 1;
		
		$render = '';
		
		if($lastpage > 1){	
			$render .= '<div class="pagination"><p>';
			
			if ($page > 1){
				$offset = ($prev - 1) * $limit;
				$render.= '<a href="' . $url . $qs . $prev . $qs_offset . $offset . '" id="pagPrev"><i class="fas fa-caret-left"></i> Previous</a>';
			}
			else{
				$render.= '<span class="disabled" id="pagPrev"><i class="fas fa-caret-left"></i> Previous</span>';	
			}
		
			if ($lastpage < 7 + ($adjacents * 2)){	
				for ($counter = 1; $counter <= $lastpage; $counter++){
					if ($counter == $page){
						$render.= '<span class="current">' . $counter . '</span>';
					}
					else{
						$offset = ($counter - 1) * $limit;
						$render.= '<a href="' . $url . $qs . $counter . $qs_offset . $offset . '">' . $counter . '</a>';		
					}
				}
			}
			elseif($lastpage > 5 + ($adjacents * 2)){
				if($page < 1 + ($adjacents * 2)){
					for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++){
						if ($counter == $page){
							$render.= '<span class="current">' . $counter . '</span>';
						}
						else{
							$offset = ($counter - 1) * $limit;
							$render.= '<a href="' . $url . $qs . $counter . $qs_offset . $offset . '">' . $counter . '</a>';	
						}
					}
					
					$render.= '...';
					$offset = ($lpm1 - 1) * $limit;
					$render.= '<a href="' . $url . $qs . $lpm1 . $qs_offset . $offset . '">' . $lpm1 . '</a>';
					$offset = ($lastpage - 1) * $limit;
					$render.= '<a href="' . $url . $qs . $lastpage . $qs_offset . $offset . '">' . $lastpage . '</a>';		
				}
				elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)){
					$render.= '<a href="' . $url . $qs . '1' . $qs_offset . 0 . '">1</a>';
					$render.= '<a href="' . $url . $qs . '2' . $qs_offset . $limit . '">2</a>';
					$render.= '...';
				
					for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++){
						if ($counter == $page){
							$render.= '<span class="current">' . $counter . '</span>';
						}
						else{
							$offset = ($counter - 1) * $limit;
							$render.= '<a href="' . $url . $qs . $counter . $qs_offset . $offset . '">' . $counter . '</a>';		
						}
					}
					
					$render.= '...';
					$offset = ($lpm1 - 1) * $limit;
					$render.= '<a href="' . $url . $qs . $lpm1 . $qs_offset . $offset . '">' . $lpm1 . '</a>';
					$offset = ($lastpage - 1) * $limit;
					$render.= '<a href="' . $url . $qs . $lastpage . $qs_offset . $offset . '">' . $lastpage . '</a>';		
				}
				else{
					$render.= '<a href="' . $url . $qs . '1' . $qs_offset . 0 . '">1</a>';
					$render.= '<a href="' . $url . $qs . '2' . $qs_offset . $limit . '">2</a>';
					$render.= '...';
				
					for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++){
						if ($counter == $page){
							$render.= '<span class="current">' . $counter  . '</span>';
						}
						else{
							$offset = ($counter - 1) * $limit;
							$render.= '<a href="' . $url . $qs . $counter . $qs_offset . $offset . '">' . $counter . '</a>';		
						}
					}
				}
			}
			
			if ($page < $counter - 1) {
				$offset = ($next - 1) * $limit;
				$render.= '<a href="' . $url . $qs . $next . $qs_offset . $offset . '" id="pagNext">Next <i class="fas fa-caret-right"></i></a>';
			}
			else{
				$render.= '<span class="disabled" id="pagNext">Next <i class="fas fa-caret-right"></i></span>';
			}
			
			$render.= '</p></div>';
		}
		
		return $render;
	} 
	
	/********************* classes *********************/
    
    class Shopcart{
		var $cookie_name = 'cart_items';
				
		function __construct() {
			
			if(!isset($_COOKIE[$this->cookie_name])){
				setcookie($this->cookie_name, '', time() + (86400 * 30), '/');
				$_COOKIE[$this->cookie_name] = '';
			}
			
		}
		function items() {
			$cookie = isset($_COOKIE[$this->cookie_name]) ? $_COOKIE[$this->cookie_name] : '';
			$cookie = stripslashes($cookie);
			$items = json_decode($cookie, true);
		 
			if(!$items){
				$items = array();
			}
			return $items;
		}
		function emptyCart() {
			unset($_COOKIE[$this->cookie_name]);
			setcookie($this->cookie_name, '', time() + (86400 * 30), '/');
			$_COOKIE[$this->cookie_name] = '';
			return true;
		}
		function add($id) {
			
			$code = 0;
			
			$items = $this->items();
			
			if(!array_key_exists(intval($id), $items)){
				
				$cart_items[$id] = array(
					'qty' => 1
				);				
				
				if(count($items) > 0){
					foreach($items as $key => $value){
						
						$cart_items[$key] = array(
							'qty' => 1
						);

					}
				}
		 
				$json = json_encode($cart_items, true);
				setcookie($this->cookie_name, $json, time() + (86400 * 30), '/');
				$_COOKIE[$this->cookie_name] = $json;
				
				$code = 1;
			 
			}
			
			return $code;
			
		}
		function remove($id){
			
			$code = 0;
			
			$items = $this->items();
			
			if(array_key_exists(intval($id), $items)){
				
				unset($items[$id]);				
				
				unset($_COOKIE[$this->cookie_name]);
				
				setcookie($this->cookie_name, '', time() - 3600);
				
				$json = json_encode($items, true);
				setcookie($this->cookie_name, $json, time() + (86400 * 30), '/');
				$_COOKIE[$this->cookie_name] = $json;
				
				$code = 1;
			 
			}
			
			return $code;
			
		}
		function qty() {
			$items = $this->items();
			return empty($items) ? 0 : count($items);
		}
		function show(){
			$qty = $this->qty();
			
			return '<a href="/cart.php" title="View Cart"><span><i class="fas fa-shopping-cart"></i></span> cart (<em id="cartQty">' . $qty . '</em>)</a>';
		}
	}

	/********************* startup *********************/

    $cart = new Shopcart();
			
?>
