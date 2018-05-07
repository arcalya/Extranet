/* 
 * Permits to transfer data in a field in a form displaied in a modal window
 * Automatic initiated from a tag that has thoses two attributes :  
 *   - data-addform-inputname   (name of the field. Refers to <input name="">)
 *   - data-addform-inputvalue  (value of the field. Refers to <input value="">)
 * Like this :
 * <a data-addform-inputname="IdClient" data-addform-inputvalue="102" data-toggle="modal" data-target="#ModalForm">
 * A field must exists as define in the data-addform-inputname attribut value
 * In the previous example a <input name="IdClient">
 */
var modalform = function()
{
     $('*[data-addform-inputname]').click(function(){
       
        $('input[name="' + $(this).data('addform-inputname') + '"]').val( $(this).data('addform-inputvalue') );
        
        modalInit( $(this) );
       
    });
    
    $('*[data-addform-datas]').click(function(){
       
        var datas       = $(this).data('addform-datas');
        
        var modalTarget = $(this).data('target');
                        
        for( field in datas )
        {
            $(modalTarget + ' *[name="' + field + '"]').val( datas[field] );
        }
        
        modalInit( $(this) );
    });
    
    $('*[data-modal-active="true"]').click();
};



var modalInit = function( obj )
{
    var target = obj.data('target');

    var modal = $('div.modal' + target);
    
    $( 'div.modal' + target ).on( 'shown.bs.modal', function()
    {
        if( $( 'div.modal' + target + ' form' ) . length > 0 )
        {
            $( 'div.modal' + target + ' form input[type="text"]' ).first().focus();
        }
    });
    

    if( !( modal.hasClass('targeted') ) )
    {
        var elements = $('div.modal' + target + ' input');

        $.each( elements, function(){

            var idValue = obj.attr('id');

            $('div.modal' + target + ' label[for="' + idValue + '"]').attr('for', idValue + '_' + target );

            $('div.modal' + target + ' input[id="' + idValue + '"]').attr('id', idValue + '_' + target );

        });

        modal.addClass('targeted');
        
        if( obj.data('modal-reset') && obj.data('modal-reset') === true )
        {
            $('div.modal' + target + ' input').val();
            
            $('div.modal' + target + ' textarea').html('');
            
            $('div.modal' + target + ' input[type="checkbox"], div.modal' + target + ' input[type="tadio"]').removeAttr('checked');
        }
    }
};


/* 
 * Selects infos to display when lauching a modal window
 * Automatic initiated from a tag that has thoses two attributes :  
 *   - data-displayinfo-classname (element(s) that has this class name. Refers to <tagname class="">)
 * Like this :
 * <span data-displayinfo-classname="classInfos" data-toggle="modal" data-target="#ModalForm">
 * An element with the class name must exists as define in the data-dispplayinfo-classname attribute value
 * In the previous example a <div class="classInfos">
 */
var modalinfo = function()
{
   $('*[data-displayinfo-classname]').on('click', function(){
       
       var toShow = $('.' + $(this).data('displayinfo-classname') );
       
       var parentToShow = $( toShow[ 0 ] ).parent();
              
       parentToShow.children().hide();
       
       $('.' + $(this).data('displayinfo-classname') ).show();
   });
};

$(document).on('ready', function()
{
    modalform();
    modalinfo();
});

$(window).on('load', function()
{
    $( 'div.modal[data-displayonload="true"]' ).modal('show');
});