<?php
	// Search results page...
get_header();
global $headerImage;

$topTitle = 'Search';
?>
<div id="content" class="clearfix row-fluid no-sidebar">
	<div id="main" class="span12 clearfix" role="main">
		<article><?php
		echo topHeader( $headerImage, $topTitle, '', '', 0 ); ?>
			<div class="container-fluid">
				<div class="span12" id="mainContent">
					<article>
						<section>
						<?php if ( '' === trim( get_search_query() )) : ?>
							<p>Enter an artist's name, a title or media to make a quick search of the Museum's collection and website.</p>
						<?php endif;
							echo get_search_form(); ?>
						</section>
					</article>
					<div class=fine-print>
Works in the museum's collection are presented with basic information consisting of artist, date and media. When available, an image of the artwork is displayed. Images are presented as a fair use educational resource and commercial use is strictly prohibited. To request an image, please contact <a href=mailto:museumimages@colby.edu>museumimages@colby.edu</a>.
					</div>
					<?php if ( '' !== trim( get_search_query() ) ) : ?>
					<ul class="nav nav-tabs">
						<li class="active" id="tab-coll"><a href="#coll" data-toggle="tab">Collections Search Results</a></li>
						<li id="tab-mus"><a href="#mus" data-toggle="tab">Museum Website Search Results</a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="coll">
							<?php embarkSearchResults( trim( get_search_query() ) ); ?>
						</div>
						<div class="tab-pane" id="mus">
							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
							<hr />
							<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">
								<header>
									<h3>
										<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
											<?php the_title(); ?>
										</a>
									</h3>
								</header> <!-- end article header -->
								<section class="post_content">
									<p><?php the_excerpt();?></p>
								</section> <!-- end article section -->
							</article> <!-- end article -->
							<?php endwhile;
	 							if ( function_exists( 'page_navi' ) ) :
									page_navi();
								else : ?>
								<nav class="wp-prev-next">
									<ul class="clearfix">
										<li class="prev-link"><?php next_posts_link(_e('&laquo; Older Entries', "bonestheme")) ?></li>
										<li class="next-link"><?php previous_posts_link(_e('Newer Entries &raquo;', "bonestheme")) ?></li>
									</ul>
								</nav>
								<?php endif; ?>
							<?php else : ?>
							<article id="post-not-found">
								<br />
							    <header>
							    	<h1><?php _e("No records found.", "bonestheme"); ?></h1>
							    </header>
							    <section class="post_content">
							    	<p><?php _e("No results were found on the Colby Museum of art website. Check the collection search results tab for relevant collections.", "bonestheme"); ?></p>
							    </section>
							</article>
						<?php endif; ?>
							</div>
							<?php
						endif;
						echo footer_credits();
						?>
					</div>
				</div>
			</article>
		</div> <!-- end #main -->
	</div> <!-- end #content -->

<?php get_footer();
