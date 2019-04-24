<?php
	$isPage = is_page();
	$postOrig = $post;

?>
	<div class="sidebar-fixed">
		<div id="sidebar1" class="fluid-sidebar sidebar" role="complementary">
				<div role="complementary">
					<div class="sidebar-inner navbar-wrapper" data-spy="affix" data-offset-top="280">
				      <!-- Wrap the .navbar in .container to center it within the absolutely positioned parent. -->
				      <div class="">

				        <div class="navbar navbar-inverse">
				          <div class="navbar-inner">
				            <!-- Responsive Navbar Part 1: Button for triggering responsive navbar (not covered in tutorial). Include responsive CSS to utilize. -->
				            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target="#sidebar1 .nav-collapse">
				              <span class="icon-bar"></span>
				              <span class="icon-bar"></span>
				              <span class="icon-bar"></span>
				            </button>
   				            <a class="hidden-desktop section-header" id="responsive-side-header" ></a>


				            <!-- Responsive Navbar Part 2: Place all navbar contents you want collapsed withing .navbar-collapse.collapse. -->
				            <div class="nav-collapse collapse">
					            <div>
					            <?php
				             if(is_archive() || is_single()) {

								if(in_category('News')) {
									// News archive is in 'About' section...
									$post = get_page_by_path('about');
									$isPage = true;
								}

								if(in_category('Featured Event')) {
									// Event archive is in 'Events' section...
									$post = get_page_by_path('events');
									$isPage = true;
								}

								if(in_category('Acquisition') || in_category('Recent Acquisitions')) {

									// Event archive is in 'Recent Acquisitions' section...
									echo '<a class="collection-search-left" href="'.get_site_url().'?s=">Collection Search <img src="/wp-content/themes/colbymuseum/images/search-icon-wht.png" alt="Search Icon" width="20" height="20" border="0"></a>';
									$post = get_page_by_path('collection');
									$isPage = true;
								}

								if(get_post_type() == 'podcast' || in_category('multimedia')) {
									// Podcast archive is in 'Multimedia' section...
									$post = get_page_by_path('multimedia');
									$isPage = true;
								}

							}

							if($isPage) {

								$relatedCollection = get_field('related_collection',$post->ID);

								if(isset($relatedCollection) && gettype($relatedCollection) == 'array') {
									// Output related table of contents for collection...
									outputMenuForType(	'collection', $relatedCollection[0]->post_title);
								}
								else {

									$parent = array_reverse(get_post_ancestors($post->ID));
									$first_parent = get_page($parent[0]);

									$menuLinkTop = get_permalink($first_parent->ID);

									/*
										Top-level menu items have one-word names. Check and either output the post name (which can be custom) or the page title (for pages, etc)
									*/
									if( stripos( $first_parent->post_name, '-' ) !== false) {
										$menuTitleTop = get_the_title( $first_parent->ID );
									}
									else {
										$menuTitleTop = ucfirst($first_parent->post_name);
									}

									$parent = array_reverse(get_post_ancestors($post->ID));

									$first_parent = get_page($parent[0]);
									echo '<div class="section-header"><a href="'.$menuLinkTop.'">'.$menuTitleTop.'</a></div>';

									$items = wp_get_nav_menu_items($menuTitleTop);

									if(count($items) > 1) {
										$menuSettings = array( 'menu' => $menuTitleTop, 'container_class' => 'menu-side');
										wp_nav_menu( $menuSettings );
									}
								}
							}

							if(is_single() && !$isPage) {
								if(get_post_type() == 'exhibition') {
									// Output Exhibition menu
									outputExhibitions($relatedCollection);
								}
								else {
									outputMenuForType(get_post_type(),trim(get_the_title(get_the_ID())));
								}
							}

							if(is_post_type_archive()) {
								switch(post_type_archive_title('',false)) {
									case 'Collections';
										echo '<a class="collection-search-left" href="'.get_site_url().'?s=">Collection Search <img src="/wp-content/themes/colbymuseum/images/search-icon-wht.png" alt="Search Icon" width="20" height="20" border="0"></a>';
										break;
									case 'Exhibitions';
										outputExhibitions($relatedCollection);
										break;
								}
							}

							if(is_single()) {
								// Output the side menu if it exists...

							}
							?>
					            </div>
				            </div><!--/.nav-collapse -->
				          </div><!-- /.navbar-inner -->
				        </div><!-- /.navbar -->

				      </div> <!-- /.container -->
				    </div><!-- /.navbar-wrapper -->
				</div>
		</div>
	</div>
		<?php
				    	if ( is_active_sidebar( 'sidebar1' ) ) : ?>
							<div id="sidebar2" class="sidebar">
								<?php dynamic_sidebar( 'sidebar1' ); ?>
							</div>
							<?php else : ?>

							<?php endif;  ?>


					<?php
					// OUTPUT WIDGETS OR ANY OTHER INFO...

	$post = $postOrig;

