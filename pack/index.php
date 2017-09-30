<!DOCTYPE html>
<?php 

require( 'dcrt.php' ) ; // $path_to_doc_root is now a global var

require( $path_to_doc_root . 'inc/scr/mains/check_routing.php' ) ;

?>
<style type="text/css">
/*
	Form & "Create New Routing" button & "Keep Old Routing" button there by default 
		so hide them to avoid confusion
*/		
	#repl_fm , #new_routing , #keep { visibility: hidden; }

/*
	Only <li>s status "picked" & "packing" are clicable here
*/
	.s8 , .s9 {  cursor: pointer; }

</style>

<html>
<head>

	<title>Pack</title>

		<?php require( $path_to_doc_root . 'inc/mkp/style_wrapper.php' ) ; ?>

		<!-- include the form to post encoded routing -->

		<?php require( $path_to_doc_root . 'inc/mkp/repl_fm.php' ) ; ?>

		<div id="routing">

			<!-- include the colour key for the <li> s -->

			<?php require( $path_to_doc_root . 'inc/mkp/key_n_new.php' ) ; ?>

			<div id="uls">

				<?php echo $uls ; ?>

			<!-- include the end tags that complete the <div> s we started  -->

			<?php require( $path_to_doc_root . 'inc/mkp/end_divs.php' ) ; ?>	


</body>
</html>
