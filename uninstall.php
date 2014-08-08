<?php
	if ( !defined( 'WP_UNINSTALL_PLUGIN' )) {
		exit;
	}
	
	if ( get_option('ehive_objects_image_grid_options') != false ) {
		delete_option('ehive_objects_image_grid_options');
	}