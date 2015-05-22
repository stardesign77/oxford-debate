(function($) {
	$(function() {
		
		// Check to make sure the input box exists
		if( 0 < $('#initduration-text').length ) {
			$('#initduration-text').datepicker();
		} // end if

		// Check to make sure the input box exists
		if( 0 < $('#endduration-text').length ) {
			$('#endduration-text').datepicker();
		} // end if
		
	});
}(jQuery));