<?php 

require( 'insert.php' ) ;

$van = '' ;
$li_index = '' ;
$position = '' ;
$order = '' ;
$status = '' ;

$db_insert_row = '' ;


function process_submission()
{


	// do not continue if user submitted a blank textarea

	if ( $_POST['raw'] == '' ) {
		
		return ;
	}

	

	// erase existing data

	pdo_erase_routing() ;	// in mysql.php

	// Insert_Using( $_POST['raw'] ) ;



	Insert_VALUES_from( $_POST['raw'] ) ;


	// We must assume that $_POST['raw'] worked

	// 	and inserted into the database 

	render_routing_maybe() ;

}

function Insert_VALUES_from( $post )
{
	// the post is semi-complete, ready-to-execute INSERT SQL

	// split post on semicolon

	$arr_raw = explode( ';' , $post ) ;

	foreach ( $arr_raw as $line ) {


		// split line on grave accent

		$arr_line = explode( '`' , $line ) ;


		$tablename = $arr_line[0] ;

		$fields = fields_from( $tablename ) ;

		$values = $arr_line[1] ;

		$INSERT_SQL = "INSERT INTO `$tablename` ( $fields ) VALUES ( $values ) " ;

		// echo "<br>$INSERT_SQL" ;

		pdo_execute( $INSERT_SQL ) ;

		
	} // foreach ( $arr_raw as $line )

}

function fields_from( $tablename )
{

	switch ( $tablename ) {

		case 'lis':

			return 'route,li_index,status' ;

			break;
		
		case 'vans':

			return 'route,li_index,vehicle_reg,driver,start_time,total_weight,load_start,load_finish,loader,load_time' ;

			break;
		
		case 'drops':

			return 'route,li_index,position,order_no,customer,postcode,weight,picker,pick_start,pick_finish' ;

			break;
	

	} // switch

}


function Insert_Using( $raw_data )
{
	// we have to assume that, if $raw_data is not a zero-length string, then it is valid

	//		(there will be a regex to pass it thru, but that would be for production code)

	$route = "0" ; // this ensures that the Route Number changes at the beginning

	// raw_data is a series of tild-delimited lists field values [textually separated by ";"]

	// 	in a pattern:-

	//		- an insert list for `vans` (simultaneously use `route` & `li_index` for `lis`)

	//		- a stack of inserts [textually separated by ";"] :-

	//				1.  for `drops` (simultaneously use `route` & `li_index` for `lis`)

	//				2.  for `orders`


	// Extract field lists from raw data

	$arr_raw = explode( ";", $raw_data ) ;

	foreach ($arr_raw as $field_value_list) {
		
		// split field list on tild

		$arr_fld = explode( "~", $field_value_list ) ;

		// Decide which table to insert into 

		// 		by comparing first field ( `route` ) to the current Route Number

		if ( $arr_fld[0] != $route ) {
			
			// a new van, we will insert this field value list into table `vans`

			// a simultaneous new record in table `lis` will have

			// 		a default `status` of 1 => outstanding
/*
			NOTE by defaulting van `status` to 0, we are denying ourselves some extensibility

			If, instead, we REUSE the SECOND field $arr_fld[1] `li_index` which - for a van - is ALWAYS 0

				and use THAT placesholder $arr_fld[1] for the status 

					( MS Word will, now, always put "1" in there,

						because it is the default `status` for a new, blank routing )

			then

			we can re-export the routing snapshot, this time using $arr_fld[1] for

				the ACTUAL current `status` of the van in question

			BETTER IDEA:-

			prepend WHOLE delimited string with [status]~ easier to separate rest

*/			$tablename = 'vans' ;

			$status = '1' ;

			// synchronise the current Route Number ( so we will know when it changes again )

			$route = $arr_fld[0] ;

		} else {
			
			// a `drop, we will insert this field value list into table `vans`

			// a simultaneous new record in table `lis` will have

			// 		a default `status` of 6 => printed

			$tablename = 'drops' ;

			$status = '6' ;

		}

		Insert_Into( $tablename, $field_value_list ) ;	// in insert.php

		// use the first two values from the above $field_value_list

		//   		to insert into `lis` ( fields `route` & `li_index` )

		$field_value_list = Left_to_Delimeter( 	// in util.php

				3, $field_value_list, '~' ) . "~$status" ;

		Insert_Into( "lis", $field_value_list ) ;	// in insert.php

	}  // foreach

}



 ?>