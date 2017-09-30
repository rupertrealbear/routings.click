
// We have no js utility library, but this would go in it

// It creates a method on jquery selector which tests for existence [value or not] of a named attribute

// call like if( $( [selector] ).hasAttr( [attribute name] ) )

$.fn.hasAttr = function( name ) {

    var attr = $( this ).attr( name ) ;

   	return ( typeof attr !== typeof undefined && attr !== false ) ;

} ;



function setup_routing_object( objRouting ) 
{

	// iterate thru all <li>s in the DOM

	$( 'li' ).each( function( index ){


		// get the id attribute from current <li>

		var li_id =  $( this ).attr( 'id' ) ;


		// also get class name

		var classname = $( this ).attr( 'class' ) ;


		// set class_index by number from remainder of class name

		var li_class_index = parseInt( classname.substr(1) ) ;


		// derive route number from <li> id . . .

		// match whatever is at the start of <li> id up to the "_" 

    	var mtch = li_id.match( /.+?(?=_)/ ) ;

    	var route = mtch[0] ;


    	// . . . and derive the LI index 

		// look for any non digits at the end of LI

		mtch = li_id.match( /(\d+)$/ ) ;

		var li_index = mtch[0] ;

		// get the position ( if it's a drop )

		var position = '' ;

		if ( li_index != '0' ) { // it's a drop


			position = $( this ).html() ;
		}


		// get the <li>'s title

		var title = $( this ).attr( 'title' ) ;


		// if <li> is a van at VAN at status VAN_LOADING (3)

		//		the loader(s) will be contained in attribute agent=""

		//			and the loading start time will be in attribute activity_start=""

		// ALSO

		// if <li> is a drop and picking has commenced (status > 6)

		//		the picker will be contained in attribute agent=""

		//			and the picking start time will be in attribute activity_start=""

		var agent = '' , start_time = '' ;

		// NOTE only a van could have status 3 and only a drop could have status > 6

		if ( li_class_index == 3 || li_class_index > 6 ) {


			agent = $( this ).attr( 'agent' ) ;


			start_time = $( this ).attr( 'activity_start' ) ;

		}


	    objRouting[ li_id ] = {

 
	        class_index : li_class_index ,

	        route_no : route , // NOTE this is a STRING [that looks like a number]

	        li_index : li_index , // NOTE this is a STRING [that looks like a number]

	        position : position , // NOTE this is a STRING [that looks like a number]

	        title : title , 

	        agent : agent , // loader or picker

	        activity_start_timestamp : start_time // loading|picking start timestamp


	    } ;				

	

	} ) ;

}
