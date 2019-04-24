<?php
/**
 * Configuration class
 *
 * @package colby-college
 */

namespace Colby_Museum;

/**
 * Set variables used throughout the plugin.
 */
class Colby_Museum {

	/**
	 * The protocol-free URL.
	 *
	 * @var $assets_url string
	 */
	public $assets_url = '';

	/**
	 * Run the plugin in debug mode.
	 *
	 * @var $debug bool
	 */
	public $debug = false;

	/**
	 * The system path to the main directory file.
	 *
	 * @var $main_file string
	 */
	public $main_file = '';

	/**
	 * The string to add to minified asset URLs when not debugging.
	 *
	 * @var $min string
	 */
	public $min = '';

	/**
	 * The system path to the plugin root.
	 *
	 * @var $path string
	 */
	public $path = '';

	/**
	 * The array of post types registered by this plugin.
	 *
	 * @var $post_types array
	 */
	public $post_types = [];

	/**
	 * The array of scripts enqueued by this plugin.
	 *
	 * @var $scripts array
	 */
	public $scripts = [];

	/**
	 * The array of shortcodes added by this plugin.
	 *
	 * @var $shortcodes array.
	 */
	public $shortcodes = [];

	/**
	 * The array of stylesheets enqueued by this plugin.
	 *
	 * @var $stylesheets array
	 */
	public $stylesheets = [];

	/**
	 * The array of taxonomies added by this plugin.
	 *
	 * @var $taxonomies array
	 */
	public $taxonomoies = [];

	/**
	 * The plugin's text domain.
	 *
	 * @var $text_domain string
	 */
	public $text_domain = '';

	/**
	 * The text domain with an underscore instead of a hyphen.
	 *
	 * @var $text_domain_underscore string
	 */
	public $text_domain_underscore = '';

	/**
	 * The plugin directory's root URL.
	 *
	 * @var $url string
	 */
	public $url = '';

	/**
	 * The plugin's version number.
	 *
	 * @var $version string
	 */
	public $version = '0.1';

	/**
	 * Populate the object's variables with real values.
	 *
	 * @param string $main_file The system path of the main plugin file.
	 * @param array  $theme_data The plugin data set in the main file.
	 */
	public function __construct( $main_file, $theme_data ) {
		$this->main_file = $main_file;
		$this->debug = isset( $_GET['debug'] ) ? true : false;
		$this->path = trailingslashit( dirname( $main_file ) );
		$this->url = trailingslashit( $theme_data['Template'] ? get_stylesheet_directory_uri() : get_template_directory_uri() );
		$this->assets_url = substr( $this->url, ( strpos( $this->url, '//' ) ) ) . 'assets/';
		$this->text_domain = $theme_data['Text Domain'];
		$this->text_domain_underscore = str_replace( '-', '_', $this->text_domain );
		$this->version = $theme_data['Version'];
		$this->min = true === $this->debug ? '' : '.min';
		$this->admin_email = $theme_data['Admin Email'];

		add_action( 'init', [ $this, 'set_post_types' ] );
		add_action( 'init', [ $this, 'set_taxonomies' ] );
		add_action( 'after_setup_theme', [ $this, 'set_shortcodes' ] );

	}

	/**
	 * Set an associative array of this plugin's post types -- name => settings.
	 * Example:
	 * $this->post_types = [
	 *		'type' => [
	 *			'label' => 'Types',
	 *			'labels' => [
	 *				'singular_name' => 'Type',
	 *			],
	 *			'public' => true,
	 *			'supports' => [ 'title', 'editor' ],
	 *			'hierarchical' => false,
	 *			'taxonomies' => [ 'type-categories' ],
	 *		],
	 *	];
	 */
	public function set_post_types() {
		$this->post_types = [];

        foreach ( $this->post_types as $name => $settings ) {
            if ( ! post_type_exists( $name ) ) {
    			register_post_type( $name, $settings );
            }
		}
	}

	/** Set an associative array of this plugin's taxonomies -- name => settings
	 * Example:
	 * $this->taxonomies = [
	 *		'type-categories' => [
	 *			'type' => 'type',
	 *			'args' => [
	 *				'label' => 'Type Categories',
	 *				'labels' => [
	 *					'singular_name' => 'Type Category',
	 *				],
	 *				'hierarchical' => true,
	 *			],
	 *		],
	 *	];
	 */
	public function set_taxonomies() {
		$this->taxonomies = [];

        foreach ( $this->taxonomies as $name => $settings ) {
            if ( ! taxonomy_exists( $name ) ) {
    			register_taxonomy( $name, $settings['type'], $settings['args'] );
            }
		}
	}

	/**
	 * Set an array of namespaces corresponding to this plugin's shortcode classes.
	 */
	public function set_shortcodes() {
		$this->shortcodes = [
		];

        foreach ( $this->shortcodes as $class ) {
			$shortcode = new $class();

            if ( ! shortcode_exists( $shortcode->shortcode ) ) {
    			add_shortcode( $shortcode->shortcode, [ $shortcode, 'run' ] );
            }
		}
	}

}
