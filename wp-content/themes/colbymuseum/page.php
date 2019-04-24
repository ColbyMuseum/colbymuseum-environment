<?php 
	get_header(); 
	global $post;

	if($post->post_name == 'multimedia') {		
		function custom_excerpt_length( $length ) {
			return 20;
		}
		add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );
	}
	
	$headerImage =  get_header_image();
	
	if(has_post_thumbnail()) {
		$thumb_id = get_post_thumbnail_id();
		$thumb_url_array = wp_get_attachment_image_src($thumb_id, 'museum-top-image', true);
		$headerImage = $thumb_url_array[0];
	}

?>		
	<div id="content" class="clearfix row-fluid">
				
				<div id="main" class="clearfix" role="main">
					<?php 
						if (have_posts()) : while (have_posts()) : the_post(); 
							get_template_part( 'templates/single', 'page');
					?>				
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