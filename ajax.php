<?php

	define('WP_USE_THEMES', false);
	
	define( 'WP_CACHE', false );
	
	require_once '../../../wp-load.php';
	
	if(!isset($_POST['find']) || empty($_POST['find'])){
		die(json_encode(array(
			"done" => true,
			"found" => 0
		)));
	}
		
	require_once 'find.replace.php';
	
	$obj = new Ip_Find_Replace( $_POST['find'], $_POST['replace'] );
	
	echo json_encode(array(
		"done" => true,
		"found" => $obj->start(array(
			array( 'options', 'option_value', 'option_id' ),
			array( 'posts', 'post_content', 'ID' ),
			array( 'postmeta', 'meta_value', 'meta_id' )
		))
	));

?>