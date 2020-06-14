<?php

echo '<div itemscope itemtype="http://schema.org/Product">
        <meta itemprop="googleProductCategory" content="500044 - Home & Garden > Decor > Artwork > Posters, Prints, & Visual Artwork"/>
        <meta itemprop="brand" content="'. $fullname .'"/>
        <meta itemprop="name" content="' . $result['title'] . '"/>
        <meta itemprop="productID" content="' . $result['itemId'] . '"/>
        <meta itemprop="url" content="https://artgallerycaribbean.com/item.php?itemId=' . $result['itemId'] . '"/>
        <meta itemprop="image" content="' . $result['pictures'][0] . '"/>
        <meta itemprop="description" content=" ' . $result['title'] . ' by ' . $fullname . '. ' . $result['medium'] . ' - ' . $dimentions . '"/>
        <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
            <link itemprop="availability" href="https://schema.org/InStock"/>
            <link itemprop="itemCondition" href="https://schema.org/NewCondition"/>
            <meta itemprop="price" content="' . $result['retailPrice'] . '"/>
            <meta itemprop="priceCurrency" content="BBD"/>
        </div>
</div>';

?>

