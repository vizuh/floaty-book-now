<?php
/**
 * Frontend rendering.
 *
 * @package FloatyBookNowChat
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Public-facing assets and data.
 */
class VZFLTY_Frontend {

	/**
	 * Enqueue assets when required.
	 *
	 * @return void
	 */
	public function enqueue_assets() {
		$options = vzflty_get_options();

		if ( ! $this->should_render( $options ) ) {
			return;
		}

		/**
		 * Fires before the floating button assets are enqueued.
		 *
		 * @since 1.1.0
		 *
		 * @param array $options Current plugin options.
		 */
		do_action( 'vzflty_before_button_render', $options );

		$style_handle  = 'vzflty-floaty';
		$script_handle = 'vzflty-floaty';

		wp_register_script(
			$script_handle,
			plugins_url( 'assets/js/floaty-button.js', VZFLTY_PLUGIN_FILE ),
			array(),
			VZFLTY_VERSION,
			true
		);

		wp_enqueue_style(
			$style_handle,
			plugins_url( 'assets/css/floaty-button.css', VZFLTY_PLUGIN_FILE ),
			array(),
			VZFLTY_VERSION
		);

		$custom_css = isset( $options['custom_css'] ) ? trim( $options['custom_css'] ) : '';

		if ( '' !== $custom_css ) {
			$inline_css = "/* Scope your selectors with #vzflty-button-container */\n" . wp_strip_all_tags( $custom_css );
			$inline_css = apply_filters( 'vzflty_inline_css', $inline_css, $options );
			wp_add_inline_style( $style_handle, wp_strip_all_tags( $inline_css ) );
		}

		$script_data = $this->prepare_script_data( $options );

		/**
		 * Filters the data passed to the frontend JavaScript.
		 *
		 * @since 1.1.0
		 *
		 * @param array $script_data Data to be localized.
		 * @param array $options     Current plugin options.
		 */
		$script_data = apply_filters( 'vzflty_script_data', $script_data, $options );

		wp_localize_script( $script_handle, 'VZFLTY_SETTINGS', $script_data );
		wp_enqueue_script( $script_handle );
	}

	/**
	 * Prepare script data.
	 *
	 * @param array $options Options array.
	 *
	 * @return array
	 */
	private function prepare_script_data( $options ) {
		$mode = $this->resolve_mode( $options );

		return array(
			'buttonLabel'     => vzflty_get_option_value( $options, 'button_label', '' ),
			'buttonTemplate'  => ( 'whatsapp' === $mode ) ? 'whatsapp' : 'default',
			'mode'            => $mode,
			'position'        => vzflty_get_option_value( $options, 'position', 'bottom_right' ),
			'actionType'      => vzflty_get_option_value( $options, 'action_type', 'link' ),
			'linkUrl'         => vzflty_get_option_value( $options, 'link_url', '' ),
			'linkTarget'      => vzflty_get_option_value( $options, 'link_target', '_blank' ),
			'iframeUrl'       => vzflty_get_option_value( $options, 'iframe_url', '' ),
			'eventName'       => vzflty_get_option_value( $options, 'event_name', 'vzflty_click' ),
			'whatsappPhone'   => vzflty_get_option_value( $options, 'whatsapp_phone', '' ),
			'whatsappMessage' => vzflty_get_option_value( $options, 'whatsapp_message', '' ),
			// Lead Capture & API.
			'apiUrl'          => esc_url_raw( rest_url( 'floaty/v1/leads' ) ),
			'nonce'           => wp_create_nonce( 'wp_rest' ),
			'leadCapture'     => array(
				'redirectType' => vzflty_get_option_value( $options, 'lc_redirect_type', 'whatsapp' ),
				'fields'       => array(
					'name'  => (bool) vzflty_get_option_value( $options, 'lc_field_name_enabled', 0 ),
					'email' => (bool) vzflty_get_option_value( $options, 'lc_field_email_enabled', 0 ),
					'phone' => (bool) vzflty_get_option_value( $options, 'lc_field_phone_enabled', 1 ),
				),
			),
			'apointoo'        => array(
				'enabled'    => (bool) vzflty_get_option_value( $options, 'apointoo_enabled', 0 ),
				'merchantId' => vzflty_get_option_value( $options, 'apointoo_merchant_id', '' ),
			),
			'gtm'             => array(
				'enabled'   => (bool) vzflty_get_option_value( $options, 'gtm_enabled', 0 ),
				'eventName' => vzflty_get_option_value( $options, 'gtm_event_name', 'vzflty_click' ),
			),
			'i18n'            => array(
				'defaultButtonLabel' => __( 'Book now', 'floaty-book-now-chat' ),
				'whatsappLabel'      => __( 'WhatsApp', 'floaty-book-now-chat' ),
				'modalCloseLabel'    => __( 'Close', 'floaty-book-now-chat' ),
				'modalCloseText'     => __( 'Close', 'floaty-book-now-chat' ),
				'formNamePlaceholder' => __( 'Name', 'floaty-book-now-chat' ),
				'formEmailPlaceholder' => __( 'Email', 'floaty-book-now-chat' ),
				'formPhonePlaceholder' => __( 'Phone', 'floaty-book-now-chat' ),
				'formSubmitLabel'     => __( 'Send', 'floaty-book-now-chat' ),
				'formSuccessMessage'  => __( 'Thank you! Redirecting...', 'floaty-book-now-chat' ),
				'formErrorMessage'    => __( 'An error occurred. Please try again.', 'floaty-book-now-chat' ),
			),
		);
	}

