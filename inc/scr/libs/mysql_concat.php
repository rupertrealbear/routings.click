<?php 

// Some stuff to build SQL that uses MySQL's CONCAT() function.

// The ultimately resulting text of the query is not only highly complex/cunning HTML 

// 						it can be delivered in "one lump" ( see PDO_fetchALL() in mysql.php )

// 								with a small crop at the beginning and a tiny addition at the end

// 										and can be echoed straight into the inner html of <div> #uls


// the following global vars deliver complex textual tokens (quotes , CRLFs)

// 						to be laid down in complex SQL / to be experienced on the web page

$title_CRLF = "&#13;&#10;" ; // used as plain text in the value of an HTML tag's title attribute this throws a carriage return (as experienced in most|all modern browsers)

$qu = 'CHAR(34)' ; // use in SQl

$title_qu = "'&quot;'" ; // use inside value of title so that quotes are seen on hovering over the tag


function cst($fieldname) // resulting SQL casts value [as will be found in field $fieldname] as char
{	
	global $spam ;

	return "CAST( $fieldname AS CHAR )" ;
}


function cnc() // delivers an SQL CONCAT() with any number of arguments
{
	// get ready to process the array of arguments passed to the function

	$arg_list = func_get_args();

	return " CONCAT( " . implode( ' , ', $arg_list ) . " ) " ;
}


function cws() // // delivers an SQL CONCAT_WS() with any number of arguments
{
	// specifically puts quotes round 1st argument (others remain as passed)

	// get ready to process the array of arguments passed to the function

	$arg_list = func_get_args() ;

	// record the first argument

	$arg1 = $arg_list[0] ;

	// remove 1st argument

	$arg_list = array_slice( $arg_list , 1 ) ;

	// reinsert 1st argument [surrounded by quotes] to the front of 

	//								the array of arguments passed to the function

	array_unshift( $arg_list, "'$arg1'" ) ;

	$parameter = implode(' , ', $arg_list) ;


	return " CONCAT_WS( " . implode(' , ', $arg_list) . " ) " ;
}


function attr( $name , $value )
{
	global $qu ;

	return "' $name=' , $qu , $value , $qu" ;
}


function attr_class() // delivers 
// returns hard-coded entry in SQL's CONCAT() function
					// which, in turn, shall return the HTML of the tag's class attribute
					// composed in terms of current field values in the current row of the query
{
	$value = "'s' , " . cst('l.status') ;

	return attr( 'class' , $value ) ;
}


function attr_id() // returns hard-coded entry in SQL's CONCAT() function
					// which, in turn, shall return the HTML of the tag's id attribute
					// composed in terms of current field values in the current row of the query
{

	$value = cst('l.route') . " , '_' , " . cst('l.li_index') ;

	return attr( 'id' , $value ) ;
}


function sq_if( $cond, $true, $false ) // delivers An SQL IF()
{
	return " IF( $cond , $true , $false )" ;
}


function status() // delivers wording of status [which is numeric] 
				  // 	to be in the value of <li>'s title attribute
				  //		and to be seen in quotes 	
{
	global $title_qu ;

	$picker_start_time_SQL = "SUBSTR( TIME( d.pick_start ) , 1 , LENGTH( TIME( d.pick_start ) ) - 3 ) " ;

	$who_is_picking_since_when = cnc( "' ['" , 'd.picker' , "' '" , $picker_start_time_SQL , "'] '" ) ;

	return cnc( $title_qu , "' '" , 'k.wording' , "' '" , $title_qu ,

					sq_if( 'l.status = 7' , $who_is_picking_since_when , "''" )

				 ) ;
}


function attr_van_title()  // delivers wording of a VAN <li>'s title attribute
{
	global $title_CRLF ;
	global $title_qu ;

	$reg = 'v.vehicle_reg' ;
	$start = 'v.start_time' ;
	$weight = cnc( 'v.total_weight' , "' kg'" ) ;
	$driver = 'v.driver' ;
	
	$who_loaded = cnc( "'loaded by '" , 'v.loader' ) ;
	$how_quick = cnc( "'in '" , 'v.load_time' ) ;

	$load_info = cnc( "'$title_CRLF'" , $who_loaded , "'$title_CRLF'" , $how_quick ) ;

	$if_loaded = sq_if( 'v.load_finish IS NULL' , "''" , $load_info ) ;

	return cnc(  cws( $title_CRLF , status() , $reg , $start , $weight , $driver ) , $if_loaded ) ;
	
}


