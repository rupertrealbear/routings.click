<!DOCTYPE html>
<?php 

require( 'dcrt.php' ) ; // $path_to_doc_root is now a global var

require( $path_to_doc_root . 'inc/scr/libs/mysql.php' ) ;

require( $path_to_doc_root . 'inc/scr/libs/util.php' ) ;

require( $path_to_doc_root . 'inc/scr/libs/mysql_concat.php' ) ;

PDO_connect() ;

$export_fetchall_result = PDO_fetchALL( sql_export() ) ;

$export_delimited_VALUES = implode( ";" , $export_fetchall_result ) ; 



PDO_disconnect() ;

?>


<html>
<head>

	<title>Export</title>

	<script src="<?php echo $path_to_doc_root ?>inc/js/jquery.js"></script>

	<script src="<?php echo $path_to_doc_root ?>inc/js/clipboard.js"></script>

		<?php require( $path_to_doc_root . 'inc/mkp/style_wrapper.php' ) ; ?>

			<h3>Postable Data Ready to Export.</h3>
			<h3>Click the Button to Put this Data on the Windows Clipboard.</h3>
			<h3>The Data Remains on the Clipboard until you Replace it in the Usual Way.</h3>
			<h3>If the Target Routing is to Hand [ on this Machine ] you can Open it, Paste into the Textarea and Post this Input to Replace [ or Create ] the Routing.</h3>
			<h3>Alternatively, you can Paste into a Text File, Save to a Removeable Drive and Paste into the Target Routing on Another Machine.</h3>

			<button id="copy-button" class="btn" style="margin: auto" data-clipboard-text="<?php echo $export_delimited_VALUES ; ?>">
			    Copy to clipboard
			</button>

			<h3>NOTE only the INDEX [ or ROOT ] File can Create or Replace the Routing</h3>
			<h3>e.g. <a href="http://routings.click">http://routings.click</a> or <a href="http://localhost">"http://localhost</a> </h3>

		<!-- complete div #wrapper -->
		</div> <!-- #wrapper -->


	<script>

		(function(){
		    new Clipboard('#copy-button');
		})();

		$( window ).on( 'load', function() {

			$( '#copy-button' ).on('mouseup', function (event) {

				alert( 'Export Data is Now on the Windows Clipboard.' ) ;

			} ) ; // page loaded

		} ) ; // #copy-button mouseup

	</script>


<!-- complete body tag -->
</body>
</html>
