$(document).on( 'ready', function(){
    adaptar();

    $('#bg').block({ 
        message: '<h1 style="font-size:22px">Cargando imágenes. Esto puede tardar unos minutos</h1>', 
        css: { 
            border: '3px solid #a00',
            width: '50%'
        },
        overlayCSS:  { 
            backgroundColor: '#f8f8f8', 
            opacity: 1, 
            cursor: 'wait'
        }, 
    });
            
    getMediaFirstTime();

    setInterval(function(){
        insertar();        
    }, 1000 );

    $(window).resize(function(){
        adaptar();
    });

    $("img").error(function () {
    });
});

function adaptar()
{
    size = $(window).height() - 170;    

    $('#inner').css({
        'height': size,
        'width': size,
        'left': $(window).width() / 2 - size / 2,
        'top': $(window).height() / 2 - size / 2 - 10
    });

    $('#visor img').css({
        'left': size / 2 - $('#visor img').width() / 2,
        'top': size / 2 - $('#visor img').height() / 2,
    });

} // end adaptar

function insertar()
{
    // Genera el src de la imagen a traves del primer
    // hijo de carousel
    src = $('#carousel img:first-child').attr('src');

    // Lo inserta en el background
    obj = $('#outer2 img');
    obj.attr('src', src );
    obj.css({
        'height': $(window).height(),
        'width': $(window).width()
    });

    // Lo inserta en el visor
    $('#visor img').attr('src', src);

    $('#visor img').css({
        'left': size / 2 - $('#visor img').width() / 2,
        'top': size / 2 - $('#visor img').height() / 2,
    });
} // end insertar