function attr_drop_title() // delivers wording of a DROP <li>'s title attribute
{
	global $qu ;
	global $title_CRLF ;
	global $title_qu ;

	
	$van = cst('l.route') ;
	$position = cst('d.position') ;
	$drop = cnc( $van , "' / '" , $position ) ;

	$order = 'd.order_no' ;
	$weight = cnc( 'd.weight' , "' kg'" ) ;
	$postcode = 'd.postcode' ;
	$customer = 'd.customer' ;

	return cws( $title_CRLF , $drop , status() , $order, $weight, $postcode , $customer )  ;
	
}


function attr_title() // delivers wording of a DROP <li>'s title attribute
					// choosing VAN / DROP based on the value of `lis`.`li_index`
{

	$value = sq_if ( 'l.li_index = 0' , attr_van_title() , attr_drop_title() ) ;

	return attr( 'title' , $value ) ;

}


function js_agent_attrs()   // delivers SQL of custom attributes

							//	activity_start=""  &  agent=""

							//    containing the contents of `vans`.`load_start` & `vans`.`loader`

							//		 where <li> is a van , status VAN_LOADING ( class="s3" )

							//	OR		

							//    containing the contents of `drops`.`pick_start` & `drops`.`picker`

							//		 where <li> is NOT a van , status DROP_PICKING ( class="s7" )


{
	$attr_load_start = attr( 'activity_start' , 'v.load_start' ) ;

	$attr_loader = attr( 'agent' , 'v.loader' ) ;

	$van_attrs = cnc( $attr_load_start , $attr_loader ) ;


	$attr_pick_start = attr( 'activity_start' , 'd.pick_start' ) ;

	$attr_picker = attr( 'agent' , 'd.picker' ) ;

	$drop_attrs = cnc( $attr_pick_start , $attr_picker ) ;


	return sq_if( 'l.li_index = 0 And l.status = 3' , $van_attrs , 

						sq_if( 'l.li_index > 0 And l.status = 7' , $drop_attrs , "''" ) ) ;

	// NOTE even tho we only show the picker and start time when status is DROP_PICKING (7),

	// 		if connection has been lost - and routing is restored from the database -

	//			the code in progress_li() needs to get agent from the <li>'s attribute agent=""

	//				should user right click a drop which is at DROP_PICKED (8)


}


// to be kept in case this all breaks by changing things

function van_js_loader_attrs()  // delivers SQL of custom attributes

								//	load_start=""  &  loader=""

								//		ONLY where <li> is a van , status VAN_LOADING 

								//			( class="s3" )

{
	$attr_load_start = attr( 'load_start' , 'v.load_start' ) ;

	$attr_loader = attr( 'loader' , 'v.loader' ) ;

	$attrs = cnc( $attr_load_start , $attr_loader ) ;

	return sq_if( 'l.li_index = 0 And l.status = 3' , $attrs , "''" ) ;
}


function start_end_tags() // delivers the opening barrage of HTML for the next <li>
						  //		as an SQL IF()

						  // if this record [of table `lis`] is for a VAN
						  // 		then we finish off a previous <ul> and start a new <li>

						  // if for a DROP
						  //		then we just start a new <li>
{
	return sq_if ( 'l.li_index = 0' , "'</ul><ul><li'" , "'<li'" ) ;
}

function li_as_tag() // delivers an SQL dynamic field definition that delivers
					 //		 all the HTML that can be authored from the information
					 //				in `drops`|`vans`|`kinds_of_status`
					 //					 relating to a single row of `lis`
{
	// the innerhtml will be:-

	//		the route number [l.route] for a van

	//		the position [d.position] for a drop

	$innerhtml = sq_if ( 'l.li_index = 0' , cst('l.route') , cst('d.position') ) ;


	$concat = cnc( start_end_tags() , attr_class() , attr_id() , attr_title() ,

						js_agent_attrs() , "'>'" , $innerhtml , "'</li>'" ) ;

/*						van_js_loader_attrs() , "'>'" , $innerhtml , "'</li>'" ) ;

	// NOTE above where I inserted: van_js_loader_attrs() ,*/

	return "$concat As Tag" ;
}


