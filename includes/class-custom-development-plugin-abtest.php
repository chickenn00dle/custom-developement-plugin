<?php
/**
 * A/B test class.
 */
class Custom_Development_Plugin_ABTest {
	/**
 	 * ABTest user meta key.
	 */
	const ABTEST_META_KEY = 'custom_development_plugin_abtest';

	/**
 	 * Control group.
	 */
	const CONTROL = 'control';

	/**
 	 * Experiement group.
	 */
	const EXPERIMENT = 'experiment';

	/**
	 * Locale.
	 */
	private $locale;

	/**
	 * Test name.
	 */
	private $name;

	/**
	 * Test variation.
	 */
	private $variation;

	/**
	 * Constructor.
	 *
	 * @param string $name
	 */
	function __construct( $name ) {
		$this->name   = $name;
		$this->locale = get_locale();

		add_action( 'init', array( $this, 'initialize_variation' ) );
	}

	/**
	 * Initialize variation.
	 */
	function initialize_variation() {
		$this->variation = $this->get_variation();
	}

	/**
	 * Fetch abtest config.
	 */
	private function get_abtest_config() {
		$url     = CUSTOM_DEVELOPMENT_PLUGIN_DIR . 'includes/data/abtests.json';
		$request = wp_remote_get( $url, array( 'sslverify' => false ) );
		$data    = json_decode( wp_remote_retrieve_body( $request ), true );
		$config  = array();

		if ( is_wp_error( $data ) ) {
			return;
		}

		if ( isset( $data[ $this->name ] ) ) {
			$config = $data[ $this->name ];
		}

		return $config;
	}

	/**
	 * get variation.
	 *
	 * @return string
	 */
	private function get_variation() {
		$variation = self::CONTROL;
		$config    = $this->get_abtest_config();
		$user_id   = get_current_user_id();

		if ( ! $config || ! $user_id ) {
			return $variation;
		}

		if ( ! $this->is_abtest_active( $config ) || ! $this->is_user_eligible( $config ) ) {
			if ( $this->is_abtest_closed( $config ) ) {
				$this->delete_variation_meta( $user_id, $variation );
			}

			return $variation;
		}

		$variation = $this->maybe_get_variation_meta( $user_id );

		if ( ! $variation ) {
			$variation = $this->assign_variation( $config );

			$this->save_and_track_variation( $user_id, $variation );
		}

		return $variation;
	}

	/**
 	 * Get variation from user meta if it exists.
	 *
	 * @param int $user_id
	 *
	 * @return string|null
	 */
	private function maybe_get_variation_meta( $user_id ) {
		$meta = get_user_meta( $user_id, self::ABTEST_META_KEY, true );

		if ( ! $meta || ! isset( $meta[ $this->name ] ) ) {
			return null;
		}

		return $meta[ $this->name ];
	}

	/**
	 * Save variation to user meta and trigger tracks event.
	 *
	 * @param string $name
	 * @param string $variation
	 */
	private function save_and_track_variation( $user_id, $variation ) {
		$meta = get_user_meta( $user_id, self::ABTEST_META_KEY, true );

		if ( ! $meta || ! is_array( $meta ) ) {
			$meta = array();
		}

		update_user_meta(
			$user_id,
			self::ABTEST_META_KEY,
			array_merge(
				$meta,
				array( $this->name => $variation )
			)
		);

		$properties = array(
			'name'      => $this->name,
			'timestamp' => current_time( 'timestamp' ),
			'group'     => $variation
		);

		$this->trackABTestEvent( $properties );
	}

	/**
	 * Delete variation from user meta.
	 *
	 * @param string $name
	 * @param string $variation
	 */
	private function delete_variation_meta( $user_id, $variation ) {
		$meta = get_user_meta( $user_id, self::ABTEST_META_KEY, true );

		if ( ! $meta || ! is_array( $meta ) || ! isset( $meta[ $this->name ] ) ) {
			return;
		}

		unset( $meta[ $this->name ] );

		update_user_meta(
			$user_id,
			self::ABTEST_META_KEY,
			$meta
		);
	}

	/**
	 * Assign a variation randomly.
	 *
	 * @param array $config
	 *
	 * @return string
	 */
	private function assign_variation( $config ) {
		$allocation         = $config['allocation'];
		$total_allocation   = array_sum( $allocation );
		$random_allocation  = rand( 1, $total_allocation );
		$current_allocation = 0;

		foreach ( $allocation as $group => $amount ) {
			$current_allocation += $amount;
			if ( $current_allocation > $random_allocation ) {
				return $group;
			}
		}
	}

	/**
	 * A mock tracks event for experiment.
	 */
	private function trackABTestEvent( $properties ) {
		custom_development_plugin()->logger->log(
			'ABTest Tracks Placeholder',
			'custom_development_plugin_abtest_start',
			$properties
		);
	}

	/**
	 * Check if current user is in eligible locale.
	 *
	 * @param array $config abtest config.
	 *
	 * @return bool
	 */
	private function is_user_eligible( $config ) {
		return $this->locale === $config['locale'] || 'any' === $config['locale'];
	}

	/**
	 * Check if test is active.
	 *
	 * @param array $config abtest config.
	 *
	 * @return bool
	 */
	private function is_abtest_active( $config ) {
		return 'active' === $config['status'] && in_array( current_time( 'timestamp' ), range( $config['start'], $config['end'] ) );
	}

	/**
	 * Check if test is closed.
	 *
	 * @param array $config abtest config.
	 *
	 * @return bool
	 */
	private function is_abtest_closed( $config ) {
		return 'closed' === $config['status'];
	}

	/**
 	 * Check if is control.
	 *
	 * @return bool
	 */
	public function is_control() {
		return self::CONTROL === $this->variation;
	}

	/**
 	 * Check if is experiment.
	 *
	 * @return bool
	 */
	public function is_experiment() {
		return self::EXPERIMENT === $this->variation;
	}

	/**
	 * Get the chosen variation.
	 */
	public function get_group() {
		return $this->variation;
	}
}

