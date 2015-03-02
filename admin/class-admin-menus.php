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
														'function'				=> 	array( $this, 'mkdo_content_output'),
														'icon' 					=> 	'dashicons-admin-page',
														'position' 				=> 	'26',
														'add_menus'				=> 	array(
																						array( 
																							'post_name' 						=> 		'Posts',
																							'menu_name' 						=> 		'Posts',
																							'capability' 						=> 		'edit_posts',
																							'function' 							=> 		'edit.php',
																							'admin_add'							=>		TRUE,
																							'mkdo_add'							=> 		TRUE,
																							'remove_original_menu' 				=> 		TRUE,
																							'remove_original_sub_menu' 			=> 		FALSE,
																							'remove_original_sub_menu_parent' 	=> 		'',
																						),
																						array( 
																							'post_name' 						=> 		'Pages',
																							'menu_name' 						=> 		'Pages',
																							'capability' 						=> 		'edit_posts',
																							'function' 							=> 		defined('CMS_TPV_URL') ? 'edit.php?post_type=page&page=cms-tpv-page-page' : 'edit.php?post_type=page',
																							'admin_add'							=>		TRUE,
																							'mkdo_add'							=> 		TRUE,
																							'remove_original_menu' 				=> 		TRUE,
																							'remove_original_sub_menu' 			=> 		FALSE,
																							'remove_original_sub_menu_parent' 	=> 		'',
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
	 * // TODO: Make this a template
	 * // TODO: Factor out the creation of dashboard widgets into own method
	 */
	public function mkdo_content_output() {

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

		$title = __('Content');
		$parent_file = 'admin.php';

		?>
		
		<div class="wrap">
			
			<h2>Site Content</h2>
			<p>Below are your sites content types. They are grouped here for ease of access to allow you to quickly get to content types either to add new content or the edit existing content.</p>
			
				<?php
					
					do_action( 'mkdo_before_content_screen_output', MKDO_Helper_Screen::get_screen_base() );
					
					$mkdo_content_blocks = apply_filters(
						'mkdo_content_menu',
						array()
					);
					
					if( ! empty( $mkdo_content_blocks ) ) {

						do_action( 'mkdo_before_content_blocks' );

						$counter = 1;
				
						foreach( $mkdo_content_blocks as $block ) {

							$function_name = 'dynamic_dash_widget_' . $counter;
							$$function_name = function() use ($block){
								
								$post_new = admin_url( 'post-new.php?post_type=' . $block[ 'post_type' ] );
								$post_listing = admin_url( 'edit.php?post_type=' . $block[ 'post_type' ] );

								if ( class_exists('sc_bulk_page_creator') ) { 
									if( $post_listing = 'edit.php?post_type=page' ) {
										$post_listing = 'edit.php?post_type=page&page=cms-tpv-page-page';
									}
								}
								
								$css_block_class = $block[ 'css_class' ];
								
								if( ! empty( $css_block_class ) ) {
									$css_block_class = ' ' . $block[ 'css_class' ];
								} else {
									$css_block_class = '';
								}
																			
								?>

								
								<div class="<?php echo esc_attr( $css_class ); ?>">
									
									<p><a class="button button-primary" href="<?php echo esc_url( $post_new ); ?>">Add New</a></p>

									<div class="content-description">
					
										<?php echo $block[ 'desc' ]; ?>
									
									</div>
									
									<?php
										
										if( $block[ 'show_tax' ] == true ) {
											
											$taxonomies = get_object_taxonomies( $block[ 'post_type' ], 'objects' );

											unset( $taxonomies[ 'post_format' ] );
											unset( $taxonomies[ 'post_status' ] );
											unset( $taxonomies[ 'ef_editorial_meta' ] );
											unset( $taxonomies[ 'following_users' ] );
											unset( $taxonomies[ 'ef_usergroup' ] );
											
											if( ! empty( $taxonomies ) ) {
												
												?>
												<h4 class="tax-title">Associated Taxonomies</h4>
												
												<ul class="tax-list">
												<?php
													
													foreach( $taxonomies as $tax ) {
														?>
														<li class="<?php echo esc_attr( sanitize_title( $tax->name ) ); ?>">
															<span class="dashicons-before dashicons-category"></span>
															<a href="<?php echo admin_url( 'edit-tags.php?taxonomy=' . $tax->name ); ?>"><?php echo esc_html( $tax->labels->name ); ?></a>
														</li>
														<?php
													}
													
												?>
												</ul>
													
												<?php
											}
											
										}	
										
									?>
									
									<p class="footer-button"><a class="button" href="<?php echo esc_url( $post_listing ); ?>"><?php echo esc_html( $block[ 'button_label' ] ); ?></a></p>
									
								</div>
								
								<?php
							};

							wp_add_dashboard_widget(
								'dynamic_dash_widget_' . $counter,
								'<span class="mkdo-block-title dashicons-before ' . esc_attr( $block[ 'dashicon' ] ) . '"></span> ' . esc_html( $block[ 'title' ] ),
								$$function_name 
							);

							$counter++;
						}
						?>
						
						<div class="clear clearfix"></div>
					<?php

					do_action( 'mkdo_after_content_blocks' );			
					
				} 
				
					
				?>
			
				<div id="dashboard-widgets-wrap">
					<?php wp_dashboard(); ?>
				</div>
				<?php do_action( 'mkdo_after_content_screen_output', MKDO_Helper_Screen::get_screen_base() );?>
				<div class="clearfix clear"></div>
			</div>
			<div class="clearfix clear"></div>
		
		<?php
			
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