function select() 
{

	// the table join relationships for our routing are encoded into the string below

	$left_join_nest_list = 'lis.l;vans.v=li_index&route;drops.d=li_index&route;kinds_of_status.k=status' ;

	$LJs = nested_Left_Joins_Out_of( $left_join_nest_list ) ;

	return "SELECT " . li_as_tag() . " FROM $LJs  ORDER BY l.route , l.li_index ";

}


function sql_report()
{
	$single_calc_field = cws( '</td><td>' , 'picker' , 'order_no' , 'weight' ,

							 time_from( 'pick_start' ) , time_from( 'pick_finish' ) , 

									time_diff_from( 'pick_start' , 'pick_finish' ) ) ;


	$where = " WHERE pick_finish is not null " ;


	$orderby = " ORDER BY picker , pick_start " ;


	return "SELECT $single_calc_field As Line FROM drops $where $orderby " ;

}

/**
 * inserts field name into some nested SQL 
 *
 * 		that returns only the time portion [ minus seconds ] from the contents of a timestamp field
 *
 * @param string $fieldname name of field type timestamp
 *
 * @return string functional MySQL snippet incorporating [ supplied ] field
 */

function time_from( $fieldname )
{
	return "LEFT( RIGHT( $fieldname , 8 ) , 5 ) " ;
}



function time_diff_from( $start , $finish )
{
	return "SUBSTR( SEC_TO_TIME( TIMESTAMPDIFF( SECOND , $start , $finish ) ) , 1 , 5 ) " ;
}


function sql_export()
{

	// the table join relationships for our routing are encoded into the string below

	$left_join_nest_list = 'lis.l;vans.v=li_index&route;drops.d=li_index&route' ;

	$LJs = nested_Left_Joins_Out_of( $left_join_nest_list ) ;


	// define  SQL to lay down the table name `lis` and it's values from the current record

	//		formatted to be re-inserted in another database

	$lis_vals = INSERTS_for_table( 'lis' ) ;

	$vans_vals = INSERTS_for_table( 'vans' ) ;

	$drops_vals = INSERTS_for_table( 'drops' ) ;

	$semicolon = 'CHAR(59)' ;

	$line = cnc( $lis_vals, $semicolon , sq_if( 'l.li_index = 0' , $vans_vals , $drops_vals  ) ) ;


	return "SELECT $line As Line FROM $LJs  ORDER BY l.route , l.li_index " ;
	
}


function INSERTS_for_table( $tablename )
{
	
	switch ( $tablename ) {

		case 'lis':

			$values = '*l.route,*l.li_index,*l.status' ;

			break;
		
		case 'vans':

			$values = '*l.route,*l.li_index,vehicle_reg,driver,start_time,*total_weight,(load_start,(load_finish,(loader,(load_time' ;

			break;
		
		case 'drops':

			$values = '*l.route,*l.li_index,*position,order_no,customer,postcode,*weight,(picker,(pick_start,(pick_finish' ;

			break;
		

	} // switch


	return cnc( "'$tablename'" , 'CHAR(96)' , VALUE_CLAUSE( $values ) ) ;


}



function VALUE_CLAUSE( $value_list )
{

	$comma = 'CHAR(44)' ;

	$CONCAT_args = '' ; 

	$arr_list = explode( ',' , $value_list ) ;

	foreach ( $arr_list as $value ) {


		// make sure a comma will appear separating the INSERT values

		if ( $CONCAT_args != '' ) {


			$CONCAT_args = delim( $comma , $CONCAT_args , ' , ' ) ;
			
		}


		$CONCAT_args = delim( NorV( $value ) , $CONCAT_args , ' , ' ) ;

		
	}

	return $CONCAT_args ;
	
}


function had_leading( $char , $value, &$leading_char_there )
{
	
	$leading_char_there =  substr( $value , 0 , 1 ) == $char ;


	// remove leading search char from value, if there, and return

	return substr( $value , ( ( $leading_char_there ) ? 1 : 0 ) ) ;

}


