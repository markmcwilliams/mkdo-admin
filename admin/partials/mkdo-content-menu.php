<?php

// Load WordPress dashboard API
require_once( ABSPATH . 'wp-admin/includes/dashboard.php' );

$title 			= __('Content');
$parent_file 	= 'admin.php';

?>

<div class="wrap">
	
	<h2>Site Content</h2>
	<p>Below are your sites content types. They are grouped here for ease of access to allow you to quickly get to content types either to add new content or the edit existing content.</p>
	
		<?php
			
			do_action( $this->slug . 'before_screen_output', MKDO_Helper_Screen::get_screen_base() );
			
			$mkdo_content_blocks = apply_filters(
				$this->slug . '_blocks',
				array()
			);
			
			if( ! empty( $mkdo_content_blocks ) ) {

				do_action( $this->slug . '_before_blocks' );

				$counter = 1;
		
				foreach( $mkdo_content_blocks as $block ) {

					$function_name = 'dynamic_dash_widget_' . $counter;
					$$function_name = function() use ( $block ){
						
						$post_new 		= admin_url( 'post-new.php?post_type=' . $block[ 'post_type' ] );
						$post_listing 	= admin_url( 'edit.php?post_type=' . $block[ 'post_type' ] );

						if ( defined('CMS_TPV_URL') ) { 
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

			do_action( $this->slug . '_after_blocks' );		
			
		} 
		
			
		?>
	
		<div id="dashboard-widgets-wrap">
			<?php 
				wp_dashboard();
			 ?>
		</div>

		<?php 
			do_action( $this->slug . 'after_screen_output', MKDO_Helper_Screen::get_screen_base() );
		?>
		
		<div class="clearfix clear"></div>
	</div>
	<div class="clearfix clear"></div>

<?php