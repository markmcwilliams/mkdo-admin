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
 * Load dependancies
 */
if( ! class_exists( 'MKDO_Class' ) )			require_once plugin_dir_path( __FILE__ ) . '../vendor/mkdo/mkdo-objects/admin/class-mkdo-class.php';
if( ! class_exists( 'MKDO_Helper_User' ) )		require_once plugin_dir_path( __FILE__ ) . '../vendor/mkdo/mkdo-objects/includes/class-mkdo-helper-user.php';
if( ! class_exists( 'MKDO_Helper_Screen' ) )	require_once plugin_dir_path( __FILE__ ) . '../vendor/mkdo/mkdo-objects/includes/class-mkdo-helper-screen.php';

/**
 * The dashboard
 *
 * Creates the MKDO dashboard items
 *
 * @package    MKDO_Admin
 * @subpackage MKDO_Admin/admin
 * @author     Make Do <hello@makedo.in>
 */
class MKDO_Admin_Dashboard extends MKDO_Class {

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
	 * Add dashboard to menu
	 */
	public function add_mkdo_dashboard() {

		if( ! MKDO_Helper_User::is_mkdo_user() ) {
		
			add_menu_page(
				'Dashboard',
				'Dashboard',
				'edit_posts',
				'mkdo_dashboard',
				array( $this, 'render_mkdo_dashboard_content'),
				'dashicons-dashboard',
				1
			);
		}

	}

	/**
	 * Render dashboard // TODO: Make this a template
	 */
	public function render_mkdo_dashboard_content() {
		
		/** Load WordPress dashboard API */
		require_once(ABSPATH . 'wp-admin/includes/dashboard.php');

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

		$title = __('Dashboard');
		$parent_file = 'admin.php';

		?>
		
		<div class="wrap">
			
			<h2>Welcome</h2>
			<p>Welcome to the Dashboard. Below are a few things to help you get around. </p>
			
				<?php
					
					do_action( 'mkdo_before_dashboard_screen_output', MKDO_Helper_Screen::get_screen_base() );
					
					do_action( 'mkdo_dashboard_blocks' );	
				?>
			
				<div id="dashboard-widgets-wrap">
					<?php wp_dashboard(); ?>
				</div>
				<?php do_action( 'mkdo_after_dashboard_screen_output', MKDO_Helper_Screen::get_screen_base() ); ?>
				<div class="clearfix clear"></div>
			</div>
			<div class="clearfix clear"></div>
		
		<?php
			
	}

	/**
	 * Redirect users to dashboard
	 */
	public function login_redirect( $redirect_to, $request_redirect_to, $user ) {
	
		if( !MKDO_Helper_User::is_mkdo_user( $user->ID ) ) {
			
			return apply_filters( 'mkdo_login_redirect', admin_url( 'admin.php?page=mkdo_dashboard' ) );
			
		} else {
			
			return apply_filters( 'mkdo_super_user_login_redirect', admin_url() );
		}
	}

	/**
	 * Add 'Content' block to dashbaord
	 */
	public function add_content_block_to_mkdo_dashboard() {

		wp_add_dashboard_widget(
				'content_dash_widget',
				'<span class="mkdo-block-title dashicons-before dashicons-admin-page"></span> Content',
				array( $this, 'render_mkdo_dashboard_content_block' )
		);

	}

	/**
	 * Render 'Content' block // TODO: Make this a template
	 */
	public function render_mkdo_dashboard_content_block(){
													
		?>

		
		<div>
			
			<p><a class="button button-primary" href="admin.php?page=mkdo_content_menu">View</a></p>


			<div class="content-description">

				<p>A website is only as good as the content it holds. Add your pages, posts items and documents here. You can even add images & media to brighten up your pages.</p>

			</div>
			
			<p class="footer-button"><a class="button" href="admin.php?page=mkdo_content_menu">Add / Edit Content</a></p>
			
		</div>
		
		<?php
	}

	/**
	 * Add 'Profile' block to dashbaord
	 */
	public function add_profile_block_to_mkdo_dashboard() {

		wp_add_dashboard_widget(
				'profile_dash_widget',
				'<span class="mkdo-block-title dashicons-before dashicons-admin-users"></span> Profile',
				array( $this, 'render_mkdo_dashboard_profile_block' )
		);

	}

	/**
	 * Render 'Profile' block // TODO: Make this a template
	 */
	public function render_mkdo_dashboard_profile_block(){
													
		?>

		
		<div>
			
			<p><a class="button button-primary" href="profile.php">View / Edit</a></p>


			<div class="content-description">

				<p>You can mange your profile through the dashboard. This includes changing your email address, or password.</p>

			</div>
			
			<p class="footer-button"><a class="button" href="profile.php">View / Edit Profile</a></p>
			
		</div>
		
		<?php
	}

}