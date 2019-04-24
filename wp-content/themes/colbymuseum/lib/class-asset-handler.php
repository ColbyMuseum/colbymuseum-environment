<?php
/**
 * Modify WP_Query objects
 *
 * @package colby-museum
 */

namespace Colby_Museum;

class Asset_Handler {
	public function __construct( &$theme ) {
		$this->theme = $theme;

        add_action( 'after_setup_theme', [ $this, 'set_image_sizes' ] );
        add_action( 'after_setup_theme', [ $this, 'set_styles' ] );
		add_action( 'after_setup_theme', [ $this, 'set_scripts' ] );
        add_filter( 'image_size_names_choose', [ $this, 'set_custom_size_names' ] );

        add_action( 'init', [ $this, 'do_extra_stuff'] );
	}

    public function do_extra_stuff() {
        update_option( 'medium_size_w', 375 );
        update_option( 'medium_size_h', 475 );
        update_option( 'medium_crop', 0 );
        update_option( 'large_size_w', 810 );
        update_option( 'large_crop', 0 );
    }

    /**
	 * Set an array of arrays corresponding to wp_enqueue_style parameters.
	 */
	public function set_styles() {
		if ( is_admin() ) {
			return;
		}

		$this->theme->stylesheets = [
			[
				'colbycollege',
				get_template_directory_uri() . '/style.css',
				[ 'bootstrap' ],
				$this->theme->version,
			],
			[
				$this->theme->text_domain,
				"{$this->theme->assets_url}{$this->theme->text_domain}{$this->theme->min}.css",
				[ 'colbycollege' ],
				$this->theme->version,
			],
        ];

        foreach ( $this->theme->stylesheets as $style ) {
            if ( ! wp_style_is( $style[0] ) ) {
    			call_user_func_array( 'wp_enqueue_style', $style );
            }
		}
	}

    /**
	 * Set an array of arrays corresponding to wp_enqueue_script parameters.
	 */
	public function set_scripts() {
		if ( is_admin() ) {
			return;
		}

		$this->theme->scripts = [
            [
				'wooslider-flexslider',
				"{$this->theme->assets_url}flexslider.js",
				[ 'jquery' ],
				$this->theme->version,
				true
			],
			[
				$this->theme->text_domain,
				"{$this->theme->assets_url}{$this->theme->text_domain}.js",
				[ 'jquery' ],
				$this->theme->version,
				true
			],
		];

        foreach ( $this->theme->scripts as $script ) {
            if ( ! wp_script_is( $script[0] ) ) {
    			call_user_func_array( 'wp_enqueue_script', $script );
            }
		}
	}

    public function set_image_sizes() {
        $image_sizes = [
            [ 'smallcustom', 240 ],
            [ 'featured-thumbnail', 300, 300, true ],
            [ 'museum-top-image', 1600, 430, true ],
        ];

        foreach ( $image_sizes as $image_size ) {
            if ( ! has_image_size( $image_size[0] ) ) {
                call_user_func_array( 'add_image_size', $image_size );
            }
        }
    }

    function set_custom_size_names( $sizes ) {
        $sizes['smallcustom'] = __( 'Small' );

        return $sizes;
    }
}
