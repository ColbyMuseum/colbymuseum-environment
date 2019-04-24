<?php
	get_header();

	$headerImage =  get_header_image();
	$topTitle = get_the_title();
	$subhead = get_field('subhead');
	$loadSocial = false;


	if((get_field('locations')))
		$subheadLocations = implode(', ', get_field('locations'));
	else
		$subheadLocations = '';

	if ( get_field( 'traveling', get_the_id() ) && ! empty( $location = get_field( 'location', get_the_id() ) ) ) {
		$subheadLocations = $location;
	}

	if(is_single() && get_post_type() == 'exhibition') {
		$subhead = date('F j, Y',strtotime(get_field('start_date')));	// Output the start date
		if ( get_field('end_date') ) {
			$subhead = $subhead . ' - ' . date('F j, Y',strtotime(get_field('end_date'))); // Output the end date (if it is not empty)
		}

	}
;
	if(has_post_thumbnail() && (!(is_single() && get_post_type() == 'post') || in_category('Featured Event') || in_category( 'Recent Acquisitions') ) ) {
		$thumb_id = get_post_thumbnail_id();
		$thumb_url_array = wp_get_attachment_image_src($thumb_id, 'museum-top-image', true);
		$headerImage = $thumb_url_array[0];
	}

?>
			<div id="content" class="clearfix row-fluid">
				<div id="main" class="span12 clearfix" role="main">
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
					<?php

						if( get_post_meta( get_the_ID(), 'loadsocial', true ) || get_post_type() == 'collection' || in_category('Featured Event') || get_post_type() == 'exhibition' || in_category( 'Recent Acquisitions') ) {

							$loadSocial = true;
						}

						echo topHeader($headerImage, $topTitle, $subhead, $subheadLocations, 3, $loadSocial);

					?>
							<div class="container-fluid">
								<div class="span3" id="sideLeft">
									<?php get_sidebar(); ?>
								</div>
								<div class="span9">
							<?php
							if($post->post_type=='podcast') {
								get_template_part( 'templates/single', 'podcast' );
							}
							elseif ($post->post_type=='collection') {
								get_template_part( 'templates/single', 'collection' );
							}
							elseif($post->post_type=='exhibition') {
								get_template_part( 'templates/single', 'exhibition' );
							}
							elseif(in_category('Featured Event')){
								get_template_part( 'templates/single', 'event' );
							}
							else {
								// Default
								if(in_category('News')) {
									echo '<p class="meta">'.get_the_date().'</p>';
								}

								the_content();

							}

							$highlightsArray = get_field( 'page_slideshow', get_the_ID() );

							if( isset($highlightsArray) ) {
								// Close up the open divs and wrapper to make full-width...
								?>
									</div>
							</div>

								<div id="highlightSlide">
								  <ul class="slides">
								  	<?php
									  	if(isset($highlightsArray) && is_array($highlightsArray)) {
										  	foreach($highlightsArray as $currentPost) {

										  		echo '<li>';
										  		echo '<a data-rel="group'.get_the_ID().'" data-title="'.get_post($currentPost['ID'])->post_excerpt.'" title="'.get_post($currentPost['ID'])->post_excerpt.'" href="'.$currentPost['url'].'"><img src="'.$currentPost['sizes']['thumbnail'].'" /></a>';
										  		echo '</li>';
									  		}
									  	}
								  	?>
								  </ul>
								</div>

								<div class="container-fluid">
									<div class="span9 offset3"><?php
							}

							if(has_post_thumbnail() && (!(is_single() && get_post_type() == 'post') || in_category('Featured Event')  || in_category( 'Recent Acquisitions') ) && get_post_type() != 'collection') {
								footer_credits();
							}
							?>
								</div>
							</div>
						<footer>
							<?php //the_tags('<p class="tags"><span class="tags-title">' . __("Tags","bonestheme") . ':</span> ', ' ', '</p>'); ?>
						</footer>
					</article>
					<?php endwhile; ?>

					<?php else : ?>

					<article id="post-not-found">
					    <header>
					    	<h1><?php _e("Not Found", "bonestheme"); ?></h1>
					    </header>
					    <section class="post_content">
					    	<p><?php _e("Sorry, but the requested resource was not found on this site.", "bonestheme"); ?></p>
					    </section>
					    <footer>
					    </footer>
					</article>

					<?php endif; ?>

				</div>
			</div>
<?php get_footer(); ?>
