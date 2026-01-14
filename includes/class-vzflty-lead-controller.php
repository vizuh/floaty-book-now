<?php
/**
 * REST API Controller for Leads.
 *
 * @package FloatyBookNowChat
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles REST API requests for lead capture.
 */
class VZFLTY_Lead_Controller extends WP_REST_Controller {

	/**
	 * Namespace for API.
	 *
	 * @var string
	 */
	protected $namespace = 'floaty/v1';

	/**
	 * Base route for leads.
	 *
	 * @var string
	 */
	protected $rest_base = 'leads';

	/**
	 * Register the routes.
	 *
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_item' ),
					'permission_callback' => array( $this, 'create_item_permissions_check' ),
					'args'                => $this->get_endpoint_args(),
				),
			)
		);
	}

	/**
	 * Check permissions for creating a lead.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return bool
	 */
	public function create_item_permissions_check( $request ) {
		// Public endpoint, but we can rate limit or nonce check here if needed later.
		// For now, simple nonce check via header is recommended in frontend.
		return true;
	}

	/**
	 * Create a lead item.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return WP_REST_Response|WP_Error
	 */
	public function create_item( $request ) {
		// Validations.
		$name  = sanitize_text_field( $request->get_param( 'name' ) );
		$email = sanitize_email( $request->get_param( 'email' ) );
		$phone = sanitize_text_field( $request->get_param( 'phone' ) );

		if ( empty( $name ) || empty( $phone ) ) {
			return new WP_Error( 'missing_fields', __( 'Name and Phone are required.', 'floaty-book-now-chat' ), array( 'status' => 400 ) );
		}

		// Prepare Data.
		$utm_params = $request->get_param( 'utm' );
		$utm_json   = ! empty( $utm_params ) ? wp_json_encode( $utm_params ) : '{}';

		$data = array(
			'lead_name'             => $name,
			'lead_email'            => $email,
			'lead_phone'            => $phone,
			'lead_normalized_phone' => $phone, // Normalize later if needed.
			'utm_data'              => $utm_json,
			'status'                => 'new',
			'source_url'            => esc_url_raw( $request->get_param( 'source_url' ) ),
		);

		// Insert into DB.
		$db      = new VZFLTY_DB();
		$lead_id = $db->insert_lead( $data );

		if ( ! $lead_id ) {
			return new WP_Error( 'db_error', __( 'Could not save lead.', 'floaty-book-now-chat' ), array( 'status' => 500 ) );
		}

		// Hook for Integrations (Sync/Async).
		do_action( 'vzflty_lead_created', $lead_id, $data );

		$response = array(
			'lead_id' => $lead_id,
			'message' => __( 'Lead captured successfully.', 'floaty-book-now-chat' ),
		);

		return rest_ensure_response( $response );
	}

	/**
	 * Endpoint arguments.
	 *
	 * @return array
	 */
	public function get_endpoint_args() {
		return array(
			'name' => array(
				'description' => __( 'Lead Name', 'floaty-book-now-chat' ),
				'type'        => 'string',
				'required'    => true,
				'sanitize_callback' => 'sanitize_text_field',
			),
			'email' => array(
				'description' => __( 'Lead Email', 'floaty-book-now-chat' ),
				'type'        => 'string',
				'required'    => false,
				'sanitize_callback' => 'sanitize_email',
			),
			'phone' => array(
				'description' => __( 'Lead Phone', 'floaty-book-now-chat' ),
				'type'        => 'string',
				'required'    => true,
				'sanitize_callback' => 'sanitize_text_field',
			),
			'utm' => array(
				'description' => __( 'UTM Parameters', 'floaty-book-now-chat' ),
				'type'        => 'object',
				'required'    => false,
			),
			'source_url' => array(
				'description' => __( 'Source URL', 'floaty-book-now-chat' ),
				'type'        => 'string',
				'required'    => false,
				'sanitize_callback' => 'esc_url_raw',
			),
		);
	}
}
