$( document ).on( 'ready', function(){
	$( '#submit-form' ).on( 'click', function( e ){
		e.preventDefault();
		var user = $( '#user' ).val().trim();
		var pass = $( '#pass' ).val().trim();

		// Comprueba que se haya ingresado datos
		if( user == '' )
		{
			alert( 'Ingrese un nombre de usuario' );
			return;
		}
		else if( pass == '' )
		{
			alert( 'Ingrese la contraseña' );
			return;
		}

		// Realiza la consulta a la base de datos
		$.ajax({
			url: '',
			data: {'ajax':'true','login':'true', 'user': user, 'pass': pass },
			contentType: 'application/x-www-form-urlencoded',
			dataType: 'text',
			error: function() {
				alert( 'Ha ocurrido un error' );
			},
			success: function( data ) {
				// Si se creo la sesión		
				if( data === 'true' )
				{
					window.location = 'admin/dashboard';	
				}
				else
				{
					alert( 'Usuario y contraseña incorrectos' );
				}
			},
			type: 'POST',
			timeout: 10000,
		});
	});
});