<?php
/**
 * Class for defined constants.
 */
class Custom_Development_Plugin_Constants {
	/**
	 * Endpoint namespace.
	 */
	public $namespace = '/custom-development-plugin/v1';

	/**
	 * Endpoint route base.
	 */
	public $rest_base = '/constants/';

	/**
	 * Constructor.
	 */
	function __construct() {
		add_action( 'plugins_loaded', array( $this, 'define_plugin_constants' ) );
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Get constants defined via plugin.
	 */
	static public function get_plugin_defined_constants() {
		return get_option( 'custom_development_plugin_constants', array() );
	}

	/**
	 * Update/Create constant.
	 *
	 * @param string $constant
	 * @param string $value
	 * @param string $type
	 */
	static public function update_constant( $constant, $value, $type ) {
		$defined_constants = get_option( 'custom_development_plugin_constants', array() );

		foreach ( $defined_constants as $index => $defined_constant ) {
			if ( $constant === $defined_constant['name'] ) {
				array_splice( $defined_constants, $index, 1 );
				break;
			}
		}

		$defined_constants[] = array(
			'name'  => $constant,
			'type'  => $type,
			'value' => $value
		);

		update_option( 'custom_development_plugin_constants', $defined_constants );
	}

	/**
	 * Delete constant.
	 *
	 * @param string $constant
	 */
	static public function delete_constant( $constant ) {
		$defined_constants = get_option( 'custom_development_plugin_constants', array() );

		foreach ( $defined_constants as $index => $defined_constant ) {
			if ( $constant === $defined_constant['name'] ) {
				array_splice( $defined_constants, $index, 1 );
				break;
			}
		}

		update_option( 'custom_development_plugin_constants', $defined_constants );
	}

	/**
	 * Handle REST request to get constants.
	 *
	 * @param WP_REST_Request $request
	 */
	function get_plugin_defined_constants_rest_cb( $request ) {
		$constants = $this->get_plugin_defined_constants();

		$response = new WP_REST_Response( $constants );
		$response->set_status( 200 );

		return $response;
	}

	/**
	 * Handle REST request to update/create constant.
	 *
	 * @param WP_REST_Request $request
	 */
	function update_constant_rest_cb( $request ) {
		$body     = $request->get_json_params();
		$constant = sanitize_text_field( $body['name'] );
		$value    = sanitize_text_field( $body['value'] );
		$type     = sanitize_text_field( $body['type'] );

		if ( empty( $constant ) || empty( $value ) || empty( $type ) ) {
			return new WP_Error( 'rest_update_constant', __( 'Error updating or creating constant.', 'custom-development-plugin' ) );
		}

		$this->update_constant( $constant, $value, $type );

		$response = new WP_REST_Response();
		$response->set_status( 200 );
	}

	/**
	 * Handle REST request to delete constant.
	 *
	 * @param WP_REST_Request $request
	 */
	function delete_constant_rest_cb( $request ) {
		$body     = $request->get_json_params();
		$constant = sanitize_text_field( $body['name'] );

		if ( empty( $constant ) ) {
			return new WP_Error( 'rest_delete_constant', __( 'Error deleting constant.', 'custom-development-plugin' ) );
		}

		$this->delete_constant( $constant );

		$response = new WP_REST_Response();
		$response->set_status( 200 );
	}

	/**
	 * REST Permission callback.
	 */
	function validate_rest_cb() {
		return true;
		// return current_user_can( 'manage_options' );
	}

	/**
	 * Register REST routes for Constant methods.
	 */
	function register_routes() {
		register_rest_route( $this->namespace, $this->rest_base, array(
			'methods'             => 'GET',
			'callback'            => array( $this, 'get_plugin_defined_constants_rest_cb' ),
			'permission_callback' => array( $this, 'validate_rest_cb' )
		) );

		register_rest_route( $this->namespace, $this->rest_base, array(
			'methods'             => 'POST',
			'callback'            => array( $this, 'update_constant_rest_cb' ),
			'permission_callback' => array( $this, 'validate_rest_cb' )
		) );

		register_rest_route( $this->namespace, $this->rest_base, array(
			'methods'             => 'DELETE',
			'callback'            => array( $this, 'delete_constant_rest_cb' ),
			'permission_callback' => array( $this, 'validate_rest_cb' )
		) );
	}

	function define_plugin_constants() {
		$constants = get_option( 'custom_development_plugin_constants', array() );

		foreach( $constants as $constant ) {
			$name = $constant['name'];
			$type = $constant['type'];
			$value = 'bool' !== $type ? $constant['value'] : 'false' !== $constant['value'];

			if ( ! defined( $constant['name'] ) ) {
				define( $name, $value );
			}
		}
	}
}

