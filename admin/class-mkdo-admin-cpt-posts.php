<?php
/**
 * Class for a custom post type.
 *
 * @link       http://makedo.in
 * @since      1.0.0
 *
 * @package    MKDO_Admin
 * @subpackage MKDO_Admin/admin
 */

/**
 * Class for a custom post type.
 *
 * Defines the Post CPT
 *
 * @package    MKDO_Admin
 * @subpackage MKDO_Admin/admin
 * @author     Make Do <hello@makedo.in>
 */
class MKDO_Admin_CPT_Posts extends MKDO_CPT {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $instance       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $instance, $version, $args = array() ) {

		$custom 						= 	array(
												'cpt_name' 			=> 'post',
												'dash_icon' 		=> 'dashicons-admin-post',
												'name_singular' 	=> 'News',
												'name_plural' 		=> 'News',
												'slug' 				=> 'post',
											);
		$args 							= 	array_merge( $custom, $args );

		parent::__construct( $instance, $version, $args );
	}

}
