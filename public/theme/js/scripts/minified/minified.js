/**
 * Minified a portion of the interface.
 * This portion is identified by an HTML tag wich has the class name 'minified'
 * (<div class="minified">) 
 * 
 * To open or close the "minified" portion, there must be an <i> HTML tag that has 
 * the class name 'mdi-chevron-up'. This <i> tag must be somewhere inside 
 * an <header class="tools-header"> tag 
 * wich is above the "minified" element. 
 * 
 * Like this :
 * <header class="tools-header">
 *      <i class="mdi-chevron-up"></i>
 * </header>
 * <div class="minified">
 *      <!-- Content minified -->
 * </div>
 * 
 * @returns {void}
 */
var minified = function()
{
  $( '.minified' ).hide();
  
  if( $( '*.minified' ).hasClass( 'minified' ) )
  {
      $( 'i.mdi-chevron-up' ).on( 'click', function(){
          
            if( $(this).hasClass('mdi-chevron-up') )
            {
                $(this).addClass( 'mdi-chevron-down' ).removeClass( 'mdi-chevron-up' );
                
                $(this).closest('header.tools-header').parent().find( '.minified' ).slideUp();
            }
            else
            {
                $(this).addClass( 'mdi-chevron-up' ).removeClass( 'mdi-chevron-down' );
                
                $(this).closest('header.tools-header').parent().find( '.minified' ).slideDown();
            }
      });
      
      $( 'i.mdi-chevron-up' ).addClass( 'mdi-chevron-down' ).removeClass( 'mdi-chevron-up' );
  }
};

$(document).on('ready', function()
{ 
    minified();     
});