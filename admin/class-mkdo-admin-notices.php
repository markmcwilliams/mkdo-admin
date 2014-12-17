<?php
/**
 * Admin notices
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
if( ! class_exists( 'MKDO_Class' ) )	require_once plugin_dir_path( __FILE__ ) . '../vendor/mkdo/mkdo-objects/admin/class-mkdo-class.php';

/**
 * Admin notices
 *
 * Adds additional notice fields to edit.php
 *
 * @package    MKDO_Admin
 * @subpackage MKDO_Admin/admin
 * @author     Make Do <hello@makedo.in>
 */
class MKDO_Admin_Notices extends MKDO_Class {

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
	 * Add taxonomies as a notice
	 */
	public function admin_notices() {
	
		global $pagenow; global $typenow;
		
		/* get the current admin page */
		$current_admin_page = $pagenow;
		
		/* check this is the post listing page for this post type */
		if( $current_admin_page == 'edit.php' ) {
			
			/* get all the taxonomies for this post type */
			$taxonomies = get_object_taxonomies( $typenow, 'objects' );
			/* remove post formats */
			unset( $taxonomies[ 'post_format' ] );
			
			/* check we have taxonomies to show */
			if( ! empty( $taxonomies ) ) {
			?>
			<div class="updated taxonomies-notice">
				<h3 class="tax-title">Taxonomies:</h3>
				
				<ul class="tax-list">
				<?php
					
					/* loop through each taxonomy */
					foreach( $taxonomies as $tax ) {
						//echo $tax->name;//'<pre>'; var_dump( $tax ); echo '</pre>';
						?>
						<li class="<?php echo esc_attr( sanitize_title( $tax->name ) ); ?>">
							<span class="dashicons-before dashicons-category"></span>
							<a href="<?php echo admin_url( 'edit-tags.php?taxonomy=' . $tax->name ); ?>"><?php echo esc_html( $tax->labels->name ); ?></a>
						</li>
						<?php
						
					} // end loop through taxonomies
					
				?>
				</ul>
			</div>
			<?php
			}
		}
	}
}