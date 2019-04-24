<?php
/**
 * Modify WP_Query objects
 *
 * @package colby-museum
 */

namespace Colby_Museum;

class Query_Handler {
	public function __construct( &$theme ) {
		$this->theme = $theme;

        add_action( 'pre_get_posts', [ $this, 'handle_exhibition_view' ] );
		add_action( 'pre_get_posts', [ $this, 'handle_podcasts' ] );
		add_action( 'pre_get_posts', [ $this, 'handle_videos' ] );
        add_action( 'pre_get_posts' , [ $this, 'exclude_categories_from_search' ] );
        add_filter( 'request', [ $this, 'fill_empty_query' ] );
        add_filter( 'query_vars', [ $this, 'add_exhibition_query_vars' ] );
	}

	public function handle_videos( $query ) {
		if ( ! is_category( 'multimedia') ) {
			return;
		}

		$query->set( 'posts_per_page', -1 );
	}

    public function handle_exhibition_view( $query ) {
		if ( ! $query->is_main_query() ) {
			return;
		}

		$exhibitionview = get_query_var( 'exhibitionview' );


		if ( 'past' === $exhibitionview ) {
			$query->set( 'order', 'DESC' );
			$query->set( 'meta_key', 'start_date' );
			$query->set( 'orderby', 'meta_value' );

			if ( isset( $_GET['filteryear'] ) ) {
				$query->set( 'meta_query', [
					[
						'key' => 'start_date',
						'value' => $_GET['filteryear'],
						'compare' => 'like',
					],
				] );
			}

    	} elseif ( ! empty( $exhibitionview ) || is_post_type_archive( 'exhibition' ) ) {
    		$query->set( 'posts_per_page', -1 );
    	}



		if ( 'upcoming' === $exhibitionview ) {
			$query->set( 'order', 'ASC' );
			$query->set( 'meta_key', 'start_date' );
			$query->set( 'orderby', 'meta_value' );
			$query->set( 'meta_query', [
				[
					'key' => 'start_date',
					'value' => date( 'Ymd' ),
					'compare' => '>=',
				],
			] );
		}

		if ( 'traveling' === $exhibitionview ) {
			$query->set( 'order', 'ASC' );
			$query->set( 'meta_key', 'start_date' );
			$query->set( 'orderby', 'meta_value' );
			$query->set( 'meta_query', [
				[
					'key' => 'traveling',
					'value' => '1',
					'compare' => '=',
				],
			] );
		}

		if ( is_post_type_archive( 'exhibition' ) && 'traveling' !== $exhibitionview && 'upcoming' !== $exhibitionview ) {
			$query->set( 'order', 'DESC' );
			$query->set( 'meta_key', 'start_date' );
			$query->set( 'orderby', 'meta_value' );
		}
    }

	public function handle_podcasts( $query ) {
		if ( ! is_post_type_archive( 'podcast' ) ) {
			return;
		}

		$query->set( 'posts_per_page', -1 );
    }

    public function exclude_categories_from_search( $query ) {
    	if ( is_admin() || ! is_search() ) {
            return;
        }

		$query->set( 'category__not_in' , [ get_cat_ID( 'Frontpage Slide' ) ] );
    }

    public function fill_empty_query( $query_vars ) {
        if ( isset( $_GET['s'] ) && empty( $_GET['s'] ) ) {
            $query_vars['s'] = ' ';
        }

        return $query_vars;
    }

    public function add_exhibition_query_vars( $query_vars ) {
        $query_vars[] = 'exhibitionview';

        return $query_vars;
    }

}
