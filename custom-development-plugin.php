<?php
/**
 * Plugin Name:     Custom Development Plugin
 * Plugin URI:      https://github.com/chickenn00dle/custom-developement-plugin
 * Description:     A react admin for custom development snippets and things.
 * Author:          Rasmy Nguyen
 * Author URI:      rzmy.win
 * Text Domain:     custom-development-plugin
 * Domain Path:     /languages
 * Version:         1.0.0
 *
 * @package         Custom_Development_Plugin
 */

defined( 'ABSPATH' ) || exit;

/**
 * Get instance of plugin.
 */
function custom_development_plugin() {
	static $plugin;

	if ( ! isset( $plugin ) ) {
		$plugin = Custom_Development_Plugin::get_instance();
	}

	return $plugin;
}

/**
 * Convenience global log function.
 */
function cdp_log( $context, ...$args ) {
	custom_development_plugin()->logger->log( $context, $args );
}

/**
 * Main Plugin class.
 */
class Custom_Development_Plugin {
	/**
	 * Singleton.
	 */
	private static $instance;

	/**
	 * Hooks class.
	 */
	public $hooks;

	/**
	 * Hooks class.
	 */
	public $logger;

	/**
	 * Instantiate or return existing Singleton instance.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	function __construct() {
		add_action( 'admin_menu', array( $this, 'add_menu_item' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_plugin' ) );

		$this->init();
	}

	/**
	 * Load dependencies.
	 */
	private function init() {
		require_once 'includes/class-custom-development-plugin-hooks.php';
		require_once 'includes/class-custom-development-plugin-logger.php';

		$this->hooks  = new Custom_Development_Plugin_Hooks();
		$this->logger = new Custom_Development_Plugin_Logger();
	}

	/**
	 * Load plugin dashboard.
	 */
	function load_plugin() {
		if ( ! is_admin() ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( empty( $_GET[ 'page' ] ) || 'custom-development' !== $_GET[ 'page' ] ) {
			return;
		}

		$css_asset = plugin_dir_url( __FILE__ ) . 'dist/main.css';
		$js_asset  = plugin_dir_url( __FILE__ ) . 'dist/bundle.js';

		wp_enqueue_style( 'custom-development-plugin-styles', $css_asset, array( 'wp-components' ) );
		wp_enqueue_script( 'custom-development-plugin-scripts', $js_asset, array(), 1, true );

		wp_localize_script(
			'custom-development-plugin-scripts',
			'customDevelopmentPlugin',
			array(
				'namespace'   => $this->hooks->namespace,
				'rest_base'   => $this->hooks->rest_base,
				'selector'    => '#root',
				'server_vars' => json_encode( $this->get_server_vars() )
			)
		);
	}

	/**
	 * Add admin menu.
	 */
	function add_menu_item() {
		add_menu_page(
			__( 'Custom Development', 'custom-development-plugin' ),
			__( 'Development', 'custom-development-plugin' ),
			'manage_options',
			'custom-development',
			array( $this, 'output_plugin_html' ),
			'dashicons-flag'
		);
	}

	/**
	 * Output plugin HTML.
	 */
	function output_plugin_html() {
		$output  = '<div id="root">';
		$output .= '<p style="margin-left:20px;">Custom development plugin requires your browser to run Javascript. Please enable and reload this page.</p>';
		$output .= '</div>';
		echo $output;
	}

	/**
	 * Get array of server variables.
	 */
	private function get_server_vars() {
		return array(
			'SERVER_SOFTWARE' => $this->maybe_get_server_var( 'SERVER_SOFTWARE' ),
			'HOME'            => $this->maybe_get_server_var( 'HOME' ),
			'SERVER_HOST'     => $this->maybe_get_server_var( 'HTTP_HOST' ),
			'SERVER_NAME'     => $this->maybe_get_server_var( 'SERVER_NAME' ),
			'HTTPS'           => $this->maybe_get_server_var( 'HTTPS' ),
			'SERVER_PORT'     => $this->maybe_get_server_var( 'SERVER_PORT' ),
			'SERVER_ADDR'     => $this->maybe_get_server_var( 'SERVER_ADDR' ),
			'REMOTE_PORT'     => $this->maybe_get_server_var( 'REMOTE_PORT' ),
			'REMOTE_ADDR'     => $this->maybe_get_server_var( 'REMOTE_ADDR' )
		);
	}

	/**
	 * Check for specific server variable.
	 */
	private function maybe_get_server_var( $var ) {
		return $_SERVER && isset( $_SERVER[ $var ] ) ? $_SERVER[ $var ] : 'Unavailable';
	}
}

custom_development_plugin();
