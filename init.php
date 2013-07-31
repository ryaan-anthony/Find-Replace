<?php
/* 
Plugin Name: Find/Replace
Description: Find and Replace data in posts and metadata. Serialized data is protected, but objects are ignored.
Version: 1.0.2
Author: Inverse Paradox LLC
*/
	
	function ip_include(){
		include 'form.phtml';
	}

	function ip_add_settings_field(){
		add_settings_field(
			null,
			'Find / Replace',
			'ip_include',
			'permalink',
			'optional'
		);	
	}
	add_action( 'admin_init', 'ip_add_settings_field');