	/**
	 * Check render conditions.
	 *
	 * @param array $options Options array.
	 *
	 * @return bool
	 */
	private function should_render( $options ) {
		if ( empty( $options['enabled'] ) ) {
			return false;
		}

		$mode               = $this->resolve_mode( $options );
		$action             = isset( $options['action_type'] ) ? $options['action_type'] : 'link';
		$has_whatsapp_phone = ! empty( $options['whatsapp_phone'] );

		if ( 'whatsapp' === $mode ) {
			return $has_whatsapp_phone;
		}

		if ( 'lead_capture' === $mode ) {
			// For Lead Capture, we assume it's valid if enabled, 
			// though we might want to check if fields are enabled or redirect is valid.
			// For now, return true.
			return true;
		}

		if ( 'link' === $action && empty( $options['link_url'] ) ) {
			return false;
		}

		if ( 'iframe_modal' === $action && empty( $options['iframe_url'] ) ) {
			return false;
		}

		// Device targeting check.
		$is_mobile       = wp_is_mobile();
		$show_on_desktop = ! empty( $options['show_on_desktop'] );
		$show_on_mobile  = ! empty( $options['show_on_mobile'] );

		if ( $is_mobile && ! $show_on_mobile ) {
			return false;
		}

		if ( ! $is_mobile && ! $show_on_desktop ) {
			return false;
		}

		// Page targeting check.
		$page_targeting = isset( $options['page_targeting'] ) ? $options['page_targeting'] : 'all';

		if ( 'homepage' === $page_targeting && ! is_front_page() ) {
			return false;
		}

		if ( 'specific' === $page_targeting ) {
			$target_pages = isset( $options['target_pages'] ) && is_array( $options['target_pages'] ) ? $options['target_pages'] : array();

			if ( empty( $target_pages ) ) {
				return false;
			}

			global $post;
			if ( ! $post || ! in_array( $post->ID, $target_pages, true ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Resolve mode with backward compatibility.
	 *
	 * @param array $options Saved options.
	 *
	 * @return string
	 */
	private function resolve_mode( $options ) {
		$mode = isset( $options['mode'] ) ? $options['mode'] : '';

		if ( 'whatsapp' === $mode ) {
			return 'whatsapp';
		}

		if ( 'lead_capture' === $mode ) {
			return 'lead_capture';
		}

		if ( 'custom' === $mode ) {
			return 'custom';
		}

		if ( isset( $options['button_template'] ) && 'whatsapp' === $options['button_template'] ) {
			return 'whatsapp';
		}

		return 'custom';
	}
}
