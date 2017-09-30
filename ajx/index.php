<?php 

require( 'dcrt.php' ) ; // $path_to_doc_root is now a global var

require( $path_to_doc_root . 'inc/scr/libs/mysql.php' ) ;

require( $path_to_doc_root . 'inc/scr/libs/ajax.php' ) ;


// connect under PDO

PDO_connect() ;

// get the context:-

// either the page has currently has NO <li>'s 

//		( in which case a variable named "routing" was posted )

if ( isset( $_POST['routing'] ) ) {


	// we are being asked "Is there any routing?" 

	// 		and we must answer 'y' or 'n'

	query_existence_of_routing() ;


} else { // no variable named "routing" was posted


	//		therefore, the Ajax form in div #buffer has been posted

	process_ajax( $_POST['instructions'] ) ; // fn in ajax.php

}


// close the connection ( particularly important, as we are calling all this in a very short loop )

PDO_disconnect() ;



function query_existence_of_routing()
{
	
	global $pdo ;


	// the easiest way to test if we have any routing [even a mouldy old one]

	//		is to see if table `lis` has any rows

	// 	( we limit the query to one row to save time & resources )

	$data = $pdo->query( " SELECT * FROM lis LIMIT 1 " )->fetchAll() ;


	if ( $data ) {


	    // there IS routing

	    echo 'y' ;

	} else {


		echo 'n' ;

	}
	
}

