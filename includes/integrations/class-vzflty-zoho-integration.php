<?php
/**
 * Zoho CRM Integration.
 *
 * @package FloatyBookNowChat
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/interface-vzflty-integration.php';

/**
 * Handles sending leads to Zoho CRM via Web-to-Lead.
 */
class VZFLTY_Zoho_Integration implements VZFLTY_Integration {

	/**
	 * Get slug.
	 *
	 * @return string
	 */
	public function get_slug() {
		return 'zoho';
	}

	/**
	 * Send lead to Zoho.
	 *
	 * @param array $lead_data Lead data.
	 *
	 * @return bool|WP_Error
	 */
	public function send( $lead_data ) {
		$options = vzflty_get_options();
		
		// 1. Get Zoho Settings.
		// Note: The user mentioned "Action URL" and specific hidden fields in their provided snippet.
		// We should allow storing these in options.
		// For MVP, we will look for 'zoho_action_url' and map basic fields.
		
		$action_url = vzflty_get_option_value( $options, 'zoho_action_url', '' );
		if ( empty( $action_url ) ) {
			return new WP_Error( 'missing_config', 'Zoho Action URL not configured.' );
		}

		// 2. Prepare Payload.
		// Standard mapping based on Zoho's default Web-to-Lead fields.
		// In a real enterprise version, we'd have a mapping UI.
		$payload = array(
			'Last Name' => $lead_data['lead_name'], // Zoho requires Last Name.
			'Email'     => $lead_data['lead_email'],
			'Mobile'    => $lead_data['lead_phone'],
			'Lead Source' => 'Floaty Button',
		);

		// Add custom hidden fields if any (from options).
		// Example: xnQsjsdp, xmIwtLD (these are Zoho specific security tokens).
		// Ideally, these should be settings fields.
		// For now, let's assume valid tokens are part of the configuration or we can parse them? 
		// Actually, Zoho Web-to-Lead relies heavily on these hidden input tokens. 
		// If we do a server-side POST, we need to send EXACTLY what the form expects.
		
		// Enterprise Approach: User pastes their "Embed Code" or we provide fields for "Action URL", "xnQsjsdp", etc.
		// Let's assume we add these to settings later. For now, I'll attempt a generic POST.
		
		$zoho_tokens = array(
			'xnQsjsdp' => vzflty_get_option_value( $options, 'zoho_xnQsjsdp', '' ),
			'xmIwtLD'  => vzflty_get_option_value( $options, 'zoho_xmIwtLD', '' ),
			'actionType' => 'TGVhZHM=',
		);

		$body = array_merge( $payload, $zoho_tokens );

		// 3. Send Request.
		$response = wp_remote_post( $action_url, array(
			'body'      => $body,
			'timeout'   => 15,
			'sslverify' => true,
		) );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$code = wp_remote_retrieve_response_code( $response );

		// Zoho redirects on success, so 302/200 are usually okay. 
		// However, pure API might behave differently than form post.
		// Web-to-Lead is designed for browser POSTs (redirects user).
		// Server-to-server POST to Web-to-Lead endpoint works but returns HTML.
		
		if ( $code >= 200 && $code < 400 ) {
			return true;
		}

		return new WP_Error( 'zoho_error', 'Zoho returned status ' . $code );
	}
}
