/**
 * Drop down menu
 * 
 * @returns {void}
 */
var dropmenu = function()
{
    $('a#menu_toggle').on('click', function( e )
    {
        e.preventDefault();
        
        if( $('body').hasClass('nav-sm') )
        {
             $('body').removeClass('nav-sm');
        }
        else
        {
             $('body').addClass('nav-sm');
        }
    });
};

$(document).on('ready', function()
{
    dropmenu();   
});