<script type="text/javascript" src="/js/jquery-3.4.1.min.js"></script>
<script type="text/javascript" src="/js/base.js"></script>

<?php 

	$current_page =  basename($_SERVER['PHP_SELF']);
	
	$menu = array (
		'Home' => array('index.php', 'Home Page'),
		'Gallery' => array('gallery.php', 'Caribbean Art Gallery'),
		'Artists' => array('artists.php', 'Caribbean Artists'),
		'Events' => array('events.php', 'Events'),
		'Search' => array('search.php', 'Search for Artwork'),
		'About' => array('about.php', 'About the Gallery Of Caribbean Art'),
		'Contact' => array('contact.php', 'Contact the Gallery Of Caribbean Art')
	);
	
echo '<div id="caption">The only art gallery in the Caribbean committed to promoting the art of Barbados and the entire region</div>';
	
echo '<div id="header">';

    echo '<div id="headerInner">';
	
		echo '<div id="headerLeft">';
		
			echo '<a href="/" title="Gallery of Caribbean Art"><img src="/images/goca-logo4.png" width="224" height="92" alt="Gallery of Caribbean Art Logo"></a>';
		
		echo '</div>';

        echo '<div id="headerRight">';

            echo '<ul>';
            foreach($menu as $key => $val){
                $class = basename($val[0]) == $current_page ? ' class="active"' : '';
                echo '<li><a href="/' . $val[0] . '" title="' . $val[1] . '"' . $class . '>' . $key . '</a></li>';
            }
            echo '</ul>';

            echo '<a href="javascript:void(0)" id="pull"><i class="fas fa-bars"></i> Menu</a>';

        echo '</div>';
		
	echo '</div>';

echo '</div>';

echo '<div id="showCart"><div id="showCartInner">' . $cart->show() . '</div></div>';

?>
