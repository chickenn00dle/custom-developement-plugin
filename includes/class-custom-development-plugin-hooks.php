<?php
/**
 * Class for custom hooks.
 */
class Custom_Development_Plugin_Hooks {
	/**
	 * Endpoint namespace.
	 */
	public $namespace = '/custom-development-plugin/v1';

	/**
	 * Endpoint route base.
	 */
	public $rest_base = '/hooks/';

	/**
	 * Constructor.
	 */
	function __construct() {
		add_action( 'init', array( $this, 'define_custom_hook_type' ) );
		add_action( 'plugins_loaded', array( $this, 'add_custom_hooks' ) );
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Get custom hooks defined via plugin.
	 */
	static public function get_plugin_defined_hooks() {
		$args = array(
			'post_type'   => 'hook',
			'numberposts' => -1,
			'order'       => 'ASC'
		);

		$hooks = array_map( function( $hook ) {
			$meta = get_post_meta( $hook->ID );
			return array(
				'id'       => $hook->ID,
				'title'    => $hook->post_title,
				'name'     => $hook->name,
				'cb'       => $hook->post_content,
				'args'     => array (
					'accepted' => $hook->accepted,
					'priority' => $hook->priority,
					'type'     => $hook->type
				)
			);
		}, get_posts( $args ) );

		return $hooks;
	}

	/**
	 * Update/Create hook.
	 *
	 * @param string $title
	 * @param string $name Action or filter name.
	 * @param string $cb
	 * @param array  $args (optional)
	 * @param int    $id (optional)
	 *
	 * @return ID of new post.
	 */
	static public function update_hook( $title, $name, $cb, $args = array(), $id = 0 ) {
		$hook_arr = array(
			'post_type'    => 'hook',
			'post_title'   => $title,
			'post_content' => $cb,
			'post_status'  => 'publish',
			'meta_input'   => array(
				'name' => $name
			)
		);

		if ( $id ) {
			$hook_arr['ID'] = $id;
		}

		foreach( $args as $key => $value ) {
			$hook_arr['meta_input'][ $key ] = $value;
		}

		return wp_insert_post( $hook_arr );
	}

	/**
	 * Delete hook.
	 *
	 * @param int $id
	 */
	static public function delete_hook( $id ) {
		return wp_delete_post( $id, true );
	}

	/**
	 * Handle REST request to get hooks.
	 */
	function get_plugin_defined_hooks_rest_cb() {
		$hooks = $this->get_plugin_defined_hooks();

		return new WP_REST_Response( $hooks );
	}

	/**
	 * Handle REST request to update/create hook.
	 *
	 * @param WP_REST_Request $request
	 */
	function update_hook_rest_cb( $request ) {
		$body  = $request->get_json_params();
		$title = sanitize_text_field( $body['title'] );
		$name  = sanitize_text_field( $body['name'] );
		$cb    = sanitize_text_field( $body['cb'] );

		if ( empty( $title ) || empty( $cb ) || empty( $name ) ) {
			return new WP_Error( 'rest_update_hook_params', __( 'Error updating or creating hook. Invalid parameters supplied.', 'custom-development-plugin' ) );
		}

		$id   = isset( $body['id'] ) ? (int) $body['id'] : 0;
		$args = array();
		
		foreach ( $body['args'] as $key => $value ) {
			$args[ $key ] = $value;
		}

		$result = $this->update_hook( $title, $name, $cb, $args, $id );

		if ( ! $result ) {
			return new WP_Error( 'rest_update_hook_failure', __( 'Error updating or creating hook. Something went wrong.', 'custom-development-plugin' ) );
		}

		return new WP_REST_Response(
			array(
				'status'   => 200,
				'response' => 'success'
			)
		);
	}

	/**
	 * Handle REST request to delete hook.
	 *
	 * @param WP_REST_Request $request
	 */
	function delete_hook_rest_cb( $request ) {
		$body = $request->get_json_params();

		if ( ! isset( $body['id'] ) ) {
			return new WP_Error( 'rest_delete_hook_params', __( 'Error deleting hook. No ID supplied', 'custom-development-plugin' ) );
		}

		$id = sanitize_text_field( $body['id'] );

		$result = $this->delete_hook( $id );

		if ( ! $result ) {
			return new WP_Error( 'rest_delete_hook_failure', __( 'Error deleting hook. Something went wrong.', 'custom-development-plugin' ) );
		}

		return new WP_REST_Response(
			array(
				'status'   => 200,
				'response' => 'success'
			)
		);
	}

	/**
	 * REST Permission callback.
	 */
	function validate_rest_cb() {
		return true;
		// return current_user_can( 'manage_options' );
	}

	/**
	 * Register REST routes for hook methods.
	 */
	function register_routes() {
		register_rest_route( $this->namespace, $this->rest_base, array(
			'methods'             => 'GET',
			'callback'            => array( $this, 'get_plugin_defined_hooks_rest_cb' ),
			'permission_callback' => array( $this, 'validate_rest_cb' )
		) );

		register_rest_route( $this->namespace, $this->rest_base, array(
			'methods'             => 'POST',
			'callback'            => array( $this, 'update_hook_rest_cb' ),
			'permission_callback' => array( $this, 'validate_rest_cb' )
		) );

		register_rest_route( $this->namespace, $this->rest_base, array(
			'methods'             => 'DELETE',
			'callback'            => array( $this, 'delete_hook_rest_cb' ),
			'permission_callback' => array( $this, 'validate_rest_cb' )
		) );
	}

	/**
	 * Define Hook CPT.
	 */
	function define_custom_hook_type() {
		$labels = array(
			'name'                  => _x( 'Hooks', 'Post type general name', 'custom-development-plugin' ),
			'singular_name'         => _x( 'Hook', 'Post type singular name', 'custom-development-plugin' ),
		);
	
		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => false,
			'show_in_menu'       => false,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'hook' ),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => null
		);
	
		register_post_type( 'hook', $args );
	}

	/**
	 * Add all defined custom hooks.
	 */
	function add_custom_hooks() {
		$hooks = $this->get_plugin_defined_hooks();

		if ( empty( $hooks ) ) {
			return;
		}

		foreach ( $hooks as $hook ) {
			$tag      = $hook['name'];
			$callback = eval( 'return ' . $hook['cb'] . ';' );
			$priority = (int) $hook['args']['priority'];
			$accepted = (int) $hook['args']['accepted'];

			if ( 'filter' === $hook['args']['type'] ) {
				add_filter( $tag, $callback, $priority, $accepted );
			} else {
				add_action( $tag, $callback, $priority, $accepted );
			}
		}
	}
}

