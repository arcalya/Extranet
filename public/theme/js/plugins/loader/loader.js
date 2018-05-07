// $('aside nav>ul.nav>li>ul.nav>li>a').loader({container: 'div.container>main'});

/**
 * Activates a loader gif animation in an element
 * 
 * @return void
 */
(function( $ ){
    
    $.fn.loader = function( options  ){
        
        var settings = $.extend({

            container: 'div.container main>div.row section'

        }, options );
        
        var elementHref   = $(this);
        
        elementHref.click(function(){
            
            $( settings.container + '>*' ).animate({ opacity:0 });

            $( settings.container ).addClass( 'ajaxload' );
        });
           
    };
    
}( jQuery ));


$( document ).on('ready', function(){
   
   
    $('aside nav>ul.nav>li>ul.nav>li>a').loader({ container: 'div.container>main' }); // Uses loader plugins
    
    $('.nav-tabs>li>a').loader();
    
});