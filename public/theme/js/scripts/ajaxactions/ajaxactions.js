/**
 * Manage several Ajax commom actions use in the system. 
 * HTML elements concerned have the data-action attribute wich specifies 
 * the ajax action to apply.
 * 
 * It could be :
 * 
 * - Delete : Is assign to any HTML tag wich has the attribute data-action="delete" and data-url="...".
 *            Because it calls the Bootstrap Modal window it also needs the attributes
 *            data-toggle="modal" data-target="#delete".
 *            Ex: <span data-toggle="modal" data-target="#delete" data-action="delete" data-url="module/deleteelement">Delete Me</span>
 *            
 * - Order : Changes order of elements in a HTML table. The order only goes up.
 *           The element must have the attibute data-action="order" and data-url="..."
 *           Ex: <td data-action="order" data-url="modulename/order/42"><i class="mdi mdi-chevron-up"></i></td>
 *           
 * - Active : Switches icons on an element (active icon <=> inactive icon).
 *            The HTML element must have the data-action="active" and data-url="..."
 *            It alse have the attributes data-icon-active="..." and data-icon-inactive="..."
 *            to indicate what are the icons to switch.
 *            Ex: <td data-action="active" data-url="module/active/42" data-icon-active="mdi-checkbox-marked" data-icon-inactive="mdi-checkbox-blank-outline"><i class="mdi mdi-checkbox-marked"></i></td>
 * 
 * - Active radio : Switches icons as for Active but it also have an effect on other HTML elements
 *                  that are in the same HTML table row <tr>. There is an interaction between them.
 *                  The HTML elements must have the data-action="activeradio" and data-url="..."
 *                  Ex: <tr>
 *                          <td>Nom</td>
 *                          <td data-action="activeradio" data-url="module/active/42" data-icon-active="mdi-radiobox-marked" data-icon-inactive="mdi-radiobox-blank mdi-disabled"><i class="mdi mdi-radiobox-marked"></i></td>
 *                          <td data-action="activeradio" data-url="module/active/43" data-icon-active="mdi-radiobox-marked" data-icon-inactive="mdi-radiobox-blank mdi-disabled"><i class="mdi mdi-radiobox-marked"></i></td>
 *                      </tr>
 * 
 * @returns {void}
 */
var ajaxactions = function()
{      
    var delElement  = null;
    
    var modalId     = null;
        
    $( '*[data-action="delete"]' ).click(function( e )
    {
        e.preventDefault();
        
        $(this).addClass( 'toconfirm' );
        
        $(this).parents('tr').addClass('danger');
        
        modalId = $(this).data('target');
        
        delElement = $(this);
    
        $( 'div.modal' + modalId ).on( 'hidden.bs.modal', function()
        {
            delElement.removeClass( 'toconfirm' );
            
            delElement.parents('tr').removeClass('danger');
            
            $( 'div.modal' + modalId ).off();
        });

        $( 'div.modal' + modalId + ' button.tosubmit' ).on( 'click', function( event )
        {
            event.preventDefault();
            
            $( 'div.modal' + modalId ).modal( 'hide' );
            
            ajaxAction( delElement );
            
            $( 'div.modal' + modalId + ' button.tosubmit' ).off();
        });
    });
    
    $( '*[data-action="order"], *[data-action="active"], *[data-action="activeradio"]' ).click(function( e )
    {
        e.preventDefault();
        
        ajaxAction( $(this) );
    });    
};

/**
 * Split the URL string to prepare it for an Ajax transfert. 
 * It seperates it into : page, action and router
 * These informations must be in a data-url attribute from wich
 * the Ajax call is sent
 * Example : 
 * <li data-url="page/action/router">Do Ajax stuff</li>
 * wich could be :
 * <li data-url="user/update/222">Do Ajax stuff</li>
 * 
 * @param {str} htmlTag The HTML object wich contains the data-url attribute
 * @returns {Array|getHrefValues.infos}
 */
var getHrefValues = function( htmlTag )
{      
    var href        = htmlTag.data('url');
    var gets        = href.split('/');
    var infos       = [];

    infos['url'] = href;
    
    infos['router'] = gets[ ( gets.length - 1 ) ];
    infos['action'] = gets[ ( gets.length - 2 ) ];
    infos['page']   = gets[ ( gets.length - 3 ) ];
    
    infos['urlAjax'] = href.replace( '/' + infos['router'], '' );

    return infos;
};

