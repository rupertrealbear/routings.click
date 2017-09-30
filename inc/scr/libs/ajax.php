<?php 

// Please NOTE any echoes left in this module for testing 

//		will be returned in data for the  ajax calling fn share_updates()

// some constants

define(	"VAN_WAITING" , 1 ) ;
define(	"VAN_READY" , 2 ) ;
define(	"VAN_LOADING" , 3 ) ;
define(	"VAN_LOADED" , 4 ) ;
define( "DROP_SHORT" , 5 ) ;
define( "DROP_PRINTED" , 6 ) ;
define( "DROP_PICKING" , 7 ) ;
define( "DROP_PICKED" , 8 ) ;
define( "DROP_PACKING" , 9 ) ;
define( "DROP_PACKED" , 10 ) ;
define( "DROP_LOADED" , 11 ) ;

define( "SECS_KEEP_INSTRUCTIONS" , '20' );


// we define a global var for the number of rows affected 

//		which is set in carry_out_single_instruction()

$latest_rowcount = 0 ;


function process_ajax( $post )
{
	
	global $pdo ;



	/*
	// get [& echo] the last of each individual instruction for any given <li> (if any at all)
	
	echo last_instructions() ; // NOTE this forms the INTENDED output that is picked up as data

								//  	in the client ajax-calling fn share_updates()
	
	*/

	// get [& echo] whatever instructions are still in table `update_instructions`

	if ( $existing_instructions_Result_Set = PDO_fetchALL(  // in mysql.php

									" SELECT instruction FROM update_instructions " ) ) {


		$existing_instructions = implode( ' ', $existing_instructions_Result_Set ) ;


	} else {


		$existing_instructions = '' ;

	}

	echo $existing_instructions ; // NOTE this forms the INTENDED output that is picked up as data

								//  	in the client ajax-calling fn share_updates()

	// insert any posted instructions into table `update_instructions` 

	//		also carrying them out on table `lis` [unless problematic] 

	//			also setting any load start|finish times etc on table `vans`


	deal_with( $post ) ;


	// delete any rows in `update_instructions` older than SECS_KEEP_INSTRUCTIONS seconds

	delete_old_instructions() ;

}


function delete_old_instructions()
{

	// get current timestamp

	$current_timestamp = pdo_get_timestamp() ;


	// compare field `time_saved` with the saved NOW() 

	//		( comparing to CURRENT_TIMESTAMP in a query doesn't work )


	pdo_execute( "DELETE FROM update_instructions WHERE TIMESTAMPDIFF( SECOND , time_saved , " .

						" '$current_timestamp' ) > " . SECS_KEEP_INSTRUCTIONS ) ;

}



function last_instructions() // DEPRECATED (as some ajax-posted instructions tend to get lost online)
							 // but kept because - as a soln to a prob - it is freaking awesome!
{

	$ResponseText = '' ;

	// It is all very hard in pure SQL, therefore, we just query `li_id` and `instruction`

	//			in reverse order and then php the result set


	// prepare and execute a statement based on SQL to get  `li_id` and `instruction` in reverse order

	$last_instr_SQL = "SELECT li_id , instruction FROM update_instructions ORDER BY id DESC" ;	

	$lst_instr_sth = pdo_executed_statement( $last_instr_SQL ) ;


	// By using PDO's fetchAll() method [ with fetch style PDO::FETCH_GROUP ]

	//		we can group the result set on the first field [ `li_id` ]

	// fetch a grouped array from the "reverse order" result set

	$arr_group = $lst_instr_sth->fetchAll( PDO::FETCH_GROUP ) ; 


	if ( $arr_group ) {

		
		// traverse the array, pulling out the first sub-item only:-

		foreach ( $arr_group as $li_id // the key of the first dimension is the grouped <li> id

										=> $arr_instr // the item is an enumerated array
				) 

		{
			
			// the corresponding instruction [to key <li> id] is the first item of the above enumerated array

			//		which we encode into our Ajax ReponseText [ delimited with ' ' ]

			$ResponseText .= ( ( $ResponseText == '' ) ? '' : ' ' ) . $arr_instr[0]['instruction'] ;

		}

	}


	return $ResponseText ;

}



