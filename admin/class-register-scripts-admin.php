<?php
/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://makedo.in
 * @since      1.0.0
 *
 * @package    MKDO_Admin
 * @subpackage MKDO_Admin/admin
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    MKDO_Admin
 * @subpackage MKDO_Admin/admin
 * @author     Make Do <hello@makedo.in>
 */
class MKDO_Register_Scripts_Admin extends MKDO_Class {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $instance       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $instance, $version ) {
		parent::__construct( $instance, $version );
	}

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->instance, plugin_dir_url( __FILE__ ) . 'css/mkdo-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		// Scripts required for 'dashboard'
		wp_enqueue_script( 'dashboard' );

		if ( current_user_can( 'edit_theme_options' ) ) {
			wp_enqueue_script( 'customize-loader' );
		}
		if ( current_user_can( 'install_plugins' ) ) {
			wp_enqueue_script( 'plugin-install' );
		}
		if ( current_user_can( 'upload_files' ) ) {
			wp_enqueue_script( 'media-upload' );
		}
		
		add_thickbox();

		if ( wp_is_mobile() ) {
			wp_enqueue_script( 'jquery-touch-punch' );
		}

		// Scripts unique to the plugin
		wp_enqueue_script( $this->instance, plugin_dir_url( __FILE__ ) . 'js/mkdo-admin.js', array( 'jquery' ), $this->version, TRUE );

	}
}