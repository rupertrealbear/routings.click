<?php 

// set path the libs modules we shall use

$libs_path = $path_to_doc_root . 'inc/scr/libs/' ;

// require certain "libraries"

require( $libs_path . 'context.php' ) ;

require( $libs_path . 'util.php' ) ;

require( $libs_path . 'mysql.php' ) ;

require( $libs_path . 'post.php' ) ;

require( $libs_path . 'li.php' ) ;

// connect to the database

PDO_connect() ;


// Check if anything was posted	

if ( isset( $_POST['raw'] ) ) {

	process_submission() ;	// in lib post.php

} else {

	render_routing_maybe() ;	// in lib li.php
	
}

PDO_disconnect() ;

