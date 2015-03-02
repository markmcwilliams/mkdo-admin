<?php
/**
 * The menus
 *
 * @link       http://makedo.in
 * @since      1.0.0
 *
 * @package    MKDO_Admin
 * @subpackage MKDO_Admin/admin
 */

/**
 * The menus
 *
 * Creates the MKDO menu items
 *
 * @package    MKDO_Admin
 * @subpackage MKDO_Admin/admin
 * @author     Make Do <hello@makedo.in>
 */
class MKDO_Admin_Menus extends MKDO_Menu {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $instance       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $instance, $version ) {
		

		$args 								= 	array(
														'page_title' 			=> 	'Content',
														'menu_title' 			=> 	'Content',
														'capibility' 			=> 	'edit_posts',
														'slug' 					=> 	'mkdo_content_menu',
														'function'				=> 	array( $this, 'mkdo_content_menu'),
														'icon' 					=> 	'dashicons-admin-page',
														'position' 				=> 	'26',
														'add_menus'				=> 	array(
																						array( 
																							'post_type'							=>		'post',
																							'post_name' 						=> 		'Posts',
																							'menu_name' 						=> 		'Posts',
																							'capability' 						=> 		'edit_posts',
																							'function' 							=> 		'edit.php',
																							'admin_add'							=>		FALSE,
																							'mkdo_add'							=> 		FALSE,
																							'remove_original_menu' 				=> 		TRUE,
																							'remove_original_sub_menu' 			=> 		FALSE,
																							'remove_original_sub_menu_parent' 	=> 		'',
																							'admin_remove'						=>		TRUE,
																							'mkdo_remove'						=> 		TRUE,
																							'add_to_dashboard'					=> 		TRUE,
																							'add_to_dashboard_slug'				=> 		'mkdo_content_menu',
																						),
																						array( 
																							'post_type'							=>		'page',
																							'post_name' 						=> 		'Pages',
																							'menu_name' 						=> 		'Pages',
																							'capability' 						=> 		'edit_posts',
																							'function' 							=> 		defined('CMS_TPV_URL') ? 'edit.php?post_type=page&page=cms-tpv-page-page' : 'edit.php?post_type=page',
																							'admin_add'							=>		FALSE,
																							'mkdo_add'							=> 		FALSE,
																							'remove_original_menu' 				=> 		TRUE,
																							'remove_original_sub_menu' 			=> 		FALSE,
																							'remove_original_sub_menu_parent' 	=> 		'',
																							'admin_remove'						=>		TRUE,
																							'mkdo_remove'						=> 		TRUE,
																							'add_to_dashboard'					=> 		TRUE,
																							'add_to_dashboard_slug'				=> 		'mkdo_content_menu',
																						),
																					),
														'remove_menus'			=> 	array(
																						array( 
																							'menu' 			=> 		'edit-comments.php',
																							'admin_remove'	=>		TRUE,
																							'mkdo_remove'	=> 		TRUE
																						),
																						array( 
																							'menu' 			=> 		'index.php',
																							'admin_remove'	=>		TRUE,
																							'mkdo_remove'	=> 		FALSE
																						),
																						array( 
																							'menu' 			=> 		'seperator1',
																							'admin_remove'	=>		TRUE,
																							'mkdo_remove'	=> 		FALSE
																						),
																						array( 
																							'menu' 			=> 		'tools.php',
																							'admin_remove'	=>		TRUE,
																							'mkdo_remove'	=> 		FALSE
																						),
																						array( 
																							'menu' 			=> 		'options-general.php',
																							'admin_remove'	=>		TRUE,
																							'mkdo_remove'	=> 		FALSE
																						),
																						array( 
																							'menu' 			=> 		'plugins.php',
																							'admin_remove'	=>		TRUE,
																							'mkdo_remove'	=> 		FALSE
																						),
																						array( 
																							'menu' 			=> 		'wpseo_dashboard',
																							'admin_remove'	=>		TRUE,
																							'mkdo_remove'	=> 		FALSE
																						),
																					),
														'remove_sub_menus'		=> 	array(
																						array(
																							'parent' 		=> 		'themes.php',
																							'child' 		=> 		'themes.php',
																							'admin_remove'	=>		TRUE,
																							'mkdo_remove'	=> 		FALSE
																						),
																						array(
																							'parent' 		=> 		'themes.php',
																							'child' 		=> 		'customize.php',
																							'admin_remove'	=>		TRUE,
																							'mkdo_remove'	=> 		FALSE
																						),
																						array(
																							'parent' 		=> 		'themes.php',
																							'child' 		=> 		'theme-editor.php',
																							'admin_remove'	=>		TRUE,
																							'mkdo_remove'	=> 		FALSE
																						),
																					),
													);

		parent::__construct( $instance, $version, $args );
	
	}

	/**
	 * Rename media menu
	 */
	public function rename_media_menu() {
	
		global $menu;
		
		$menu[10][0] = 'Assets';
	}

	/**
	 * Rename media menu
	 */
	public function rename_media_page( $translation, $text, $domain )
	{
	    if ( 'default' == $domain and 'Media Library' == $text )
	    {
	        // Once is enough.
	        remove_filter( 'gettext', 'rename_mkdo_media_page' );
	        return 'Assets Library';
	    }
	    return $translation;
	}

	/**
	 * Render menu dashboard 
	 */
	public function mkdo_content_menu() {

		$mkdo_content_menu_path 		= 	dirname(__FILE__) . '/partials/mkdo-content-menu.php';
		$theme_path 					= 	get_stylesheet_directory() . '/mkdo-admin/mkdo-content-menu.php';
		$partials_path					= 	get_stylesheet_directory() . '/partials/mkdo-content-menu.php';

		if( file_exists( $theme_path ) ) {
			$mkdo_content_menu_path = 	$theme_path;
		}
		else if( file_exists( $partials_path ) ) { 
			$mkdo_content_menu_path =  	$partials_path;
		}

		include $mkdo_content_menu_path;
			
	}

	/**
	 * Add 'Comments' to the menu dashboard
	 */
	public function add_comments_to_mkdo_dashboard() {
	// 	if ( current_user_can('moderate_comments') ) {
	// 		//if( MKDO_Helper_User::is_mkdo_user() ) {
	// 			wp_add_dashboard_widget(
	// 				'comments_dash_widget',
	// 				'<span class="mkdo-block-title dashicons-before dashicons-admin-comments"></span> Comments',
	// 				array( $this, 'render_mkdo_dashboard_comments' )
	// 			);
	// 		//}
	// 	}
	}

	/**
	 * Render 'Comments' block in menu dashboard // TODO: Make a template
	 */
	public function render_mkdo_dashboard_comments(){
													
		?>

		<!-- <div>
			
			<p><a class="button button-primary" href="edit-comments.php">Manage</a></p>


			<div class="content-description">

				<p>Manage comments left by site visitors.</p>

			</div>
			
			<p class="footer-button"><a class="button" href="edit-comments.php">Edit / Manage Comments</a></p>
			
		</div> -->
		
		<?php
	}
}
