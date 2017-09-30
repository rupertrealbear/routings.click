
// some constants

VAN_WAITING = 1 ;
VAN_READY = 2 ;
VAN_LOADING = 3 ;
VAN_LOADED = 4 ;
DROP_SHORT = 5 ;
DROP_PRINTED = 6 ;
DROP_PICKING = 7 ;
DROP_PICKED = 8 ;
DROP_PACKING = 9 ;
DROP_PACKED = 10 ;
DROP_LOADED = 11 ;

MSE_BTN_LFT = 0 ;
MSE_BTN_CTRL_LFT = 1 ;
MSE_BTN_RGT = 2 ;
MSE_BTN_CTRL_RGT = 3 ;

LOWER = 0 ;
UPPER = 1 ;
ROUTING = 0 ;
GUN = 1 ;
PACK = 2 ;
GOD = 3 ;



// create an object of 

//		- ranges into which the current class index must fall for the given page and click

// 		- the default force|revert indexes for the control clicks: the required current & new indeces

//				based on the context of intPage

//			NOTE since there are no associative arrays in js, we have a certain amount of dead space

//				which will just have zeroes in it

var click_index = { 

	// we can also define lower and upper limits for an allowed current class index

	//		( if current class index is in this range  )


	current_range : [ /* intPage = ROUTING */ [ /* only left click applies */ 

					// can only inc VAN_WAITING->VAN_LOADING->VAN_LOADED 
					/* MSE_BTN_LFT */ [ 0 , VAN_LOADED ] , [ 100, 0 ] , [ 100, 0 ] , [ 100, 0 ] 

			// NOTE no index will ever be in range of 100 . . 0 

			] /* intPage = ROUTING */ ,

			  /* intPage = GUN */ [ 

			  		/* MSE_BTN_LFT */ // cannot inc past DROP_SHORT or past DROP_PICKED
			  		 	[ DROP_SHORT , DROP_PICKED ] ,

			  		/* MSE_BTN_CTRL_LFT */ // can only be forced before | during picking
			  			[ DROP_SHORT , DROP_PICKED ] ,

			  		/* MSE_BTN_RGT */ // cannot dec to DROP_SHORT, must be < DROP_PACKING 
			  			[ DROP_PRINTED , DROP_PACKING ] ,

			  		/* MSE_BTN_CTRL_RGT */ // can only be reverted from DROP_SHORT
			  			[ VAN_LOADED , DROP_PRINTED ]

			  ]  /* intPage = GUN */ ,


			  /* intPage = PACK */ [   

			  		/* MSE_BTN_LFT */ // cannot inc past DROP_PACKED ( must be > DROP_PICKING )
			  			[ DROP_PICKING , DROP_LOADED ] ,

			  		/* MSE_BTN_CTRL_LFT */ // cannot use control left click here
			  			[ 100, 0 ] ,
										
			  		/* MSE_BTN_RGT */ // cannot dec past DROP_PICKED ( must be < DROP_LOADED )
			  			[ DROP_PICKING , DROP_LOADED ] ,
											
			  		/* MSE_BTN_CTRL_RGT */ // can only "revert" to DROP_PICKED from DROP_PRINTED
			  			[ VAN_LOADED , DROP_PICKING ]


			  ]  /* intPage = PACK */ ,

			  /* intPage = GOD */ [   

			  		/* MSE_BTN_LFT */ // cannot inc past DROP_SHORT or past DROP_PACKED
			  			[ DROP_SHORT , DROP_PACKED ] ,

			  		/* MSE_BTN_CTRL_LFT */ // can only be forced before | during picking
			  			[ DROP_SHORT , DROP_PICKED ] ,

			  		/* MSE_BTN_RGT */ // cannot dec past DROP_PRINTED ( must be < DROP_LOADED )
			  			[ DROP_PRINTED , DROP_LOADED ] ,

			  		/* MSE_BTN_CTRL_RGT */ // can "revert" from either DROP_SHORT or DROP_PRINTED
			  			[ VAN_LOADED , DROP_PICKING ]


			  ]  /* intPage = GOD */

			] /* current_range */  ,



	// AlSO NOTE that, because page Control Right Click can "revert" to

	//	 two possible UNADJACENT indeces

	//		 (depending on one of two possible - but adjacent - CURRENT indeces)

	//			- , the referable item under object property "new" is, in fact any array

	//					of usually just one item [TWO, in hte case of intPage = GOD, MSE_BTN_CTRL_RGT ]


	force_revert_new : [ /* intPage = ROUTING */ [  [0] , [0] , [0] , [0]  ] ,  /* i.e. can't use ctrl clicks on this page */

			/* intPage = GUN */ [  [0] , /*MSE_BTN_CTRL_LFT*/ [DROP_SHORT] , [0]  , 

													/*MSE_BTN_CTRL_RGT*/ [DROP_PRINTED] ] ,

			/* intPage = PACK */ [  [0]  , [0]  , [0]  , /*MSE_BTN_CTRL_RGT*/ [ DROP_PRINTED , DROP_PICKED ]  ] ,

			/* intPage = GOD */ [  [0]  , /*MSE_BTN_CTRL_LFT*/ [DROP_SHORT] , [0]  , 

										/*MSE_BTN_CTRL_RGT*/ [ DROP_PRINTED , DROP_PICKED ]  ] ,

			] /* force_revert_new */ 


} // click_index



