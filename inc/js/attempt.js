
// 	Routines that attempt to carry out instuctions as returned by Ajax.



// the "main" from the setTimeout loop

function process_ajax( data , sync ) {

	// alert( 'process_ajax( '+data+' )' ) ;

	// there usually are NO instructions

	if ( data == '' ) {


		return ; // short circuits the fn


	} else { // there ARE instructions


		// data is a space delimited string of individual instructions

		// Attempt each one

		arr_data = data.split( ' ' ) ;

	
		for ( var i = 0 ; i < arr_data.length ; i++ ) {

		    
		    attempt( arr_data[i] , sync ) ;

		} 


	} // there ARE instructions

}


//	Essentially, the middle part of the instuction +|f|-|r stipulates

//		 that the progression to a new class index

//				must increment (+) or decrement (-) to this new index.  

// If the middle part = f then we can just 

//			carry out the progession [to stipulated new class index] without hesitation.

//  If e.g. the middle part = "+" then the current class index must be ONE LESS

//													 than the stipulated new class index.




function attempt( instruction , sync ) {


    // create an object of the encoded parts of the instruction

    var objInstr = { LI_Id : '' , op : '' , new_class_index : 0 , agent : '' } ;


    // unravel the encoding of the instruction into objInstr

    regex_decode( instruction , objInstr ) ;


    // test the wisdom of this instruction

    if ( any_point_carrying_out( objInstr.op , objInstr.new_class_index , objInstr.LI_Id ) ) {

    	progress_li( objInstr.LI_Id , objInstr.op /*button*/ , 

    		objInstr.new_class_index , objInstr.agent , sync ) ;

    }


}



// unravel the encoding of the instruction into an instruction object

function regex_decode( instruction , objInstr ) {
	


    var mtch = [] ;

    // Because it's usually absent - and at the END of the instruction - we have to

    //		check for the presence of van loader FIRST



    // after replacing OUT the loader, what's left is called . . .

    var agent_less = instruction ;  // ( but usually it's just the original, unchanged 

    //										instruction )


    // look for any non digits at the end of the instruction

    if ( mtch = instruction.match( /(\D+)$/ ) ) { // only if there IS any non digits at the end . .
    
    	// . . put the match in var agent . . 

    	objInstr.agent = mtch[0] ;


    	// . . replace OUT the match

    	agent_less = instruction.replace( objInstr.agent, '' ) ;
    }


    // match whatever is at the start of the instruction [minus any agent]

    //		up to any of '+' | 'f' | '-' | 'r'

    mtch = agent_less.match( /.+?(?=[+f\-r])/ ) ; 


    // put the match in var LI_Id [ for it is the id of the target <li> ]

    objInstr.LI_Id = mtch[0] ;


    // replace OUT the match

    var agent_less_id_less = agent_less.replace( objInstr.LI_Id , '' ) ;


    // with what remains . . .

    // . . . the first character is the single-char operation

    //			[ "increment to: " | "force to: " | "decrement to: " | "revert to: "  ]

    objInstr.op = agent_less_id_less.substr( 0 , 1 ) ;


    // . . . the remainder is the new class index [ after the operation ]

    objInstr.new_class_index = parseInt( agent_less_id_less.substr(1) ) ;	

}



function any_point_carrying_out( op , new_class_index , LI_Id ) 
{

	// get the <li>s current class index

	var current_class_index =  objRouting[LI_Id].class_index ;  //get_class_index( $( '#'+LI_Id ).attr( 'class' ) ) ;


	switch( op ) {


		case 'f' :


			// it is pointless to force an <li> to an index it already has

			// "force" op is onlt applicable to a drop in DROP_PRINTED

			return ( current_class_index == DROP_PRINTED ) ;

			break ;


		case 'r' :  


			// "revert" op "r" is only applicable to "free" a drop marooned as DROP_SHORT (to DROP_PRINTED)

			// or to "jumping" a stray order from DROP_PRINTED to DROP_PICKED

			return ( ( current_class_index == DROP_SHORT && new_class_index == DROP_PRINTED ) ||

					( current_class_index == DROP_PRINTED && new_class_index == DROP_PICKED )
				 ) ;


		break ;


		case '+' :


			//	we just need to be sure that

			//		- the current index is not DROP_SHORT and

			//		-  the new index is GREATER

			return ( current_class_index != DROP_SHORT && 

					new_class_index > current_class_index ) ;


		break ;


		case '-' :


			return ( !( 

							// the current index cannot be DROP_SHORT

							current_class_index == DROP_SHORT || 

							// the new index must be LESS

							new_class_index >= current_class_index  || 

							
							// decrementing a drop from DROP_PACKED might require to revert

							//		the sibling van back from VAN_LOADING to VAN_WAITING

							//			( if all other siblings are at DROP_PACKED )

							trying_to_interrupt_van_thats_loading( LI_Id , op , current_class_index )  ||


							// starting|finishing van loading cannot be "undone"

							IN( [ VAN_LOADING , VAN_LOADED ] , current_class_index )

				  		) 

			) ;


		break ;


	} // switch


}


