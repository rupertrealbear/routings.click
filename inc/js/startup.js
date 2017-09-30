
OBSRVD_CMFTBL_Y_4_KY_TBL = 1167 ;

LOOP_LENGTH_MILLI_SECS = 5000 ;





function share_updates( sync ) 
{

	// Do the ajax business

	if ( routing_is_loaded() && sync.priority == 'none' ) {


		sync.priority = 'ajax' ; //  <li>s will NOT respond to clicks right now


		// post the contents ofsync.instructions
							
		$.post( sync.ajax_url, { instructions: sync.instructions  } )

				
				.done( function( data ) {

					$( '#ajax_in' ).html( data ) ; // TRACE feature, only applicable while HTML for

													  // span #ajax_in not commented out 

					// alert(data); // TEST when have console error 'mtch is null'

					// if any "posted" instructions do not appear in data, leave them in for next time

					// take a copy of incoming data padded with spaces (for searching)

					sync.compare_data = ' ' + data + ' ' ;

					// initialise conserve property which be new value of instructions
					
					sync.conserve = '' ;

					// split instructions on space (for iteration & searching)
					
					sync.arr_instr = sync.instructions.split( ' ' ) ;


					// iterate instructions and ensure all found in incoming data . . .

					for ( var i = 0 ; i < sync.arr_instr.length ; i++ ) {

						// . . . any NOT found, put in sync.conserve

						if ( sync.compare_data.indexOf( ' ' + sync.arr_instr[i] + ' ' ) == -1 ) {

							sync.conserve += ( ( sync.conserve == '' ) ? '' : ' ' ) + sync.arr_instr[i] ;
						}
					}

					// replace instuctions with sync.conserve

					sync.instructions = sync.conserve ;

					$( '#ajax_out' ).html( sync.instructions ) ; // TRACE feature, only applicable 

													  // while HTML for span #ajax_in not commented out

    				process_ajax( data , sync ) ; // fn in attempt.js

  				} ) ;


  		sync.priority = 'none' ; // <li>s WILL respond to clicks now 



	} else { // no routing in the page's HTML

		
		// ask if there is any routing defined in the server database yet

		$.post( sync.ajax_url , { routing: "q" } )

		 
		 	.done(function( data ) {


		    	if ( data == 'y' ) {  


		    		// stop the loop

		    		clearTimeout( sync.timeout ) ;


		    		// pull the document from the web-server again - NOT from cache

		    		location.reload( true ) ;

		    	}


			} ) ;


	} // no routing in the page's HTML


	// repeat every LOOP_LENGTH_MILLI_SECS milliseconds

	// 		passing the ID value of the timer that is set [var timeout]

	//			to a recursive call to share_updates()

	sync.timeout = setTimeout( function(){ share_updates( sync ) ; } , LOOP_LENGTH_MILLI_SECS ) ; 


}



function routing_is_loaded() 
{	

	// we can tell if the routing is loaded or not 

	// 		by what's inside div #uls

	// When e.g. there is nothing in the database to load

	//		all div #uls has is one child div #buffer 

	//			( used to provide some space at the bottom of the routing - loos wierd, otherwise )

	return ( $( '#uls' ).children().length > 1 ) ;
}


