<?php
/**
 * Logger class.
 */
class Custom_Development_Plugin_Logger {
	/**
	 * Generic logging function wrapping error_log.
	 *
	 * @param string $key Unique key to add to log entry.
	 * @param array  $args
	 *
	 * @throws Exception When no arguments are passed.
	 */
	function log( $key, ...$args ) {
		if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
			return;
		}

		if ( 0 < sizeof( $args ) ) {
			$log_identifier = 'Custom Development Plugin Logger %s - [%s]';

			error_log( '=======================================' );
			error_log( sprintf( $log_identifier, 'Begin', $key ) );

			foreach ( $args as $message ) {
				if ( 'string' === gettype( $message ) ) {
					error_log( $message );
				} else {
					error_log( print_r( $message, true ) );
				}
			}

			error_log( sprintf( $log_identifier, 'End', $key ) );
			error_log( '=======================================' );
		} else {
			throw new \Exception( 'INVALID LOGGER ARGS: Please provide at least one argument' );
		}
	}
}

