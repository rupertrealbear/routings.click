
// This should be called by Ajax or live mouse click

// We can distinguish by whom by having button either be:-

//		- numeric [live mouse click] or

//		- string [Ajax]

function progress_li( id , button , new_class_index , agent , sync ) 
{

	// change the target <li>'s class

	$( '#' + id ).attr( 'class' , 's'+new_class_index ) ;


	// also change class_index sub property in objRouting

	objRouting[id].class_index = new_class_index ;


	// update title to reflex new class index 

	// ( if starting a pick insert picker's name and start time - remove, if going beyond )

	update_title( id , new_class_index , agent ) ;


	// only if incrementing to DROP_PICKING record the user supplied picker and time now

	//		in <li> attributes and objRouting

	if(  IN( [ MSE_BTN_LFT , '+' ] , button ) // incrementing to DROP_PICKING

					&&  new_class_index == DROP_PICKING ) {


		
		record_agent_activity_start( id , agent ) ;

	}

	

	// Only if [live mouse click] . . 

	// . . append new instruction to sync.instructions	( buffered by space )

	setup_post_if_numeric( id , button , new_class_index , agent , sync ) ;


	Issue_and_Carry_Out_any_Related_Instructions( id , button , new_class_index , agent , sync ) ;


}


function Issue_and_Carry_Out_any_Related_Instructions( id , button , new_class_index , agent , sync )
{

	// Check if this is van <li>

	if ( Is_van( id ) ) { 

	 /****************************************************************************************

	 Having changed an <li>

			- a van that increments to VAN_LOADING requires that loader be put in
					attribute agent="" and timestamp of now be put in attribute load_start=""

				NOTE agent is passed as a parameter to fn progress_li() 

			- a van that increments to VAN_LOADED requires that

					- the time difference between now and attribute load_start=""
					  [formatted as HH:mm] be appended to attribute title=""

					- the sibling drop <li>s be incremented to DROP_LOADED

		****************************************************************************************/

		create_or_change_attributes_plus_sibling_drops_on_progression_van_( 

														id , button , new_class_index , agent , sync ) ;

	}	else { // NOT a van <li> [but a DROP]

		/****************************************************************************************

		 Having changed an <li>

				- a drop that increments to DROP_PACKED could be the last one to do so
						requiring that the sibling van goes to VAN_READY

				- a drop that decrements to DROP_PACKING could interrupt a column of all DROP_PACKED
						requiring that the sibling van reverts to VAN_WAITING

		****************************************************************************************/

		make_ready_or_revert_van_on_packedness_of( id , sync ) ;


	} // NOT a van <li> [but a DROP]


}



function update_title( id , new_class_index , agent ) 
{
	
	var title = objRouting[id].title ;


	// define quick array with wordings of status according to class index

	var arr_status = [ "" , "waiting" , "ready" , "loading" , "loaded" , "short" , "printed" , 

						"picking" , "picked" , "packing" , "packed" , "loaded" ] ;


	// make wording for new class index

	var str_new_index_wdg = arr_status[new_class_index] ;	


	// take existing title 

	// 		and replace out whatever was between the two quotes

	//			with the new index wording

	// plus possible picker + pick start in square brackets

	//		 - if starting a pick ( new_class_index == DROP_PICKING )


	// since square brackets might already be in there, we start by replacing them out

	//		 if NOT starting a pick 

	if ( new_class_index != DROP_PICKING ) {

		title = title.replace( / \[(.*)\] / , '' ) ;

	}


	// define what MIGHT be added added the status wording

	var possible_picker = '' ;


	if ( new_class_index == DROP_PICKING ) {


		var pick_start = moment().format( "H:mm" ) ;


		// user might have right clicked an <li> at DROP_PICKED (8)

		//		 in which case agent will not have been entered and passed to progress_li()

		//				but we can still get it from the <li>'s agent="" attribute

		if ( agent != '' ) {


			picker = agent ;


		} else { // get picker from attribute ( in fact, from objRouting )


			picker = objRouting[id].agent ;


		}


		possible_picker = ' [ ' + picker + ' ' + pick_start + ' ] ' ;


	}


	// now replace out whatever was between the two quotes - i.e the OLD status wording

	//			with the new index wording and append either nothing or the picker & time

	var new_title = title.replace( /" (.*) "/igm, '" ' + str_new_index_wdg + ' "' + possible_picker ) ;
 

	// copy this new title to <li> and objRouting

	$( '#' + id ).attr( 'title' ,  new_title ) ;

	objRouting[id].title = new_title ;


}



