// $('aside nav>ul.nav>li>ul.nav>li>a').loader({container: 'div.container>main'});

/**
 * Set elements form inventory in a basket
 * 
 * @return void
 */
(function( $ ){
    
    $.fn.basket = function(){
        
        $(this).on( 'click', function(){
        
            var id = $(this).data('article');
            
            if( $(this).hasClass('selected') )
            {    
                $( 'div#article_'+id )
                $(this).removeClass('selected');
            }
            else
            {
                $('div#panier > div > section').append('<div class="body-section" id="article_'+id+'"><section class="profile clearfix"><header class="tools-header"><h2>'+id+'</h2></header></section></div>');
                $(this).addClass('selected');                                           
            }
        });           
    };
    
}( jQuery ));


$( document ).on('ready', function(){
   
    $('*[data-article]').basket();
    
});