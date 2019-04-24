<?php
	global $current_blog; // WPMU current site information
	global $is_winIE;
	global $headerImage;
	global $colby_museum;

	// Vars...
	$navOutput = false;
	$showSlideshow = of_get_option('showhidden_slideroptions');
	$headerImage =  get_header_image();
	$colbyHomepage = (get_the_title()=='Front Page' && get_current_blog_id()=='1');
	$printView = (isset($_GET['print']) || isset($_GET['renderforprint'])?true:false);

	$fullWidth = (isset($_GET['fullwidth'])?true:false);
	$loadJumbo = false;				// Slideshow carousel (Jumbo slideshow)
	$emergencyMessages = false;
	$bodyClass = '';

	if($printView) {
		$bodyClass .= ' render-for-print';
	}

	if(!strlen($headerImage))
		$headerImage = get_template_directory_uri() . '/library/images/default_header.jpg';

	$imagesDir = get_stylesheet_directory_uri() . '/images/';

	// Check for any category/post type archives and output the appropriate image. These can be changed out in the main /images/ directory.
	if(is_archive() || is_search() || is_post_type_archive()) {
		$imagesDir = get_stylesheet_directory_uri() . '/images/';
		if(is_search()) {
			$headerImage = $imagesDir . 'header_search.jpg';
		}

		if(is_post_type_archive()) {
			switch ( post_type_archive_title('',false) ) {
				case 'Podcasts':
				case 'Multimedia':
					$headerImage = $imagesDir . 'header_multimedia.jpg';
					break;
				case 'News':
					$headerImage = $imagesDir . 'header_news.jpg';
					break;
			}
		}

		if(is_category()) {
			switch ( single_cat_title( '', false ) ) {
				case 'Multimedia':
					$headerImage = $imagesDir . 'header_multimedia.jpg';
					break;
				case 'News':
					$headerImage = $imagesDir . 'header_news.jpg';
					break;
			}
		}
	}

	if(get_post_meta('headerimage') != '') {
		$headerImage = get_post_meta(get_the_ID(),'headerimage');
	}

	if(wp_is_mobile())
		$bodyClass .= ' mobile';
	if($colbyHomepage)
		$bodyClass .= ' colby-edu-homepage';
	if(of_get_option('slider_options_type')=='2' && (is_front_page() || $colbyHomepage))
		$loadJumbo = true;
	if($colbyHomepage){
		$emergencyMessages = check_emercencymessages();
		$bodyClass .= ' emergency-notification emergency-notification-'.$emergencyMessages['placement'];
		if($emergencyMessages['placement'] == 'major')
			$loadJumbo = false;
	}
