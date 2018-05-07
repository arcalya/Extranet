/* 
 * Sends datas from a form in an AJAX process
 * The formula must have an action attribute defining a module and an action 
 * to wich Ajax/ajax will be add so the system process and authorize an AJAX traitment 
 * <form action="module/action"> becomes module/actionAjax/ajax
 * 
 * PHP side
 * The action in PHP Controller has to consider the Ajax added (actionAjax)
 * to consider the process.
 * The response from PHP must be in JSON format in wich has to be :
 *   - statut       : has to OK or FAIL
 *   - token        : Sends back the updated token define by the system
 *   - alertsucces  : (OK process only) indicates the objects (by their class name) 
 *                                      and messages to write into each of them
 *   - callback     : (OK process only) [array] Containing infos to call a JS specific function
 *                                      [
 *                                      'function' => 'jsFunctionName', // [mandatory] | function name
 *                                      'initSelectors' => 'div',       // [accessory] | selectors of the element to reinit
 *                                      'selector' => '.current',       // [accessory] | selector of the element to update
 *                                      'content' => 'new content'      // [accessory] | content to insert in the element to update
 *                                      ]
 *   - errors       : (FAIL process only) indicates the objects (by their class name).
 *                                        Messages are already writing in the objects but invisible.
 * Example : Success PHP response
 * echo json_encode( [ 'token' => $_SESSION[ 'token' ], 'status' => 'OK', 'alertsuccess' => ['alert-success'=>'Une entrée vient d\'être ajoutée.'], 'callback'=>['function'=>'jsFunctionName', 'initSelectors'=>'div', 'selector'=>'.current', 'content'=>'new content' ]  ] );
 * 
 * Example : Failed PHP response    
 * echo json_encode( [ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL', 'errors' => ['emptyfield1'=>true, 'emptyfield2'=>true] ]  );                   
 */
var ajaxform = function()
{
    //$('div.modal:not(#delete) button.tosubmit').click(function(e){
    $('div.modal:not([id*="delete"]) button.tosubmit').click(function(e){
        
        e.preventDefault();
        
        $('.alert').hide(); 
        
        var form    = $(this).parents( 'div.modal' ).find( 'form#' + $(this).data( 'form' ) );
        
        if( form.length > 0 )
        {
            $.ajax({
                method : form.attr( 'method' ),
                url : form.attr( 'action' ) + 'Ajax/ajax',
                data : form.serialize(),
                success : function( response )
                {
                    var respJSON = JSON.parse( response );

                    $('input[name="token"]').val( respJSON.token );

                    if( respJSON.status === 'OK' )
                    {
                        form.find('input[type="text"]:not(.datepicker), input:not([type="hidden"], [type="checkbox"], [type="radio"]), textarea').val('');

                        for( var alertSuccess in respJSON.alertsuccess )
                        {
                            $('.' + alertSuccess).show().html( respJSON.alertsuccess[ alertSuccess ] );
                        }
                        $( 'div.modal' ).modal( 'hide' );

                        if( typeof respJSON.callback !== 'undefined' )
                        {
                            window[ respJSON.callback[ 'function' ]]( respJSON.callback );
                        }
                    }
                    else if( respJSON.status === 'FAIL' )
                    {
                        for( var errorName in respJSON.errors )
                        {
                            form.find('.' + errorName).show().html( respJSON.errors[ errorName ] );
                        }
                    }
                }
            });
        }
    });
};

/*
 * Callbacks indicated form the "ajaxform" method
 * 
 * Those functions are called at the end of an ajax process in case there is
 * a callback property in the JSON sent back by the server. (see "ajaxFormSend" 
 * comments.
 * 
 * Examples JSON (server sent)
 * {..., 'callback' : ['function':'jsFunctionName', 'initSelectors':'div', 'selector':'.current', 'content':'new content' ], ... }
 */

/*
 * Refresh content of a specific selector.
 * @param {str|int} id
 * @returns {undefined}
 */
var refreshInfos = function( param )
{
    if( typeof param[ 'initSelectors' ] !== 'undefined' )
    {   
        $( param[ 'initSelectors' ] ).removeClass( 'bg-success' );
    }
 
    if( typeof param[ 'selector' ] !== 'undefined' )
    { 
        $( param[ 'selector' ] ).addClass( 'bg-success' );
        if( typeof param[ 'content' ] !== 'undefined' )
        {
            $( param[ 'selector' ] ).html( param[ 'content' ] );
        }
    }
};


var appendDatas = function( param )
{
    $( param[ 'selector' ] ).val( param[ 'content' ] );
}


$(window).on('load', function()
{
    ajaxform();
});