<?php
/**
 * The dashboard
 *
 * @link       http://makedo.in
 * @since      1.0.0
 *
 * @package    MKDO_Admin
 * @subpackage MKDO_Admin/admin
 */

/**
 * The dashboard
 *
 * Creates the MKDO dashboard items
 *
 * @package    MKDO_Admin
 * @subpackage MKDO_Admin/admin
 * @author     Make Do <hello@makedo.in>
 */
class MKDO_Admin_Dashboard extends MKDO_Menu {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $instance       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $instance, $version ) {
		

		$args 								= 	array(
														'page_title' 			=> 	'Dashboard',
														'menu_title' 			=> 	'Dashboard',
														'capibility' 			=> 	'edit_posts',
														'slug' 					=> 	'mkdo_dashboard',
														'function'				=> 	array( $this, 'mkdo_dashboard'),
														'icon' 					=> 	'dashicons-admin-page',
														'position' 				=> 	'1'
													);

		parent::__construct( $instance, $version, $args );
	
	}

	/**
	 * Add dashboard to menu
	 */
	public function add_menu() {

		if( ! MKDO_Helper_User::is_mkdo_user() ) {
		
			add_menu_page(
				'Dashboard',
				'Dashboard',
				'edit_posts',
				'mkdo_dashboard',
				array( $this, 'mkdo_dashboard'),
				'dashicons-dashboard',
				1
			);
		}

	}

	/**
	 * Render dashboard
	 */
	public function mkdo_dashboard() {
		
		$mkdo_dashboard_path 			= 	dirname(__FILE__) . '/partials/dashboard.php';
		$theme_path 					= 	get_stylesheet_directory() . '/mkdo-admin/dashboard.php';
		$partials_path					= 	get_stylesheet_directory() . '/partials/dashboard.php';

		if( file_exists( $theme_path ) ) {
			$mkdo_dashboard_path 		= 	$theme_path;
		}
		else if( file_exists( $partials_path ) ) { 
			$mkdo_dashboard_path 		=  	$partials_path;
		}

		include $mkdo_dashboard_path;
			
	}

	/**
	 * Redirect users to dashboard
	 */
	public function login_redirect( $redirect_to, $request_redirect_to, $user ) {
	
		if( $user && is_object( $user ) && !is_wp_error( $user ) && is_a( $user, 'WP_User' ) ) {

			if( !get_user_meta( $user->ID, 'mkdo_user', TRUE ) ) {
			
				$redirect_to = apply_filters( 'mkdo_login_redirect', admin_url( 'admin.php?page=mkdo_dashboard' ) );
				
			} else {
				
				$redirect_to = apply_filters( 'mkdo_super_user_login_redirect', admin_url() );
			}
		}

		return $redirect_to;

	}

}