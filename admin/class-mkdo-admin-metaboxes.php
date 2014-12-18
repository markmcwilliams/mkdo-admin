<?php
/**
 * Metaboxes
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
if( ! class_exists( 'MKDO_Helper_User' ) )		require_once plugin_dir_path( __FILE__ ) . '../vendor/mkdo/mkdo-objects/includes/class-mkdo-helper-user.php';

/**
 * Metaboxes
 *
 * Functions relating to metaboxes
 *
 * @package    MKDO_Admin
 * @subpackage MKDO_Admin/admin
 * @author     Make Do <hello@makedo.in>
 */
class MKDO_Admin_Metaboxes extends MKDO_Class {

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
	 * Remove metaboxes
	 */
	public function remove_metaboxes() {
	
		/* if the current user is not a mkdo super user */
		if( ! MKDO_Helper_User::is_mkdo_user() ) {
			
			$metaboxes = apply_filters(
				'mkdo_remove_metaboxes',
				array(
					array(
						'id' 		=> 'postcustom',
						'page' 		=> array('post','page'),
						'context' 	=> 'normal'
					),
					array(
						'id' 		=> 'commentsdiv',
						'page' 		=> array('post','page'),
						'context' 	=> 'normal'
					),
					array(
						'id' 		=> 'commentstatusdiv',
						'page' 		=> array('post','page'),
						'context' 	=> 'normal'
					),
					array(
						'id' 		=> 'slugdiv',
						'page' 		=> array('post','page'),
						'context' 	=> 'normal'
					),
					array(
						'id' 		=> 'trackbacksdiv',
						'page' 		=> array('post','page'),
						'context' 	=> 'normal'
					),
					array(
						'id' 		=> 'revisionsdiv',
						'page'		=> array('post','page'),
						'context' 	=> 'normal'
					),
					// array(
					// 	'id' 		=> 'tagsdiv-post_tag',
					// 	'page' 		=> array('post','page'),
					// 	'context' 	=> 'side'
					// ),
					array(
						'id' 		=> 'authordiv',
						'page' 		=> array('post','page'),
						'context' 	=> 'normal'
					),
					array(
						'id' 		=> 'authordiv',
						'page' 		=> array('post','page'),
						'context' 	=> 'normal'
					),
					array(
						'id' 		=> 'wpseo_meta',
						'page' 		=> 'all',
						'context' 	=> 'normal'
					),
				)
			);
			
			foreach( $metaboxes as $metabox ) {
				
				if( $metabox[ 'page' ] == 'all' ) {
					$pages = get_post_types();

					foreach( $pages as $page )
					{
						remove_meta_box( $metabox[ 'id' ], $page , $metabox[ 'context' ] );
					}
				}
				else {
					foreach( $metabox[ 'page' ] as $page )
					{
						remove_meta_box( $metabox[ 'id' ], $page , $metabox[ 'context' ] );
					}
				}
			}
			
		}
	}
}