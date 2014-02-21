
$(document).ready( function() {

	$( 'a[id|="list-delete"]' ).on( 'click', function( event ){

		event.preventDefault();
		url = $(this).attr("href");

	    bootbox.dialog( "Are you <strong>REALLY</strong> sure you want to delete this patch panel port?"
                + "<br /><br />This is NOT the way to free up a port for reuse!", [{
	    	"label": "Cancel",
	    	"class": "btn-primary"
	    },
	    {
	    	"label": "Delete",
	    	"class": "btn-danger",
	    	"callback": function() { document.location.href = url; }
	    }]);

    });

});