function main() 
{
	
	// HTML title text will tell us which page this is

	var Page = $( 'title' ).html() ; // 'Routing'|'Gun'|'Pack'



	// runs after page is loaded ( i.e. when php has finished any rendering )

	$( window ).on( 'load', function() {


		// prevent context menu when Right Clicking an <li>

		$( document ).on("contextmenu", function( event ){

	        if( event.target.nodeName == "LI" ) {

	        	event.preventDefault() ;
	        }
             
     	} ) ;


     	// create object which will contain the relevant properties of each <li>

     	objRouting = {} ; // the id of each <li> will be a named property of objRouting 

     					  //	which is itself an object

     					  // [copies of] properties of the <li>s can be accessed like:-

     					  //		objRouting[ <li> id in quotes ].[sub property]

     	setup_routing_object( objRouting ) ;

		// create object that will facilitate sync between <li> click event and

		//		 ajax code running on a timer

		var sync = { 


			timeout : null , // the ID value of a timer set in a later call to setTimeout()


			ajax_url : '' , // where to ajax post, set once from div #buffer ajax_url=""

							//		which has php tokens , echoing php script that calulates

							//			the relative url based on page's relative position in 

							//				on the file structure
			

			instructions : '' , // space delimited string of individual instructions of each valid click

								//		since the last ajax post.

								// Get's eraased on ajax .done


			priority : 'none' , // can prevent execution of 

								//		<li> click event [ when = 'ajax' ]

								//	or

								//		ajax posting of sync.instructions [ when = 'click' ]

			compare_data : '' , // somewhere to put copy of incoming data [ from ajax ]

							    //	to compare with sync.instructions

			arr_instr : [] ,	// incoming data [ from ajax ], split on space to iterate thru

								//		instructions, seeing of found in ajax data

			conserve : ''		// what is to be left in sync.instructions for "next time"


		} ;

		// set up click event for any <li> 

		capture_li_click_for( Page , sync ) ; // ( fn in validate_click.js )


		// for reasons that are unclear, we do not always get all the vans rendered

		//		therefore we test if div #uls has one more child than 

		//			the number of rows in table `vans` ( attribute "vans" in div #buffer )

		// Of course, in the first instance, we must drop thru this test if

		//		the number of rows in table `vans` = '0' ( this is passed from server in tokens 

		//													placed in div #buffer vans="" )


		if ( $( '#buffer' ).attr( 'vans' ) != '0' ) {


			if ( parseInt( $( '#buffer' ).attr( 'vans' ) ) != parseInt($( '#uls' ).children().length) - 1 ) {


				// stop the loop identified by the var [timeout] global to this document onload block

				clearTimeout( sync.timeout ) ;

				// pull the document from the web-server again - NOT from cache

				location.reload( true ) ;


			} //  $( '#uls' ).children().length != parseInt( $( '#buffer' ).attr( 'vans' ) ) - 1


		} // $( '#buffer' ).attr( 'vans' ) != '0'



		if ( Page=='Routing' ) {

			// swap between form and <li>s on button mouseup

			swap_between_form_and_li_s_or_submit_on_button_mouseup( sync ) ;

		}

		// jump to <li>s :

		//	'Routing' -> if DIV #uls has > 1 child [ first one is <div>#buffer ]

		//	Otherwise -> go there anyway

		jump_to_li_s_if_there( Page ) ;


		// We need to call share_updates() once 

		//		then it will repeat every LOOP_LENGTH_MILLI_SECS milliseconds 


		// We pass the var [timeout] - global to this document onload block - which is 

		//		the ID value of a timer that will be set in a call to setTimeout() in share_updates() 

		// The url where to post to ajax [ the relative path to php file ]  

		//		was rendered into attribute ajax_path="" of div #buffer on page loading

		sync.ajax_url = $( '#buffer' ).attr( 'ajax_path' ) ; 

		share_updates( sync ) ; 


		// set up click event for big fat "title" - which is actually miles down the page

		$( '#h1_title' ).on('mouseup', function (event) {

			
			$( '#row4' ).toggle() ;
			$( '#row5' ).toggle() ;

		} ) ; 

	} ) ; // page loaded


} // main()



function swap_between_form_and_li_s_or_submit_on_button_mouseup( sync ) 
{

	//  There are three buttons on the Routing page:-

	//		- #repl "Replace Routing"

	//				which submits the encoded routing data pasted into the textarea from Word . . .

   $( 'button' ).mouseup( function( event ) {
	    

	    if (  $( event.target ).attr( 'id' ) == 'repl'  ) {


	    		// kill the Ajax loop 

	    		// NOTE ID value of the timer "timeout"

	    		// 		is global to the Window onload block and so this fn

	    		clearTimeout( sync.timeout ) ;


	    		// submit the form

	    		$( '#repl_rtg' ).submit() ;


	    } else { // button NOT 'repl'


			// . . . the other two buttons in the Routing page are:-

			// 		- #new_routing "Create New Routing" 

			//				which scrolls to top of page ( where form is )

			// 		- #keep "Keep Old Routing" 

			// 				which scrolls the routing and colour key table into view
    
		    $( 'html, body' ).animate( {

		        scrollTop: ( $( event.target ).attr( 'id' ) == 'keep' ) ? OBSRVD_CMFTBL_Y_4_KY_TBL : 1                   

		    } , 1 ) ; // NOTE 1 = millisecs to complete animation


		} // button NOT 'repl'

   } ) ; // button mouseup

}


function jump_to_li_s_if_there( Page ) 
{

	// if <div> #uls has MORE THAN ONE child ( first one is <div>#buffer )

	// 		scroll to 1167 (that's miles down) so that routing comes into view

	//				( it is pulled out of position so as to be clickable )

	var has_routing = routing_is_loaded() ;


	// perform the jump anyway:

	//	In the case of 'Routing'

	// 			jump to routing if its there, else to the top of the page

	//	Otherwise

	//			jump to position of routing [whether it's there or not]

	    var calculated_y_position = OBSRVD_CMFTBL_Y_4_KY_TBL ;

	    if (  !( has_routing ) && Page == 'Routing'  ) {

	    	calculated_y_position = 1 ;
	    }

	    $( 'html, body' ).animate( {

	        scrollTop: calculated_y_position

	    } , 1 ) ; // NOTE 1 = millisecs to complete animation


		// set the visibility of "Keep Old Routing" button according to has_routing

		// NOTE this only matters to Page = 'Routing' 

		//		[ since the form is INVISIBLE otherwise ] so we do the following regardless

		$( "#keep" ).css( 'visibility' ,  ( ( has_routing ) ? 'visible' : 'hidden' )  ) ;


		// Normally, row three of the Key table has an <h1> describing the context

		//		[ for the purposes of demonstration ] so we can easily see which

		//			context applies when different browsers show the same shaped routing

		//					side by side


		// In the case of 'Gun' & 'Pack', the form remains present but invisible 

		//		[to ensure everything appears in the same position]

		// When there is no routing, all there is to see is the colour key

		//		so we go there and change row three to say that there is no routing

		if ( !( has_routing ) ) {

			// row three has an <h1> with id="h1_title"

			$( '#h1_title' ).html( '( No routing in the database )' ) ;
		}

}




