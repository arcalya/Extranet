/**
 * Slidedown effects on the sidebar menu
 * 
 * @returns {void}
 */
var sidebarmenu = function(){
    
    $('aside nav>ul.nav>li>a').on('click', function( e )
    {
        e.preventDefault();
        
        $('div.sidebar-footer a[data-btn="lock"] span.glyphicon-eye-close').addClass('glyphicon-eye-open');
        
        $('div.sidebar-footer a[data-btn="lock"] span.glyphicon-eye-close').removeClass('glyphicon-eye-close');
        
        $('aside nav>ul.nav>li').removeClass('active');
        
        $('aside nav>ul.nav>li>ul').not($(this).parent().find('ul')).slideUp();
        
        if( !$(this).parent().hasClass('active') )
        {
             $(this).parent().addClass('active');
             
             $(this).parent().find('ul').slideDown();
        }
    });
    
    $('aside nav>ul.nav>li>ul>li.selected').parent().parent().find( '>a:first-child' ).click();
};

/**
 * Tools in the bottom of the sidebar
 *  - Fullscreen (uses the jQuery fullscreen plugin)
 *  - Slidedown/up sidebar menus 
 * 
 * @returns {void}
 */
var sidebarmenufootertools = function()
{
    $('div.sidebar-footer a[data-btn="fullscreen"]').click(function(e){
        
        e.preventDefault();
        
        if( $('div.sidebar-footer a[data-btn="fullscreen"] span.glyphicon').hasClass('glyphicon-resize-small') )
        {
            $('div.sidebar-footer a[data-btn="fullscreen"] span.glyphicon-resize-small').addClass('glyphicon-fullscreen');
            
            $('div.sidebar-footer a[data-btn="fullscreen"] span.glyphicon-resize-small').removeClass('glyphicon-resize-small');
            
            $(document).fullScreen(false);
        }
        else
        {
            $('div.sidebar-footer a[data-btn="fullscreen"] span.glyphicon-fullscreen').addClass('glyphicon-resize-small');
            
            $('div.sidebar-footer a[data-btn="fullscreen"] span.glyphicon-fullscreen').removeClass('glyphicon-fullscreen');
            
            $(document).fullScreen(true);
        }
    });
    
    $('div.sidebar-footer a[data-btn="lock"]').click(function(e){
        
        e.preventDefault();
        
        if( $('div.sidebar-footer a[data-btn="lock"] span.glyphicon').hasClass('glyphicon-eye-open') )
        {
            $('aside nav>ul.nav>li').addClass('active');
            
            $('aside nav>ul.nav>li>ul').slideDown();
            
            $('div.sidebar-footer a[data-btn="lock"] span.glyphicon-eye-open').addClass('glyphicon-eye-close');
            
            $('div.sidebar-footer a[data-btn="lock"] span.glyphicon-eye-open').removeClass('glyphicon-eye-open');
        }
        else
        {
            $('aside nav>ul.nav>li').removeClass('active');
            
            $('aside nav>ul.nav>li>ul').slideUp();
            
            $('div.sidebar-footer a[data-btn="lock"] span.glyphicon-eye-close').addClass('glyphicon-eye-open');
            
            $('div.sidebar-footer a[data-btn="lock"] span.glyphicon-eye-close').removeClass('glyphicon-eye-close');
        }
    });
    
};

$(document).on('ready', function()
{
    sidebarmenu();   
    sidebarmenufootertools();
});