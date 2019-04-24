<?php
/**
 * Set rewrites
 *
 * @package colbymuseum
 */

namespace Colby_Museum;

class Rewrite_Handler {
	public function __construct( &$theme ) {
		$this->theme = $theme;
		add_action( 'init', [ $this, 'add_rewrite_rules' ] );
	}
	public function add_rewrite_rules() {
		add_rewrite_rule(
			'^exhibition/view/(upcoming|past|traveling|)/page/([0-9]+)/?$',
			'index.php?post_type=exhibition&exhibitionview=$matches[1]&paged=$matches[2]',
			'top'
		);

		add_rewrite_rule(
			'^exhibition/view/(upcoming|past|traveling|)?$',
			'index.php?post_type=exhibition&exhibitionview=$matches[1]',
			'top'
		);
	}
}
