<?php 

require('mysql_concat.php') ;

$uls = '' ;
 
$vans = 0 ;

function render_routing_maybe()
{

	global $uls , $vans ;


	// See if any <li> data in database

	$sth = pdo_execute( " SELECT id FROM vans " ) ;

	$data = $sth->fetchAll(); 

	if ( $data ) { // get the routing


		// we want to echo the number of vans in case not all columns get rendered

		$vans = count( $data ) ;


		// Highly cunning php can construct SQL that delivers HTML

		//		to go in the innerhtml of <div> #uls

		//			in ONE SINGLE HIT

		$Routing_HTML_All_As_One_Lump_Db_Result_Set = PDO_fetchALL(  // in mysql.php

																	select() // in mysql_concat.php 

																	) ;

		
		// What is returned is an array which we can implode [with no delimiter]

		$Routing_HTML_All_As_One_Lump = implode( $Routing_HTML_All_As_One_Lump_Db_Result_Set ) ;


		// We now have that HTML which just needs a leading '</ul>' to be moved to the end

		$uls = substr( $Routing_HTML_All_As_One_Lump , 5 ) . // erases the leading '</ul>' . .

															'</ul>' ; // . . puts it at the end


	} // if ( $data )
	
}

