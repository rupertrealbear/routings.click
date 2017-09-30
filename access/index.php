<?php 

define( "ERR_VALIDATION_OK" , 		0 ) ;
define( "ERR_VAN_WAITING" , 		1 ) ;
define( "ERR_VAN_READY" , 			2 ) ;
define( "ERR_VAN_LOADING" , 		3 ) ;
define( "ERR_VAN_LOADED" , 			4 ) ;
define( "ERR_DROP_SHORT" , 			5 ) ;
define( "ERR_DROP_PRINTED" , 		6 ) ;
define( "ERR_DROP_PICKING" , 		7 ) ;
define( "ERR_DROP_PICKED" , 		8 ) ;
define( "ERR_DROP_PACKING" , 		9 ) ;
define( "ERR_DROP_PACKED" , 		10 ) ;
define( "ERR_DROP_LOADED" , 		11 ) ;
define( "ERR_NO_POST" , 			12 ) ;
define( "ERR_TOO_MANY_POSTS" , 		13 ) ;
define( "ERR_WRG_POST_NAME" , 		14 ) ;
define( "ERR_EMPTY_INSTR" , 		15 ) ; // nothing in $_POST['instr']
define( "ERR_ILLEGAL_CHARS" , 		16 ) ;
define( "ERR_NO_OP" , 				17 ) ; // "+" | "-" | "f" | "r" not found
define( "ERR_CANNOT_SPLIT" , 		18 ) ; // cannot split on "+" | "-" | "f" | "r"
define( "ERR_TOO_MANY_OPS" , 		19 ) ; // MORE THAN ONE of "+" | "-" | "f" | "r"  found
define( "ERR_OP_AT_START" , 		20 ) ;
define( "ERR_OP_AT_END" , 			21 ) ;
define( "ERR_VAN_OUT_OF_RNG" , 		22 ) ;
define( "ERR_POS_OUT_OF_RNG" , 		23 ) ;
define( "ERR_INVALID_ORDER" , 		24 ) ;
define( "ERR_ORDER_NOT_FOUND" , 	25 ) ;
define( "ERR_ROUTE_NOT_FOUND" , 	26 ) ;
define( "ERR_POSITION_NOT_FOUND" , 	27 ) ;
define( "ERR_NO_NEW_CLASS" , 		28 ) ;
define( "ERR_MISSING_AGENT" , 		29 ) ;
define( "ERR_INAPPROP_AGENT" , 		30 ) ;
define( "ERR_IDX_OUT_OF_RNG" , 		31 ) ;
define( "ERR_VAN_IDX_OUT_OF_RNG" , 	32 ) ;
define( "ERR_DRP_IDX_OUT_OF_RNG" , 	33 ) ;
define( "ERR_VAN_ONLY_INC" , 		34 ) ;
define( "ERR_PICKER_CHAR" , 		35 ) ;
define( "ERR_LOADER_CHAR" , 		36 ) ;
define( "ERR_ILLEGAL_FORCE" , 		37 ) ;
define( "ERR_FORCE_CONTEXT" , 		38 ) ; // 'Can Only Force from DROP_PRINTED'
define( "ERR_ILLEGAL_REVERT" , 		39 ) ; 
define( "ERR_REVERT_CONTEXT" , 		40 ) ; // 'Can Only Revert from DROP_SHORT or DROP_PRINTED'
define( "ERR_REVERT_PRINTED" , 		41 ) ; // 'Can Only Revert to DROP_PRINTED from DROP_SHORT'
define( "ERR_REVERT_PICKED" , 		42 ) ; // 'Can Only Revert to DROP_PICKED from DROP_PRINTED'
define( "ERR_INC_DEC_SHORT" , 		43 ) ; // 'Cannot Increment or Decrement Out of DROP_SHORT'
define( "ERR_INC_OUT_OF_RNG" , 		44 ) ;
define( "ERR_DEC_OUT_OF_RNG" , 		45 ) ;

