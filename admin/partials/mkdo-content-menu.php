<?php

// Load WordPress dashboard API
require_once( ABSPATH . 'wp-admin/includes/dashboard.php' );

$title 			= __('Content');
$parent_file 	= 'admin.php';

?>

<div class="wrap">
	
	<h2>Site Content</h2>
	<p>Below are your sites content types. They are grouped here for ease of access to allow you to quickly add new content or edit existing content.</p>
	
	<?php
		
		do_action( $this->slug . 'before_screen_output', MKDO_Helper_Screen::get_screen_base() );

		do_action( $this->slug . '_before_blocks' );

		do_action( $this->slug . '_render_blocks' );
		
	?>
	
	<div class="clear clearfix"></div>
	<?php

		do_action( $this->slug . '_after_blocks' );	
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
