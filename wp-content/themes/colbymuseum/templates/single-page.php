<?php
	global $headerImage;
	$topTitle = get_the_title();
	$topTitle = $topTitle === 'Colby Students' ? 'Get Involved' : $topTitle;
	$subhead = '';
	$loadsocial = false;

	if(get_field('subhead') != '') {
		$subhead = get_field('subhead');
	}

	if( get_post_meta( get_the_ID(), 'loadsocial', true ) ) {
		$loadsocial = true;
	}

?>
	<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
		<?php $hideTitle = get_post_custom_values('hidetitle');
			echo topHeader($headerImage,$topTitle,$subhead, null, 3, $loadsocial );

		?>
		<section class="container-fluid post_content clearfix" itemprop="articleBody">
		<div class="span3" id="sideLeft"><?php get_sidebar(); ?></div>
		<div class="span9">
		<?php
			the_content();

			$highlightsArray = get_field('page_slideshow');

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
						  		echo '<a data-rel="group'.get_the_ID().'" data-title="'.get_post($currentPost['ID'])->post_excerpt.'" title="'.get_post($currentPost['ID'])->post_excerpt.'" href="'.$currentPost['url'].'"><img src="'.$currentPost['sizes']['slideshow-rectangle'].'" /></a>';
						  		echo '</li>';
					  		}
					  	}
				  	?>
				  </ul>
				</div>

				<div class="container-fluid">
					<div class="span9 offset3"><?php
			}
			if(strlen($other_page_content = get_field('other_page_content'))) {
				echo '<hr /><div class="exhibition-other-content">';
				echo $other_page_content;
				echo '</div>';

			}
			footer_credits();
		?>
				</div>
			</div>
		</section>
		<footer></footer>
	</article>
