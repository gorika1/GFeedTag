var element; // Guarda los nodos en los que se esta trabajando
var idHashtag; // ID del hashtag a editar o eliminar
var hashtag;

$(document).on( 'ready', function(){

	$( '.left-nav #config' ).addClass( 'current' );

	// Acordeon de Palabras censuradas
	iniciarAcordeon();

	$("#black-list").mCustomScrollbar({
		theme:"3d-thick",
		scrollButtons:{
          enable:true
        },
        scrollInertia: 0
	});
	
	// Si se hace click para agregar un hashtag
	$('#hashtag-add').on( 'click', function(){
		hashtag = $('#hashtag-input').val();
		// Borra los espacios
		hashtag = hashtag.replace(/ /g, '');

		// Si hay un hashtag para agregar a la base de datos
		if ( hashtag.length > 0 )
		{
			// Agrega el hashtag en la base de datos y en el DOM
			addHashtagInDB( hashtag );
		}
		else
		{
			// Reestablece el valor del input y hace focus en el
			$('#hashtag-input').val('');
			$('#hashtag-input').focus();
		}
		
	});

	// Si se presiona el boton para editar un hashtag
	$('#hashtag-list').on( 'click', '.hashtag-edit', function() {
	    editHashtag( this );
    });
 
	// Si se hace click para eliminar un hashtag
    $('#hashtag-list').on( 'click', '.hashtag-delete', function() {
	    deleteHashtag( this );
    });

    // Si se hace click para agregar un palabra censurada
    $('#black-list').on( 'click', '.word-delete', function() {
	    deleteWord( this );
    });

    // Si se hace click fuera del textbox
    $('body').on( 'click', '.blockUI.blockOverlay', function(){
    	$('body').unblock();
    });

	/*
		EVENTOS DEL TEXTBOX PARA MODIFICAR O ELIMINAR UN HASHTAG
		EN LOS TEXTBOXES
	*/
	// Si se da click en guardar
	$('.hashtag-save').on( 'click', function() {
		// Modifica el texto
	    element.text(  $('div#hashtag-textbox .form-control').val() );
	    // Guarda en la base de datos
	    updateHashtagInDB( $('div#hashtag-textbox .form-control').val() );
	    // Cierra la ventana
	    $('body').unblock();
	});

	// Si se hace click para confirmar la eliminación
	$('.hashtag-delete-confirm').on( 'click', function(){
		deleteHashtagInDB();
		$('td#' + idHashtag ).parent().remove();
		$('body').unblock();
	});

	// Si se hace click en cancelar
	$('.hashtag-cancel').on('click', function() { 
	    $('body').unblock(); 
	    return false;
	});

});


/*
	FUNCIONES PARA AGREGAR, EDITAR Y BORRAR HASHTAGS
*/

function addHashtag( idHashtag, hashtag ){
	//console.log( idHashtag );
	$('#hashtag-list').append( '<tr>' +
							    '<td id="' + idHashtag + '">' + hashtag + '</td>' +                  
							    '<td class="td-actions">' +
							        '<a class="btn btn-small btn-success hashtag-edit">' +
							            '<i class="fa fa-edit"> </i>' +
							        '</a>' +
							        '<a class="btn btn-danger btn-small hashtag-delete added">' +
							            '<i class="fa fa-times"> </i> ' +
							        '</a>' +
							    '</td>' +
							'</tr>'
							);
	$('#hashtag-input').val('');
	$('#hashtag-input').focus();
}