function setup_post_if_numeric( id , button , new_class_index , agent , sync )
{


	if ( $.isNumeric( button ) ) { // string button is passed, if no posting is required


		// construct instruction from click details . . .

		var new_instr = new_instruction( id , button , new_class_index , agent ) ;


		// . . . append to instructions ( with space )

		sync.instructions += ( ( sync.instructions == '' ) ? '' : ' ' ) + new_instr ;


		// as a TRACE, append new instruction to ajax out

		$( '#ajax_out' ).append( ' ' + new_instr ) ;

	} 

}



function record_agent_activity_start( id , agent ) 
{


	// create a MySQL-like timestamp of now [ uses moment.js ]

	var MySQL_like_timestamp = moment().format("YYYY-MM-DD HH:mm") ;


	// assign [passed] agent to a new attribute agent=""

	//		and the MySQL-like timestamp to a new attribute activity_start=""

	$( '#'+id ).attr( {


			  agent: agent ,

			  activity_start: MySQL_like_timestamp

	} ) ;


	// also copy these to objRouting

	objRouting[id].agent = agent ;


	objRouting[id].activity_start = MySQL_like_timestamp ;


}




function create_or_change_attributes_plus_sibling_drops_on_progression_van_( 

														id , button , new_class_index , agent , sync ) 
{

	// check if <li> has just incremented to VAN_LOADING

	// alert ( 'create_or_change_attributes_plus_sibling_drops_on_progression_van_( id = ' + id + 

	// 	'  button = ' + button + ' new_class_index = ' + new_class_index + ' agent = ' + agent + ' )' ) ;

	if ( IN( [ MSE_BTN_LFT , '+' ] , button )  

			&&  new_class_index == VAN_LOADING )  {

		
		// We do not do this if attribute agent="" already exists

		if ( !( $( '#'+id ).hasAttr( 'agent' ) ) ) { // attribute "agent" does NOT exist


			record_agent_activity_start( id , agent ) ;


		} // if ( !( $( '#'+id ).hasAttr( 'agent' ) ) ) 



	} // . . && ( new_class_index == VAN_LOADING )


	// check if <li> has just incremented to VAN_LOADED

	if ( IN( [ MSE_BTN_LFT , '+' ] , button )  

			&&  new_class_index == VAN_LOADED ) {



		// get the loader's name from the actual <li> [not passed to fn in this case]

		agent = $( '#'+id ).attr( 'agent' ) ;

		
		// We do not do this if title already contains the sequence "loaded by"

		if ( $( '#'+id ).attr( 'title' ).search( 'loaded by' ) == -1 ) { // title does NOT contain "loaded by"


			// calculate the load time and append to this <li>'s title:-

			//  [CRLF] loaded by [agent] in HH:mm

			append_loaded( id , agent , calc_load_time( id ) ) ;


			// altho not required [we will never undo title], we can copy new title to objRouting

			objRouting[ id ].title = $( '#'+id ).attr( 'title' ) ;

			// turn the class [& what's said in title] of all the siblings to DROP_LOADED

			// If this is live click

			//		there is no need for instructions to be posted

			//				so we "fake" the button as coming from Ajax ( '+' )


			$( '#'+id ).nextAll().each( function() { 

				progress_li( this.id , '+' , DROP_LOADED , '' , sync ) ;

			} ) ;



		} // ( $( '#'+id ).attr( 'title' ).search( 'loaded by' ) == -1 )



	} // . . && ( new_class_index == VAN_LOADED )
		
}




