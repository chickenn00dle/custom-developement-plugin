<?php
/**
 * Class for defined constants.
 */
class Custom_Development_Plugin_Constants {
	/**
	 * Constructor.
	 */
	function __construct() {
		add_action( 'wp_ajax_get_plugin_defined_contants', array( $this, 'fetch_plugin_defined_constants' ) );
		add_action( 'wp_ajax_update_constant', array( $this, 'post_update_constant' ) );
	}

	/**
	 * Get constants defined via plugin.
	 */
	static public function get_plugin_defined_constants() {
		return get_option( 'custom_development_plugin_constants', array() );
	}

	/**
	 * Update constant.
	 *
	 * @param string $constant
	 * @param string $value
	 */
	static public function update_constant( $constant, $value ) {
		$defined_contants              = get_option( 'custom_development_plugin_constants', array() );
		$defined_contants[ $constant ] = $value;

		update_option( 'custom_development_plugin_constants', $defined_contants );
	}

	/**
	 * Fetch plugin defined constants (AJAX).
	 */
	function fetch_plugin_defined_constants() {
		wp_send_json_success( $this->get_plugin_defined_constants() );
	}

	/**
 	 * Post update to contant (AJAX).
	 */
	function post_update_constant() {
		if ( ! isset( $_POST[ 'constant' ], $_POST[ 'value' ] ) ) {
			wp_send_json_error();
			exit;
		}
		
		$constant = sanitize_text_field( $_POST[ 'constant' ] );
		$value    = sanitize_text_field( $_POST[ 'value' ] );

		$this->update_constant( $constant, $value );

		wp_send_json_success();
		exit;
	}
}

