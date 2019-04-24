<?php
	// Full-width slideshow carousel...
	global $post;
	$tmp_post = $post;
	$show_posts = (of_get_option('slider_options') + 1);

	$catobj = get_category_by_slug('frontpage-slide');

	if(isset($catobj))
		$args = array( 'posts_per_page' => $show_posts,'cat'=>$catobj->term_id );
	else
		$args = array( 'posts_per_page' => $show_posts );

	// Create a new filtering function that will add our where clause to the query
	function filter_where( $where = '' ) {
    	if(isset($_GET['passedDate'])){

			$passedDate = date('Y-m-d G:i',strtotime($_GET['passedDate']));
			if(date('G:i',strtotime($passedDate)) == '0:00')
				$passedDate = date('Y-m-d',strtotime($_GET['passedDate'])) . ' 23:59:59';
		}
		else
			$passedDate = date('Y-m-d G:i',strtotime("now"));

	    $where .= " AND post_date <= '" . $passedDate . "'";
	    return $where;
	}

	add_filter( 'posts_where', 'filter_where' );

	$slideQuery = new WP_Query( $args );
	$myposts = $slideQuery->get_posts();

	if(!count($myposts) && isset($_GET['passedDate'])){
		// Date passed, and no posts.
		$myposts = get_posts($args);
	}

	remove_filter( 'posts_where', 'filter_where' );

	//$myposts = get_posts( $args );
	$post_num = 0;

	if(count($myposts)> 0){
?>
<!--[if lte IE 8 ]><style>
#sectionMenu.navbar.container-fluid{
	margin-top:68px!important;
}
</style><![endif]-->
	<script>
		jQuery(document).ready(function() {
			jQuery('.flexslider li.item').css('height',jQuery(window).height());

			jQuery('.flexslider').flexslider2({
			    animation: "slide",
			    direction: "vertical",
			    itemMargin: 0,
			    animationSpeed: 1000,
			    slideshowSpeed: 6000,
			    animationLoop: false,
					move: 1,
					

					start: function(slider){
						jQuery('.flexslider img').show();

						//custom mousewheel:

						var timer = null;
						var wheeling = false;

						jQuery('.flexslider').on('wheel', function(event){
							var deltaY = event.originalEvent.deltaY;

						  if(timer){
						    clearTimeout(timer);
						  }

						  if(!wheeling){
						    var target = deltaY > 0 ? slider.getTarget('next') : slider.getTarget('prev');
								var canTransition;

								var slides = document.querySelector('.slides');
								var firstSlideActive = slides.firstElementChild.className.includes('flex-active-slide');
								var lastSlideActive = slides.lastElementChild.className.includes('flex-active-slide');
								
								if (firstSlideActive && target === slides.childElementCount - 1) {
									canTransition = false;
								} else if (lastSlideActive && target === 0) {
									canTransition = false;
								} else {
									canTransition = true;
								}

								if (canTransition) {
									slider.flexAnimate(target, true);
								}
						  }

						  wheeling = true;

						  timer = setTimeout(function(){
						    wheeling = false;
						  }, 60);

						});
					}
		  });
		});
	</script>

	<div id="museum-homeslider">
		<div class="flexslider<?php echo count($myposts)==1?' single-slide':''; ?>">
			<ul class="slides">
		    	<?php
				foreach( $myposts as $post ) :	setup_postdata($post);
					$post_num++;
					$post_thumbnail_id = get_post_thumbnail_id();
					$featured_src = wp_get_attachment_image_src( $post_thumbnail_id, 'full' );
					$readMoreText = 'Read more';

					if(strlen(get_field('more_text')))
						$readMoreText = trim(get_field('more_text'));

					$linkurl = get_field('link_url');

					if(!$linkurl)
						$linkurl = get_permalink();

					if(strpos($linkurl,'://')===false)
						$linkurl = 'http://'.trim($linkurl);

					if(strpos($linkurl,'///')!==false)
						$linkurl = str_ireplace('http://','',$linkurl);

					$thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ),'full' );
					/* remove the protocol from the URL ('http:') so there are no issues with https view */
                                        $thumbnail_src[0]=preg_replace('/^http\:/','',$thumbnail_src[0]);
				?>
			    <li class="<?php if($post_num == 1){ echo 'active'; } ?> item" style="background-image:url(<?php echo $thumbnail_src[0];?>);">
				   	<div class="slideshow-caption<?php echo (strpos(get_the_title(),' ') !== false?' large-title':'');?>">
		                <h4><a href="<?php echo $linkurl; ?>" rel="bookmark"><?php echo trim(get_the_title()); ?></a></h4>
		                <div>
		                	<?php
		                		$excerpt_length = 450; // length of excerpt to show (in characters)
		                		$the_excerpt = strip_tags(get_the_content(),'<a>,<i>,<b>,<strong>,<em><ul><li><span>');
		                		if($the_excerpt != ""){
		                			$the_excerpt = substr( $the_excerpt, 0, $excerpt_length );
		                			echo trim($the_excerpt);
		                	?>
		                </div>
		                	<?php }else {
			                	echo '</a>';
		                	} ?>
	                </div>
			    </li>
			   <?php endforeach; ?>
			</ul>
				<?php $post = $tmp_post; ?>
	    </div>
	</div>
<?php
}?>
