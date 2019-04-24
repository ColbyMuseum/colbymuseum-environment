<?php
	$gridData = array();
	global $i;

	if(is_post_type_archive()) {
		if(has_post_thumbnail()) {
			$thumb_id = get_post_thumbnail_id();
			$thumb_url_array = wp_get_attachment_image_src($thumb_id, 'museum-top-image', true);
			$bannerImage = $headerImage = $thumb_url_array[0];
		}

		$topTitle = get_the_title();
		$subhead = date('F j, Y',strtotime(get_field('start_date'))) . ' - ' . date('F j, Y',strtotime(get_field('end_date')));	// Output the dates...
		if(get_field('ongoing')) {
			$subhead = '';
		}
		$subheadLocations = get_field('locations',get_the_ID());

		if(isset($subheadLocations) && is_array($subheadLocations)) {
			$subheadLocations = implode(', ', $subheadLocations);
		}
		else
			$subheadLocations = '';


		if ( get_field( 'traveling', get_the_id() ) && ! empty( $location = get_field( 'location', get_the_id() ) ) ) {
			$subheadLocations = $location;
		}

		$subheadLocations = str_ireplace('Lobby', 'William D. Adams Gallery, Museum Lobby', $subheadLocations);

		echo '<article>';
		echo topHeader($headerImage,$topTitle,$subhead,$subheadLocations,3,true);

		echo '<div class="container-fluid">';

		if($i == 0) {
			echo '<div class="span3" id="sideLeft">';
			get_sidebar();		// Output the sidebar on the first article.
			echo '</div>';
		}


		echo '<div class="span9';

		if($i > 0) {
			echo ' offset3';
		}

		echo '">';
	}
	if ( ! is_single() ) : ?>

	<div class="exhibition-overview-top">
		<?php if ( has_excerpt() || strpos( $post->post_content, '<!--more-->' ) ) :
			if ( strpos( $post->post_content, '<!--more-->' ) ) :
				the_content();
			else:
				the_excerpt();
			endif; ?>

		<a href="<?php the_permalink(); ?>">More &raquo;</a>
		<?php else :
			the_content(); ?>

		<a href="<?php the_permalink(); ?>">More &raquo;</a>
		<?php endif; ?>
	</div>
</div>
</div>
</article><?php
		return;
	endif; ?>
	<div class="exhibition-overview-top">
		<?php the_content();?>
	</div>
