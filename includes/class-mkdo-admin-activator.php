<?php

/**
 * Fired during plugin activation
 *
 * @link       http://makedo.in
 * @since      1.0.0
 *
 * @package    MKDO_Admin
 * @subpackage MKDO_Admin/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    MKDO_Admin
 * @subpackage MKDO_Admin/includes
 * @author     MKDO Limited <hello@makedo.in>
 */
class MKDO_Admin_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		// get the current users, user ID
		$mkdo_user_id = get_current_user_id();
	
		// make the user a mkdo super user
		update_usermeta( $mkdo_user_id, 'mkdo_user', 1 );
	
		// set option to initialise the redirect
		add_option( 'mkdo_activation_redirect', TRUE );

	}

}
