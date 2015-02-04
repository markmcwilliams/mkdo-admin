<?php
/**
 * Class for registering a taxonomy
 *
 * @link       http://makedo.in
 * @since      1.0.0
 *
 * @package    MKDO_Incidents
 * @subpackage MKDO_Incidents/admin
 */

/**
 * Class for registering a taxonomy
 *
 * Defines the Type Taxonomy
 *
 * @package    MKDO_Incidents
 * @subpackage MKDO_Incidents/admin
 * @author     Make Do <hello@makedo.in>
 */
class MKDO_Admin_Taxonomies extends MKDO_Class {


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

	public function add_category_to_page() {
		register_taxonomy_for_object_type('category', 'page');  
	}

	public function add_tags_to_page() {
		register_taxonomy_for_object_type('post_tag', 'page'); 
	}

}