<?php
	$highlightsArray = get_field('highlights_from_the_collection');

?>
			<div class="collection-overview-top">
				<?php echo get_field('overview_top_text');?>
			</div>
			<?php
			if(is_array($highligsArray)) {?>
			<h3>Highlights from the Collection</h3><?php
			}?>
		</div>
	</div>
	<?php
		// Close up the open divs and wrapper to make full-width...
	?>
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
		<div class="span9 offset3">
	<?php
		if(get_field('more_collection') != '') {
		?>
		<a class="more-highlights" href="<?php echo get_field('more_collection');?>">View full collection&nbsp;></a>
		<?php
		}?>
	<?php
	$bottomText = get_field('overview_bottom_text');

	if(strlen(trim($bottomText))) {?>
	<div class="collection-overview-top">
	<?php echo $bottomText;?>
	</div>
	<?php
	}

	footer_credits();

?>