function outputMenuForType($passedPostType, $compareTitle, $outputFirst = true, $outputLast = true) {
	if( $outputFirst ) {
		if(get_post_type() != 'post') {
			$postType = get_post_type_object($passedPostType);

			echo '<div class="section-header"><a href="'.get_post_type_archive_link($passedPostType).'">'.$postType->labels->menu_name.'</a></div>';
		}
	}

	if( $outputLast ) {
		$order = 'DESC';

		if( get_query_var('exhibitionview' ) != '') {
			$order = 'ASC';
		}

		// Output all for type...
		if($passedPostType == 'exhibition') {
			$typePosts = get_posts(array(
						'posts_per_page' => '500',
						'meta_key' => 'start_date',
						'orderby' => 'meta_value_num',
						'order' => $order,
						'post_type' => $passedPostType));
		}
		else {

			$typePosts = get_posts(array(
						'posts_per_page' => '500',
						'order' => $order,
						'post_type' => $passedPostType));

		}

		if(count($typePosts)) {
			foreach($typePosts as $currentPost) {
				$postTitle = get_the_title($currentPost->ID);

				if($passedPostType == 'collection') {

					if($postTitle == $compareTitle) {

						echo '<a href="'.get_the_permalink($currentPost->ID).'">'.$postTitle.'</a>';

						$menuTemp = get_field('menu',$currentPost->ID);

						if(strlen($menuTemp) && is_numeric($menuTemp)) {
							$menuSettings = array( 'menu' => $menuTemp, 'container_class' => 'menu-side');

							if(count(wp_get_nav_menu_items( $menuTemp )) > 0) {
								wp_nav_menu( $menuSettings );
							}
						}

						break;
					}
				}

				if($passedPostType == 'exhibition') {
					// Determine what to show for subitems...

					if ( 'traveling' !== get_query_var( 'exhibitionview' ) && get_field( 'traveling', $currentPost->ID ) ) :
						continue;
					endif;

					$showExhibition = false;

					if(get_query_var('exhibitionview') == '') {
						// Make sure we are between start and end dates...
						if(strtotime('now') >= strtotime(get_field('start_date', $currentPost->ID)) &&
							strtotime('now') <= strtotime(get_field('end_date', $currentPost->ID))) {
								$showExhibition = true;
							}
					}
					else {

						if(get_query_var('exhibitionview') == 'upcoming') {
							if(strtotime('now') < strtotime(get_field('start_date', $currentPost->ID))) {
								$showExhibition = true;
							}
						}

					}

					if($showExhibition) {
						echo '<li><a class="exhibition-link-left" href="'.get_the_permalink($currentPost->ID).'">'.$currentPost->post_title.'</a></li>';
					}

					// Current archive shoes title on page that jumps to that part of the page (URL links to full page, but JS will stop)

					// Upcoming shows nothing extra...

					// Past shows dropdown to show year...

				}
			}
		}
	}
}

function outputExhibitions($relatedCollection) {
	outputMenuForType(	'exhibition', $relatedCollection[0]->post_title, true, false); ?>
	<div class="menu-side">
		<ul id="menu-exhibitions" class="menu">
			<li id="menu-item-181" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-181<?php echo get_query_var('exhibitionview') == ''?' current-menu-item active':''; ?>"><a href="<?php echo get_site_url();?>/exhibition/">Current Exhibitions</a><?php
			if(get_query_var('exhibitionview') == '') {
				echo '<ul>';
				outputMenuForType(	'exhibition', $relatedCollection[0]->post_title, false, true);
				echo '</ul>';
			}
			?></li>
			<li id="menu-item-182" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-182<?php echo get_query_var('exhibitionview') == 'traveling'?' current-menu-item active':''; ?>"><a href="<?php echo get_site_url();?>/exhibition/view/traveling/">Traveling Exhibitions</a></li>
			<li id="menu-item-185" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-185<?php echo get_query_var('exhibitionview') == 'upcoming'?' current-menu-item active':''; ?>"><a href="<?php echo get_site_url();?>/exhibition/view/upcoming/">Upcoming Exhibitions</a><?php
			if(get_query_var('exhibitionview') == 'upcoming') {
				echo '<ul>';
				outputMenuForType(	'exhibition', $relatedCollection[0]->post_title, false, true);
				echo '</ul>';
			}
			?></li>
			<li id="menu-item-182" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-182<?php echo get_query_var('exhibitionview') == 'past'?' current-menu-item active':''; ?>"><a href="<?php echo get_site_url();?>/exhibition/view/past/">Past Exhibitions</a></li>
			<?php
			if(get_query_var('exhibitionview') == 'past') {
				?><li><div class="btn-group" id="past-archives-pulldown">
	                <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Viewing Archives: <?php echo isset($_GET['filteryear']) && is_numeric($_GET['filteryear'])?$_GET['filteryear']:date('Y',strtotime('now')); ?> <span class="caret"></span></button>
	                <ul class="dropdown-menu"><?php
	                  for($i = date('Y',strtotime('now')); $i > 1953; $i--) { ?>
	                  <li><a href="<?php echo get_site_url().'/exhibition/view/past/?filteryear=' . $i;?>"><?php echo $i;?></a></li>
	                  <?php
	                  }?>
	                </ul>
	              </div>
			</li>
			<?php
		} ?>

		</ul>
	</div><?php
}
