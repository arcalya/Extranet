/* 
 * Performs effects and offers navigation tools in the interface
 */
 var interface = function()
{
    $('a[href*="#"]').on('click', function()
    {
        $(this).preventDefault;
     
        $('*.emphasis').removeClass( 'emphasis' );  
        
        var selector = $(this).attr('href');
        
        var selectorHeight = $(selector).offset().top;    
        
        $('html, body').animate({ scrollTop:( selectorHeight - 20 ) }, 1000, function()
        {
            
            $(selector).addClass( 'emphasis' );    
            
        });
    }); 
    
    $('span.input-group-addon.addon-operation i.mdi.mdi-eye').on( 'click', function()
    {
        if( $(this).hasClass('mdi-eye') )
        {
            $(this).parent().prev().attr('type', 'text'); 
            
            $(this).addClass('mdi-eye-off');
            
            $(this).removeClass('mdi-eye');
        }
        else
        {
            $(this).parent().prev().attr('type', 'password');
            
            $(this).addClass('mdi-eye');
            
            $(this).removeClass('mdi-eye-off');
        }
    });
};


$(document).on('ready', function()
{ 
    interface();      
});