// A generic function that captures a click on an <li>

//		and validates the wisdom of such a click based on

//			- Left|Middle|Right button

//			- the current "class index" [classname after 's']

//			- which page we are on (passed to function)

function capture_li_click_for( Page , sync ) 
{
	
	// Now that we are in script validate.js, there are some numeric constants

	//		for the different pages we could have: ROUTING=0   GUN=1   PACK=2   GOD=3

	// Lexically speaking, these constants are the upper case of what is in <title></title>

	// Therefore we can easily create the numerical equivalent:-

	intPage = new Function( 'return  ' + Page.toUpperCase() )();

	// alert('capture_li_click_for( ' + Page + ' ) intPage = ' + intPage ) ;


	// NOTE we capture the mouseup event rather than click 

	//		( in order to guarantee capturing right click )

	$('li').on('mouseup', function (event) {

		 // alert( objRouting[ $( event.target ).attr( 'id' ) ].title ) ;
		

		// we short circuit all this if ajax code is running

		if ( sync.priority == 'none' ) { 


			// . . . and so is click_busy:-

			sync.priority = 'click' ; // ajax will not be able to import/export anything 


			// On each mouseup we create an object to pass to validation fns etc

			// NOTE although we could just make this object global

			//		[and then re-initialise on each new click]

			//			there is the problem that global vars, in javascript, are

			//				global TO THE WINDOW (i.e. the browser itself)

			//					so - if somebody opened the routing, gun & pack pages in 

			//						different tabs of the same browser - each page would update

			//							the same object asynchronously (with very unexpected results)

			var objLi = { id : ''  , current_class_index : 0 , button : 0 , page : 0 ,

											new_class_index : 0 , agent : '' , load_time : ''  ,
						 	

				init : function( pssd_id , pss_ind , pssd_btn, pssd_pg ) // assign passed properties
				{ 

					this.id = pssd_id ; 

					this.current_class_index = pss_ind ;

					this.button = pssd_btn ;

					this.page = pssd_pg ; 

				} 


			} ; // var objLi	// initialise object ( or re-initialise after last <li> click )

			

			// We use the new object's init() method to store the <li>'s

			// 		id , the current "index" of its class name , 

			//			a numerical index for the mouse button clicked on it

			//				a numerical index for the page we are on 

			var target_li_id = $( event.target ).attr( 'id' ) ;

			var current_class_index = objRouting[target_li_id].class_index ; // get_class_index( $( event.target ).attr( 'class' ) ) ;

			var button_clicked = get_btn_clkd( event ) ;

			
			objLi.init( target_li_id , current_class_index, button_clicked , intPage ) ;

			// alert('objLi: target_li_id; ' + objLi.id + ' , current_class_index: ' + objLi.current_class_index + 

			// 	' , button: ' + objLi.button + ' , intPage: ' + objLi.page ) ;


			// we now check if we can do anything with that

			validate_click( objLi , sync ) ; // button_clicked ,  target_id , current_class_index, intPage ) ;

			
			sync.priority = 'none' ; // ajax can import/export on the next timeout ( 5 second interval )


		} // if ( sync.priority == 'none' )


	} ) ; // $("li").mouseup()


} // fn capture_li_click_for( Page )