$error = array( 
 
		/* 0 ERR_VALIDATION_OK => */  'Validation Passed, Update Failed' ,
		/* 1 ERR_VAN_WAITING  => */ "Instruction Validated, Status now VAN_WAITING" ,
		/* 2 ERR_VAN_READY  => */ "Instruction Validated, Status now VAN_READY" ,
		/* 3 ERR_VAN_LOADING  => */ "Instruction Validated, Status now VAN_LOADING" ,
		/* 4 ERR_VAN_LOADED  => */ "Instruction Validated, Status now VAN_LOADED" ,
		/* 5 ERR_DROP_SHORT  => */ "Instruction Validated, Status now DROP_SHORT" ,
		/* 6 ERR_DROP_PRINTED  => */ "Instruction Validated, Status now DROP_PRINTED" ,
		/* 7 ERR_DROP_PICKING  => */ "Instruction Validated, Status now DROP_PICKING" ,
		/* 8 ERR_DROP_PICKED  => */ "Instruction Validated, Status now DROP_PICKED" ,
		/* 9 ERR_DROP_PACKING  => */ "Instruction Validated, Status now DROP_PACKING" ,
		/* 10 ERR_DROP_PACKED  => */ "Instruction Validated, Status now DROP_PACKED" ,
		/* 11 ERR_DROP_LOADED  => */ "Instruction Validated, Status now DROP_LOADED" ,
		/* 12 ERR_NO_POST => */  'No POST at all' , 
		/* 13 ERR_TOO_MANY_POSTS => */  'Too many POST vars' ,
		/* 14 ERR_WRG_POST_NAME => */  'Wrong POST var name' ,
		/* 15 ERR_EMPTY_INSTR => */  'Empty Instruction' , 
		/* 16 ERR_ILLEGAL_CHARS => */  'Illegal character(s) found in Instruction' ,
		/* 17 ERR_NO_OP => */  'No operator in the Instruction' , 
		/* 18 ERR_CANNOT_SPLIT => */  'Cannot split Instruction on the operator' ,
		/* 19 ERR_TOO_MANY_OPS => */  'Too many operators in the Instruction' ,
		/* 20 ERR_OP_AT_START => */  'Operand at the START of the Instruction' ,
		/* 21 ERR_OP_AT_END => */  'Operand at the END of the Instruction' ,
		/* 22 ERR_VAN_OUT_OF_RNG => */  'Van Number Out of Range' ,
		/* 23 ERR_POS_OUT_OF_RNG => */  'Drop Position Out of Range' ,
		/* 24 ERR_INVALID_ORDER => */  'Not Possible to Identify Order in Instruction' ,
		/* 25 ERR_ORDER_NOT_FOUND => */  'Order not Found in Database' ,
		/* 26 ERR_ROUTE_NOT_FOUND => */  'Route Number not Found in Database' ,
		/* 27 ERR_POSITION_NOT_FOUND => */  'Van Position not Found in Database' ,
		/* 28 ERR_NO_NEW_CLASS => */  'New Class Index Missing or in the Wrong Place' ,
		/* 29 ERR_MISSING_AGENT => */  'Missing Agent for New Class Index' ,
		/* 30 ERR_INAPPROP_AGENT => */  'Unwanted Agent for New Class Index' ,
		/* 31 ERR_IDX_OUT_OF_RNG => */  'New Class Index Completely Out of Range' ,
		/* 32 ERR_VAN_IDX_OUT_OF_RNG => */  'New Class Index Out of Range For Van' ,
		/* 33 ERR_DRP_IDX_OUT_OF_RNG => */  'New Class Index Out of Range For Drop' ,
		/* 34 ERR_VAN_ONLY_INC => */  'Illegal Operator, Vans can Only Increment' ,
		/* 35 ERR_PICKER_CHAR => */  'Only Capital Letters and Periods for Picker' ,
		/* 36 ERR_LOADER_CHAR => */  'Only Capital Letters, Periods ( +"&"s ) for Loader(s)' ,
		/* 37 ERR_ILLEGAL_FORCE => */  'A Drop Can Only be Forced to DROP_SHORT' ,
		/* 38 ERR_FORCE_CONTEXT => */  'A Drop Can Only be Forced from DROP_PRINTED' ,
		/* 39 ERR_ILLEGAL_REVERT => */  'A Drop Can Only be Reverted to DROP_PRINTED or DROP_PICKED' ,
		/* 40 ERR_REVERT_CONTEXT => */  'Can Only Revert from DROP_SHORT or DROP_PRINTED' ,
		/* 41 ERR_REVERT_PRINTED => */  'Can Only Revert to DROP_PRINTED from DROP_SHORT' ,
		/* 42 ERR_REVERT_PICKED => */  'Can Only Revert to DROP_PICKED from DROP_PRINTED' ,
		/* 43 ERR_INC_DEC_SHORT => */  'Cannot Increment or Decrement Out of DROP_SHORT' ,
		/* 44 ERR_INC_OUT_OF_RNG => */  'New Class Index Out of Range for Incrementing' ,
		/* 45 ERR_DEC_OUT_OF_RNG => */  'New Class Index Out of Range for Decrementing'

	);

