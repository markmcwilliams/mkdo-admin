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
class MKDO_Admin_Menus extends MKDO_Class {

	/**
	 * The arguments for the CPT
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $args    The arguments for the CPT.
	 */
	protected $args;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $instance       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $instance, $version, $args = array() ) {
		parent::__construct( $instance, $version );

		add_action( 'parent_file', 	array( $this, 'correct_menu_hierarchy'), 9999 );
		add_action( 'admin_head', 	array( $this, 'correct_sub_menu_hierarchy') );
		
		$this->args = $args;
	}

	/**
	 * Remove admin menus
	 */
	public function remove_admin_menus() {

		$menus = array();

		$menus[] = 'edit.php';
		$menus[] = 'edit.php?post_type=page';
		$menus[] = 'edit-comments.php';
		//$menus[] = 'upload.php';

		if( ! MKDO_Helper_User::is_mkdo_user() ) {

			$menus[] = 'index.php';
			$menus[] = 'seperator1';

			//if( ! current_user_can('manage_options') ) {
				$menus[] = 'tools.php';
				$menus[] = 'options-general.php';
				$menus[] = 'plugins.php';
				$menus[] = 'wpseo_dashboard';
				//$menus[] = 'themes.php';
			//}
		}

		$remove_menu_items = apply_filters(
			'mkdo_remove_admin_menus',
			$menus
		);

		foreach( $remove_menu_items as $remove_menu_item ) {
			remove_menu_page( $remove_menu_item );
		}
	}

	/**
	 * Remove admin sub menus
	 */
	public function remove_admin_sub_menus() {

		$menus = array();

		if( ! MKDO_Helper_User::is_mkdo_user() ) {

			$menus[] = 	array(
							'parent' 	=> 'themes.php',
							'child' 	=> 'themes.php',
						);
			$menus[] = 	array(
							'parent' 	=> 'themes.php',
							'child' 	=> 'customize.php',
						);
			$menus[] = 	array(
							'parent' 	=> 'themes.php',
							'child' 	=> 'theme-editor.php',
						);
		}

		$remove_menu_items = apply_filters(
			'mkdo_remove_admin_sub_menus',
			$menus
		);

		foreach( $remove_menu_items as $remove_menu_item ) {
			remove_submenu_page( $remove_menu_item[ 'parent'], $remove_menu_item[ 'child' ] );
		}

	}

	/**
	 * Rename media menu
	 */
	public function rename_mkdo_media_menu() {
	
		global $menu;
		
		$menu[10][0] = 'Assets';
	}

	/**
	 * Rename media menu
	 */
	public function rename_mkdo_media_page( $translation, $text, $domain )
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
	 * Add admin menu
	 */
	public function add_mkdo_content_menu() {
	
		/* add the main page for wpbroadbean info */
		add_menu_page(
			'Content',
			'Content',
			'edit_posts',
			'mkdo_content_menu',
			array( $this, 'mkdo_content_output'),
			'dashicons-admin-page',
			'26'
		);
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
	 * Add pages to the menu
	 */
	public function add_posts_to_mkdo_content() {
		add_submenu_page(
			'mkdo_content_menu',
			'News',
			'News',
			'edit_posts',
			'edit.php'
		);
	}

	/**
	 * Add posts to the menu
	 */
	public function add_pages_to_mkdo_content() {
		
		if ( class_exists('sc_bulk_page_creator') ) {

			add_submenu_page(
				'mkdo_content_menu',
				'Pages',
				'Pages',
				'edit_posts',
				'edit.php?post_type=page&page=cms-tpv-page-page'
			);

		}
		else {
			add_submenu_page(
				'mkdo_content_menu',
				'Pages',
				'Pages',
				'edit_posts',
				'edit.php?post_type=page'
			);
		}		
	}

	/**
	 * Correct the heirachy
	 */
	public function correct_menu_hierarchy( $parent_file ) {
	
		global $current_screen;
		global $submenu;

		$pages 	= array();
		$parent = 'mkdo_content_menu' ;
		
		if ( is_array( $submenu ) && isset( $submenu[$parent] ) ) {

			foreach ( (array) $submenu[$parent] as $item) {
				if ( $parent == $item[2] || $parent == $item[2] )
						continue;
				if ( current_user_can($item[1]) ) {
					$menu_file = $item[2];
					if ( false !== ( $pos = strpos( $menu_file, '?' ) ) )
						$menu_file = substr( $menu_file, 0, $pos );
					if ( file_exists( ABSPATH . "wp-admin/$menu_file" ) ) {
						$pages[] = $item[0];
					} else {
						$pages[] = $item[0];
					}
				}
			}
		}

		$post_type = get_post_type_object( $current_screen->post_type );
		
		if( isset( $post_type->labels ) && isset( $post_type->labels->name ) ) {
			$post_type = $post_type->labels->name;
		}
		

		if( $post_type == 'Posts' && isset( $this->args['posts'] )  && isset( $this->args['posts']['name_plural'] ) ) {
			$post_type = $this->args['posts']['name_plural'];
		}

		if( $post_type == 'Pages' && isset( $this->args['pages'] )  && isset( $this->args['pages']['name_plural'] ) ) {
			$post_type = $this->args['pages']['name_plural'];
		}
		
		/* get the base of the current screen */
		$screenbase = $current_screen->base;

		/* if this is the edit.php base */
		if( $screenbase == 'edit' || ( $screenbase == 'post' && in_array( $post_type, $pages ) ) ) {

			/* set the parent file slug to the custom content page */
			$parent_file = 'mkdo_content_menu';
			
		}

		if( class_exists('sc_bulk_page_creator') && $screenbase == 'pages_page_cms-tpv-page-page' ) {
			$parent_file = 'mkdo_content_menu';
		}
		
		/* return the new parent file */	
		return $parent_file;
	}

	/**
	 * Correct the heirachy
	 */
	public function correct_sub_menu_hierarchy() {
		global $submenu;
		
		foreach( $submenu['edit.php?post_type=page'] as $key=>$smenu ) {
			$submenu['edit.php?post_type=page'][$key][2] = $smenu[2] . '&post_type=page';
		}

	}

	/**
	 * Add 'Comments' to the menu dashboard
	 */
	public function add_comments_to_mkdo_dashboard() {
		if ( current_user_can('moderate_comments') ) {
			//if( MKDO_Helper_User::is_mkdo_user() ) {
				wp_add_dashboard_widget(
					'comments_dash_widget',
					'<span class="mkdo-block-title dashicons-before dashicons-admin-comments"></span> Comments',
					array( $this, 'render_mkdo_dashboard_comments' )
				);
			//}
		}
	}

	/**
	 * Render 'Comments' block in menu dashboard // TODO: Make a template
	 */
	public function render_mkdo_dashboard_comments(){
													
		?>

		<div>
			
			<p><a class="button button-primary" href="edit-comments.php">Manage</a></p>


			<div class="content-description">

				<p>Manage comments left by site visitors.</p>

			</div>
			
			<p class="footer-button"><a class="button" href="edit-comments.php">Edit / Manage Comments</a></p>
			
		</div>
		
		<?php
	}
}
