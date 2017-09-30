<?php 



function Insert_Into( $tablename, $field_value_list )
{
	// we have to assume that all data is valid

	$field_name_list = "route,li_index," . 

		( ( $tablename == 'vans' ) ? "vehicle_reg,driver,start_time,total_weight" :
		
						( ( $tablename == 'drops' ) ? "position,order_no,customer,postcode,weight" : 

									"status" ) );


	pdo_execute( insert_sql_from_predictable_list( $tablename, $field_name_list, $field_value_list ) ) ;

}

function insert_sql_from_predictable_list( $tablename, $field_name_list, $delimited_field_value_list, 

															$delimiter='~' )
{
	// because the [texual] list is predictable, we can assume

	//		- numeric values will always be for a numeric field

	//		- nothing at all => a zero length string for a textual field

	// put angle quotes around each field name in $field_name_list

	$field_name_list = "`" . str_replace( ',' , "`,`" ,$field_name_list ) . "`" ;

	$converted_field_value_list = convert_to_insert_values( $delimited_field_value_list ) ;

	// construct the insert sql

	return "INSERT INTO `$tablename` ( $field_name_list ) VALUES ( $converted_field_value_list ) " ;

}

function convert_to_insert_values( $delimited_field_value_list, $delimiter='~' )
{
	$converted_values = '' ;

	$arr_field = explode( $delimiter, $delimited_field_value_list ) ;

	foreach ($arr_field as $fld) {
					
		$insert_ready_value = ( $fld == '' ) ? "''" : ( ( is_numeric($fld) ) ? $fld : "'$fld'" ) ;

		$converted_values = delim( $insert_ready_value, $converted_values ) ;
	}

	return $converted_values ;
}


 ?>