require( 'dcrt.php' ) ; // $path_to_doc_root is now a global var

require( $path_to_doc_root . 'inc/scr/libs/mysql.php' ) ;

require( $path_to_doc_root . 'inc/scr/libs/ajax.php' ) ;


// connect under PDO

PDO_connect() ;


// initialise some global vars ( used in constructing an <li> id="" attribute )

$route = -1 ;

$li_index = '' ;

$status = '' ;

// initialise global operator for instruction  ( "+" | "-" | "f" | "r"  )

$op = '' ;

$new_class_index = '' ;
$agent = '' ;


// print_r($_POST);

// initialise the [numeric] output to 0

$final_analysis = 0 ;

perform_analysis() ;

function ajax_style_instruction() {


	global $route , $li_index , $op , $new_class_index , $agent ;


	return $route . "_$li_index$op$new_class_index$agent" ;

}

function perform_analysis()
{

	
	global $final_analysis ;
	global $latest_rowcount ;
	global $new_class_index ;
	global $error ;

	// check there is a single POST var named "instr" with something in it

	$final_analysis = validate_post() ;

	if ( $final_analysis == 0 ) {


		// validate content of posted instruction

		$final_analysis = analyse( $_POST['instr'] ) ;


	} // $final_analysis == 0



	// if validation checks out, we can attempt carrying out the instruction

	if ( $final_analysis == 0 ) {


		deal_with(  ajax_style_instruction()  ) ;

		// echo "<br>latest_rowcount: $latest_rowcount<br>" ;

		// global $latest_rowcount will indicate


		//		failure [ 0 ] or

		//		success [ > 0 ] ( fortunately there is only one update so will = 1 )

		$final_analysis = ( ( $latest_rowcount == 0 ) ? 0 : $new_class_index ) ;


	}


	echo $final_analysis ;

	// echo '<br>' . $error[ $final_analysis ] . '<br>'; 


	// echo "LI_id: $route / $li_index<br> status: $status<br>" ;


}

// echo  ajax_style_instruction() ;

// close the connection 

PDO_disconnect() ;




function validate_post()
{

	if ( ( empty($_POST) ) ) { // nothing at all posted


		return ERR_NO_POST ;


	} else { // there IS post


		// check we don't have more than ONE post var

		if ( sizeof( $_POST ) > 1 ) { // more than ONE post var
			

			return ERR_TOO_MANY_POSTS ;


		} else { // only one post var
			

			// check it's the RIGHT post var ()

			if ( !( isset( $_POST['instr'] ) ) ) {
				

				return ERR_WRG_POST_NAME ;


			} else { // just one post var named "instr"
				

				// check anything actually IN $_POST['instr']

				if ( empty( $_POST['instr']) ) { // something IN $_POST['instr']
					

					return ERR_EMPTY_INSTR ;


				} // something IN $_POST['instr']


			} // just one post var named "instr"

			
		} // only one post var
		

	} // there IS post

	
}



