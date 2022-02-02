<?php
namespace ImprovedBreadcrumbs;


/**
 * Class Plugin
 *
 * Main Plugin class
 * @since 1.0
 */
class Plugin {

	/**
	 * Instance
	 *
	 * @since 1.0
	 * @access private
	 * @static
	 *
	 * @var Plugin The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @return Plugin An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Include Widgets files
	 *
	 * Load widgets files
	 *
	 * @since 1.0
	 * @access private
	 */
	private function include_widgets_files() {
		require_once __DIR__ . '/widgets/breadcrumbs.php';
	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager
	 *
	 * @throws \Exception
	 */
	public function register_widgets( $widgets_manager ) {
		// Its is now safe to include Widgets files
		$this->include_widgets_files();

		// Register Widgets
		$widgets_manager->register_widget_type( new Widgets\Improved_Breadcrumbs() );
	}

	/**
	 * Filter yoast breadcrumbs seperator
	 *
	 * Add css class to seperator and add Li's for better structure.
	 *
	 * @since  1.0
	 * @access public
	 *
	 */
    public function filter_wpseo_breadcrumb_separator($this_options_breadcrumbs_sep) {
        return '</li><li><span class="separator">' . $this_options_breadcrumbs_sep . '</span>';
    }
    
	/**
	 *  Plugin class constructor
	 *
	 * Register plugin action hooks and filters
	 *
	 * @since 1.0
	 * @access public
	 */
	public function __construct() {
		// Register widgets
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
        // Seperator modify
        add_action( 'wpseo_breadcrumb_separator', [ $this, 'filter_wpseo_breadcrumb_separator' ] );
	}
}

// Instantiate Plugin Class
Plugin::instance();
