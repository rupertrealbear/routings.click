<?php 

require( 'dcrt.php' ) ; // $path_to_doc_root is now a global var

require( $path_to_doc_root . 'inc/scr/libs/mysql.php' ) ;



// connect under PDO

PDO_connect() ;


// the task, here, is to 

//		- identify the order from $_POST['clickable']

//		- look up in table `lis`

//		- echo `status`



// close the connection 

PDO_disconnect() ;
