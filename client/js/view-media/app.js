var oMedia = {}; // guarda las imagenes

function getPhotos() {
    setInterval( getMedia, 120000 );
    setInterval( getMediaDeleted, 30000 );
}
// Guarda los datos en un json
// en la primera carga del sistema
function getMediaFirstTime()
{
	$.ajax({
		data: { 'ajax':'true','get':'Photos', 'lastTime':1, 'lastPos':0  },
		contentType: 'application/x-www-form-urlencoded',
		dataType: 'json',
		ifModified: false,
		processData: true,
		error: function(){
			alert( 'Un error ha ocurrido. Por favor recarge la página');
		},
		success: function( data ) {
			// Si se devolvieron datos
			if( data.media )
			{ 	
				// inserta los atributos last-time y last-pos
				$( '#last-time' ).attr( 'time', data.lastTime );
				$( '#last-time' ).attr( 'last-pos', data.lastPos );

				oMedia = data.media;

				drawPhotos( true );

				setTimeout(function(){
					$('#bg').unblock();
					initCarousel();
					getPhotos();
				}, 4000);
	
			}
			// Si se devolvio un codigo de error
			else if( data.error ){
				// Si el error se produce porque no hay un hashtag configurado
				if( data.error === 1 )
				{
					$('#bg').block({ 
				        message: '<h1 style="font-size:22px">Debe añadir por lo menos un hashtag en la configuración</h1>', 
				        css: { 
				            border: '3px solid #a00',
				            width: '50%'
				        },
				        overlayCSS:  { 
				            backgroundColor: '#f8f8f8', 
				            opacity: 1, 
				            cursor: 'wait'
				        }, 
				    }); // end $('#bg').block()
				} // end if

			} // end if
			// Si data.media fue null
			// no hay datos que mostrar
			else 
			{
				$('#bg').block({ 
			        message: '<h1 style="font-size:22px">Aún no hay imágenes con los hashtags configurados</h1>', 
			        css: {
			        	backgroundColor: '#AA3939', 
			            color: '#fff',
			            border: '3px solid #a00',
			            width: '50%'
			        },
			        overlayCSS:  { 
			            backgroundColor: '#f8f8f8', 
			            opacity: 1, 
			            cursor: 'wait'
			        },
				 });
				$('.blockUI.blockMsg.blockElement h1').css({ 'color': '#f8f8f8' });
			}// end if...else
		},
		type: 'POST',
		timeout: 120000
	});
} // end getMediaFirsTime

//Obtiene las ultimas fotos que aun no fueron cargadas
// en el objeto JSON y los carga en la cola
function getMedia( options ) 
{
	//get the time of the last media uploaded
	time = $( '#last-time' ).attr( 'time' );
	lastPos = $( '#last-time' ).attr( 'last-pos' );

	$.ajax({
		data: {'get':'Photos', 'lastTime':time, 'lastPos':lastPos  },
		contentType: 'application/x-www-form-urlencoded',
		dataType: 'json',
		ifModified: false,
		processData: true,
		success: function( data ) {

			if( data.media )
			{ 				
				$( '#last-time' ).attr( 'time', data.lastTime );

				$.each( data.media, function( index, item ){
					/*pos = oMedia.length;
					oMedia[pos] = { 'URL': item.URL };
					oMedia.length = oMedia.length + 1;*/
					drawPhotos( false, data.media );
				});						
				
			}
		},
		type: 'POST',
		timeout: 45000
	});	
} // end getMedia

// Obtiene las url de las imagenes borradas en el admin
// para poder quitarlas del DOM
function getMediaDeleted( options ) 
{
	$.ajax({
		data: {'get':'PhotosDeleted' },
		contentType: 'application/x-www-form-urlencoded',
		dataType: 'json',
		ifModified: false,
		processData: true,
		success: function( data ) {
			if( data )
			{
				// Por cada url devuelta
				for( i in data )
				{
					lastTimeElem = $('#last-time' );
					element = $('#carousel img[src="' + data[ i ] + '"]' );
					// Elimina la imagen en el DOM
					lastPos = lastTimeElem.attr( 'last-pos' );

					if( lastPos == element.attr( 'pos' ) )
						lastTimeElem.attr( 'last-pos', lastPos - 1 );

					element.remove();
				} // end for
							
			} // end if
		},
		type: 'POST',
		timeout: 45000
	});	
} // end getMediaDeleted

function drawPhotos( firstTime, data )
{
	if( firstTime )
	{
		$.each( oMedia, function(index, item) {
			 $('#carousel').append( '<img src="' + item.URL + '" user="' + item.screenName + '" text ="' + parseText( item.text ) + '" pos="' + item.position + '" from="' + item.from + '" />' );
		});
	} // end if
	else
	{
		$.each( data, function(index, item) {
			 $('#carousel img[pos="' + ( parseInt( item.position ) - 1 ) + '"]').after( 
			 	'<img text ="' + parseText( item.text ) + '" src="' + item.URL + '" user="' + item.screenName + ' " pos="' + item.position + '" from="' + item.from + '" />' 
			 );
		});
	} // end if...else
	
} // end drawPhotos


function initCarousel()
{
	var obj = $('#carousel img:first-child');
	$('#screen-name').text( obj.attr( 'user' ) );
	$('#text').html( obj.attr( 'text' ) );
	drawIcon( obj.attr( 'from' ) );
	drawData();

	$('#carousel').carouFredSel({
        items: {
            visible: 1,
            width: 'variable',
            height: 'variable'
        },
        scroll: {
            fx: 'none',
            timeoutDuration: 15000
        }
 
    });
} // end initCarousel

function drawData()
{
	setInterval( function(){
		var obj = $('#carousel img:first-child');
		
			drawIcon( obj.attr('from') );
			$('#screen-name').text( '@' + obj.attr( 'user' ) );
			$('#text').html( obj.attr( 'text' ) );
	}, 500)
} // end drawData

function drawIcon( from )
{
	if( from == 1 ) // Si viene de Twitter
	{
		$('.social-icon img.visible').removeClass('visible');
		$('.social-icon .fa').addClass('fa-twitter');
	}
	else // Si viene de Instagram
	{
		$('.social-icon img').addClass('visible');
		$('.fa.fa-twitter').removeClass('fa-twitter');
	}
} // end drawIcon
