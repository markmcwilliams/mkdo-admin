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
class MKDO_Admin_MU_Menus extends MKDO_Class {

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
	 * Add admin menus
	 */
	public function add_admin_menus() {

		// If the user is a super admin (network manager)
		if( is_super_admin() ) {

			if( !MKDO_Helper_User::is_mkdo_user() ) 
			{
				add_menu_page( 
					'Global Settings', 
					'Global Settings', 
					'manage_network',
					'network/index.php',
					'',
					'dashicons-admin-site',
					9999
				);
			}

			add_menu_page( 
				'Sites', 
				'Sites', 
				'manage_network', 
				'network/sites.php',
				'',
				'dashicons-networking',
				3
			);

		}
		// If the user is not a super admin (network manager)
		else {

			// If the user belogs to more than one blog (site)
			$user_id 	= get_current_user_id();
			$user_blogs = get_blogs_of_user( $user_id );

			if ( count( $user_blogs ) > 1 || ( !user_can( $user_id, 'edit_posts' ) && count( $user_blogs ) > 0 ) ) {

				add_menu_page( 
					'My Sites', 
					'My Sites', 
					'read', 
					'my-sites.php',
					'',
					'dashicons-networking',
					2
				);

			}
		}
	}

	/**
	 * Add admin sub menus
	 */
	public function add_admin_sub_menus() {

		if( is_super_admin() ) {

			add_submenu_page(
				'options-general.php',
				'Global Settings',
				'Global Settings',
				'manage_network',
				'network/index.php',
				''
			);

		}
	}

	/**
	 * Add network menus
	 */
	public function add_network_admin_menus() {

		if( is_super_admin() ) {
			
			$user_id 		= get_current_user_id();
			$user_role 		= get_user_meta( $user_id , 'cpd_role', TRUE );
			$menu_slug 		= 'mkdo_dashboard';

			add_menu_page( 
				'Dashboard', 
				'Dashboard', 
				'manage_network',
				'../admin.php?page=' . $menu_slug,
				'',
				'dashicons-dashboard',
				1
			);
		}
	}

	/**
	 * Add network sub menus
	 */
	public function add_network_admin_sub_menus() {

		if( is_super_admin() ) {
			
			add_submenu_page(
				'index.php',
				'Sites',
				'Sites',
				'manage_network',
				'sites.php',
				''
			);

			add_submenu_page(
				'index.php',
				'Users',
				'Users',
				'manage_network',
				'users.php',
				''
			);

			add_submenu_page(
				'index.php',
				'Themes',
				'Themes',
				'manage_network',
				'themes.php',
				''
			);

			add_submenu_page(
				'index.php',
				'Plugins',
				'Plugins',
				'manage_network',
				'plugins.php',
				''
			);

			add_submenu_page(
				'index.php',
				'Settings',
				'Settings',
				'manage_network',
				'settings.php',
				''
			);

			add_submenu_page(
				'index.php',
				'Updates',
				'Updates',
				'manage_network',
				'update-core.php',
				''
			);
		}
	}

	/**
	 * Rename / reorder network menus
	 */
	public function rename_network_admin_menus() {

		if( is_super_admin() ) {

			global $menu;

			// Rename menu items
			foreach( $menu as $key=>&$menu_item ) {
				if( $menu_item[0] == 'Dashboard' || $menu_item[0] == 'Global Settings' ) {

					$menu_item[0] 	= 'Global Settings';
					$menu_item[6] 	= 'dashicons-admin-site';
					$network		= $menu[$key];
					unset( $menu[$key] );
					$menu[6] 	= $network; 
				}
			}
		}
	}

	/**
	 * Rename network sub menus
	 */
	public function rename_network_admin_sub_menus() {

		if( is_super_admin() ) {

			global $submenu;

			// Rename submenu items
			foreach( $submenu as $key=>&$menu_item ) {
				if( $key == 'sites.php') {

					foreach( $menu_item as &$submenu_item ) {
						if( $submenu_item[0] == 'All Sites' ) {
							$submenu_item[0] = 'All Sites';
							break;
						}
					}
					break;
				}
			}
		}
	}

	/**
	 * Remove sub menus
	 */
	public function remove_admin_sub_menus() {
		
		$user_id 	= get_current_user_id();
		$user_type 	= get_user_meta( $user_id, 'cpd_role', true );

		if( $user_type == 'participant' )
		{
			remove_submenu_page( 'users.php', 'users.php' );
			remove_submenu_page( 'users.php', 'user-new.php' );
			remove_submenu_page( 'tools.php', 'ms-delete-site.php' );
			remove_submenu_page( 'options-general.php', 'options-discussion.php' );
		}
	}

	/**
	 * Remove network menus menus
	 */
	public function remove_network_admin_menus() {
		
		if( is_super_admin() ) {
			remove_menu_page( 'sites.php' );
			remove_menu_page( 'users.php' );
			remove_menu_page( 'themes.php' );
			remove_menu_page( 'plugins.php' );
			remove_menu_page( 'settings.php' );
			remove_menu_page( 'update-core.php' );
		}
	}

	/** 
	 * Fix the menu hierarchy
	 */
	public function correct_sub_menu_hierarchy() {

		global $submenu;
		$screen = get_current_screen();
		//print_r($submenu);
		if( strpos( $screen->base, '-network' ) ) {
	
			foreach( $submenu as $path=>&$submenu_item ) {
				if ( 
						$path == 'sites.php' 		||
						$path == 'users.php' 		|| 
						$path == 'themes.php' 		|| 
						$path == 'plugins.php' 		|| 
						$path == 'settings.php' 	|| 
						$path == 'update-core.php'
					) {
					foreach( $submenu_item as $key=>&$smenu ) {
						$submenu_item[$key][2] = 'index.php';
					}
				}
			}
		}
	}

	/**
	 * Filter the MU dashboad actions
	 * 
	 * @param  string 	$actions 	The actions to be filtered
	 * @return string 	$actions 	The actions to be filtered
	 */
	public function filter_dashboard_actions( $actions ) {

		$user_id 		= get_current_user_id();
		$user_role 		= get_user_meta( $user_id , 'cpd_role', TRUE );
		$menu_slug 		= 'mkdo_dashboard';

		$actions = str_replace( 'wp-admin/\'', 'wp-admin/admin.php?page=' . $menu_slug . '\'', $actions );
		return $actions;
	}

}
