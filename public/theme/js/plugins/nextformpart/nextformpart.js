//$( 'button.next-form-part' ).nextformpart();

/**
 * Show a part of a form wich is in a tag containing the class specified in the 
 * Example :
 * <button class="btn btn-primary next-form-part" data-nextform="form-secondpart">
 * 
 * In this current example it refers to a tag that has the class "form-secondpart"
 * For instance : 
 * <div class="form-secondpart"> Content to show in a second step </div>
 * 
 * It also gets the datas from the form and sets them into elements that contains
 * an data-url attribute.
 * 
 * @return void
 */
(function( $ ){
    
    $.fn.nextformpart = function(){

        var nextform = $(this).data('nextform');
        
        $( '.' + nextform ).hide();

        $(this).on( 'click', function( e ){

            e.preventDefault();

            var nextform    = $(this).data('nextform');
            
            var form        = $(this).parents('form');
            
            var datas       = form.serialize();
            
            var fields      = datas.split('&');
            
            var errors      = false;
            
            var href        = '';

            form.find( '*[name="*"]' ).removeClass( 'error-form-field' );

            $.each( fields, function( key, value){
                
                var fieldsAndValue = value.split('=');
                
                if( fieldsAndValue[1].length === 0 )
                {
                    
                    form.find( '*[name="'+fieldsAndValue[0]+'"]' ).addClass( 'error-form-field' );
                    
                    errors = true;
                    
                }
                
                href += fieldsAndValue[1] + '-';
            });


            if( !errors )
            {
                form.find( '*[name]' ).prop( "disabled", true );
                
                $( '.' + nextform ).fadeIn();
                
                $.each( $( '*[data-url]' ), function(){
                    
                    var element     = $(this);
                    
                    var dataUrl     = $(this).data( 'url' );
                    
                    var datasUrl    =  dataUrl.split( '/' );
                    
                    var nbDatasUrl  = datasUrl.length;
                    
                    var dataUrlNew  = '';

                    $.each( datasUrl, function( key, value){
                        
                        dataUrlNew += ( key < ( nbDatasUrl - 1 ) ) ? value + '/' :  href + value ;
                        
                    });

                    element.data( 'url', dataUrlNew );
                    
                });
            }

        });        
    };
    
}( jQuery ));