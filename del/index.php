<!DOCTYPE html>
<?php 

require( 'dcrt.php' ) ; // $path_to_doc_root is now a global var

require( $path_to_doc_root . 'inc/scr/libs/mysql.php' ) ;

$h1 = '' ;

$display = '' ;

if ( isset($_POST['erase']) ) { 

	
	// connect under PDO

	PDO_connect() ; // in mysql.php


	pdo_erase_routing() ; // in mysql.php


	// close the connection 

	$pdo = null ; // $pdo is global defined in mysql.php


	$h1 = 'Routing Erased' ;


	$display = 'none' ;


	echo '<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(Do the back button to reset form)' ;
	
} 

 ?>
<html>
<head>
	<title>Erase Db</title>
		<link rel="stylesheet" type="text/css" href="../style.css">

</head>
<body>
		<div id="wrapper">

		<h1><?php echo $h1 ; ?></h1>

		<div id="repl_fm" style='display: <?php echo $display ?>;''>
			
			<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ; ?>">

				<input name="erase" type="submit" value="Erase Routing">

			</form>

		</div>

		<div id="routing">
</body>
</html>