function editHashtag( thisObj ){
	$('body').block({ 
    	message: $('#hashtag-textbox'),
    	css: {
    		border : 'none',
            cursor : 'auto',
            height: '300px',
            position: 'fixed',
            width: '50%'
        },
        overlayCSS:  {
        	border : 'none',
            cursor: 'auto'
        },
    });

    // Obtiene el valor del hashtag y lo pone el textbox
    idHashtag = $(thisObj).parent().prev('td').attr( 'id' );
    element = $(thisObj).parent().prev('td');
    hashtag = element.text();
    $('div#hashtag-textbox .form-control').val( hashtag );
    $('div#hashtag-textbox .form-control').focus();

    // Centra la ventana de dialogo
    $('.blockUI.blockMsg.blockElement').css({
    	'top': $(window).height() / 2 - 150
    }); 

 } // end editHashtag


 function deleteHashtag( thisObj ){
	$('body').block({ 
    	message: $('#hashtag-del-textbox'),
    	css: {
    		border : 'none',
            cursor : 'auto',
            height: '300px',
            position: 'fixed',
            width: '50%'
        },
        overlayCSS:  {
        	border : 'none',
            cursor: 'auto'
        },
    });

    idHashtag = $(thisObj).parent().prev('td').attr( 'id' );

    // Centra la ventana de dialogo
    $('.blockUI.blockMsg.blockElement').css({
    	'top': $(window).height() / 2 - 150
    }); 

 } // end deleteHashtag


/*
	FUNCIONES QUE MODIFICAN HASHTAGS EN LA BASE DE DATOS
*/
function addHashtagInDB( hashtag ) {

 	$.ajax({
		url: '',
		data: {'add':'Hashtag', 'hashtag':hashtag},
		dataType: 'text',
		contentType: 'application/x-www-form-urlencoded',
		error: function() {
			alert( 'Ha ocurrido un error' );
		},
		success: function(data) {			
			// Agrega el hashtag al DOM en el listado
			addHashtag( data, hashtag );
		},
		type: 'POST',
		timeout: 10000,
	});
}

 function updateHashtagInDB( hashtag ) {
 	$.ajax({
		url: '',
		data: {'update':'Hashtag', 'idHashtag':idHashtag, 'hashtag':hashtag},
		contentType: 'application/x-www-form-urlencoded',
		error: function() {
			alert( 'Ha ocurrido un error' );
		},
		type: 'POST',
		timeout: 10000,
	});
}

function deleteHashtagInDB() {
 	$.ajax({
		url: '',
		data: {'delete':'Hashtag', 'idHashtag':idHashtag},
		dataType: 'text',
		contentType: 'application/x-www-form-urlencoded',
		error: function() {
			alert( 'Ha ocurrido un error' );
		},
		type: 'POST',
		timeout: 10000,
	});
}


/*
	FUNCIONES PARA AGREGAR, VER Y BORRAR PALABRAS AL BLACKLIST
*/

function iniciarAcordeon() {
	// Oculta el div que contiene la lista
	$('#censured-words').hide();
	var sw = false;

	// Si se hace click en el titulo (el div)
	$('div#accordion').on('click', function() {
		$div = $(this).next(); // Guarda el div que contiene la lista

	 	// Si la lista estaba oculta
	    if ( ! sw ) {
	    	// Cambia el icono
	    	$('#accordion h3 i').removeClass( 'fa-caret-down' );
	    	$('#accordion h3 i').addClass( 'fa-caret-up' );
	    	// Despliega la lista
	        $div.slideToggle();
	        sw = true;	        
	    } else {
	    	$('#accordion h3 i').removeClass( 'fa-caret-up' );
	    	$('#accordion h3 i').addClass( 'fa-caret-down' );
	       $div.slideToggle();
	       sw = false;
	    }
	});
} // end iniciarAcordeon


function deleteWord( thisObj ) {
	var id = $(thisObj).parent().prev().attr('id-word');
	$(thisObj).parent().parent().remove();
	deleteWordInDB( id );
}

/*
	FUNCIONES QUE MODIFICAN HASHTAGS EN LA BASE DE DATOS
*/
function deleteWordInDB( idWord ) {
 	$.ajax({
		url: '',
		data: { 'delete':'Word', 'idWord':idWord },
		dataType: 'text',
		contentType: 'application/x-www-form-urlencoded',
		error: function() {
			alert( 'Ha ocurrido un error' );
		},
		type: 'POST',
		timeout: 10000,
	});
}
