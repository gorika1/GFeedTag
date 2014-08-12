var idMedia;
var from;
var urlMedia;

$(document).on( 'ready', function(){
	$( '.left-nav #gallery' ).addClass( 'current' );

	$("#media-container").mCustomScrollbar({
		theme:"3d-thick",
		scrollButtons:{
          enable:true
        },
        scrollInertia: 0
	});

	// Si se hace click para eliminar una foto 
	$('#media-table .media-delete').on( 'click', function(){
        idMedia = $(this).attr( 'id-media' );
        from = $(this).attr( 'from' );
        urlMedia = $(this).parent().siblings('.media-photo').children().attr( 'src' );
		deleteMedia( $(this) );
	});

	// Si se hace click fuera del textbox
    $('body').on( 'click', '.blockUI.blockOverlay', function(){
    	$('body').unblock();
    });

    /*
        EVENTOS DEL TEXTBOX PARA MODIFICAR O ELIMINAR UN PUBLICACION
        EN LOS TEXTBOXES
    */
    // Si se hace click para confirmar la eliminación
    $('.media-delete-confirm').on( 'click', function(){
        deleteMediaInDB();
        $('body').unblock();
        // Borra la fila correspondiente en la tabla
        $('a[id-media="' + idMedia + '"][from="' + from + '"]').parent().parent().remove();
    });

    // Si se hace click en cancelar
    $('.media-cancel').on('click', function() { 
        $('body').unblock(); 
        return false;
    });
		
});


// POPUP PARA CONFIRMAR  LA ELIMINACIÓN
function deleteMedia( thisObj ) {
	$('body').block({ 
    	message: $('#media-del-textbox'),
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

    // Centra la ventana de dialogo
    $('.blockUI.blockMsg.blockElement').css({
    	'top': $(window).height() / 2 - 150
    });
} // end deleteMedia


function deleteMediaInDB() {
    $.ajax({
        url: '',
        data: {'delete':'media', 'idMedia':idMedia, 'from': from, 'urlMedia':urlMedia},
        dataType: 'text',
        contentType: 'application/x-www-form-urlencoded',
        error: function() {
            alert( 'Ha ocurrido un error' );
        },
        type: 'POST',
        timeout: 10000,
    });
} // end deleteMediaInDB