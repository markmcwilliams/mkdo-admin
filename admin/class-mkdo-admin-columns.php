<?php
/**
 * Adds additional fields to profile page
 *
 * @link       http://makedo.in
 * @since      1.0.0
 *
 * @package    MKDO_Admin
 * @subpackage MKDO_Admin/admin
 */

/**
 * Admin profile
 *
 * Adds additional fields to profile page
 *
 * @package    MKDO_Admin
 * @subpackage MKDO_Admin/admin
 * @author     Make Do <hello@makedo.in>
 */
class MKDO_Admin_Columns extends MKDO_Class {

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
	 * Remove columns
	 */
	public function remove_columns( $columns ) {

		if( ! MKDO_Helper_User::is_mkdo_user() ) {
			
			$mkdo_columns = apply_filters(
				'mkdo_remove_columns',
				array(
					'comments',
					'tags',
					'wpseo-score',
					'wpseo-title',
					'wpseo-metadesc',
					'wpseo-focuskw',
				)
			);
			
			foreach( $mkdo_columns as $column ) {
				unset( $columns[$column] );
			}	
		}
		return $columns;
	}

	/**
	 * Remove columns for each post type
	 */
	public function remove_custom_post_columns() {

		foreach( get_post_types() as $post_type )
		{
			add_filter( 'manage_' . $post_type . '_posts_columns', 	array( $this, 'remove_columns' ), 9999, 1 );
		}
	}

	/**
	 * Remove column filters
	 */
	public function remove_column_filters() {
		
		global $wpseo_metabox;

		if ( $wpseo_metabox ) {
			remove_action( 'restrict_manage_posts', array( $wpseo_metabox, 'posts_filter_dropdown' ) );
		}
	}
}