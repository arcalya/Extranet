/* 
 * Tools to activate filters on datas.
 */

var datasfilter = function(){
  
    $('span.operation[data-filers="history"]').on('click', function(){
        
        if( $(this).hasClass( 'open' ) )
        {
            $(this).parents('tr').nextUntil('tr.cell-h1').not(':not(.minified)').slideUp();
            
            $(this).removeClass( 'open' );            
        }
        else
        {
            $(this).parents('tr').nextUntil('tr.cell-h1').not(':not(.minified)').slideDown();
            
            $(this).addClass( 'open' );
        }
        
        
        
    });
    
};


$(window).on('load', function(){
    
    datasfilter();
    
});