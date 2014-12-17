<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://makedo.in
 * @since             1.0.0
 * @package           MKDO_Admin
 *
 * @wordpress-plugin
 * Plugin Name:       MKDO Admin
 * Plugin URI:        http://makedo.in
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress dashboard.
 * Version:           1.0.0
 * Author:            MKDO Limited
 * Author URI:        http://makedo.in
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mkdo-admin
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-mkdo-admin-activator.php
 */
function activate_mkdo_admin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mkdo-admin-activator.php';
	MKDO_Admin_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-mkdo-admin-deactivator.php
 */
function deactivate_mkdo_admin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mkdo-admin-deactivator.php';
	MKDO_Admin_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_mkdo_admin' );
register_deactivation_hook( __FILE__, 'deactivate_mkdo_admin' );

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-mkdo-admin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_mkdo_admin() {

	$plugin = new MKDO_Admin();
	$plugin->run();

}
run_mkdo_admin();
