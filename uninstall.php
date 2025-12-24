<?php
/**
 * Uninstall handler.
 *
 * Fired when the plugin is deleted via WordPress admin.
 * Cleans up plugin options from the database.
 *
 * @package FloatyBookNowChat
 */

// Exit if accessed directly or not during uninstall.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Delete plugin options.
delete_option( 'vzflty_options' );

// Clean up legacy option if it exists.
delete_option( 'floaty_button_options' );