<?php

	// Determine bottom grid...
	if(is_array($gridArray = get_field('gallery'))) {
		$galleryAppend = '';
		$galleryAppend .= '<div class="exhibitionSlide">';
		$galleryAppend .= '<ul class="slides">';

	  	foreach($gridArray as $currentPost) {
	  		$galleryAppend .=  '<li>';
	  		$postTitle = get_post($currentPost['ID'])->post_excerpt;
	  		$galleryAppend .=  '<a data-rel="group'.get_the_ID().'" data-title="'. str_replace('"', "&quot;", $postTitle) . '" title="'.str_replace('"', "&quot;", $postTitle).'" href="'.$currentPost['url'].'"><img src="'.$currentPost['sizes']['thumbnail'].'" /></a>';
	  		$galleryAppend .=  '</li>';
  		}

		$galleryAppend .= '</ul>';
		$galleryAppend .= '</div>';

		$gridAppend = '<div class="exhibition-gallery"><h3>Selected Works from the Exhibition (Click to Enlarge)</h3>'.$galleryAppend.'</div>';
		$gridData[] = $gridAppend;
	}

	if(strlen(get_field('multimedia'))) {
		$gridAppend = '<div class="exhibition-multimedia"><h3>Multimedia</h3>';

		if(strlen(get_field('more_multimedia_url'))) {
			$gridAppend .= '<a class="more-link" href="'.get_field('more_multimedia_url').'">More multimedia ></a>';
		}

		$gridAppend .= html_entity_decode(get_field('multimedia'));

		$gridAppend .= '</div>';
		$gridData[] = $gridAppend;
	}

	// Check for any events that are related to this exhibition...
	$args = array(
		'posts_per_page' => 900,
		'post_type' => 'post',
		'category' => get_cat_ID('Featured Event'),

		'meta_query' => array(
			array(
				'key' => 'related_exhibition',
				'value' => '',
				'compare' => '>'
			)
		),
		'meta_key' => 'event_datetime',
		'orderby' => 'meta_value_num',
		'order' => 'ASC'
	);

	$results = get_posts($args);
	$eventArray = array();

	foreach($results as $result) {
		$related = get_field('related_exhibition',$result->ID);
		foreach($related as $curRelated) {
			if($curRelated->ID == get_the_ID()) {
				$eventArray[] = $result;
			}
		}
	}

	if(count($eventArray)) {
		$eventTemp = new colbyEvents();

		// Check for events that are related to this exhibition...
		ob_start();
		echo '<div class="exhibition-events"><h3>Related Programming</h3>';
		echo '<div id="featured-list"><ul>';
		$event_number = 0;
		foreach($eventArray as $relatedEvent) {
			setup_postdata($relatedEvent);
			$eventDatetime = get_field('event_datetime',$relatedEvent->ID);

			// JW addition 10/8/15
			$numdate = explode('/', substr($eventDatetime, 0, strpos($eventDatetime, ' '))); // Get the date portion of this string
			$numdate = (int) ($numdate[2] . $numdate[0] . $numdate[1]); // Reorder to YMD and make it an int
			$today = (int) date('Ymd'); // Get today's date in YMD and make it an int
			if ($numdate < $today) { // If event date is less than today's date ...
				continue; 			// ...skip it
			}
			// End JW addition
			$event_number++;
			if ( 3 === $event_number) : ?>
<div id="UniqueName" class="accordion">
	<div class="accordion-group">
		<div class="accordion-heading">
			<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#UniqueName" href="#collapse1">More +</a>
		</div>
		<div id="collapse1" class="accordion-body collapse ">
			<?php

			endif;
			echo '<li class="slide">';

			echo '<div class="calendar-left">';
			echo '<div class="calendar-left-day">'.date('j',strtotime($eventDatetime)).'</div>';
			echo '<div class="calendar-left-month">'.date('M',strtotime($eventDatetime)).'</div>';
			echo '</div>'; ?>
			<a href="<?php echo get_permalink($relatedEvent->ID);?>"><h3 class="slide-title"><?php echo $relatedEvent->post_title;?></h3></a>
			<div class="calendar-full-date"><?php echo $eventTemp::formatColbyTime(date('F d, Y g:i a',strtotime(strip_tags($eventDatetime))));?></div>
			</li>
			<?php
		}
		if ( 2 < $event_number ) : ?>


		</div>
	</div>
</div><?php
		endif;
		wp_reset_postdata();
		echo '</ul>';

		echo '<br /><a class="more-link" href="'.get_site_url().'/events/">Full event calendar ></a></div></div>';
		$gridAppend = ob_get_contents();

		ob_end_clean();

		$gridData[] = $gridAppend;
	}

	if(strlen(get_field('related_publications'))) {
		$gridAppend = '<div class="exhibition-publications"><h3>Related Publications and Press</h3>'.get_field('related_publications').'</div>';
		$gridData[] = $gridAppend;
	}

	if(strlen(get_field('related_coursework'))) {
		$gridAppend = '<div class="exhibition-publications"><h3>Related Coursework</h3>'.get_field('related_coursework').'</div>';
		$gridData[] = $gridAppend;
	}

	// Loop through the grid and output the appropriate items in the slots...
	$spanWidth1 = 6;
	$spanWidth2 = 6;

	if(count($gridData) == 1) {
		$spanWidth1 = 12;
	}

	for($i=0; $i < count($gridData); $i++) {
		if($i % 2 == 0) {
			if($i > 0) {
				echo '</div>';
			}

			echo '<div class="row">';
		}

		if($i % 2 == 0) {
			echo '<div class="span'.$spanWidth1.'">';
		}
		else {
			echo '<div class="span'.$spanWidth2.'">';
		}

		echo $gridData[$i];

		echo '</div>';
	}

	if(count($gridData) > 0) {
		echo '</div>';
	}

	if(strlen($otherContent = get_field('other_content'))) {
		echo '<hr /><div class="exhibition-other-content">
				<h3>Continue Exploring</h3>';
		echo $otherContent;
		echo '</div>';
	}

	if(strlen($bannerImage)) {
		footer_credits();
	}


	if(is_post_type_archive()) {
		echo '</div></div></article>';
	}
?>