function analyse( $instruction )
{
	
	// initialise an error integer . . .

	$input_analysis = 0 ;


	// . . . and a potential order [+ drop position] that might be found in the instruction 

	$order = '' ;

	$position = '' ;


	// check there are no illegal characters

	// we are only allowed digits, capital letters, periods, forward slashes, ampersands or

	//		a single character operator "-" | "+" | "r" | "f"


	if ( !( preg_match( '/^[\dA-Z\.\/&fr+-]+$/' , $instruction , $match ) ) ) { // some ILLEGAL chars


		// short-cicuit the function, returning error

		return ERR_ILLEGAL_CHARS ;


	} // some ILLEGAL chars


	// check if there is ONE instruction operator in the MIDDLE somewhere

	$input_analysis = analyse_op_in( $instruction , $input_analysis ) ;


	if ( $input_analysis == 0 ) {
		

		// check if the target of the instruction (the order)

		//		is written as an order number or a route-slash-position . . .
	
		$input_analysis = analyse_target_in( $instruction , $input_analysis , $order , $position ) ;


		if ( $input_analysis == 0 ) { 

			
			// . . . and whether either of these can be found in the routing database

			$input_analysis = check_db_contains( $order , $position , $input_analysis ) ;

		}


	} // $input_analysis == 0


	if ( $input_analysis == 0 ) {


		// check if there is any agent appended to the proposed [numerical] new class index

		// 		and, if so, whether the new class index WARRANTS an agent

		// ALSO , check that new class index in [ 1..11 ]

		$input_analysis = analyse_agent_etc_in( $instruction , $input_analysis ) ;

	}


	return $input_analysis ;


}


function check_db_contains( $order , $position , $input_analysis )
{

	global $route ;
	global $li_index ;
	global $status ;


	if ( $order != '' ) { // it is the order number that checks out
		

		// check that order is found in table `drops`

		if ( !( $data = pdo_executed_statement( 

			"SELECT drops.route , drops.li_index , status FROM drops LEFT JOIN lis " . 

				" ON drops.route = lis.route And drops.li_index = lis.li_index " .

						" WHERE order_no = ? " , [ $order ] )->fetch() ) ) { // order NOT found in database


				$input_analysis = ERR_ORDER_NOT_FOUND ;

			
		} else { // order FOUND in database 



			// assign delivered field values to global vars

			$route = $data['route'] ;

			$li_index = $data['li_index'] ;

			$status = $data['status'] ;

			//  ( $input_analysis remains 0 )


		} // order FOUND in database


	} else {  // it is the route number + position that check out


		// SPECIAL CASE:-

		// where position = 0, this is the VAN so all we need do is check 

		//		that $route is found in table `vans`

		if ( $position == 0 ) { // it's the van


			if ( !( $data = pdo_executed_statement( 

				"SELECT li_index FROM vans  WHERE route = ? " , 

							[ $route ] )->fetch() ) ) { // route number NOT FOUND


					$input_analysis = ERR_ROUTE_NOT_FOUND ;


			} else { // route number FOUND



				// assign '0' to $li_index

				$li_index = '0' ;

			    // $input_analysis remains 0 ( $route already known )


			} // route number FOUND

			
		} else { // it's NOT the van


			
			// check route (route) + position found in table `drops`

			if ( !( $data = pdo_executed_statement( 

				"SELECT drops.li_index , status FROM drops LEFT JOIN lis " .

					" ON drops.route = lis.route And drops.li_index = lis.li_index " .

						"  WHERE drops.route = ? And position = ? " , 

					[ $route , $position ] )->fetch() ) ) { // route number + position NOT found


						$input_analysis = ERR_POSITION_NOT_FOUND ;


				}  else { // route number + position IS found


					// print_r($data);

					// assign delivered field value to global var

					$li_index = $data['li_index'] ;

					$status = $data['status'] ;

					//  	( $input_analysis remains 0 )


				} // route number + position IS found


			
		} // it's NOT the van



	} // it is the route number + position that check out



	return $input_analysis ;
	
}



