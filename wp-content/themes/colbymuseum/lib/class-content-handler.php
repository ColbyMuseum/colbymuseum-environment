<?php
/**
 * Hook into content-related actions and filters
 *
 * @package colbymuseum
 */

namespace Colby_Museum;

/**
 * Set variables used throughout the plugin.
 */
class Content_Handler {
    public function __construct( &$theme ) {
        $this->theme = $theme;

        add_filter( 'the_content_more_link', [ $this, 'remove_exhibition_more_link' ] );
    }

    // Disable jump in 'read more' link.
    public function remove_exhibition_more_link( $link ) {
        if ( is_post_type_archive( 'exhibition' ) ) {
    		return '';
    	}
    }
}
