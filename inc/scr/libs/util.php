<?php 

$sql = '' ; // for testing with $msg

$msg = '' ; // use $msg like :

// 					$msg = [blah blah]  e([a var name as string])


// for testing:

// 	returns $ [var]  = [and the value in that named variable]
//		for echoing

function e($var_nm)
{
	global $$var_nm ;

	return '$' . $var_nm . ' = ' . $$var_nm . ' ' ;
}

// returns   <br>
// 			 function name $function 
// 			 <br>
// 			 function call $function 


function ee( $function_call ) // remember to include brackets on the end if no parameters
{
	global $title_CRLF ;

	$result ;

	
	eval( "\$result = $function_call ;" ) ;


	return "$title_CRLF$function_call$title_CRLF$title_CRLF$result$title_CRLF" ;

}


// appends item $new_string to delimited string $existing_string ( buffered by $delimiter )

function delim( $new_string, $existing_string, $delimiter=',' )
{
	
	return $existing_string . ( ( $existing_string == '' ) ? '' : $delimiter ) . $new_string ;

}


// The following treats delimited strings like lists.

// It returns the left few list items - up to the nth delimiter.

function Left_to_Delimeter( $n, $delimited_list, $delimiter=',' )
{

	$arr_list = explode( $delimiter, $delimited_list ) ;
	

	 $array_slice = array_slice( $arr_list, 0, $n-1 ) ;


	 return implode( $delimiter, $array_slice ) ;

}

 ?>