<?php 

$webhost_id = "id1737068_" ;

$host = 'localhost'  ; // '127.0.0.1'
$db   = $webhost_id . "paragon" ;
$user = $webhost_id . "root" ;
$pwd = "Algenon234" ;

$con = mysqli_connect( $host , $user , $pwd , $db ) ;

// Check connection
if ( mysqli_connect_errno() )
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error() ;
  } else {

  	echo "success for Mysqli" ;

  	mysqli_close($con);
  }

  	$charset = 'utf8' ;


	$dsn = "mysql:host=$host;dbname=$db;charset=$charset" ;

	$opt = [
	    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
	    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
	    PDO::ATTR_EMULATE_PREPARES   => false,
	] ;

	$pdo = new PDO( $dsn , $user , $pwd , $opt ) ;

	$stmt = pdo_execute( "SELECT wording FROM kinds_of_status" ) ;

	$result = $stmt->fetchAll() ;

	print_r( $result ) ;

	$pdo = null ;

function pdo_execute( $sql ) // run a query without parameters
{
	
	global $pdo ;

	// NOTE it doesn't matter if no result set is returned

	//		we just call it LIKE $stmt = pdo_execute( $sql ) if there IS result data set

	//		and LIKE pdo_execute( $sql ) if NO result data set

	return $pdo->query( $sql ) ;
}

 ?>