?><!doctype html>
<!--[if IEMobile 7 ]> <html <?php language_attributes(); ?>class="no-js iem7"> <![endif]-->
<!--[if lt IE 7 ]> <html <?php language_attributes(); ?> class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html <?php language_attributes(); ?> class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html <?php language_attributes(); ?> class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html <?php language_attributes(); ?> class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|(gt IEMobile 7)|!(IEMobile)|!(IE)]><!--><html <?php language_attributes(); ?> class="no-js<?php echo is_front_page() ? ' html--front-page' : ''; ?>"><!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<?php
			// Don't allow Google to index search result pages...
			if( is_search() ) {
				echo '<meta name="robots" content="noindex,follow">';
			}
		?>
		<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
		<title><?php wp_title( '&middot;', true, 'right' ); ?><?php echo (get_bloginfo('name')!='Colby College')?' &middot; Colby College':'';?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="icon" href="<?php echo get_template_directory_uri();?>/favicon.ico" type="image/x-icon">
		<!--[if lt IE 9]><script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->

		<script type="text/javascript" src="//use.typekit.net/mko7rzv.js"></script>
		<script type="text/javascript">try{Typekit.load();}catch(e){}</script>

  		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

		<?php get_wpbs_theme_options(); ?>
		<?php wp_head(); ?>
		<!--[if IE]><link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/library/css/ie.css" media="screen" type="text/css" /><script src="<?php echo get_template_directory_uri() . '/library/js/jquery.textshadow.js';?>"></script><![endif]-->
		<!--[if lt IE 9]><script src="<?php echo get_template_directory_uri() . '/library/js/jquery.corner.js';?>"></script><link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/library/css/ie8below.css" media="screen" type="text/css" /><![endif]-->
		<link href='//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800;subset=latin,latin-ext' rel='stylesheet' type='text/css' />
		<link href='//fonts.googleapis.com/css?family=Advent+Pro:200' rel='stylesheet' type='text/css'>
	</head>
	<body <?php body_class($bodyClass); ?>>
		<?php include(get_template_directory().'/library/inc/analyticstracking.php');	// Google Analytics Code ?>

		<div id="main-wrapper">
		<?php if(!$printView){
		?>
	<header role="banner"<?php if(false && strlen($headerImage) && !$jumboSlideshow && !preg_match('/(?i)msie [2-8]/',$_SERVER['HTTP_USER_AGENT']) && !$colbyHomepage && get_post_type() != 'sport'){
			echo ' style="background-image:url('.$headerImage.')"';
		}?> class="clearfix <?php echo ($showSlideshow==="1" && is_front_page())?'hasSlideshow':''; ?>">
		<?php
		// IE8 and under don't support stretching header images. Add real image to use as background...
		if(preg_match('/(?i)msie [2-8]/',$_SERVER['HTTP_USER_AGENT']) && !$colbyHomepage){
			echo '<div id="headerBackgroundImageIE-container"><img id="headerBackgroundImageIE" ';
			if(strpos($headerImage,'default_header.jpg') !== false)
				echo ' style="height:190px;" ';
			echo 'alt="Background Image" src="'.$headerImage.'"'.(($showSlideshow!="1")?' class="noSlideshow"':'').' /></div>';
		}
		?>
				<div class="colby-header container-fluid clearfix">				<?php // removed .navbar ?>

					<div class="navbar-wrapper">
				      <div class="container">
				        <div class="navbar navbar-inverse">
				          <div class="navbar-inner">
				            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".colby-header .nav-collapse">
				              <span class="icon-bar"></span>
				              <span class="icon-bar"></span>
				              <span class="icon-bar"></span>
				            </button>

				            <a href="<?php echo get_site_url(); ?>" id="museumLogo">
								<svg class=colby-museum-logo-svg>
									<use xlink:href="#colby-museum-logo"></use>
								</svg>
							</a>

							<div id="top-hours">
								<span class="free-public">Free and open to all</span><br />
								<div class="todays-hours"><a href="<?php echo site_url() . '/visit/'; ?>"><?php echo do_shortcode('[open-hours location="Museum of Art" showclock="false"]');?></a></div>
							</div>

							<a id="top-colbylogo" href=<?php echo network_home_url(); ?>>
								<svg class=colby-logo-svg>
									<use xlink:href="#colby-logo"></use>
								</svg>
							</a>

							<div class=top-right-links>
								<a href="javascript:void(0)" id="top-right-info">
									<svg class=info-circle-icon>
										<use xlink:href="#info-circle-icon"></use>
									</svg>
								</a>
								<div id="info-container" class="hidden-container">
									<?php
										$infoTab = get_page_by_path('/header-info');
										echo apply_filters('the_content', $infoTab->post_content);
									?>
								</div>
								<a href="javascript:void(0)" id="top-right-location">
									<svg class=marp-marker-icon>
										<use xlink:href="#map-marker-icon"></use>
									</svg>
								</a>
								<div id="location-container" class="hidden-container">
									<?php
										$infoTab = get_page_by_path('/header-map');
										echo apply_filters('the_content', $infoTab->post_content);
									?>
								</div>
								<a href="javascript:void(0)" id="top-right-search">
									<svg class=search-icon>
										<use xlink:href="#search-icon"></use>
									</svg>
								</a>
								<div id="search-container" class="hidden-container">
									<div class="">
										<form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
											<input type="search" id="searchTop" class="search-field" placeholder="<?php echo esc_attr_x( 'Search collections and website...', 'placeholder' ) ?>" value="" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label' ) ?>" />
											<input type="submit" class="search-submit" value="<?php echo esc_attr_x( 'Search', 'submit button' ) ?>" />
										</form>
									</div>
									<div class="hidden-container-right pull-right">

									</div>
								</div>
							</div>

				            <div class="nav-collapse collapse">
					            <?php bones_main_nav(); ?>
				            </div>
				          </div>
				        </div>
				      </div>
				    </div>
				</div>
			<?php

			if($loadJumbo){
				include('library/inc/topJumboSlideshow.php');				// Jumbo slideshow (Gateways, Colby.edu homepage, etc.)...
				$showSlideshow==false;
			}
			 ?>

		</header><?php
		}?>
		<?php
				$show_breadcrumbs = of_get_option('show_breadcrumbs');
				if($show_breadcrumbs && !is_front_page() && false){
					include('library/inc/breadcrumbs.php');
					generate_breadcrumbs();
				}

		?>
		<div id="<?php echo $printView==true?'':'content-container';?>" class="">