function analyse_op_in( $instruction , $input_analysis )
{
	
	global $op ;

	// look for the instruction operator ( "op" ) :-

	//		either "+" | "-" | "f" | "r"

	if ( preg_match_all( '/[-+fr]/' , $instruction , $match ) ) {
		

		// also check that there is only ONE "op" . . .

		if ( sizeof( $match[0] ) > 1 ) { // too many "op"s


			return ERR_TOO_MANY_OPS ;


		} else { // just one operator


			// . . . and that it is not at the beginning of the instruction . . .

			// while it's there, quickly assign it to global $op ( we can always throw it away )

			$op = $match[0][0] ; 


			if ( preg_match( '/^[-+fr]/' , $instruction , $match ) ) { // operator IS at the beginning


				return ERR_OP_AT_START ;


			} else { // operator is NOT at the beginning


				// . . . or at the END

				if ( preg_match( '/[-+fr]$/' , $instruction , $match ) ) {


					return ERR_OP_AT_END ;


				} else { // operator is NOT at the end


					// everything is as it should be - so far

					return 0 ;

				}


			} // operator IS at the beginning


		} // just one operator

		

	} else { // no operator found


		// echo "preg_match_all() returned false <br><br>";

		return ERR_NO_OP ;


	} // no operator found
	

}


function analyse_target_in( $instruction , $input_analysis , &$order , &$position )
{

	global $route ;
	

	// isolate what is BEFORE operator 

	preg_match( '/.+?(?=[-+fr])/' , $instruction , $match ) ;


	$target = $match[0] ;


	// check if target is written like [route]/[position]

	if ( preg_match( '/^\d{1,}\/\d{1,}$/' , $target , $match ) ) {


		// isolate what is BEFORE slash ( the route )

		preg_match( '/.+?(?=\/)/' , $target , $match ) ;


		$route = $match[0] ;


		// check route is between 1 and 25

		if ( !( 0 < $route && $route < 26 ) ) {


			$input_analysis = ERR_VAN_OUT_OF_RNG ;


		} else { // route number checks out


			//  isolate what is AFTER slash ( the position )

			preg_match( '/(?<=\/).*/' , $target , $match ) ;


			$position = $match[0] ; // will be passed back by reference


			// check position is between 0 and 25 ( 0 => it's the van )

			if ( !( 0 <= intval($position) && intval($position) < 26 ) ) { 


				$input_analysis = ERR_POS_OUT_OF_RNG ;


			} // if route in 1..25  and position in 0..25 then $input_analysis remains 0		


		} // route number checks out


	} else { // target is NOT written like [route]/[position] 


		
		// check if target is written like SO[number][possible capital letter]

		if ( !( preg_match( '/^SO\d{6,7}[A-Z]?$/' , $target , $match ) ) ) {
			

			$input_analysis = ERR_INVALID_ORDER ;


		} else { // target IS written like SO[number][possible capital letter]



		  $order = $match[0] ; // will be passed back by reference

		  // ( $input_analysis remains 0 )

		} // target IS written like SO[number][possible capital letter]


	} // target is NOT written like [route]/[position]


	return $input_analysis ;

}


function analyse_agent_etc_in( $instruction , $input_analysis )
{
	
	
	global $new_class_index ;
	global $agent ;

	// echo "analyse_agent_etc_in( $instruction , $input_analysis )<br>";

	// isolate what is AFTER operator ( the agent etc )

	preg_match( '/(?<=[-+fr]).*/' , $instruction , $match ) ;

	$agent_etc = $match[0] ;

	// echo "agent_etc: $agent_etc<br>";

	// check that the agent etc begins with one or two digits 

	//		possibly followed by nothing or capital letters and periods and ampersands

	if ( !( preg_match( '/(^\d{1,2}$|^\d{1,2}[A-Z\.&]+$)/' , $agent_etc , $match ) ) ) { // NO digits at start


		$input_analysis = ERR_NO_NEW_CLASS ; // "new class index missing or in the wrong place"


	} else { // digit(s) FOUND


		// isolate the leading digit(s)

		preg_match( '/^\d{1,2}/' , $agent_etc , $match ) ;

		$new_class_index = intval( $match[0] ) ;

		// echo strval( "new_class_index: $new_class_index " ) ;

		// see if there IS an appended agent

		$agent = '' ;

		if ( preg_match( '/^\d{1,2}[A-Z\.&]+$/' , $agent_etc , $match ) ) { // agent PRESENT


			// replace out the leading digit(s)

			$agent = preg_replace( '/^\d{1,2}/' , '' , $agent_etc ) ;

			// echo "agent: $agent " ;


		} // agent PRESENT


	} // digit(s) FOUND


	if ( $input_analysis == 0 ) {


		$input_analysis = analyse_use_of( $new_class_index, $agent , $input_analysis ) ;
		
	}

	return $input_analysis ;

}



