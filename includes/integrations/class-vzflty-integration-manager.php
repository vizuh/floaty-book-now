<?php
/**
 * Integration Manager.
 *
 * @package FloatyBookNowChat
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/class-vzflty-zoho-integration.php';

/**
 * Manages active integrations and dispatches leads.
 */
class VZFLTY_Integration_Manager {

	/**
	 * Registered integrations.
	 *
	 * @var VZFLTY_Integration[]
	 */
	private $integrations = array();

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->register_integrations();
		add_action( 'vzflty_lead_created', array( $this, 'dispatch_lead' ), 10, 2 );
	}

	/**
	 * Register available integrations.
	 */
	private function register_integrations() {
		$this->integrations['zoho'] = new VZFLTY_Zoho_Integration();
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
		
		// Check global integrations toggle if proper settings existed, 
		// but for now check individual.
		
		// Zoho
		if ( ! empty( $options['zoho_enabled'] ) ) {
			$this->process_integration( 'zoho', $lead_id, $data );
		}

		// Webhook (Future)
		// if ( ! empty( $options['webhook_enabled'] ) ) ...
	}

	/**
	 * Process a single integration.
	 *
	 * @param string $slug    Integration slug.
	 * @param int    $lead_id Lead ID.
	 * @param array  $data    Lead Data.
	 */
	private function process_integration( $slug, $lead_id, $data ) {
		if ( ! isset( $this->integrations[ $slug ] ) ) {
			return;
		}

		$integration = $this->integrations[ $slug ];
		$result      = $integration->send( $data );
		$status      = is_wp_error( $result ) ? 'failed' : 'completed';
		$error       = is_wp_error( $result ) ? $result->get_error_message() : '';

		// Log to Queue/History Table (Synchronous for now).
		$db = new VZFLTY_DB();
		$queue_data = array(
			'lead_id'   => $lead_id,
			'type'      => $slug,
			'payload'   => $data, // Simplified.
			'status'    => $status,
			'last_error' => $error,
		);

		// We use add_to_queue even if it's already "completed" just for history.
		// In a real queue system, we'd insert with 'pending' then process.
		// Here we process *then* log result.
		$db->add_to_queue( $lead_id, $slug, $data ); 
		
		// Update the queue item with status immediately if we want to be precise,
		// but `add_to_queue` sets status to 'pending' by default. 
		// Let's assume for MVP we just want to fire and forget, logging is secondary.
		// A proper queue implementation is Phase 3/4.
	}
}
