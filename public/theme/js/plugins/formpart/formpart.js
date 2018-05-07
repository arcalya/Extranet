/**
 * Clone a part of a form wich is in a tag containing the class specified in the 
 * data-addform="" attribute of the button used to duplicate content.
 * Example :
 * <button class="btn btn-primary add-form-part" data-addform="form-activite">
 * 
 * In this current example it refers to a tag that has the class "form-activite"
 * For instance : 
 * <div class="form-activite"> Content to duplicate </div>
 * 
 * JS call : $( 'span.add-form-part' ).formpart();
 * 
 * @return void
 */
(function( $ ){
    
    $.fn.formpart = function(){

        $( this ).on( 'click', function( e ){

            e.preventDefault();

            var addform = $(this).data('addform');

            $( 'div.form-add-zone' ).append( $( '.' + addform + ':last-child' ).clone() );

        });
        
        
        return{
            
            addFormRows : function( modal, datas ){
                
                var modalForm = $( modal );

                $( modalForm ).modal('show');

                // Clean form from previous datas
                modalForm.find( '*[name]:not([type="hidden"], [type="checkbox"], [type="radio"], .datepicker, .datepicker+select)' ).val('');
                
                modalForm.find( '[type="checkbox"]' ).attr('checked', false);
                
                modalForm.find( 'form[id="delete-form"]' ).show();

                if( modalForm.find( 'div.form-add-zone' ).length > 0 )
                {
                    var rowClass = $( 'span.add-form-part' ).data('addform');

                    $( '.' + rowClass + ':not(:last)' ).remove();
                    
                    $( '.' + rowClass ).find('input, textarea, select').prop('disabled', false);
                }

                // Inserts datas in form (in modal window)
                if( datas )
                {
                    $.each( datas, function( key, value )
                    {
                        if( datas.datas && typeof datas[ key ] === 'object' )
                        {
                            var nbObj = datas.datas.length;
                            
                            $.each( datas.datas, function( k, v )
                            {     
                                $.each( v, function( field, val )
                                {
                                    var element = modalForm.find('[name="' + field + '"]');
                                      
                                    if( element.length > 0 )
                                    {
                                        modalForm.find('[name="' + field + '"]').val( val );
                                        
                                    }
                                    else
                                    {
                                        var elementList = modalForm.find('[name="' + field + '[]"]');

                                        if( elementList.attr('type') !== 'checkbox' )
                                        {
                                            modalForm.find('[name="' + field + '[]"]:last').val( val );
                                        }
                                        else
                                        {
                                            modalForm.find('[value="' + val + '"]').prop( 'checked', true );
                                        }
                                    }
                                    
                                });
                                if( k < ( nbObj - 1 ) )
                                {
                                    $( 'span.add-form-part' ).click();
                                }
                            });
                        }
                        else
                        {
                            var matches = value.match(/^(\d{2})\.(\d{2})\.(\d{4}) (\d{2}):(\d{2}):(\d{2})$/);
                            
                            if( matches === null )
                            {
                                modalForm.find('*[name="' + key + '"]').val( value );
                            }
                            else
                            {
                                var year    = parseInt( matches[3], 10 );
                                var month   = parseInt( matches[2], 10 );
                                var day     = parseInt( matches[1], 10 );
                                var hour    = parseInt( matches[4], 10 );
                                var minute  = parseInt( matches[5], 10 );
                                
                                modalForm.find('[name="' + key + '[]"]').eq(0).val( ( ( day < 10 ) ? '0' + day : day ) + '.' + ( ( day < 10 ) ? '0' + month : month )  + '.' + year );
                                modalForm.find('[name="' + key + '[]"]').eq(1).val( ( ( hour < 10 ) ? '0' + hour : hour ) + '_' + ( ( minute < 10 ) ? '0' + minute : minute )  + '_00' );
                            }
                        }
                    });
                    
                    $( 'div.form-add-zone [data-deleteform]' ).on( 'click', function(){
                        
                        $(this).closest( '.' + $(this).data('deleteform') ).find('input, textarea, select').prop('disabled', true);
            
                    });
                }
                               
            }
            
        };
        
        
    };
    
}( jQuery ));