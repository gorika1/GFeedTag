var profileName;
var profileUser;
var profilePass;

$(document).on( 'ready', function(){

	// Si se quiere cambiar algun dato del usuario
	$( '#change-profile' ).on( 'click', function(){
		$('body').block({ 
	    	message: $('#profile-textbox'),
	    	css: {
	    		border : 'none',
	            cursor : 'auto',
	            height: '530px',
	            width: '50%'
	        },
	        overlayCSS:  {
	        	border : 'none',
	            cursor: 'auto'
	        },
	    }); // end $(body).block()

	    $('.blockUI.blockMsg.blockElement').css({
			'top': $(window).height() / 2 - $('.blockUI.blockMsg.blockElement').height() / 2
		});

		getData();
	});	// end (#profile-textbox).click

	// Centra el popup en caso de que cambie el tamaño de la ventana
	$(window).resize(function(){
		$('.blockUI.blockMsg.blockElement').css({
			'top': $(window).height() / 2 - $('.blockUI.blockMsg.blockElement').height() / 2,
			'left': $(window).width() / 2 - $('.blockUI.blockMsg.blockElement').width() / 2

		});
	});

	 // Si se hace click fuera del textbox
    $('body').on( 'click', '.blockUI.blockOverlay', function(){
    	$('body').unblock();
    });

    // Si se hace click en cancelar
	$('.btn-cancel').on('click', function() { 
	    $('body').unblock(); 
	    return false;
	});

	// Si se quiere guardar los datos del perfil
	$( '#profile-save' ).on( 'click', function(){
		// Si los datos se cambiaron
		name = $('#profile-name').val();
		user = $('#profile-user').val();
		pass = $('#profile-pass').val();
		if( name != profileName ||
			user != profileUser ||
			pass != profilePass	)
		{
			// Si las  contraseñas no coinciden
			if( pass != $('#profile-repeat-pass').val() )
			{
				alert( 'Las contraseñas no coinciden' );
				return;
			}
			else
			{
				data = {
					'name' : name,
					'user' : user,
					'pass'	: pass
				};

				// Actualiza los datos en la base de datos
				setProfile( data );

				// Actualiza el nombre de usuario en el header
				$( '#user-name' ).text( name );
			} // end if...else
		} // en if

		// Cierra el popup
		$('body').unblock(); 
	    return false;

	}); // end $( '#profile-save' ).on()
}); // end (document).click


// Obtiene los datos del perfil
function getData() {
	$.ajax({
		url: 'config',
		data: {'get':'profile'},
		dataType: 'json',
		contentType: 'application/x-www-form-urlencoded',
		error: function() {
			alert( 'Ha ocurrido un error' );
		},
		success: function(data) {		
			// Agrega los datos del perfil en el formulario
			drawProfile( data );
		},
		type: 'POST',
		timeout: 10000,
	});
} // end getData

// Establece los datos del perfil
function setProfile( data ) {
	$.ajax({
		url: 'config',
		data: { 'update':'profile', 'data':data },
		contentType: 'application/x-www-form-urlencoded',
		error: function() {
			alert( 'Ha ocurrido un error' );
		},
		type: 'POST',
		timeout: 10000,
	});
} // end getData

// Almacena los datos obtenidos por AJAX
// y los muestra en los inputs
function drawProfile( data ) {
	profileName = data.name;
	$('#profile-name').val( profileName );
	profileUser = data.user;
	$('#profile-user').val( profileUser );
	profilePass = data.pass;
	$('#profile-pass').val( profilePass );
	$('#profile-repeat-pass').val( profilePass );
} // end drawProfile