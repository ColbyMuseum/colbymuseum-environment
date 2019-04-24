<?php
global $colby_museum;

/**
 * Concise function to pretty-print data in the browser; optionally var_dump; optionally wp_die.
 *
 * @param  mixed   $data Any variable.
 * @param  integer $die  Zero for false, one for true.
 * @param  integer $dump Zero for false, one for true.
 */
if ( ! function_exists( 'pp' ) ) :
function pp( $data, $die = 0, $dump = 0 ) {
	echo '<pre>';
	if ( 1 === $dump ) {
		var_dump( $data );
	} else {
		print_r( $data );
	}
	echo '</pre>';
	if ( 1 === $die ) {
		wp_die( '', '', [ 'response' => 200 ] );
	}
}
endif;


$info = [
	'Theme Name' => 'Colby Museum',
	'Description' => 'Theme for the Colby Museum WordPress site',
	'Version' => '0.0.17',
	'Author' => 'John Watkins',
	'Template' => 'colbycollege',
	'Text Domain' => 'colbymuseum',
	'Namespace' => 'Colby_Museum',
	'Admin Email' => 'communicationsweb@colby.edu',
	];

if ( ! function_exists( 'register_wp_autoload' ) ) {
	require_once( 'vendor/autoload.php' );
}

register_wp_autoload( 'Colby_Museum\\', __DIR__ . '/lib' );
$colby_museum = new Colby_Museum\Colby_Museum( __FILE__, $info );

new Colby_Museum\Rewrite_Handler( $colby_museum );
new Colby_Museum\Template_Router( $colby_museum );
new Colby_Museum\Query_Handler( $colby_museum );
new Colby_Museum\Asset_Handler( $colby_museum );
new Colby_Museum\Content_Handler( $colby_museum );


if ( isset( $_GET['debug'] ) ) {
	register_shutdown_function(function() {
		print_r( error_get_last() );
	});
}



function topHeader( $header_image, $top_title, $subhead = '', $subhead2 = '', $offset = 3, $load_social = false ) {
	global $post;
	ob_start(); ?>

	<header<?php
		$header_image = $header_image ?: get_header_image();
		echo " style=background-image:url($header_image);"; ?>
		class="topHeader<?php echo $header_image ? '' : ' noBackground'; ?>">
		<div class="container-fluid top-text-container">
			<div class="span9 offset<?php echo $offset; ?>">
				<?php if ( $load_social ) : ?>
				<div class="addthis_toolbox addthis_default_style">
					<a addthis:url="<?php the_permalink();?>"
					   addthis:title="<?php echo get_the_title( $post->ID ); ?>"
					   href="http://www.addthis.com/bookmark.php"
					   class="addthis_button_expanded addthis_button"
					   style="text-decoration:none;">
				         Share
						 <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/shareimage.png"
						 	  border="0"
							  alt="Share" />
					</a>
				</div>
				<?php endif; ?>
				<h1 itemprop="headline">
					<?php if ( is_post_type_archive( 'exhibition' ) ) : ?>
						<a href="<?php the_permalink(); ?>">
					<?php endif;
					echo $top_title;
					if ( is_post_type_archive() ) : ?>
						</a>
					<?php endif; ?>
				</h1>
				<?php
				if ( $subhead ) : ?>
				<h3 class="single-subhead">
					<?php echo $subhead;?>
				</h3>
				<?php endif;
				if ( $subhead2 ) :
					echo "<div class=single-subhead-locations>$subhead2</div>";
				endif; ?>
				</div>
			</div>
		</header>
	<?php
	return ob_get_clean();
}