function deal_with( $post )
{

	
	if ( $post != '' ) {
			
		
		// prepare [but DON'T execute] a statement based on SQL 

		//		to insert new instructions into `update_instructions` 

		$insert_SQL = " INSERT INTO update_instructions ( instruction , li_id ) VALUES ( ? , ? ) " ;

		$insert_sth = prepared_statement( $insert_SQL ) ;


		// quickly define an associative array for all the components of 

		//		any instruction we uncover ( + db referencing indeces + a WHERE clause )
	
		$arr_comp = array( 'agent' => '' , 'op' => '' , 'new_class_index' => '0' , 

					'route' => '0' , 'LI_index' => '0' , 'LI_id_WHERE'  => ' WHERE true' ) ;


		// take a quick snapshot of all currently-saved instructions that we can search

		$snapshot = ' ' . 

			implode( ' ' , PDO_fetchALL( "SELECT instruction FROM update_instructions" ) ) . ' ' ;

		// NOTE if two screens have same page open, two instances of the instruction could be saved


		// split post into single instructions ( explode on space )

		$arr_instr = explode( ' ' , $post ) ;


		foreach ( $arr_instr as $instruction ) {
			
		
			// extract the <li> id from the textual instruction

			preg_match( '/.+?(?=[+f\-r])/' , $instruction , $match ) ; // match everything upto any of + f - r


			$LI_id = $match[0] ;


			// unravel the agent (if any) , operator , new class index and <li> index

			//	 from the exploded instruction

			//		into associative array $arr_comp

			get_instr_components( $instruction , $LI_id , $arr_comp ) ;


			// UNLESS 

			// 			- already IN table `update_instructions` or

			// 			- <li> is a van and ( op = "-" or 

			//			- ( going to VAN_READY "2" ) ) 

			//		insert instruction & <li> id into `update_instructions`

			if ( !(  strpos( $snapshot , ' ' . $instruction . ' ' )  !== false ||

						$arr_comp['LI_index'] == '0' && // van

							 	( $arr_comp['op'] == "-" || $arr_comp['new_class_index'] == "2" ) // VAN_READY


					) ) {


				$insert_sth->execute( [ $instruction , $LI_id ] ) ;


			}

			/*****************************************************************************************

			NOTE disallowing duplicate instructions (above) means that somebody cannot click up and down

			quickly, over and over, in some random fashion - and expect accurately rendered changes.

			However, while that might be some instinctive "test", this is not the Stock Exchange and 

			that WOULD NEVER ACTUALLY HAPPEN in a production environment.

			What disallowing duplicate instructions DOES achieve, is prevention of pointless duplicated

			instructions spilling out into the client with the hope that the "rightful" final status is 

			achieved in all open browsers

			******************************************************************************************/

			


			// so that a new, up-to-date screen could be opened at any time

			//		we must carry out the posted instruction on the database

			
			carry_out_single_instruction( $arr_comp ) ;

			// if this is a drop incrementing to DROP_PICKING

			//		we set field `drops`.`pick_start` to now() 

			//			and set `drops`.`picker` to $arr_comp['agent']

			// if this is a drop incrementing to DROP_PICKED

			//		we set field `drops`.`pick_finish` to now() 

			if ( $arr_comp['op'] == '+' &&  

				( $arr_comp['new_class_index'] == '7' || $arr_comp['new_class_index'] == '8'  )  ) {


				deal_with_any_picking_context( $arr_comp ) ;
			}


			// if this is a van that is just starting|finishing loading

			//		then we might also need to record the loader + load_start | load_finish + load_time

			//			in table `vans`

			if ( $arr_comp['LI_index'] == '0' ) { // <li> is a van


				deal_with_any_van_loading_context( $arr_comp ) ;

			}


		} // foreach ( $arr_instr as $instruction )


	} // if ( $post != '' )
	
}



