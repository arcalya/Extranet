// $('#calendar').calendar();

/**
 * This calendar plugin uses the fullcalendar plugin [js/lib/fulcalendar/fulcalendar.min.js]
 * Parameters are defined in data-type, data-events and data-url attributes
 * data-type : Indicates the type of datas to get. It could be :
 *             "generic" : All events from a module. Wors for "workshops"
 *             "currentuser" : The actual user (looks for the sessions $_SESSION['adminId'])
 *             "INTERGER" : A user and it's id (must be like 349)
 * data-events : Choose the contents to display in the calendar.
 *               It could be : "activities", "tasks", "workshops", "timestamp", "appointments"
 *               They must be seperate by a slash "/"
 * data-url : Is the Ajax call made that get and control datas for this calendar
 * 
 * Example :
 * <div id="calendar" data-type="303" data-events="activities/tasks/workshops/timestamp/appointments" data-url="schedule/calendarevents"></div>
 * 
 * Response : 
 * The Ajax response must be a JSON format that contains an array of object. Each on containing 
 * {'id':         INT, 
 *  'title':      STR,                  // Event title
 *  'description':STR,                  // Event desicription
 *  'className':  SET('activities', 'tasks', 'workshops', 'timestamp', 'appointments'),  
 *  'start':      YYYY-MM-DD,           // Date to display this event in the calendar
 *  'token':      $_SESSION[ 'token' ], // Token used for validate Ajax transfers
 *  'target':     STR,                  // Modal window to open for insert and update
 *  'datas':      OBJ                   // Datas transfer to the modal Window.
 * }
 * @param {object} $ | jQuery plugin object instance
 * @returns {void}
 */
var calendar = null; 

(function( $ ){
    
    $.fn.calendar = function( options ){
        
        var settings = $.extend({
            
            pluginUpdateElement: ""
            
        }, options );
        
        var events   = $(this).data('events');
        
        var url      = $(this).data('url');
        
        var type     = $(this).data('type');
        
        calendar = $(this).fullCalendar({
            
            header: {
                left  : 'prev,next today',
                center: 'title',
                right : 'month,agendaWeek,agendaDay'
            },
            editable    : false,
            lang        : 'fr-ch',
            eventSources: [ 
                {
                url : url + 'Ajax/ajax',
                type:'POST',
                data: function() 
                    {
                        return {
                            token  : $('input[name="token"]').val(),
                            events : events,
                            type   : type
                        };
                    }
                } 
            ],
            eventRender: function( datas, element ) // Display Calendar and datas into it (see : schedule/calendarevents').
            {
                //console.log( datas );
                $( 'input[name="token"]' ).val( datas.token );

                var content = ( typeof datas.description != 'undefined' ) ? datas.description : '';

                $( element ).append('<div class="description">'+ content +'</div>');
            },
            dayClick: function( date )  // Click on a day - INSERT FOR TASKS MODULE : Sets up the form for adding a new element
            {    
                $( '#TaskModalForm' ).modal('show');

                $( '#TaskModalForm' ).find( '*[name]:not([type="hidden"], [type="checkbox"], [type="radio"], .datepicker, .datepicker+select)' ).val('');
                
                $( '#TaskModalForm' ).find( '[type="checkbox"]' ).attr('checked', false);
                
                $( '#TaskModalForm' ).find( '.datepicker' ).val( date.format('DD.MM.YYYY') );
                
                $( '#TaskModalForm' ).find( '.datepicker:first+select' ).val( '09_00_00' );
                
                $( '#TaskModalForm' ).find( '.datepicker:not(:first)+select' ).val( '11_00_00' );
                
                $( '#TaskModalForm' ).find( '[name="IdTache"]' ).val( '' );
                
                $( '#TaskModalForm' ).find( 'form[id="delete-form"]' ).hide();
            },
            eventClick: function( calEvent ) // Click on an event - UPDATE FOR TASKS MODULE | INSERT AND UPDATE FOR ACTIVITIES MODULE : Gets datas to insert in form fields.
            {
                settings.pluginUpdateElement.addFormRows( calEvent.target, calEvent.datas ); // This is a call to a method of the "formpart" module.
                
                return false;
            }
        });

        return {
            
            refresh : function()
            {
                calendar.fullCalendar( 'refetchEvents' );
            }
        };

    };
    
}( jQuery ));