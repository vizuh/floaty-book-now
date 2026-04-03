<?php
/**
 * Integration Manager.
 *
 * @package FloatyBookNowChat
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Manages active integrations and dispatches leads.
 */
class VZFLTY_Integration_Manager {

	/**
	 * Initialize Manager.
	 */
	public function init() {
		add_action( 'vzflty_lead_created', array( $this, 'dispatch_lead' ), 10, 2 );
	}

	/**
	 * Dispatch lead to active integrations.
	 *
	 * @param int   $lead_id Lead ID.
	 * @param array $data    Lead Data.
	 *
	 * @return void
	 */
	public function dispatch_lead( $lead_id, $data ) {
		$options = vzflty_get_options();

		if ( ! empty( $options['integration_enabled'] ) && ! empty( $options['integration_webhook_url'] ) ) {
			$this->send_webhook( $lead_id, $data, $options['integration_webhook_url'] );
		}
	}

	/**
	 * Send webhook.
	 *
	 * @param int    $lead_id Lead ID.
	 * @param array  $data    Lead data.
	 * @param string $url     Webhook URL.
	 *
	 * @return void
	 */
	private function send_webhook( $lead_id, $data, $url ) {
		$payload = array(
			'event'     => 'lead_created',
			'lead_id'   => $lead_id,
			'timestamp' => current_time( 'c' ),
			'data'      => $data,
		);

		$args = array(
			'body'        => wp_json_encode( $payload ),
			'headers'     => array(
				'Content-Type' => 'application/json',
			),
			'timeout'     => 5,
			'blocking'    => false,
		);

		wp_remote_post( $url, $args );

		// Log attempt to queue (history).
		$db = new VZFLTY_DB();
		$db->add_to_queue( $lead_id, 'webhook', $payload );
	}
}
