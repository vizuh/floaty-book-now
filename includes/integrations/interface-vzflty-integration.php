<?php
/**
 * Integration Interface.
 *
 * @package FloatyBookNowChat
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Interface for lead integrations.
 */
interface VZFLTY_Integration {

	/**
	 * Send lead data to the integration.
	 *
	 * @param array $lead_data Lead data from DB.
	 *
	 * @return bool|WP_Error True on success, WP_Error on failure.
	 */
	public function send( $lead_data );

	/**
	 * Get integration slug.
	 *
	 * @return string
	 */
	public function get_slug();
}
