
jQuery(document).ready(function() {

    /*
        Background slideshow
    */
    $.backstretch([
      "client/images/backgrounds/1.jpg"
    , "client/images/backgrounds/2.jpg"
    , "client/images/backgrounds/3.jpg"
    ], {duration: 3000, fade: 750});

    /*
        Tooltips
    */
    $('.links a.home').tooltip();
    $('.links a.blog').tooltip();


});


