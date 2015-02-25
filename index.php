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
class MKDO_Admin {

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

		$this->instance = $instance;
		$this->version 	= $version;

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

		/**
		 * The classes responsible for defining all actions that occur in the Dashboard.
		 */
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-mkdo-admin-admin.php';
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-mkdo-admin-bar.php';
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-mkdo-admin-footer.php';
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-mkdo-admin-dashboard.php';
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-mkdo-admin-menus.php';
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-mkdo-admin-notices.php';

		/**
		 * The classes responsible for defining all actions that occur in user profiles.
		 */
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-mkdo-admin-profile.php';

		/**
		 * The classes responsible for defining all the custom post types.
		 */
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-mkdo-admin-cpt-posts.php';
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-mkdo-admin-cpt-pages.php';

		/**
		 * The classes responsible for defining all the custom metaboxes.
		 */
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-mkdo-admin-metaboxes.php';

		/**
		 * The classes responsible for defining all the custom metaboxes.
		 */
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-mkdo-admin-columns.php';

		/**
		 * The classes responsible for defining all the taxonomies.
		 */
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-mkdo-admin-taxonomies.php';

		/**
		 * The classes responsible for defining all the statuses.
		 */
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-mkdo-admin-status.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( __FILE__ ) . 'public/class-mkdo-admin-public.php';

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
		 * Customisation
		 *
		 * Custom parameters for the various classes
		 */
		$custom_page_title_args = 	array();
		$custom_post_title_args = 	array(
										// 'name_singular' 	=> 'News',
										// 'name_plural' 		=> 'News' 
									);

		$custom_menu_args 		=	array(
										// 'posts'				=> $custom_post_title_args,
										// 'pages'				=> $custom_page_title_args,
									);

		/** 
		 * Classes
		 *
		 * Load the admin classes used in this Plugin
		 */
		$plugin_admin 			= new MKDO_Admin_Admin						( $this->get_instance(), $this->get_version() );
		$admin_bar_admin		= new MKDO_Admin_bar						( $this->get_instance(), $this->get_version() );
		$footer_admin			= new MKDO_Admin_Footer						( $this->get_instance(), $this->get_version() );
		$dashboard_admin		= new MKDO_Admin_Dashboard					( $this->get_instance(), $this->get_version() );
		$menus_admin			= new MKDO_Admin_Menus						( $this->get_instance(), $this->get_version(), $custom_menu_args 			);
		$notices_admin			= new MKDO_Admin_Notices					( $this->get_instance(), $this->get_version() );
		$profile_admin			= new MKDO_Admin_Profile					( $this->get_instance(), $this->get_version() );
		$cpt_posts				= new MKDO_Admin_CPT_Posts					( $this->get_instance(), $this->get_version(), $custom_post_title_args 	);
		$cpt_pages				= new MKDO_Admin_CPT_Pages					( $this->get_instance(), $this->get_version(), $custom_page_title_args 	);
		$metaboxes_admin		= new MKDO_Admin_Metaboxes					( $this->get_instance(), $this->get_version() );
		$columns_admin			= new MKDO_Admin_Columns					( $this->get_instance(), $this->get_version() );
		$taxonomies				= new MKDO_Admin_Taxonomies					( $this->get_instance(), $this->get_version() );
		$status 				= new MKDO_Admin_Status						( $this->get_instance(), $this->get_version() );

		/** 
		 * Admin Bar 
		 *
		 * Removes the admin bar for all users
		 * Removes the admin bar for non admins
		 * Restricts access to the dashboard for non admins
		 * Remove howdy message
		 * Remove my sites
		 * Remve WP logo
		 * Remove site name
		 * Remove WP SEO menu
		 * Remove Comments
		 * Remove +New
		 * Remove updates
		 * Remove search
		 * Add custom admin logo
		 * Add menu switcher
		 */
		// $this->loader->add_action( 'init', 								$admin_bar_admin, 		'remove_admin_bar' 						);
		// $this->loader->add_action( 'init', 								$admin_bar_admin, 		'remove_admin_bar_for_non_admins' 		);
		// $this->loader->add_action( 'admin_init', 						$admin_bar_admin, 		'restrict_admin_access' 				);
		$this->loader->add_action( 'wp_before_admin_bar_render', 		$admin_bar_admin, 		'remove_howdy' 							);
		$this->loader->add_action( 'wp_before_admin_bar_render', 		$admin_bar_admin, 		'remove_my_sites' 						);
		//$this->loader->add_action( 'wp_before_admin_bar_render', 		$admin_bar_admin, 		'remove_wp_logo' 						);
		$this->loader->add_action( 'wp_before_admin_bar_render', 		$admin_bar_admin, 		'remove_site_name' 						);
		$this->loader->add_action( 'wp_before_admin_bar_render', 		$admin_bar_admin, 		'remove_wp_seo_menu' 					);
		$this->loader->add_action( 'wp_before_admin_bar_render', 		$admin_bar_admin, 		'remove_comments' 						);
		$this->loader->add_action( 'wp_before_admin_bar_render', 		$admin_bar_admin, 		'remove_new_content' 					);
		$this->loader->add_action( 'wp_before_admin_bar_render', 		$admin_bar_admin, 		'remove_updates' 						);
		$this->loader->add_action( 'wp_before_admin_bar_render', 		$admin_bar_admin, 		'remove_search' 						);
		
