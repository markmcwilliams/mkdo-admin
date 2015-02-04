<?php
/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://makedo.in
 * @since      1.0.0
 *
 * @package    MKDO_Admin
 * @subpackage MKDO_Admin/admin
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    MKDO_Admin
 * @subpackage MKDO_Admin/admin
 * @author     Make Do <hello@makedo.in>
 */
class MKDO_Admin_Status extends MKDO_Class {

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
	 * 
	 * @since  		1.0.0
	 * 
	 * Custom post status for archived news
	 * 
	 */
	public function add_archived_status() {
		register_post_status( 'archived', array(
			'label'                     => _x( 'Archived', $instance ),
			'public'                    => true,
			'exclude_from_search'       => true,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Archived <span class="count">(%s)</span>', 'Archived <span class="count">(%s)</span>' ),
		));
	}


	/**
	 * 
	 * @since  		1.0.0
	 * 
	 * Add the post status as a choosable option
	 * 
	 */
	function make_archived_status_choosable() {
		global $post;

		$label 		= '';
		$value 		= '';
		$complete 	= '';
		$span 		= '';
		$post_types = 	apply_filters(
							'mkdo_custom_status_archived_filter',
							array()
						);
		
		foreach( $post_types as $post_type)
		{
			if( $post->post_type == $post_type )
			{
				if( $post->post_status == 'archived' )
				{
					$complete = ' selected=\"selected\"';
					$label = '<span id=\"post-status-display\"> Archived</span>';
				}
				echo '
				<script>
					jQuery(document).ready(function($){
						$("select#post_status").append("<option value=\"archived\" '.$complete.'>Archived</option>");
						$(".misc-pub-section label").append("'.$label.'");
					});
				</script>
				';
			}
		}
	}

	/**
	 * 
	 * @since  		1.0.0
	 * 
	 * Append the post status state to post list
	 * 
	 */
	function add_archived_status_to_post_list( $states ) {
		
		global $post;
		$arg = get_query_var( 'post_status' );
		if($arg != 'archived'){
			if($post->post_status == 'archived'){
				return array('Archived');
			}
		}
	    return $states;
	}

	/**
	 * 
	 * @since  		1.0.0
	 * 
	 * Edit flow removes the post status from the post list, lets fix that
	 * 
	 */
	public function override_edit_flow_status() {
		wp_dequeue_style('edit_flow-custom_status');
	}

	/**
	 * 
	 * @since  		1.0.0
	 * 
	 * Edit flow only cares about its own custom statuses, lets fix that!
	 * 
	 */
	public function override_edit_flow_stauts_column( $column_name ) {
		
		global $post;

		if ( $column_name == 'status' ) {
			
			if( $post->post_status == 'archived' )
			{
				echo 'Archived';
			}
		}
		
	}
}