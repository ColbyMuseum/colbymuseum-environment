<?php
/*
	Post template for single podcasts...
*/

?>
<header>
<p class=meta>
<?php if ( in_category( 'in-the-news' ) ) :
	if ( '' !== get_post_custom_values( 'source_name' ) ) :
		echo get_post_custom_values( 'source_name' )[0] . '<br /> ';
	endif;
endif;

$link = get_permalink();
$base = basename( $link );
$link = str_replace("$base/", '', $link ); ?>
<time datetime="<?php echo the_time('Y-m-j'); ?>" pubdate><?php the_date(); ?></time>
<?php if ( function_exists( 'get_field' ) && '' === ! empty( $author = get_field( 'author' ) ) ) :
	echo " | <span class=authorName>by $author</span>";
endif; ?>

<div class="addthis_toolbox addthis_default_style ">
	<a href="http://www.addthis.com/bookmark.php" class="addthis_button_expanded addthis_button" style="text-decoration:none;">
        <img src="<?php echo get_template_directory_uri(); ?>/images/sm-plus-custom.png"
        	 width="16" height="16" border="0" alt="Share" />
	</a>
	<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
</div>
<script type="text/javascript">var addthis_config = {"data_track_addressbar":false};</script>
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-52274afd3385fbef"></script>

</p>
</header> <!-- end article  -->

<section class="post_content clearfix podcast-body" itemprop="articleBody">
	<?php
	echo do_shortcode('[displaypodcasts]');
	echo "<hr />";

	if ( has_post_thumbnail() ) :
		$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
		$medium_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'medium' );
		$thumb_caption = nl2br( the_post_thumbnail_caption( get_the_id() ) );

		if ( trim( $thumb_caption ) ) :
			echo "<div class='alignright wp-caption' style='width:{$medium_image_url[1]}px'>";
		else :
			echo "<div class='alignright wp-caption'>";
		endif;
		echo "<a class=fancybox href='{$full_image_url[0]}'>";
		the_post_thumbnail( 'thumbnail', [ 'class' => 'alignright' ] );
		echo '</a>';
		if ( trim( $thumb_caption ) ) :
			echo "<p class=wp-caption-text>$thumb_caption</p></div>";
		endif;
	endif;

	// Display content. If there isn't any content, output the excerpt...
	if ( trim( $post->post_content ) ) :
		the_content();
	else :
		the_excerpt();
	endif;
	?>
	<br />
    <div class=podcast__footer>
    	<small>
    		<a href="<?php echo $link ?>">More podcast episodes</a>
    	</small> |
    	<small>
    		<a href="https://itunes.apple.com/us/podcast/colby-college-museum-art-podcast/id991306774?mt=2/feed">
    			Subscribe
    			<svg class="feed-icon svg-icon" viewBox="0 0 1792 1792">
    				<path d="M576 1344q0 80-56 136t-136 56-136-56-56-136 56-136 136-56 136 56 56 136zm512 123q2 28-17 48-18 21-47 21h-135q-25 0-43-16.5t-20-41.5q-22-229-184.5-391.5t-391.5-184.5q-25-2-41.5-20t-16.5-43v-135q0-29 21-47 17-17 43-17h5q160 13 306 80.5t259 181.5q114 113 181.5 259t80.5 306zm512 2q2 27-18 47-18 20-46 20h-143q-26 0-44.5-17.5t-19.5-42.5q-12-215-101-408.5t-231.5-336-336-231.5-408.5-102q-25-1-42.5-19.5t-17.5-43.5v-143q0-28 20-46 18-18 44-18h3q262 13 501.5 120t425.5 294q187 186 294 425.5t120 501.5z"
                          fill="#be0f34"/>
    			</svg>
    		</a>
    	</small>
    </div>
	<?php wp_link_pages(); ?>
</section>