function carry_out_single_instruction( $arr_comp )
{
	
	global $latest_rowcount ;


	if ( !( problematic( $arr_comp ) ) ) {

		

		// define SQL to update table `lis`.`status` for our route number and <li> index

		$lis_SQL = "UPDATE lis SET status = " . $arr_comp['new_class_index'] . $arr_comp['LI_id_WHERE'] ;


		// prepare and execute statement using above SQL

		$result = pdo_executed_statement( $lis_SQL ) ;


		// assign number of rows affected to a global var (in case needed)

		$latest_rowcount = $result->rowCount() ;


		// if this is a van going to VAN_LOADED ( "4" )

		//		all the sibling drops must go to DROP_LOADED ( "11" )	

		if ( $arr_comp['LI_index'] == '0' && $arr_comp['new_class_index'] = '4' ) {


			// prepare and execute statement to set all <li>s to DROP_LOADED ( "11" ) 

			//		that have the same route number but LI index > 0

			$result = pdo_executed_statement(  "UPDATE lis SET status = 11 " .

												" WHERE route = " . $arr_comp['route'] .

														" And li_index > 0 " ) ;


		}
		
	} 

}


function problematic( $arr_comp )
{
	
	// we will need to compare current index with proposed new index

	// we don't want to do anything illogical (eg increment to a LOWER index)


	// look up the current status for the target <li> of the instruction

	$lookup_SQL = "SELECT status FROM lis " . $arr_comp['LI_id_WHERE'] ;


	$row = pdo_executed_statement( $lookup_SQL )->fetch() ;


	$current_class_index = intval( $row['status'] ) ;


	// get proposed new index from associative array

	$new_class_index = intval( $arr_comp['new_class_index'] ) ;


	switch (  $arr_comp['op'] ) {

		case 'r' : // "revert" only applies to "freeing" drop marooned in DROP_SHORT

			//	or to "jumping" a stray order (found to be picked) to DROP_PICKED

			//		that is still in DROP_PRINTED	

			// for the sake of "reliablity" we just look at the new class index

			// return ( !( $new_class_index == DROP_PRINTED || $new_class_index == DROP_PICKED ) ) ;

			$freeing = ( $current_class_index == DROP_SHORT && $new_class_index == DROP_PRINTED ) ;

			$jumping = ( $current_class_index == DROP_PRINTED && $new_class_index == DROP_PICKED ) ; 
				
			return ( !( $freeing || $jumping ) ) ;


		break;
		
		case 'f' : // no point forcing to index it already IS
						
				
				return ( $current_class_index == $new_class_index ) ;

			break;
		
		case '+' : // should not increment to a LOWER index [or to the same as before]

					// also, obviously current cannot be DROP_SHORT
						
				
				return ( $current_class_index != DROP_SHORT && 

									$current_class_index >= $new_class_index  ) ;

			break;
		
		case '-' :
						
				return ( 
						 // should not decrement to a HIGHER index [or to the same as before]

						// also, obviously current cannot be DROP_SHORT

						$current_class_index != DROP_SHORT && 

						(

						 $current_class_index <= $new_class_index || 

							// cannot decrement a drop from DROP_PICKED after

						 	//		the sibling van has started loading

							trying_to_interrupt_van_thats_loading( $current_class_index , $arr_comp ) ||


								// starting|finishing van loading cannot be "undone"

								( in_array( $current_class_index , [ VAN_LOADING , VAN_LOADED ] ) )

						)


				  ) ;

			break;
		

	}


}


function trying_to_interrupt_van_thats_loading( $current_class_index , $arr_comp )
{

	// lookup status in `lis` for the sibling van <li>

	$lookup_SQL = "SELECT status FROM lis WHERE route = " . $arr_comp['route'] . 

							" And li_index = 0 " ;

	$stmt = pdo_executed_statement( $lookup_SQL ) ;

	$row = $stmt->fetch( PDO::FETCH_NUM ) ;

	$sibling_van_index = intval( $row[0] ) ;

	return ( $current_class_index == DROP_PACKED && $sibling_van_index == VAN_LOADING ) ;

}

