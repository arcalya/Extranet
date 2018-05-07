//$( 'ul.pagination' ).alphafilter();

/**
 * Show activate the alpha filter
 * 
 * @return void
 */
(function( $ ){
    
    $.fn.alphafilter = function(){

        var filterContent   = $(this);
        var filterTags      = filterContent.find('a');
        
        filterTags.click(function( e ){
            
            e.preventDefault();
                
            var type = $(this).text()
            
            $('div.body-section>div:not(.' + type + ')' ).hide();
            $('.' + type ).fadeIn();
        });
           
    };
    
}( jQuery ));