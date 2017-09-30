<?php 

// quickly set $h1_title - only applies to ///index.php , ///gun/index.php & ///pack/index.php

$h1_title = '' ;

// echo substr( $scfn, strpos( $scfn, 'index.php') - 5, 4 ) . '<br>' ;

switch ( substr( $scfn, strpos( $scfn, 'index.php') - 5, 4 ) ) {

	case 'pack':

		$h1_title = 'Packing Activity' ;

		break;

	case '/gun' :

		$h1_title = 'Picking Activity' ;

		break;

	case '/max' :

		$h1_title = '( Huge Test Data )' ;

		break ;

	case '/god' :

		$h1_title = '"God Mode"' ;

		break ;
	
	default:

		$h1_title = "Tonight's Routing" ;

		break;
}