function NorV( $value )
{
	
	$textual_NULL = "'NULL'" ;

	// look for any leading "(" 

	// 		( means it MIGHT be Null, in which case we must deliver string "NULL" )


	$in_parentheses = false ;

	$bracketable_value = had_leading( "(" , $value, $in_parentheses ) ; // parenthesis now REMOVED


	// quickly get just the field name expression ( there might be leading star )

	$naked_fieldname_expr = substr( $bracketable_value , 

								( ( ( substr( $bracketable_value , 0 , 1 ) == '*' ) ) ? 1 : 0 ) ) ;


	return ( $in_parentheses ) ? " IF ( $naked_fieldname_expr IS NULL , $textual_NULL , " .

										TorN( $bracketable_value ) . " ) " : TorN( $bracketable_value ) ;


}


function TorN( $value )
{
	
	$qu = 'CHAR(39)' ;

	// look for any leading "*" - means a numeric field which must be CAST AS CHAR 

	// 		( alternative is textual wich must be emclosed in single quotes )

	$is_numeric = false ;

	$quoteable_value = had_leading( "*" , $value, $is_numeric ) ; // star now REMOVED


	return ( $is_numeric ) ? cst( $quoteable_value ) :  cnc(  $qu , $quoteable_value , $qu ) ;

}


function nested_Left_Joins_Out_of( $left_join_nest_list )
{
	global $title_CRLF ;

	// $left_join_nest_list = semicolon-delimited list of 

	// [tablename] . [alias] the "leftmost alias" whose fields all related tables will join ON 

	// followed by

	// [tablename] . [alias] = [ field of this table to join ON with "leftmost alias" ] 

	//								plus, possibly & [ ANOTHER field of this table . . . ]


	// result is the SQL of a nested LEFT JOIN all onto the "leftmost alias"

	$sql = '' ;

	$Is_First_Join = true ;

	$arr_join = explode( ';' , $left_join_nest_list ) ;

	foreach ($arr_join as $encoded_join) {

		if ( strpos( $encoded_join, '=' ) === false ) { // this is the very first table definition

			// define the stuff right of '.' as the leftmost alias 

			//		( whose fields everything will LEFT JOIN to )

			$leftmost_alias = substr( strstr( $encoded_join , '.' ) , 1 ) ;

			// begin the LEFT JOIN with just the table clause (expanded out with ' As ')

			$sql =  str_replace( '.' , ' As ' , $encoded_join ) ;

			
		} else {


			Add_Join_To( $sql , $encoded_join , $Is_First_Join , $leftmost_alias ) ;
			
		} // if strpos( $encoded_join, '>'


	} // foreach $arr_join as $encoded_join


	return $sql ;

}

function Add_Join_To( &$sql , $encoded_join , &$Is_First_Join , $leftmost_alias )
{

	// split encoded join on =

	$arr_table = explode( '=' , $encoded_join ) ;

	// define encoded alias from stuff left of =

	$encoded_alias = $arr_table[0] ;

	// expand this out with ' As ' to use, as is, for the table clause

	$table_clause =  str_replace( '.' , ' As ' , $encoded_alias ) ;

	// define this join alias as the stuff in encoded alias right of '.'

	$this_join_alias = substr( strstr( $encoded_alias , '.' ) , 1 ) ;

	// define encoded ON clause as stuff right of =

	$encoded_on = $arr_table[1] ;

	// split encoded ON clause on &

	$arr_on = explode( '&' ,$encoded_on ) ;

	$on_clause = '' ;

	// build ON clause with however many field names 

	foreach ($arr_on as $field) {
		
		$on_clause .= ( ( $on_clause == '' ) ? '' : ' And ' ) . 

			"$this_join_alias.$field = $leftmost_alias.$field" ;
	}

	if ( $Is_First_Join ) {

		// DON'T put the resulting LEFT JOIN in parantheses the very first time

		$sql .= " LEFT JOIN $table_clause ON $on_clause" ;

		$Is_First_Join = false ;
		
	} else {

		// put existing LEFT JOIN sql in parentheses

		$sql = " ( $sql ) LEFT JOIN $table_clause ON $on_clause" ;
		
	} // If $Is_First_Join
	
}



 ?>