function make_ready_or_revert_van_on_packedness_of( LI_Id , sync ) 
{

	// get the new class index  [ after the action of progress_li() ] 

	//		of the drop whose LI_Id is passed

	new_class_index =  objRouting[LI_Id].class_index ;  //get_class_index( $( '#'+LI_Id ).attr( 'class' ) ) ;


	// calculate LI_Id of the van <li> to which this LI_Id is sibling

	var van_id = objRouting[LI_Id].route_no + '_0' ; // get_route_no( LI_Id ) + '_0' ;


	// get that van's current class index

	sibling_van_curr_index = objRouting[ van_id ].class_index ; // get_class_index( $( '#'+van_id ).attr( 'class' ) ) ;


	// see if all the other drops are DROP_PACKED

	if ( All_other_siblings_are( DROP_PACKED , van_id ) ) { 

		
		// we could turn the van to VAN_READY, but it must currently be VAN_WAITING

		if ( sibling_van_curr_index == VAN_WAITING) {


			// change van's class [& what's said in title] to VAN_READY

			// If this is live click

			//		there IS need for instructions to be posted

			//				( but Ajax will know not to SAVE the instruction

			//						 - which would cause it to propogate into other clients ) 

			progress_li( van_id , MSE_BTN_LFT , VAN_READY , '' , sync ) ;


		} // if ( sibling_van_curr_index == VAN_WAITING)


	} else { // NOT ALL other siblings DROP_PACKED
		

		// - a drop that decrements to DROP_PACKING

		// 	 could interrupt a column of all DROP_PACKED

		// 		requiring that the sibling van reverts to VAN_WAITING


		// NOTE in live click, a drop WILL BE PREVENTED 

		// 		from decrementing DROP_PACKED -> DROP_PACKING 

		// 			if the sibling van has since incremented to VAN_LOADING

		// 	 but in Ajax, something asynchronous may have happened

		// 			so we must test regardless

		if ( new_class_index == DROP_PACKING ) {


			// if the sibling van is at VAN_READY

			//		 it must be reverted back to VAN_WAITING

			if ( sibling_van_curr_index == VAN_READY ) {


				// change the van's class [& what's said in title]

				// If this is live click

				//		there IS need for instructions to be posted

				//				( but Ajax will know not to SAVE the instruction

				//						 - which would cause it to propogate into other clients ) 


				progress_li( van_id , MSE_BTN_RGT , VAN_WAITING , '' , sync ) ;


			} // if ( sibling_van_curr_index == VAN_READY)


		} // if ( new_class_index == DROP_PACKING )


	} // NOT ALL other siblings DROP_PACKED

}


function Is_van( LI_Id ) { // are the last two chars [of the <li>'s id] underscore zero?
	

	return ( LI_Id.slice(-2) == '_0' ) ;

}



// iterate siblings of <li> [id = passed LI_Id]

//		until all gone or one is NOT of [passed] class index

function All_other_siblings_are( class_index , LI_Id ) {


	// set a boolean [outside the .nextAll().each loop] true

	//		so that, if iterations complete without any comparision failures,

	//			 we can automatically return true

	var outside_boolean = true ;

	
	// iterate the siblings with a callback function 

	$( '#'+LI_Id ).nextAll().each( function() {


		// short circuits if any sibling's class index does not match

		// 		what is passed to fn All_other_siblings_are()

		if ( get_class_index( this.className ) != class_index ) {


			outside_boolean = false ; // NOTE this is global to fn All_other_siblings_are() 


			return false ; // short circuits the .nextAll().each loop

		}


	} ) ;


	return outside_boolean ;

}


function new_instruction( id , button , new_class_index , agent ) 
{

	// start off the new instruction

	var lhs = id + button_to_instruction( button ) ;


	// the remainder of the new instruction is usually just the new class index

	//		where new class index = VAN_LOADING (3) or DROP_PICKING (7)

	//			agent will NOT be ''

	return lhs + new_class_index + agent ;

}



function button_to_instruction( button ) 
{
	
	var arr_button = [ '+' , 'f' , '-' , 'r' ] ; // 'r' => "revert" Ctrl+Right : 'Gun' DROP_SHORT

	return arr_button[button] ;

}



function calc_load_time( li_id ) 
{

	// NOTE the following leverages moment.js
	

	// create a MySQL-like timestamp of now

	load_finish = moment().format( "YYYY-MM-DD HH:mm:ss" ) ;


	// get the load start time from the actual <li>

	load_start = $( '#'+li_id ).attr( 'activity_start' ) ; // can ALSO use 

														   // objRouting[li_id].MySQL_like_timestamp	


	// calculate & return the time to load, formatted in familiar hrs and minutes

	var diff = moment.utc( moment( load_finish , "YYYY-MM-DD HH:mm:ss" ).diff( 

				moment( load_start , "YYYY-MM-DD HH:mm:ss" ) ) ).format( "HH:mm" ) ;


	return diff.toString() ;

}


function append_loaded( li_id , loader , load_time ) 
{

	// the following mechanism to append to existing title is suggested by http://api.jquery.com
	
	// $( '#'+li_id ).attr( 'title', function( i , val ) { 


	// 			return val + '&#13;&#10; loaded by ' + loader + ' in ' + load_time ;

	// } ) ;

	append_to_attribute( 'title' , li_id , '\nloaded by ' + loader + '\nin ' + load_time ) ;

}


function append_to_attribute( attribute_name , element_id , additional_text ) {
	

	// the following mechanism to append to existing element attribute

	// 		is suggested by http://api.jquery.com
	
	$( '#'+element_id ).attr( attribute_name , function( i , val ) { 


				return val + additional_text ;

	} ) ;


}
