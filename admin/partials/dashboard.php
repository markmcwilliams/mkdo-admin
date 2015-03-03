<?php

// Load WordPress dashboard API
require_once( ABSPATH . 'wp-admin/includes/dashboard.php' );

$title 			= __('Dashboard');
$parent_file 	= 'admin.php';

?>

<div class="wrap">
	
	<h2>Welcome</h2>
	<p>Welcome to the Dashboard. Below are a few things to help you get around. </p>
	
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
