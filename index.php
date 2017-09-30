<!DOCTYPE html>
<?php 

require( 'dcrt.php' ) ; // $path_to_doc_root is now a global var

require( $path_to_doc_root . 'inc/scr/mains/check_routing.php' ) ;

?>
<style type="text/css">
/*
	Only VAN <li>s status "ready" & "loading" are clicKable here
*/
	.s2 , .s3 {  cursor: pointer; }

</style>

<html>
<head>

	<title>Routing</title>

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