/**
 * Engage the Ajax proccess and restore the display depending on parameters found
 * Uses the data-action attribute of the object from wich the Ajax is called to 
 * define de proccess. Values could be : active, 'activeradio', 'order' or 'delete'
 * 
 * This object must also have the data-url wich is used to set the Ajax post call
 * 
 * Example : 
 * <li data-action="active" data-url="user/publish/222">Do Ajax stuff</li>
 * 
 * @param {obj} htmlTag The HTML object wich contains the data-url attribute
 * @returns {void}
 */
var ajaxAction = function( htmlTag )
{
    var values      = getHrefValues( htmlTag );
    var action      = htmlTag.data( 'action' );

    htmlTag.parents('table').find('tr').removeClass('success warning active danger info');

    if( action === 'order' )
    {
        var curLine         = htmlTag.parents('tr');
        var curLineLevel    = htmlTag.parents('tr').data('level');
    }
    else if( action === 'active' || action === 'activeradio' )
    {
        var iconActive      = htmlTag.data( 'icon-active' );
        var iconInactive    = htmlTag.data( 'icon-inactive' );
    }
    
    var token = $('input[name="token"]').val();

    if( values['page'] && values['action'] && values['router'] )
    {
        var elementId = values['router'];
        
        $.ajax({
            method : 'post',
            url : values['urlAjax'] + 'Ajax/ajax',
            data : {'id':values['router'], 'token':token},
            success : function( response )
            {   
                var respJSON = JSON.parse( response );
                
                $('input[name="token"]').val( respJSON.token );
                
                if( respJSON.status === 'OK' )
                {
                    if( action === 'order' )
                    {
                        var curLineGroup = curLine.nextUntil( 'tr[data-level="' + curLineLevel + '"]' );

                        curLine.insertBefore( curLine.prevAll( 'tr[data-level="' + curLineLevel + '"]:first' ) );

                        var nextSimblingData = curLineGroup.eq((curLineGroup.length-1)).next().data('level');

                        if( nextSimblingData === curLine.data('level') || !nextSimblingData ) // In case there is no subelement
                        {
                            curLineGroup.insertAfter( curLine );
                        }
                    }
                    else if( action === 'active' )
                    {
                        var icon = htmlTag.find('i');

                        if( icon.hasClass( iconActive ) )
                        {
                            icon.addClass( iconInactive );

                            icon.removeClass( iconActive );
                        }
                        else if( icon.hasClass( iconInactive ) )
                        {
                            icon.removeClass( iconInactive );

                            icon.addClass( iconActive ); 
                        }
                        else if( icon.hasClass( 'mdi-check' ) )
                        {
                            if( icon.parent().hasClass( 'selected' ) )
                            {
                                icon.parent().removeClass( 'selected' );
                            }
                            else
                            {
                                icon.parent().addClass( 'selected' ); 
                            }
                        }
                    }
                    else if( action === 'activeradio' )
                    {
                        var icon = htmlTag.find('i');
                        
                        if( icon.hasClass( iconActive ) )
                        {
                            icon.addClass( iconInactive );
                            
                            icon.removeClass( iconActive );
                        }
                        else if( icon.hasClass( iconInactive ) )
                        {
                            var line = htmlTag.parent();
                            
                            line.find( '.' + iconActive ).removeClass( iconActive ).addClass( iconInactive );
                            
                            icon.removeClass( iconInactive );
                            
                            icon.addClass( iconActive ); 
                        }
                    }
                    else if( action === 'delete' )
                    {
                        $('#' + elementId).fadeOut(500, function()
                        {
                            $('#'.elementId).remove();
                        });
                        
                        htmlTag.parents('tr').fadeOut(500, function()
                        {
                            htmlTag.parents('tr').remove();
                        });
                    }
                    htmlTag.parents('tr').addClass( 'success' );
                    
                    
                    if( respJSON.msg !== undefined && respJSON.msg !== '' )
                    {
                        var alertTxtZone = $( '*.alert.alert-success > span' );
                        
                        alertTxtZone.parent().show();
                        
                        alertTxtZone.html( respJSON.msg );
                    }
                }
                else if( respJSON.status === 'FAIL' )
                {
                    console.log(values);
                    
                    console.log(respJSON);
                }
                else
                {
                    //window.location.href = values['url'];
                }
            }
        });
    }
    else
    {
        window.location.href = values['url'];
    } 
};


$(document).on('ready', function()
{
    ajaxactions();
});