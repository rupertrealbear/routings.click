<!DOCTYPE html>
<?php 

require( 'dcrt.php' ) ; // $path_to_doc_root is now a global var

require( $path_to_doc_root . 'inc/scr/libs/mysql.php' ) ;

require( $path_to_doc_root . 'inc/scr/libs/util.php' ) ;

require( $path_to_doc_root . 'inc/scr/libs/mysql_concat.php' ) ;

PDO_connect() ;

$report_fetchall_result = PDO_fetchALL( sql_report() ) ;

$report_delimited_VALUES = implode( "</td></tr><tr><td>" , $report_fetchall_result ) ; 



PDO_disconnect() ;

?>
<style type="text/css">


	th , td {  
		border: 1px solid black ;

		margin: 0px;
	}

	td:nth-child(1) , td:nth-child(2) {
		padding-left: 1cm;
	}

	td:nth-child(3) , td:nth-child(4) , td:nth-child(5) , td:nth-child(6) {
		text-align: center;
	}

</style>

<html>
<head>

	<title>Report</title>

	<?php require( $path_to_doc_root . 'inc/mkp/style_wrapper.php' ) ; ?>

		<script src="<?php echo $path_to_doc_root ?>inc/js/jquery.js"></script>

		<script src="<?php echo $path_to_doc_root ?>inc/js/clipboard.js"></script>

		<h4>The Report merely Groups Pickers together in Pick Start Order</h4>

		<h4>( "Average Time per kg" etc Requires that Pickers' Names were Typed Exactly the Same ).</h4>

		<h4>It is Best to Copy the Report to Excel and Clean everything up Before Trying anything like that.</h4>

		<h4>Just Click the Copy Button:-</h4>

		<h4><button id="copy-button" class="btn" style="margin: auto" data-clipboard-target="#rpt_tbl" onclick="alert( 'Report is Now on the Clipboard' )">
		    Copy Report to Clipboard
		</button></h4>

		<table id="rpt_tbl" >
			<thead>
				<tr><th>Person Picking</th><th>Order Number</th><th>Weight</th> <th>Start</th><th>Finish</th><th>Time</th></tr>
			</thead>
			<tbody>
				<tr><td><?php echo $report_delimited_VALUES ?></td></tr>
			</tbody>
		</table>


	<!-- complete div #wrapper -->
	</div> <!-- #wrapper -->

	<script>
		(function(){
    		new Clipboard('#copy-button');
		})();
	</script>

</body>
</html>