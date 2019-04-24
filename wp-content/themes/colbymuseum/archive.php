<?php
global $wp_query;

$i = 0;
$sortOrder = 'DESC';
$loadSocial = true;
$origID = get_the_ID();

$queried_object = get_queried_object();
$get_post_type = get_post_type();

$top_title = $queried_object && isset( $queried_object->label ) ? $queried_object->label : '';
if ( '' === $top_title && isset( $queried_object->name ) && $queried_object->name ) :
	$top_title = $queried_object->name;
endif;
$top_title = is_tag() ? $top_title = "Tagged: $top_title" : $top_title;
$top_title = 'News' === $top_title ? 'Museum News' : $top_title;

$full_width_archive = 'exhibition' === $get_post_type;

get_header(); ?>
<div id=content class="clearfix row-fluid">
	<div id=main class="span12 clearfix" role=main>
	<?php if ( ! $full_width_archive ) : ?>
	<article><?php
		if ( 'exhibition' !== $get_post_type ) :
			if ( 'collection' === $get_post_type ) :
				$load_social = true;
			endif;
			echo topHeader( $headerImage, $top_title, $subhead );
		endif; ?>
		<div class=container-fluid>
			<div class=span3 id=sideLeft>
				<?php get_sidebar(); ?>
			</div>
			<?php $span_width = 'podcast' === $get_post_type ? '6' : '9';
			if ( 'podcast' === $get_post_type ) : ?>
			<div class="span3 pull-right">
				<a href=https://itunes.apple.com/us/podcast/colby-college-museum-art-podcast/id991306774?mt=2>
					Subscribe on iTunes
				</a>
				<br />
			<?php
			echo "<div><h3>" . get_option('colbyPodcast_title') . "</h3>";
			if ( ! empty( $author_name = get_option( 'colbyPodcast_authorName' ) ) ) :
				echo "<div>$author_name</div>";
				if ( ! empty( $author_email = get_option( 'colbyPodcast_authorEmail' ) ) ) :
					echo "<a href=mailto:$author_email>$author_email</a>";
				endif;
			endif;
			if ( ! empty( $description = get_option( 'colbyPodcast_description' ) ) ) :
				echo "<hr />$description<br /></div>";
			endif; ?>
			</div>
		</div>
		<?php endif; ?>
		<div class=span<?php echo $span_width; ?>>
	<?php endif;

	if ( ! have_posts() ) : ?>
		</div>
		<div class="container-fluid">
			<div class="span3">
				<?php get_sidebar(); ?>
			</div>
			<div class="exhibitions-not-found span9 pull-right">
				<a href=<?php bloginfo( 'url' ); ?>/exhibition/>Back to current exhibitions</a>.
			</div>
		</div>
	<?php endif;
	while ( have_posts() ) :
		the_post();
		if ( 'exhibition' === $get_post_type ) :
			if ( 'traveling' !== get_query_var( 'exhibitionview' ) && get_field( 'traveling' ) ) :
				continue;
			endif;

			$show_exhibition = false;
			if ( ! get_query_var( 'exhibitionview' ) ) :
				// Make sure we are between start and end dates.
				if ( strtotime( 'now' ) >= strtotime( get_field( 'start_date' ) ) &&
						strtotime( 'now' ) <= strtotime( get_field( 'end_date') ) ) :
					$show_exhibition = true;
				endif;
			elseif ( 'past' === get_query_var( 'exhibitionview' ) ) :
				if ( strtotime( 'now' ) > strtotime( get_field( 'end_date' ) ) ) :
					if ( ! isset($year_top) ) :
						$year_top = date( 'Y', strtotime( get_field( 'start_date' ) ) );
					endif;

					if ( ( ! isset( $_GET['filteryear'] ) &&
							( $year_top === date( 'Y', strtotime( get_field( 'start_date' ) ) ) ||
							$year_top === date( 'Y' , strtotime( get_field( 'end_date' ) ) ) ) ) ||
							( isset( $_GET['filteryear'] ) &&
							( $_GET['filteryear'] === date( 'Y', strtotime( get_field( 'start_date' ) ) ) ||
							$_GET['filteryear'] ===date( 'Y', strtotime( get_field( 'end_date' ) ) ) ) ) ) :
							$show_exhibition = true;
					endif;
				endif;
			elseif ( 'upcoming' === get_query_var ( 'exhibitionview' ) ) :
				if ( strtotime( 'now' ) < strtotime( get_field( 'start_date' ) ) ) :
					$show_exhibition = true;
				endif;
			elseif ( 'traveling' === get_query_var ( 'exhibitionview' ) ) :
				$show_exhibition = true;
			endif;
			if ( $show_exhibition ) :
				get_template_part('templates/single','exhibition');
			endif;
		else :
			if ( 'collection' === $get_post_type ) :
				get_template_part( 'templates/archive', 'collection' );
			else :
				get_template_part( 'templates/archive', 'default' );
			endif;
		endif;
	endwhile;
	if ( in_category( 'news' ) ) : ?>
	<nav class=archive__footer>
	  <div class=archive__footer-prev><?php previous_posts_link( 'Newer Entries' ); ?></div>
	  <div class=archive__footer-next><?php next_posts_link( 'Older Entries' ); ?></div>
	</nav>
	<?php
	endif;
	if ( 1 < $wp_query->max_num_pages && 'past' === get_query_var('exhibitionview') ) :
		if ( function_exists( 'page_navi' ) ) : ?>
			<div class=container-fluid>
				<div class="span9 offset3">
					<?php page_navi(); ?>
				</div>
			</div>
		<?php else : ?>
			<nav class=wp-prev-next>
				<ul class=clearfix>
					<li class=prev-link>
						<?php next_posts_link( _e( '&laquo; Older Entries', 'colbymuseum' ) ) ?>
					<li class=next-link>
						<?php previous_posts_link( _e( 'Newer Entries &raquo;', 'colbymuseum' ) ) ?>
				</ul>
			</nav>
		<?php endif;
		if ( 'collection' !== $get_post_type &&
				'exhibition' !== $get_post_type &&
				! ( is_archive() && in_category( 'Recent Acquisitions' ) ) ) :
			footer_credits();
		endif;
		if ( ! $full_width_archive ) : ?>
		</div>
	<?php endif; ?>
<?php endif; ?>
	</article>
	</div>
</div>
<?php get_footer();