function validate_click( objLi , sync )  // button , target_li_id , current_class_index, intPage ) 
{

	if ( can_progress( objLi ) ) {

		//	( any intended new class index will be put in object objLi )

		// alert('CAN progress  objLi.page: ' + objLi.page );

		if ( objLi.page == ROUTING || is_van_god( objLi ) ) {


			// all we can do [ in the top-level display ] is

			//		click a van to substantiate that loading has begun|finished 

			van_click( objLi , sync ) ;


		} else { // objLi.page != ROUTING


			// alert('We DIDNOT van_click(objLi) - about to progress_li()') ;

			if ( objLi.button == MSE_BTN_LFT && objLi.new_class_index == DROP_PICKING ) {


				// get the name of the picker ( or abort )

				progress_with_agent_maybe( objLi , sync , "Pick" ) ;


			} else { // NOT starting a pick

				
				// also check if we are skipping over DROP_PRINTED with Contrl Right Click

				//		( which would mean NOT taking picker's name - as "how long do they take?" )


				var last_minute_hold_up = false ;

				if ( objLi.button == MSE_BTN_CTRL_RGT && objLi.new_class_index == DROP_PICKED ) {

					// confirm they saw the picked order

					last_minute_hold_up = !( confirm( "Did you See the Picked Order?" ) ) ; 

				}


				if ( !( last_minute_hold_up ) ) {

					
					// change the <li>'s class [& what's said in title] ( issuing instructions )

					progress_li( objLi.id , objLi.button , objLi.new_class_index , objLi.agent , sync ) ;


				} // !( last_minute_hold_up )


			} // NOT starting a pick


		} // objLi.page != ROUTING 


	} // if ( can_progress( objLi ) )


}


// When script is picking up instructions from Ajax,

//		the only question is:-

//	"IS THERE ANY POINT?" (i.e. never mind the context, based on the index now,
//								if the operator is, say "-" [to a new index], 
//									are we currently one above that specified new index ?)

// Any force or reverts are carried out anyway 
//							UNLESS the default force index = the current index

// THEREFORE, there is no point trying to merge functionality

//		 between picking up Ajax & live mouse clicks


// test fn to make string of nested array ( with square brackets where helpful )

function possible_arr_contents(a) {
	
	var out = '' ;

	if ( Array.isArray(a) ) {

		for (var i = 0; i < a.length; i++) {
			
			out += ( ( out == '' ) ? '' : ' , ' ) + possible_arr_contents( a[i] ) ;
			
		}

		return '[ ' + out + ' ]' ;

	} else { return a ; }

}


// specifically tests for "+" button on the two valid van indeces ( when in "God Mode" )

function is_van_god( objLi ) 
{
	
	return ( objLi.page == GOD && objLi.button == MSE_BTN_LFT && 


			( IN( [ VAN_READY , VAN_LOADING ] , objLi.current_class_index ) )

		 ) ;
}



