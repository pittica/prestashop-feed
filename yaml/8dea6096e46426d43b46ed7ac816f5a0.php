<?php return array (
  'name' => 'AngarTheme',
  'display_name' => 'AngarTheme',
  'version' => '2.4.4',
  'theme_key' => '4c7fd674df47e176f77e9cb6dcd5337e',
  'author' => 
  array (
    'name' => 'AngarThemes',
    'email' => 'https://addons.prestashop.com/en/contact-us?id_product=30887',
    'url' => 'https://addons.prestashop.com/en/2_community-developer?contributor=147992',
  ),
  'meta' => 
  array (
    'compatibility' => 
    array (
      'from' => '1.7.0.0',
      'to' => NULL,
    ),
    'available_layouts' => 
    array (
      'layout-full-width' => 
      array (
        'name' => 'Full Width',
        'description' => 'No side columns, ideal for distraction-free pages such as product pages.',
      ),
      'layout-both-columns' => 
      array (
        'name' => 'Three Columns',
        'description' => 'One large central column and 2 side columns.',
      ),
      'layout-left-column' => 
      array (
        'name' => 'Two Columns, small left column',
        'description' => 'Two columns with a small left column',
      ),
      'layout-right-column' => 
      array (
        'name' => 'Two Columns, small right column',
        'description' => 'Two columns with a small right column',
      ),
    ),
  ),
  'assets' => 
  array (
    'css' => 
    array (
      'all' => 
      array (
        0 => 
        array (
          'id' => 'bxslider',
          'path' => 'assets/css/libs/jquery.bxslider.css',
        ),
        1 => 
        array (
          'id' => 'font-awesome',
          'path' => 'assets/css/font-awesome.css',
        ),
        2 => 
        array (
          'id' => 'angartheme',
          'path' => 'assets/css/angartheme.css',
        ),
        3 => 
        array (
          'id' => 'homemodyficators',
          'path' => 'assets/css/home_modyficators.css',
        ),
        4 => 
        array (
          'id' => 'rwd',
          'path' => 'assets/css/rwd.css',
        ),
        5 => 
        array (
          'id' => 'black',
          'path' => 'assets/css/black.css',
        ),
      ),
    ),
    'js' => 
    array (
      'all' => 
      array (
        0 => 
        array (
          'id' => 'bxslider',
          'path' => 'assets/js/libs/jquery.bxslider.min.js',
        ),
        1 => 
        array (
          'id' => 'angartheme',
          'path' => 'assets/js/angartheme.js',
        ),
      ),
    ),
  ),
  'global_settings' => 
  array (
    'configuration' => 
    array (
      'PS_IMAGE_QUALITY' => 'png',
      'PS_PRODUCTS_PER_PAGE' => 12,
      'BLOCK_CATEG_ROOT_CATEGORY' => 0,
      'BLOCKSOCIAL_FACEBOOK' => 'https://www.facebook.com/',
      'BLOCKSOCIAL_TWITTER' => 'https://twitter.com/',
      'BLOCKSOCIAL_YOUTUBE' => 'https://www.youtube.com/',
      'BLOCKSOCIAL_GOOGLE_PLUS' => 'https://plus.google.com/',
      'BLOCKSOCIAL_PINTEREST' => 'https://pinterest.com',
      'BLOCKSOCIAL_INSTAGRAM' => 'https://www.instagram.com/',
    ),
    'modules' => 
    array (
      'to_enable' => 
      array (
        0 => 'angarfastconfig',
        1 => 'angarbanners',
        2 => 'angarbestsellers',
        3 => 'angarcatproduct',
        4 => 'angarcmsdesc',
        5 => 'angarcmsinfo',
        6 => 'angarfacebook',
        7 => 'angarfeatured',
        8 => 'angarhomecat',
        9 => 'angarmanufacturer',
        10 => 'angarnewproducts',
        11 => 'angarparallax',
        12 => 'angarslider',
        13 => 'angarspecials',
        14 => 'angarcontact',
        15 => 'angarscrolltop',
        16 => 'angarthemeconfigurator',
        17 => 'productcomments',
        18 => 'ps_categoryproducts',
        19 => 'ps_linklist',
      ),
      'to_disable' => 
      array (
        0 => 'ps_imageslider',
        1 => 'ps_banner',
        2 => 'ps_featuredproducts',
        3 => 'ps_bestsellers',
        4 => 'ps_newproducts',
        5 => 'ps_specials',
        6 => 'ps_customtext',
        7 => 'ps_contactinfo',
        8 => 'gamification',
      ),
    ),
    'hooks' => 
    array (
      'custom_hooks' => 
      array (
        0 => 
        array (
          'name' => 'angarCmsDesc',
          'title' => 'angarCmsDesc',
          'description' => 'Angar hook',
        ),
      ),
      'modules_to_hook' => 
      array (
        'displayBanner' => 
        array (
          0 => 'angarbanners',
        ),
        'displayNav1' => 
        array (
          0 => 'angarcontact',
        ),
        'displayNav2' => 
        array (
          0 => 'ps_customersignin',
          1 => 'ps_languageselector',
          2 => 'ps_currencyselector',
        ),
        'displayTop' => 
        array (
          0 => 'ps_shoppingcart',
          1 => 'ps_searchbar',
        ),
        'displayNavFullWidth' => 
        array (
          0 => 'ps_mainmenu',
        ),
        'displayTopColumn' => 
        array (
          0 => 'angarslider',
          1 => 'angarbanners',
        ),
        'displayLeftColumn' => 
        array (
          0 => 'ps_categorytree',
          1 => 'ps_linklist',
          2 => 'ps_facetedsearch',
          3 => 'ps_brandlist',
          4 => 'ps_supplierlist',
          5 => 'angarbestsellers',
          6 => 'angarbanners',
        ),
        'displayRightColumn' => 
        array (
          0 => 'angarbanners',
        ),
        'displayHomeTab' => 
        array (
          0 => 'angarnewproducts',
          1 => 'angarfeatured',
          2 => 'angarspecials',
        ),
        'displayHomeTabContent' => 
        array (
          0 => 'angarnewproducts',
          1 => 'angarfeatured',
          2 => 'angarspecials',
        ),
        'displayHome' => 
        array (
          0 => 'angarbanners',
        ),
        'angarCmsDesc' => 
        array (
          0 => 'angarcmsdesc',
        ),
        'angarParallax' => 
        array (
          0 => 'angarparallax',
        ),
        'angarProductCat' => 
        array (
          0 => 'angarcatproduct',
        ),
        'angarManufacturer' => 
        array (
          0 => 'angarmanufacturer',
        ),
        'angarBannersBottom' => 
        array (
          0 => 'angarbanners',
        ),
        'angarCmsBottom' => 
        array (
          0 => 'angarcmsinfo',
        ),
        'angarFacebook' => 
        array (
          0 => 'angarfacebook',
        ),
        'displayProductListReviews' => 
        array (
          0 => 'productcomments',
        ),
        'displayCommentsExtra' => 
        array (
          0 => 'productcomments',
        ),
        'displayProductTab' => 
        array (
          0 => 'productcomments',
        ),
        'displayProductTabContent' => 
        array (
          0 => 'productcomments',
        ),
        'displayFooter' => 
        array (
          0 => 'ps_linklist',
          1 => 'ps_customeraccountlinks',
          2 => 'angarcontact',
          3 => 'angarscrolltop',
          4 => 'angarthemeconfigurator',
        ),
        'displayFooterAfter' => 
        array (
          0 => 'ps_emailsubscription',
          1 => 'ps_socialfollow',
          2 => 'angarbanners',
        ),
        'displaySearch' => 
        array (
          0 => 'ps_searchbar',
        ),
        'displayProductAdditionalInfo' => 
        array (
          0 => 'ps_sharebuttons',
          1 => 'productcomments',
        ),
        'displayReassurance' => 
        array (
          0 => 'blockreassurance',
        ),
        'displayBeforeBodyClosingTag' => 
        array (
          0 => 'angarslider',
          1 => 'angarcatproduct',
          2 => 'angarmanufacturer',
          3 => 'statsdata',
        ),
        'displayFooterProduct' => 
        array (
          0 => 'ps_categoryproducts',
          1 => 'productcomments',
        ),
        'displayBackOfficeHeader' => 
        array (
          0 => 'angarbanners',
          1 => 'angarfastconfig',
          2 => 'angarscrolltop',
          3 => 'welcome',
        ),
      ),
    ),
    'image_types' => 
    array (
      'cart_default' => 
      array (
        'width' => 125,
        'height' => 125,
        'scope' => 
        array (
          0 => 'products',
        ),
      ),
      'small_default' => 
      array (
        'width' => 98,
        'height' => 98,
        'scope' => 
        array (
          0 => 'products',
          1 => 'categories',
        ),
      ),
      'medium_default' => 
      array (
        'width' => 452,
        'height' => 452,
        'scope' => 
        array (
          0 => 'products',
        ),
      ),
      'home_default' => 
      array (
        'width' => 259,
        'height' => 259,
        'scope' => 
        array (
          0 => 'products',
        ),
      ),
      'large_default' => 
      array (
        'width' => 800,
        'height' => 800,
        'scope' => 
        array (
          0 => 'products',
        ),
      ),
      'category_default' => 
      array (
        'width' => 200,
        'height' => 200,
        'scope' => 
        array (
          0 => 'categories',
        ),
      ),
      'stores_default' => 
      array (
        'width' => 170,
        'height' => 115,
        'scope' => 
        array (
          0 => 'stores',
        ),
      ),
      'manufacturer_default' => 
      array (
        'width' => 125,
        'height' => 125,
        'scope' => 
        array (
          0 => 'manufacturers',
        ),
      ),
    ),
  ),
  'theme_settings' => 
  array (
    'default_layout' => 'layout-full-width',
    'layouts' => 
    array (
      'index' => 'layout-left-column',
      'category' => 'layout-left-column',
      'best-sales' => 'layout-left-column',
      'new-products' => 'layout-left-column',
      'prices-drop' => 'layout-left-column',
      'product' => 'layout-left-column',
      'contact' => 'layout-left-column',
      'manufacturer' => 'layout-left-column',
      'supplier' => 'layout-left-column',
      'search' => 'layout-left-column',
    ),
  ),
  'dependencies' => 
  array (
    'modules' => 
    array (
      0 => 'angarfastconfig',
      1 => 'angarbanners',
      2 => 'angarbestsellers',
      3 => 'angarcatproduct',
      4 => 'angarcmsdesc',
      5 => 'angarcmsinfo',
      6 => 'angarfacebook',
      7 => 'angarfeatured',
      8 => 'angarhomecat',
      9 => 'angarmanufacturer',
      10 => 'angarnewproducts',
      11 => 'angarparallax',
      12 => 'angarslider',
      13 => 'angarspecials',
      14 => 'angarcontact',
      15 => 'angarthemeconfigurator',
      16 => 'angarscrolltop',
      17 => 'productcomments',
      18 => 'ps_categoryproducts',
      19 => 'ps_brandlist',
      20 => 'ps_supplierlist',
    ),
  ),
);