function analyse_use_of( $new_class_index , $agent , $input_analysis )
{
	
	global $op ;
	global $route ;
	global $li_index ;

	// echo '<br>analyse_use_of( ' . strval($new_class_index) . " , '" . $agent . "' ) operator; $op";


	// first of all, detect any new class index that could never be true

	if ( !( 1 <= $new_class_index && $new_class_index <= 11 ) ) { // nci completely out of range


		$input_analysis = ERR_IDX_OUT_OF_RNG ;


	} else { // nci WITHIN range


		// look at vans

		if ( $li_index == '0' ) { // target is a van


			$input_analysis = analyse_van( $new_class_index , $agent , $input_analysis ) ;


		} else { // target is a drop


			$input_analysis = analyse_drop( $new_class_index , $agent , $input_analysis ) ;


		} // target is a drop


	} // nci WITHIN range



	return $input_analysis ;

}

function analyse_van( $new_class_index , $agent , $input_analysis )
{

	global $op ;

	// new class index can only be VAN_LOADING (3) or VAN_LOADED (4)

	if ( !( 3 <= $new_class_index && $new_class_index <= 4 ) ) { // bad nci for van

		
		$input_analysis = ERR_VAN_IDX_OUT_OF_RNG ;


	} else { // nci ok for van


		// vans can only INCREMENT

		if ( $op != '+') { // wrong operator for van


			$input_analysis = ERR_VAN_ONLY_INC ;


		} else { // correct operator for van


			// check if need agent or if it's there incorrectly

			// pass loader validation pattern ( capital letters, periods or ampersands )

			//		and also pass the error number to return if wording validation fails

			$loader_pattern = '/^[A-Z\.&]+$/' ;

			$input_analysis = wanted_unwanted( $agent , $new_class_index , 

																$loader_pattern , ERR_LOADER_CHAR ) ;


		} // correct operator for van


	} // nci ok for van


	return $input_analysis ;

}