function can_progress( objLi ) 
{	

	// give current class index a shorter name for use in condition formulae

	var cci = objLi.current_class_index ; 

	 
	// use the click_index object to establish if the current class index is 

	// 		within the allowed range for the page and click

	// reference the array of upper & lower limits	

	var arr_current = click_index['current_range'][objLi.page][objLi.button] ;

	var too_low = arr_current[LOWER] ;

	var too_high = arr_current[UPPER] ;

	current_is_within_range = ( too_low < cci && cci < too_high ) ;


	// if we are in "God Mode", it only checks the drop indeces . . .

	if ( ( !( current_is_within_range ) ) && objLi.page == GOD ) {


		// . . . so, if not within drop range, try van range

		current_is_within_range = is_van_god( objLi ) ;

	}


	// also check we are not undoing a column of DROP_PACKED once the van is loading

	var can = ( current_is_within_range &&
	
				!( trying_to_interrupt_van_thats_loading( objLi.id  , objLi.button , 
	
																	objLi.current_class_index ) ) ) ;

	
	if ( can ) {

		switch( objLi.button ) {


			case MSE_BTN_LFT :
			case MSE_BTN_RGT :


				// to save mucking about with + [which is usually a text concatenator]

				//	 we just start by assigning the current index to the new

				objLi.new_class_index = cci ;


				// we now perform either a decrement | increment on the new index

				//		based on the button

				if ( objLi.button == MSE_BTN_LFT ) {


					objLi.new_class_index++ ;


				} else {


					objLi.new_class_index-- ;

				}

			break ;

			case MSE_BTN_CTRL_LFT :
			case MSE_BTN_CTRL_RGT :


				// reference the calculated "force" | "revert" value in the click_index object

				// alert( possible_arr_contents( click_index['force_revert_new'] ) ) ;

				var arr_new = click_index['force_revert_new'][objLi.page][objLi.button] ;

				// alert('arr_new: ' + possible_arr_contents(arr_new) );

				if ( arr_new.length == 1 ) {


					objLi.new_class_index = arr_new[0] ;


				} else { // two possible new class indeces


					objLi.new_class_index = ( ( cci == DROP_SHORT ) ? DROP_PRINTED : DROP_PICKED ) ;


				} // two possible new class indeces


			break ;


		} // switch( objLi.button )


	} // if ( can )


	return can ;

}



function van_click( objLi , sync ) 
{


	switch( objLi.current_class_index ) {


		case VAN_READY :


	    	// ask who is loading and progress the <li> click with the agent

	    	//		provided they enter valid name | initials [ no dots or spaces ]
		
			progress_with_agent_maybe( objLi , sync , "Load" ) ;


		break ;


		case VAN_LOADING :


		    if ( confirm( "Finished Loading?" ) ) {

		    		// change the <li>'s class [& what's said in title] plus append a new instruction

		    		progress_li( objLi.id , objLi.button , objLi.new_class_index , objLi.agent , sync ) ;

			}  


		break ;

	} // switch


}   



function progress_with_agent_maybe( objLi , sync , verb ) // verb = "Pick" | "Load"
{

	var user_bottles_it = false , li_progressed = false ;

	var route = objRouting[ objLi.id ].route_no + " " ;

	var drop_fraction = " " + route + " / " + objRouting[ objLi.id ].position + " " ;

	var target = ( verb == "Pick" ) ? drop_fraction : " Van " + route ;

	var original_prompt_text = "Who is " + verb + "ing" + target + "?\n\n" ;

	var agent = '' ; 

	var WhiteListPattern = new RegExp( "^[a-zA-Z\.]{1,}$" ) ; // whole string containing only 

																 //		letters or period 

	var prompt_blacklist = "Only Letters or Periods Wanted\n\nNo Numbers, Spaces etc\n\n" ;


	if ( verb == "Load" ) { // could be TWO people

		WhiteListPattern = new RegExp( "^[a-zA-Z&\.]{1,}$" ) ; // whole string containing only 

																 //		letters or period or "&"

		prompt_blacklist = "Only Letters or Periods Wanted\n\n" +

								'No Numbers, Spaces etc except "&" ( If Two People )\n\n' ;

	}

	var prompt_text = original_prompt_text ;

	
	// Set up a loop to ensure that only characters in the white list are entered

	// clicking Cancel or entering nothing is taken to mean "forget it" (i.e. "not really picking|loading yet")

	//		so such things drop thru ( as does receipt of viable entry 

	//			which is then used in progression of <li> click )

	while ( !( user_bottles_it || li_progressed ) ) {


		// ask who is loading

		agent = prompt( prompt_text ) ; 


		if ( IN( [ '' , null ] , agent ) ) { // nothing entered or clicked Cancel

			
			user_bottles_it = true ; // while() loop will end


		} else { // SOMETHING entered


			if ( WhiteListPattern.test( agent ) ) { //input IS all letters


	    		// assign upper case agent to object 

	    		objLi.agent = agent.toUpperCase() ;


	    		// change the <li>'s class [& what's said in title] plus append a new instruction

	    		//		( this time with something in agent )

	    		progress_li( objLi.id , objLi.button , objLi.new_class_index , objLi.agent , sync ) ;


				li_progressed = true ; // while() loop will end


			}  else { // input NOT in the white list


						prompt_text = prompt_blacklist + original_prompt_text ;


				// booleans user_bottles_it and li_progressed are

				//		 both still false, so while loop will continue


			} // input NOT in the white list


		} // SOMETHING entered


	} // while ( !( user_bottles_it || progressed ) )	
		
}


