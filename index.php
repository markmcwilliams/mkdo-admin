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
 * MKDO_Admin
 *
 * This is the class that orchestrates the entire plugin
 *
 * @since             	1.0.0
 */
class MKDO_Admin extends MKDO_Class {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      MKDO_Admin_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $mkdo_admin    The string used to uniquely identify this plugin.
	 */
	protected $mkdo_admin;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the Dashboard and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $instance, $version ) {

		parent::__construct( $instance, $version );

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - MKDO_Admin_Loader. Orchestrates the hooks of the plugin.
	 * - MKDO_Admin_i18n. Defines internationalization functionality.
	 * - MKDO_Admin_Admin. Defines all hooks for the dashboard.
	 * - MKDO_Admin_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		// Register Scripts
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-register-scripts-admin.php';
		require_once plugin_dir_path( __FILE__ ) . 'public/class-register-scripts-public.php';

		// Dashboard		
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-admin-bar.php';
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-admin-footer.php';
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-admin-menus.php';

		require_once plugin_dir_path( __FILE__ ) . 'admin/class-mkdo-admin-dashboard.php';
		
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-mkdo-admin-notices.php';

		// Profiles
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-mkdo-admin-profile.php';

		// Custom post types

		// Meta boxes
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-mkdo-admin-metaboxes.php';

		// Taxonomies
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-mkdo-admin-taxonomies.php';

		// Columns
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-mkdo-admin-columns.php';

		// Statuses
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-mkdo-admin-status.php';

		$this->loader = new MKDO_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the MKDO_Admin_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new MKDO_i18n();
		$plugin_i18n->set_domain( $this->get_instance() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the dashboard functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		/** 
		 * Classes
		 *
		 * Load the admin classes used in this Plugin
		 */
		
		$admin_scripts 			= new MKDO_Register_Scripts_Admin			( $this->get_instance(), $this->get_version() );
		$admin_bar				= new MKDO_Admin_bar						( $this->get_instance(), $this->get_version() );
		$admin_footer			= new MKDO_Admin_Footer						( $this->get_instance(), $this->get_version() );
		$admin_menus			= new MKDO_Admin_Menus						( $this->get_instance(), $this->get_version() );
		
		$dashboard_admin		= new MKDO_Admin_Dashboard					( $this->get_instance(), $this->get_version() );
		$notices_admin			= new MKDO_Admin_Notices					( $this->get_instance(), $this->get_version() );
		$profile_admin			= new MKDO_Admin_Profile					( $this->get_instance(), $this->get_version() );
		$metaboxes_admin		= new MKDO_Admin_Metaboxes					( $this->get_instance(), $this->get_version() );
		$columns_admin			= new MKDO_Admin_Columns					( $this->get_instance(), $this->get_version() );
		$taxonomies				= new MKDO_Admin_Taxonomies					( $this->get_instance(), $this->get_version() );
		$status 				= new MKDO_Admin_Status						( $this->get_instance(), $this->get_version() );

		/** 
		 * Scripts
		 */
		
		// Enqueue the styles
		if( get_option( 'mkdo_admin_enqueue_styles', TRUE ) === TRUE ) { 
			$this->loader->add_action( 'admin_enqueue_scripts', $admin_scripts, 'enqueue_styles' );
		}

		// Enqueue the scripts
		if( get_option( 'mkdo_admin_enqueue_scripts', TRUE ) === TRUE ) { 
			$this->loader->add_action( 'admin_enqueue_scripts', $admin_scripts, 'enqueue_scripts' );
		}

		/** 
		 * Admin Bar
		 */
		
		// Removes the admin bar for all users
		if( get_option( 'mkdo_admin_remove_admin_bar', FALSE ) === TRUE ) { 
			$this->loader->add_action( 'init', $admin_bar, 'remove_admin_bar' );
		}

		// Removes the admin bar for non admins
		if( get_option( 'mkdo_admin_remove_admin_bar_non_admins', FALSE ) === TRUE ) { 
			$this->loader->add_action( 'init', $admin_bar, 'remove_admin_bar_for_non_admins' );
		}

		// Restricts access to the dashboard for non admins
		if( get_option( 'mkdo_admin_restrict_admin_access', FALSE ) === TRUE ) { 
			$this->loader->add_action( 'admin_init', $admin_bar, 'restrict_admin_access' );
		}

		// Remove howdy message
		if( get_option( 'mkdo_admin_remove_howdy', TRUE ) === TRUE ) { 
			$this->loader->add_action( 'wp_before_admin_bar_render', $admin_bar, 'remove_howdy' );
		}

		// Remove my sites
		if( get_option( 'mkdo_admin_remove_my_sites', TRUE ) === TRUE ) { 
			$this->loader->add_action( 'wp_before_admin_bar_render', $admin_bar, 'remove_my_sites' );
		}

		// Remove my sites
		if( get_option( 'mkdo_admin_remove_wp_logo', FALSE ) === TRUE ) { 
			$this->loader->add_action( 'wp_before_admin_bar_render', $admin_bar, 'remove_wp_logo' );
		}

		// Remove site name
		if( get_option( 'mkdo_admin_remove_site_name', TRUE ) === TRUE ) { 
			$this->loader->add_action( 'wp_before_admin_bar_render', $admin_bar, 'remove_site_name' );
		}

		// Remove WP SEO menu
		if( get_option( 'mkdo_admin_remove_wp_seo_menu', TRUE ) === TRUE ) { 
			$this->loader->add_action( 'wp_before_admin_bar_render', $admin_bar, 'remove_wp_seo_menu' );
		}

		// Remove Comments
		if( get_option( 'mkdo_admin_remove_comments', TRUE ) === TRUE ) { 
			$this->loader->add_action( 'wp_before_admin_bar_render', $admin_bar, 'remove_comments' );
		}

		// Remove +New
		if( get_option( 'mkdo_admin_remove_new_content', TRUE ) === TRUE ) { 
			$this->loader->add_action( 'wp_before_admin_bar_render', $admin_bar, 'remove_new_content' );
		}

		// Remove updates
		// - Does not remove updates for Super Users
		if( get_option( 'mkdo_admin_remove_updates', TRUE ) === TRUE ) { 
			$this->loader->add_action( 'wp_before_admin_bar_render', $admin_bar, 'remove_updates' );
		}

		// Remove search
		if( get_option( 'mkdo_admin_remove_search', TRUE ) === TRUE ) { 
			$this->loader->add_action( 'wp_before_admin_bar_render', $admin_bar, 'remove_search' );
		}
		
		// Add custom admin logo
		// 
		// - To use a custom logo you must not use 'remove_wp_admin_logo'
		// - The CSS in this function will vary from stie to site
		// - For best results the logo should not be larger then 20px in height
		// - To make this work by default place an image 20x20px in your theme /img/ 
		//   folder named 'admin-logo.php'
		// - For more complex customisation copy the template in the /admin/partials/ folder in this plugin 
		//   to your theme in one of these locations. Here you can alter the image path and CSS as required:
		//   - /mkdo-admin/custom-admin-logo.php
		//   - /partials/custom-admin-logo.php
		if( get_option( 'mkdo_admin_custom_admin_logo', FALSE ) === TRUE ) { 
			$this->loader->add_action( 'admin_head', $admin_bar, 'custom_admin_logo' );
		}

		// Add menu switcher
		if( get_option( 'mkdo_admin_add_menu_switcher', TRUE ) === TRUE ) { 
			$this->loader->add_action( 'wp_before_admin_bar_render', $admin_bar, 'add_menu_switcher' );
		}

		/** 
		 * Admin Footer
		 */
		
		// Removes the admin footer message
		if( get_option( 'mkdo_admin_remove_admin_footer', TRUE ) === TRUE ) { 
			$this->loader->add_action( 'admin_footer_text', $admin_footer, 'remove_admin_footer', 9999 );
		}

		// Removes the WP version number
		if( get_option( 'mkdo_admin_remove_admin_version', TRUE ) === TRUE ) { 
			$this->loader->add_action( 'update_footer', $admin_footer, 'remove_admin_version', 9999 );
		}

		// Add custom footer text
		// - Use the filter 'mkdo_footer_text' to add your own text
		if( get_option( 'mkdo_admin_add_footer_text', TRUE ) === TRUE ) { 
			$this->loader->add_action( 'admin_footer_text', $admin_footer, 'add_footer_text', 9999 );
		}

		/**
		 * Menus
		 */
		
		// Add custom menu
		// 
		// - Use the filter ''mkdo_content_menu_add_menu_items' to add menu items
		// - Each item in the filter is an array in the following format
		// 		$mkdo_content_menus[] 	= 	array( 
		// 										'post_type'							=>		'page',
		//										'post_name' 						=> 		'Pages',
		//										'menu_name' 						=> 		'Pages',
		//										'capability' 						=> 		'edit_posts',
		//										'function' 							=> 		defined('CMS_TPV_URL') ? 'edit.php?post_type=page&page=cms-tpv-page-page' : 'edit.php?post_type=page',
		//										'admin_add'							=>		TRUE,
		//										'mkdo_add'							=> 		TRUE,
		//										'remove_original_menu' 				=> 		TRUE,
		//										'remove_original_sub_menu' 			=> 		FALSE,
		//										'remove_original_sub_menu_parent' 	=> 		'',
		//										'add_to_dashboard'					=> 		TRUE,
		//										'add_to_dashboard_slug'				=> 		'mkdo_content_menu',
		//									);
		//	 - 'post_type' is the post_type you are adding
		//	 - 'post_name' is the name of the page (if you are renaming the menu also change this)
		//	 - 'menu_name' is the name of the menu (if you are renaming the mneuy also change this)
		//	 - 'capability' is the access required to view the menu item
		//	 - 'function' is the function or the URL that the menu item links to
		//	 - 'admin_add' will add the item if the user is an administrator
		//	 - 'mkdo_add' will add the item if the user is an MKDO admin
		//	 - 'remove_original_menu' will remove the original menu item before adding it to the menu
		//	 - 'remove_original_sub_menu' will remove the original sub menu item before adding it to the menu
		//	 - 'remove_original_sub_menu_parent' the parent of the sub menu item that needs removing
		//	 - 'add_to_dashboard' will add the menu item to a dashboard
		//	 - 'add_to_dashboard_slug' the slug of the dashboard to add to
		//	 
		// - For more complex customisation of the admin page created by the menu copy the template in the 
		//   /admin/partials/ folder in this plugin to your theme in one of these locations. Here you 
		//   can alter the image path and CSS as required:
		//   - /mkdo-admin/mkdo-content-menu.php
		//   - /partials/mkdo-content-menu.php
		if( get_option( 'mkdo_admin_add_mkdo_content_menu', TRUE ) === TRUE ) { 
			$this->loader->add_action( 'admin_menu', $admin_menus, 'add_mkdo_content_menu', 9999 );
			$this->loader->add_action( 'admin_menu', $admin_menus, 'add_mkdo_content_menu_items', 9999 );
		}
		
		// Remove admin menus
		// 
		// - Use the filter 'mkdo_content_menu_remove_admin_menus' to add menu items to be removed
		// - Each item in the filter is an array in the following format:
		// 		$admin_menu[] 	= 	array( 
		// 								'menu' 			=> 		'edit.php',
		// 								'admin_remove'	=>		TRUE,
		// 								'mkdo_remove'	=> 		TRUE
		// 							);
		// 	  - 'menu' is the menu to remove
		// 	  - 'admin_remove' will remove the item for admins
		// 	  - 'mkdo_remove' will remove the item for super users
		if( get_option( 'mkdo_admin_remove_admin_menus', TRUE ) === TRUE ) { 
			$this->loader->add_action( 'admin_menu', $admin_menus, 'remove_admin_menus', 9999 );
		}

		// Remove admin sub menus
		// 
		// - Use the filter 'mkdo_content_menu_remove_admin_sub_menus' to add sub menu items to be removed
		// - Each item in the filter is an array in the following format:
		// 		$admin_sub_menu[] 	= 	array(
		// 								'parent' 		=> 		'themes.php',
		//								'child' 		=> 		'theme-editor.php',
		//								'admin_remove'	=>		TRUE,
		//								'mkdo_remove'	=> 		FALSE
		//							);
		// 	  - 'parent' is the parent of the sub menu to remove						
		// 	  - 'child' is the sub menu to remove
		// 	  - 'admin_remove' will remove the item for admins
		// 	  - 'mkdo_remove' will remove the item for super users
		if( get_option( 'mkdo_admin_remove_admin_sub_menus', TRUE ) === TRUE ) { 
			$this->loader->add_action( 'admin_menu', $admin_menus, 'remove_admin_sub_menus', 9999 );
		}

		// Rename Media Library to Assets Library
		if( get_option( 'mkdo_admin_rename_media_library', FALSE ) === TRUE ) { 
			$this->loader->add_action( 'admin_menu', 	$admin_menus,	'rename_mkdo_media_menu' 	);
			$this->loader->add_filter( 'gettext', 		$admin_menus,	'rename_mkdo_media_page', 	10,	3 );
		}
		
		/**
		 * Dashboards
		 *
		 * Add custom dashboard
		 * Add login redirect to custom dashboard
		 * Add 'content' dashboard block
		 * Add 'Profile' dashboard block
		 * Add 'Comments' dashboard block (from content dashboard)
		 * 
		 */
		// $this->loader->add_action( 'admin_menu', 						$dashboard_admin, 		'add_mkdo_dashboard' 					);
		// $this->loader->add_action( 'login_redirect', 					$dashboard_admin, 		'login_redirect', 						9999, 3 );
		// $this->loader->add_action( 'mkdo_dashboard_blocks', 			$dashboard_admin, 		'add_content_block_to_mkdo_dashboard'	);
		// $this->loader->add_action( 'mkdo_dashboard_blocks', 			$dashboard_admin, 		'add_profile_block_to_mkdo_dashboard'	);
		// $this->loader->add_action( 'mkdo_dashboard_blocks', 			$admin_menus, 			'add_comments_to_mkdo_dashboard'		);
		// $this->loader->add_action( 'mkdo_after_content_blocks', 		$admin_menus, 			'add_comments_to_mkdo_dashboard'		);


		/**
		 * Admin notices
		 *
		 * Show taxonomies of posts
		 */
		$this->loader->add_action( 'all_admin_notices', 				$notices_admin, 		'admin_notices' 						);

		/**
		 * Profile
		 *
		 * Add MKDO user checkbox
		 * Save user checkbox data
		 * Save user checkbox data
		 * Remove colour scheme picker
		 * Force colour scheme
		 * Change user permissions
		 */
		$this->loader->add_action( 'personal_options', 					$profile_admin, 		'add_mkdo_user_profile_field' 			);
		$this->loader->add_action( 'personal_options_update', 			$profile_admin, 		'save_mkdo_user_profile_field_data' 	);
		$this->loader->add_action( 'edit_user_profile_update', 			$profile_admin, 		'save_mkdo_user_profile_field_data' 	);
		$this->loader->add_action( 'admin_init', 						$profile_admin, 		'remove_admin_color_schemes' 			);
		$this->loader->add_action( 'get_user_option_admin_color', 		$profile_admin, 		'force_user_color_scheme' 				);
		$this->loader->add_action( 'user_has_cap', 						$profile_admin, 		'edit_user_capabilities' 				);

		

		/** 
		 * Metaboxes
		 *
		 * Remove the metaboxes
		 */
		$this->loader->add_action( 'do_meta_boxes', 		$metaboxes_admin, 		'remove_metaboxes' 						);
		$this->loader->add_action( 'default_hidden_meta_boxes', $metaboxes_admin, 	'hide_metaboxes', 						10, 2 );

		/** 
		 * Taxonomies
		 *
		 * Add category to page
		 * Add tag to page
		 */
		$this->loader->add_action( 'admin_init', 			$taxonomies, 			'add_category_to_page' 					);
		$this->loader->add_action( 'admin_init', 			$taxonomies, 			'add_tags_to_page' 						);


		/** 
		 * Columns
		 *
		 * Remove the columns
		 */
		$this->loader->add_filter( 'init', 					$columns_admin, 		'remove_custom_post_columns', 			9998, 1 );
		$this->loader->add_action( 'parse_request', 		$columns_admin, 		'hide_columns'							);
		$this->loader->add_filter( 'admin_init', 			$columns_admin, 		'remove_column_filters', 				9999 );


		/** 
		 * Statuses
		 *
		 * Register the status
		 * Make the status choosable
		 * Dispaly the post status in the post list
		 * Override Edit Flow post list status disable
		 * Add status to Edit Flow column
		 */
		// $this->loader->add_action( 'init', 							$status, 				'add_archived_status' 					);
		// $this->loader->add_action( 'admin_footer-post.php',			$status, 				'make_archived_status_choosable' 		);
		// $this->loader->add_filter( 'display_post_states', 			$status, 				'add_archived_status_to_post_list'	 	);
		// //$this->loader->add_action( 'admin_enqueue_scripts', 		$status, 				'override_edit_flow_status', 			9999 );
		// $this->loader->add_action( 'manage_posts_custom_column', 	$status, 				'override_edit_flow_stauts_column'	 	);
		// $this->loader->add_action( 'manage_pages_custom_column', 	$status, 				'override_edit_flow_stauts_column'	 	);

		// // Add status to posts
		// $this->loader->add_filter( 'mkdo_custom_status_archived_filter', 	$cpt_posts, 	'post_type_filter' 						);
		

		// Register hooks from other plugins
		$this->loader->add_action( 'plugins_loaded', 		$this, 					'define_admin_dependancy_hooks' 		);
	}

	/**
	 * Register all of the hooks related to the dashboard functionality
	 * of the plugin that are dependant on other plugins
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function define_admin_dependancy_hooks() {
		
		// if( class_exists( 'MKDO_Incidents' ) ) {

		// 	$cpt_incidents 			= new MKDO_Incidents_CPT_incidents				( $this->get_instance(), $this->get_version() );
			
		// 	add_filter( 'mkdo_custom_status_archived_filter', array( $cpt_incidents, 'post_type_filter' ) );
		// }
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$public_scripts = new MKDO_Register_Scripts_Public( $this->get_instance(), $this->get_version() );

		// Enqueue the styles
		if( get_option( 'mkdo_admin_enqueue_styles_public', FALSE ) === TRUE ) { 
			$this->loader->add_action( 'wp_enqueue_scripts', $public_scripts, 'enqueue_styles' );
		}

		// Enqueue the scripts
		if( get_option( 'mkdo_admin_enqueue_scripts_public', FALSE ) === TRUE ) { 
			$this->loader->add_action( 'wp_enqueue_scripts', $public_scripts, 'enqueue_scripts' );
		}

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_instance() {
		return $this->instance;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    MKDO_Admin_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Fired during plugin activation.
	 *
	 * This class defines all code necessary to run during the plugin's activation.
	 *
	 * @since      1.0.0
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

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-mkdo-admin-activator.php
 */
register_activation_hook( __FILE__, 'activate_mkdo_admin' );
function activate_mkdo_admin() {
	MKDO_Admin::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-mkdo-admin-deactivator.php
 */
register_deactivation_hook( __FILE__, 'deactivate_mkdo_admin' );
function deactivate_mkdo_admin() {
	MKDO_Admin::deactivate();
}

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

	$plugin = new MKDO_Admin( 'mkdo-admin', '1.0.0' );
	$plugin->run();

}
run_mkdo_admin();
