<?php 


// The following is to establish whether we are online or on a local wamp server


// define a boolean for the  directory path of page where script is executing

$scfn = '' ;


// define a boolean as whether we are online or not 

$page_is_online = false ;

// define a boolean of the path to the document root [ relative to page ]

$path_to_doc_root = '' ;

// define an absolute path down to the document root

$path_down_to_doc_root = '' ;

get_bearings() ; // the above globals are now CODED


function get_bearings()
{
	global $scfn ;

	global $page_is_online ;

	global $path_to_doc_root ;

	global $path_down_to_doc_root ;


	// lookup directory path of page where script is executing 

	$scfn = $_SERVER['SCRIPT_FILENAME'] ;	


	// In wamp, the document root is a folder called "www"

	// Online, there is no folder [ nor any part of any sub folder ] containing "www"

	$page_is_online = ( strpos( $scfn , 'www' ) === false ) ;


	// Online, the document root is a folder called "public_html"

	$doc_root_folder_name = ( $page_is_online ) ? "public_html" : "www" ;


	// To keep things simple, pages live either ON the document root or on a folder ONE LEVEL DOWN

	// It is easy to establish the position of the LAST slash in this page's path

	$position_of_last_slash = strrpos( $scfn, '/' ) ;

	// If a page is ON the document root, 

	//		the position of the LAST slash in it's path

	//			is equal to the position of the FIRST slash in the path 

	//  			starting where the document root folder name is found

	//				( otherwise [one level down], the position is MORE )

	$position_of_slash_after_doc_root_folder_name = 

								strpos( $scfn , '/' , strpos( $scfn , $doc_root_folder_name ) ) ;


	// define absolute path down to route ( for when writing to files thru script )

	$path_down_to_doc_root = substr( $scfn , 1 , $position_of_slash_after_doc_root_folder_name ) ;


	$on_doc_root = ( $position_of_last_slash == $position_of_slash_after_doc_root_folder_name ) ;


	// If a page is ON the document root,

	//		the path TO the document root is nothing [ '' ]

	// 			otherwise [one level down], the path TO the document root = "../"

	$path_to_doc_root = ( $on_doc_root ) ? '' : "../" ;


}


 ?>