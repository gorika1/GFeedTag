$( document ).on( 'ready', function(){
	$( '#submit-form' ).on( 'click', function( e ){
		e.preventDefault();
		var user = $( '#user' ).val();
		var pass = $( '#pass' ).val();

		$.ajax({
			url: '',
			data: {'ajax':'true','login':'true', 'user': user, 'pass': pass },
			contentType: 'application/x-www-form-urlencoded',
			dataType: 'text',
			error: function() {
				alert( 'Ha ocurrido un error' );
			},
			success: function( data ) {				
				if( data != 'false' )
				{
					window.location = 'admin';	
				}
			},
			type: 'POST',
			timeout: 10000,
		});
	});
});

feedBack = function(){
	$.blockUI({ 
  		css: { 
	        border: 'none', 
	        padding: '15px', 
	        backgroundColor: '#000', 
	        '-webkit-border-radius': '10px', 
	        '-moz-border-radius': '10px',
	        'border-radius': '10px',
	        opacity: .5, 
	        color: '#FFF'
   	 	},
   	 	message:  '<h2 id="blockui-message" style="font-size: 20px">Autenticando...</h2>',
    });
} // end feedBack