function embarkSearchResults( $search_term ) { ?>
	<?php
	$embarkURL = 'http://embark.colby.edu/';

	if ( trim( $search_term ) ) {
		$embarkURL .= '4DACTION/HANDLECGI/CTN3?display=por';
	}

	if ( isset( $_GET['x'] ) && isset( $_GET['obj'] ) && is_numeric($_GET['x'] ) ) {

		$args = array();

		if ( substr_count( $_GET['obj'], '?') >= 1 )  {
			$parameters = explode( '?', $_GET['obj'] );

			foreach ( $parameters as $arrayitem ) {
				if ( false !== stripos( $arrayitem, "Obj" ) ) {
					$_GET['obj'] = $arrayitem;
				}

				if ( false !== stripos( $arrayitem, "sid" ) ) {
					$_GET['sid'] = str_ireplace( 'sid=', '', $arrayitem);
				}
			}
		}

		$embarkURL = "http://embark.colby.edu/{$_GET['obj']}";

		if ( false != stripos( $_GET['obj'], '?' ) ) {
			$embarkURL .= '&';
		} else {
			$embarkURL .= '?';
		}

		$embarkURL .= "x={$_GET['x']}";

		if ( isset( $_GET['sid'] ) && is_numeric( $_GET['sid'] ) ) {
			$embarkURL .= "&sid={$_GET['sid']}";
		}

		if ( isset( $_GET['port'] ) && is_numeric( $_GET['port'] ) ) {
			$embarkURL .= "&port={$_GET['port']}";
		}

		$embarkURL = str_replace( '//P', '/P', $embarkURL);
		$embarkURL = str_replace( '//O', '/O', $embarkURL);

		$response = wp_remote_get( $embarkURL );


		if ( isset( $_GET['debug'] ) ) {
			pp( $embarkURL );
			pp( $response, 1 );
		}
	} else {
		if ( isset( $_GET['obj'] ) ) {
			$embarkURL = "http://embark.colby.edu/{$_GET[obj]}";

			if ( isset( $_GET['sid'] ) && is_numeric( $_GET['sid'] ) ) {
				$embarkURL .= "&sid={$_GET['sid']}";
			}

			$response = wp_remote_get( $embarkURL );

			if ( is_wp_error( $response ) ) {
				return;
			}
		} else {
			// Submit search query via POST.
			$args = [
				'body' => [
					'searchType' => 'all',
					'WholeWord' => '0',
					'RefineSearch' => 'NewSelection',
					'theKW' => $search_term
				]
			];

			$response = wp_remote_post( $embarkURL, $args );

		}

	}

	$replacements = [
		'/academics_cs/museum/images/' => '/wp-content/themes/colbymuseum/images/',
			'BACK TO SINGLE OBJECT VIEW' => '< Back to Single Object View',
		  		'SINGLE OBJECT' => 'Single Object',
		  		'THUMBNAILS' => 'Thumbnails',
		  		'LIST VIEW' => 'List View',
		  		'BACK TO TOP' => 'Back to Top &uarr;',
		  		'ENLARGE/ZOOM IMAGE' => 'Enlarge/Zoom Image',
		  		'BACKGROUND:' => 'Background:',
		  		'ENLARGE/ZOOM IMAGE' => 'Enlarge/Zoom Image',
	  		'LIGHT' => 'Light',
	  		'MEDIUM' => 'Medium',
	  		'DARK' => 'Dark',
	  		'</a> &bull; <a' => '</a> | <a'
	];

	if ( is_array( $response ) ) {
		$response_body = str_replace( array_keys( $replacements ), array_values( $replacements ), $response['body'] );
		$response_body = str_replace( '/academics_cs/museum/search/', "?s=$search_term&obj=", $response_body );

		echo str_replace( 'its-embark.colby.edu', 'embark.colby.edu', $response_body );

		// If no results, automatically select the 'Museum website search results' tab.
		if ( false !== stripos( $response['body'], 'no results found' ) || get_query_var( 'paged' ) > 1 ) { ?>
		    <script>
		      jQuery(document).ready(function() {
			     jQuery("#tab-coll").removeClass("active");
			     jQuery("#tab-mus a").click();
		      });
		  </script>
		    <?php
		}
	}

}

function footer_credits() {
	// Footer credits.
	$thumbnail_caption = get_the_post_thumbnail_caption();

	if ( is_category() ) {
		switch ( single_cat_title( '', false ) ) {
			case 'Multimedia':
				break;
			case 'News':
				$thumbnail_caption = 'Alex Katz, <em>Three Figures On a Subway</em>, '
					. 'c. 1948, Oil on masonite, 13 1/2 in. x 29 in., Gift of the artist, 1995.076.';
				break;
		}

	}

	if ( is_search() ) {
		$thumbnail_caption = 'Sanford Robinson Gifford, <em>A Twilight in the Adirondacks</em>, '
			. '1864, Oil on canvas, 10 1/2 in. x 18 1/2 in., The Lunder Collection, 2013.130';
	}

	if ( $thumbnail_caption ) {
		echo "
			<div class='bottomCredits small'>
				<hr />
				Banner Image: $thumbnail_caption
			</div>";
	}
}

add_filter( 'wp_nav_menu_args', function( $args ) {
	if ( 'main_nav' === $args['menu'] ) {
		$args['menu'] = 'Top Menu';
		$args['theme_location'] = '';
	}

	return $args;
} );