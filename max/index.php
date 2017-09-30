<!DOCTYPE html>
<?php 

require( 'dcrt.php' ) ; // $path_to_doc_root is now a global var

// we don't include check_routing.php - but we DO need context.php

require( $path_to_doc_root . 'inc/scr/libs/context.php' ) ;

?>

<html><style type="text/css">
/*
	Form & "Create New Routing" button there by default 
		so hide them to avoid confusion
*/		
	#repl_fm , #new_routing{ visibility: hidden; }

</style>

<head>

	<title>Max Routing</title>

		<?php require( $path_to_doc_root . 'inc/mkp/style_wrapper.php' ) ; ?>

		<!-- include the form to post encoded routing -->

		<?php require( $path_to_doc_root . 'inc/mkp/repl_fm.php' ) ; ?>

		<div id="routing">

			<!-- include the colour key for the <li> s -->

			<?php require( $path_to_doc_root . 'inc/mkp/key_n_new.php' ) ; ?>

			<div id="uls">

				<!-- include gigantic set of <ul>s -->

				<?php require( $path_to_doc_root . 'inc/mkp/max_li.php' ) ; ?>

			<!-- include the end tags that complete the <div> s we started  -->

			<?php require( $path_to_doc_root . 'inc/mkp/end_divs_less.php' ) ; ?>	


</body>
</html>
