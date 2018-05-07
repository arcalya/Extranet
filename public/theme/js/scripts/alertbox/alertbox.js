/**
 * Manage the alert box displaied in the interface.
 * The box won't show up if the class 'alert-display-ajax' is not
 * used.
 * 
 * Note : As it is used in the Bootstrap Framework, the alert box 
 *        could also have on of those class names :
 *           'alert-success', 
 *           'alert-info', 
 *           'alert-warning', 
 *           'alert-danger'
 * 
 * @returns {void}
 */
var alertbox = function()
{
    $('.alert.alert-display-ajax').hide();  
    
    $( '*.alert>button.close' ).click( function( e ){
        
       e.preventDefault();
       
       $(this).parents( '*.alert' ).fadeOut();
       
    });
};

$(document).on('ready', function()
{ 
    alertbox();      
});