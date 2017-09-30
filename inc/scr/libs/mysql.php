<?php 

// Shared hosting requires a random prefix to our username and database.

// So as to be able to use any edits to mysql.php immediately online,

//			we construct username and database name conditionally

// the following is the random prefix issued to us by www.000webhost.com

$my000webhost_id = "id1737068_" ;

$webhost_id = ( $page_is_online ) ? $my000webhost_id : '' ;

// The random prefix is now '' if we are using wampserver.

// This allows us to export anything we develop on wampserver straight to

//		online shared hosting, as is 


$pdo ; // a PDO connection

function PDO_connect() // following advice from phpdelusions.net
{
	global $page_is_online ;
	global $webhost_id ;
	global $www ;
	global $pdo ;

	$host = ( $page_is_online ) ? 'localhost' : '127.0.0.1'  ; 
	$db   = $webhost_id . "paragon" ;
	$user = $webhost_id . "root" ;
	$pwd = "Algenon234" ;  //  NOTE to be REALLY secure, we would include a file [ defining the password ]
						   //	   that is located	ABOVE THE DOCUMENT ROOT 
						   //		( forward from a folder parallel to the document root folder
						   //			normally "www" or "public_html" online )

						   //  Hacks exist that can display a file's php code - but only files
						   // 		on or below the document root
	$charset = 'utf8' ;


	$dsn = "mysql:host=$host;dbname=$db;charset=$charset" ;

	$opt = [
	    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
	    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
	    PDO::ATTR_EMULATE_PREPARES   => false
	] ;

	$pdo = new PDO( $dsn , $user , $pwd , $opt ) ;


}



function PDO_disconnect() // so we can avoid "global $pdo" anywhere else but mysql.php
						  //	making it pretty much a "layer"
{
	global $pdo ;

	$pdo = null ;
}



function pdo_execute( $sql ) // run a query without parameters
{
	
	global $pdo ;

	// NOTE it doesn't matter if no result set is returned

	//		we just call it LIKE $stmt = pdo_execute( $sql ) if there IS result data set

	//		and LIKE pdo_execute( $sql ) if NO result data set

	return $pdo->query( $sql ) ;
}



function prepared_statement( $sql ) // prepare statement but wait for execution
{
	
	global $pdo ; 
  

	return $pdo->prepare( $sql ) ;
	
}



// prepare and execute statement

function pdo_executed_statement( $sql , $arr_exec_parrams=[] // you can SKIP parameters
															 // this is used for 
															 //		when shared in conditional code
															 // 		where mainly parameters ARE passed

															 // If you KNOW there will be NO parameters
															 // 	use pdo_execute()
								) 
{
	
	$sth = prepared_statement( $sql ) ;

	$sth->execute( $arr_exec_parrams ) ;

	return $sth ;

}



// PDO has the ability to grab all the rows of a query simultaneously into an array 

//										(which can be imploded into a string: -> HTML) 

function PDO_fetchALL( $sql , $arr_exec_parrams=[] )
{

	$sth = pdo_executed_statement( $sql , $arr_exec_parrams ) ;

	return $sth->fetchAll( PDO::FETCH_COLUMN , 0 ) ; // all of 1st col in one array

}



// shortcut function - used more than once - to truncate the data in database paragon

function pdo_erase_routing()
{
	
	// empty all tables except `kinds_of_status`

	foreach ( [ 'drops' , 'lis' , 'update_instructions' , 'vans' ] as $tablename ) {

		pdo_execute( " TRUNCATE TABLE $tablename " ) ;

	}
	
}



// delivers current timestamp as string

function pdo_get_timestamp()
{
	$stmt = pdo_executed_statement( 'SELECT CURRENT_TIMESTAMP' ) ;

	$row = $stmt->fetch( PDO::FETCH_NUM ) ; // default fetch style is PDO::FETCH_ASSOC requiring you to . .

	return $row[0]  ; // . . name the key ( in this case 'CURRENT_TIMESTAMP' )
}



 ?>