		// To use a custom logo you must not use 'remove_wp_admin_logo'
		// The CSS in this function will vary from stie to site
		// For best results the logo should not be larger then 20px in height
		//$this->loader->add_action( 'admin_head', 						$admin_bar_admin, 		'custom_admin_logo' 					);

		$this->loader->add_action( 'wp_before_admin_bar_render', 		$admin_bar_admin, 		'add_menu_switcher' 					);

		/** 
		 * Admin Footer
		 *
		 * Removes the admin footer message
		 * Removes the WP version number
		 * Add custom MKDO footer text
		 */
		$this->loader->add_filter( 'admin_footer_text', 				$footer_admin, 			'remove_admin_footer', 					9999	);
		$this->loader->add_filter( 'update_footer', 					$footer_admin, 			'remove_admin_version', 				9999	);
		//$this->loader->add_filter( 'admin_footer_text', 				$footer_admin, 			'add_mkdo_footer_text', 				9999	);

		/** 
		 * Scripts
		 *
		 * Enqueue the styles
		 * Enqueue the scripts
		 */
		$this->loader->add_action( 'admin_enqueue_scripts', 			$plugin_admin, 			'enqueue_styles' 						);
		$this->loader->add_action( 'admin_enqueue_scripts', 			$plugin_admin, 			'enqueue_scripts' 						);

		/**
		 * Dashboard
		 *
		 * Add custom dashboard
		 * Add login redirect to custom dashboard
		 * Add 'content' dashboard block
		 * Add 'Profile' dashboard block
		 * Add 'Comments' dashboard block (from content dashboard)
		 */
		$this->loader->add_action( 'admin_menu', 						$dashboard_admin, 		'add_mkdo_dashboard' 					);
		$this->loader->add_action( 'login_redirect', 					$dashboard_admin, 		'login_redirect', 						9999, 3 );
		$this->loader->add_action( 'mkdo_dashboard_blocks', 			$dashboard_admin, 		'add_content_block_to_mkdo_dashboard'	);
		$this->loader->add_action( 'mkdo_dashboard_blocks', 			$dashboard_admin, 		'add_profile_block_to_mkdo_dashboard'	);
		$this->loader->add_action( 'mkdo_dashboard_blocks', 			$menus_admin, 			'add_comments_to_mkdo_dashboard'		);

		/**
		 * Menu
		 *
		 * Remove admin menus
		 * Remove admin sub menus
		 * Add custom menu
		 * Rename media menu
		 * Rename media page
		 * Add posts to menu
		 * Add pages to menu
		 * Add 'comments' to menu dashbaord
		 */
		$this->loader->add_action( 'admin_menu', 						$menus_admin, 			'remove_admin_menus', 					9999 );
		$this->loader->add_action( 'admin_menu', 						$menus_admin, 			'remove_admin_sub_menus', 				9999 );
		$this->loader->add_action( 'admin_menu', 						$menus_admin, 			'add_mkdo_content_menu', 				5 );
		$this->loader->add_action( 'admin_menu', 						$menus_admin, 			'rename_mkdo_media_menu' 				);
		$this->loader->add_filter( 'gettext', 							$menus_admin, 			'rename_mkdo_media_page', 				10,3);
		$this->loader->add_action( 'admin_menu', 						$menus_admin, 			'add_posts_to_mkdo_content', 			9 );
		$this->loader->add_action( 'admin_menu', 						$menus_admin, 			'add_pages_to_mkdo_content', 			9 );
		$this->loader->add_action( 'mkdo_after_content_blocks', 		$menus_admin, 			'add_comments_to_mkdo_dashboard'		);
		
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
		 * Post Types
		 *
		 * Add the post type to the admin menu
		 */
		if( class_exists( 'MKDO_Admin' ) ) {
			$this->loader->add_filter( 'mkdo_content_menu', $cpt_posts, 'add_content_block' );
			$this->loader->add_filter( 'mkdo_content_menu', $cpt_pages, 'add_content_block' );
		}

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
		$this->loader->add_action( 'init', 							$status, 				'add_archived_status' 					);
		$this->loader->add_action( 'admin_footer-post.php',			$status, 				'make_archived_status_choosable' 		);
		$this->loader->add_filter( 'display_post_states', 			$status, 				'add_archived_status_to_post_list'	 	);
		//$this->loader->add_action( 'admin_enqueue_scripts', 		$status, 				'override_edit_flow_status', 			9999 );
		$this->loader->add_action( 'manage_posts_custom_column', 	$status, 				'override_edit_flow_stauts_column'	 	);
		$this->loader->add_action( 'manage_pages_custom_column', 	$status, 				'override_edit_flow_stauts_column'	 	);

		// Add status to posts
		$this->loader->add_filter( 'mkdo_custom_status_archived_filter', 	$cpt_posts, 	'post_type_filter' 						);
		
		/**
		 * Add actions from other hooks
		 *
		 * Register hooks from other plugins
		 */
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
		
		if( class_exists( 'MKDO_Incidents' ) ) {

			$cpt_incidents 			= new MKDO_Incidents_CPT_incidents				( $this->get_instance(), $this->get_version() );
			
			add_filter( 'mkdo_custom_status_archived_filter', array( $cpt_incidents, 'post_type_filter' ) );
		}
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		// $plugin_public = new MKDO_Admin_Public( $this->get_instance(), $this->get_version() );

		// $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		// $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

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