function analyse_drop( $new_class_index , $agent , $input_analysis )
{
	global $op ;
	global $status ;

	$current_class_index = intval( $status ) ;

	// check new class index is within the range for a drop ( 5 .. 10 ) 

	if ( !( 5 <= $new_class_index && $new_class_index <= 10 ) ) { // bad nci for drop


		$input_analysis = ERR_DRP_IDX_OUT_OF_RNG ;


	} else { // nci within range for drop


		// look at the operator
		
		switch ( $op ) {

			case 'f' : // "force"
				
				// can only force new class index to DROP_SHORT (5) 

				if ( $new_class_index != DROP_SHORT ) {

					$input_analysis = ERR_ILLEGAL_FORCE ;

				} else { // final index OK

					// can only be forced from DROP_PRINTED

					if ( $current_class_index != DROP_PRINTED ) {


						$input_analysis = ERR_FORCE_CONTEXT ;

					}


				} // final index OK

				break;

			case 'r' : // "revert"
			
				// can only revert new class index to DROP_PRINTED (6) or DROP_PICKED (8)

				if ( !( $new_class_index == DROP_PRINTED || $new_class_index == DROP_PICKED ) ) {


					$input_analysis = ERR_ILLEGAL_REVERT ;//'A Drop Can Only be Reverted to DROP_PRINTED or DROP_PICKED'


				} else { // final index OK

					// can only revert from DROP_SHORT or DROP_PRINTED

					if ( !( $current_class_index == DROP_SHORT || $current_class_index == DROP_PRINTED ) ) {


						$input_analysis = ERR_REVERT_CONTEXT ;//'Can Only Revert from DROP_SHORT or DROP_PRINTED'


					} else { // starting index OK


						// check the starting & final indeces are compatible

						if ( $current_class_index == DROP_SHORT ) {


							// make sure final index is DROP_PRINTED

							if ( $new_class_index != DROP_PRINTED ) {


								$input_analysis = ERR_REVERT_PRINTED ;// 'Can Only Revert to DROP_PRINTED from DROP_SHORT'
								
							}
							
						} else { // $current_class_index = DROP_PRINTED


							// make sure final index is DROP_PICKED

							if ( $new_class_index != DROP_PICKED ) {


								$input_analysis = ERR_REVERT_PICKED ;// 'Can Only Revert to DROP_PICKED from DROP_PRINTED'

							}
							
						} // $current_class_index = DROP_PRINTED


					} // starting index OK


				} // final index OK

				break;

			case '+' : // MASSIVE NOTE - need to add error ERR_INC_DEC_SHORT 'Cannot Increment or Decrement Out of DROP_SHORT'

				// most important: we cannot inc or dec out of DROP_SHORT

				if ( $current_class_index == DROP_SHORT ) {


					return ERR_INC_DEC_SHORT ;

				}

				// check if need agent or if it's there incorrectly

				// pass loader validation pattern ( capital letters or periods )

				//		and also pass the error number to return if wording validation fails

				$picker_pattern = '/^[A-Z\.]+$/' ;

				$input_analysis = wanted_unwanted( $agent , $new_class_index , 

																$picker_pattern , ERR_PICKER_CHAR ) ;


				if ( $input_analysis == 0 ) { // no agent issues


					// check we're not incrementing to illegal new index

					// cannot inc out of DROP_SHORT (5) [to 6] or inc beyond DROP_PACKED (10) [to 11]

					// therefore the range of valid inc's is 7 . . 10

					if ( !( 7 <= $new_class_index && $new_class_index <= 10 ) ) { // OUT OF range

						
						$input_analysis = ERR_INC_OUT_OF_RNG ;// ;

						
					}  // OUT OF range


				} // no agent issues


				break;


			case '-' :

				// most important: we cannot inc or dec out of DROP_SHORT

				if ( $current_class_index == DROP_SHORT ) {


					return ERR_INC_DEC_SHORT ;
					
				}

				// check we're not decrementing to illegal new index

				// cannot dec to DROP_SHORT (5) or out of DROP_LOADED (11) [to 10]

				// 		therefore, our maximum new class index is 9


				if ( !( 5 < $new_class_index && $new_class_index < 10 ) ) { // OUT OF range


					$input_analysis = ERR_DEC_OUT_OF_RNG ;
					
					
				} // OUT OF range


				break;


		} // switch


	} // nci within range for drop


	return $input_analysis ;

}


function wanted_unwanted( $agent , $new_class_index , $pattern , $WDG_VLDN_FAIL )
{

	global $op ;

	$agent_analysis = 0 ;

	// we MUST have an agent when incrementing to VAN_LOADING (3) or DROP_PICKING (7)

	if ( $new_class_index == 3 || ( $new_class_index == 7 && $op == '+' ) ) { // we WANT agent


		if ( $agent == '' ) {

			
			$agent_analysis = ERR_MISSING_AGENT ;

		}

	} else { // we DON'T want agent


		if ( $agent != '' ) {
			
			
			$agent_analysis = ERR_INAPPROP_AGENT ;

		}

	} // we DON'T want agent


	if ( $agent_analysis == 0 ) { // agent NEITHER missing OR unwanted


		if ( $agent != '' ) { // agent there


			// validate the included characters of the agent with passed $pattern

			if ( !( preg_match( $pattern , $agent ) ) ) { // some illegal chars in agent


				$agent_analysis = $WDG_VLDN_FAIL ;


			} // some illegal chars in agent


		} // agent there


	} // agent NEITHER missing OR unwanted


	return $agent_analysis ;

}

/*
 ?>
 <!DOCTYPE html>
 <html>
 <head>
 	<title></title>
 </head>
 <body>
 <form method="post">
 <input name="instr" type="text">
  <input type="submit" value="ok"></form>
 </body>
 </html> */