function deal_with_any_picking_context( $arr_comp )
{
	switch (  intval( $arr_comp['new_class_index'] )  ) {

		case DROP_PICKING :


			// define SQL to save the agent and set pick start time ( as now )

			$pick_start_SQL = "UPDATE drops SET pick_start = CURRENT_TIMESTAMP() , " .

											"picker = ? " . $arr_comp['LI_id_WHERE'] ;


			// prepare and execute a statement using this SQL, passing agent 

			$result = pdo_executed_statement( $pick_start_SQL , [ $arr_comp['agent'] ] ) ; 

			break;

		case DROP_PICKED :
			
			// prepare and execute a statement with SQL to set pick finish time ( as now )

			$pick_finish_SQL = "UPDATE drops SET pick_finish = CURRENT_TIMESTAMP() " .

																		$arr_comp['LI_id_WHERE'] ;

			$result = pdo_executed_statement( $pick_finish_SQL ) ;

			break;

	} // switch



}


function  deal_with_any_van_loading_context( $arr_comp )
{
	
	

	switch (  intval( $arr_comp['new_class_index'] )  ) // NOTE $arr_comp has all strings 

														//	for direct use in any SQL strings

														// we are switching on integer constants
	{

		case VAN_LOADING:


			// define SQL to save the agent and set load start time ( as now )

			$load_start_SQL = "UPDATE vans SET load_start = CURRENT_TIMESTAMP() , " .

											"loader = ? " . $arr_comp['LI_id_WHERE'] ;


			// prepare and execute a statement using this SQL, passing agent 

			$result = pdo_executed_statement( $load_start_SQL , [ $arr_comp['agent'] ] ) ; 

			break;

		
		case VAN_LOADED:


			// Define SQL to set load_finish and calculate load_time, setting it formated hh:mm

			// We set the return unit of the TIMESTAMPDIFF() as seconds and feed it to the

			//		SEC_TO_TIME() function which has the format hh:mm:ss

			//			so we use the SUBSTR() function deliver the first five characters

			$load_finish_SQL = "UPDATE vans SET load_finish = CURRENT_TIMESTAMP() , " .

					"load_time = SUBSTR( SEC_TO_TIME( " . 

						" TIMESTAMPDIFF( SECOND, load_start , CURRENT_TIMESTAMP() ) ) , 1 , 5 ) " .

									$arr_comp['LI_id_WHERE'] ;


			// prepare and execute a statement using this SQL

			$result = pdo_executed_statement( $load_finish_SQL ) ;

			break;


	} // switch

}

function get_instr_components( $instruction , $LI_id , &$arr_comp )
{

	// Because it's usually absent - and at the END of the instruction - we have to

    //		check for the presence of agent FIRST


   	$match = [] ;

   	// look for any non digits at the end of the instruction

   	if ( preg_match( '/(\D+)$/' , $instruction , $match ) == 1 ) {


   		$arr_comp['agent'] = $match[0] ;

   	
   	} // if no match then $match will be empty ( so we don't assign it - just leave $arr_comp['agent'] as '')


   	// remove any agent [ AND the passed <li> id] from the instruction

   	$remainder = str_replace( [ $arr_comp['agent'] , $LI_id ] , [ '' , '' ] , $instruction ) ;


   	// the operator is the first character of what remains

   	$arr_comp['op'] = substr( $remainder , 0 , 1 ) ;


   	// the new class index is everything of the remainder after operator

   	$arr_comp['new_class_index'] = substr( $remainder , 1 ) ;

   	
   	// while we're here the route number is the <li> id up to the underscore . . .

	$underscore = strpos( $LI_id , '_'  ) ;


	$arr_comp['route'] = substr( $LI_id , 0 , $underscore ) ;

	// . . . and the LI index is what's left after the underscore

	$arr_comp['LI_index'] = substr( $LI_id , $underscore + 1 ) ;


	// there is a WHERE clause we will use more than once

	$arr_comp['LI_id_WHERE'] = " WHERE route = " . $arr_comp['route'] . 

											" And li_index = " . $arr_comp['LI_index'] ;


}