// quick utility fn to test if 2nd parameter is in the [array] 1st parameter

function IN( arr , item ) 
{
 	return ( arr.indexOf( item ) > -1 ) ;
 } 


// a drop CANNOT be decremented DROP_PACKED -> DROP_PACKING 

//		if the sibling van has since incremented to VAN_LOADING (or even to VAN_LOADED)

//				( if at VAN_LOADED, all the sibling drops would be at DROP_LOADED anyway )

function trying_to_interrupt_van_thats_loading( LI_Id , button , current_class_index )
{


	// check if <li> being decremented DROP_PACKED -> DROP_PACKING

	if ( IN( [ MSE_BTN_RGT , '-' ] , button )  // being decremented

				&& current_class_index == DROP_PACKED )  {

		
		// get the sibling van id [and its class index]
		
		var sibling_li_van_id = get_route_no( LI_Id ) + '_0' ;


		var sibling_li_van_index = objRouting[sibling_li_van_id].class_index ; // get_class_index( $( '#'+sibling_li_van_id ).attr( 'class' ) ) ;


		return ( sibling_li_van_index == VAN_LOADING ) ;


	} else {

		return false ;
	}
}


// NOTE this is not USED . . yet

function instruction_to_button( instruction ) 
{
	
	// make an array of the possible single-char instructions

	var arr_button = [ '+' , 'f' , '-' , 'r' ] ; // 'r' => "revert" Ctrl+Right : 'Gun' DROP_SHORT


	return arr_button.indexOf( instruction ) ;

}




function get_btn_clkd( event )
{
	
	switch( event.which ) {

		case 1: // left button


				return ( event.ctrlKey ) ?  MSE_BTN_CTRL_LFT : MSE_BTN_LFT ;

		break;

		case 3: // right button    MSE_BTN_CTRL_RGT


			return ( event.ctrlKey ) ?  MSE_BTN_CTRL_RGT : MSE_BTN_RGT ; 

		break;

	} // switch

}


function get_class_index( classname ) 
{

	// make a number out of the remainder of the class name (after "s")

	return parseInt( classname.substr(1) ) ;

}


function get_route_no( LI_Id ) 
{
	// match whatever is at the start of LI_Id up to "_" 

    var mtch = LI_Id.match( /.+?(?=_)/ ) ;

    return mtch[0] ;

} 


// NOTE not actually USED . . yet

function get_LI_index( LI_Id ) 
{
	
	// look for any non digits at the end of LI

	mtch = LI_Id.match( /(\d+)$/ ) ;

	return mtch[0] ;

}

/*******************************************************************************************************
BELOW IS THE CONTENTS OF PROGRESS.JS - SEE IF THIS MAKES VALIDATE_CLICK.JS DISAPPEAR
******************************************************************************************************/

