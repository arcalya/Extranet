$(document).ready(function(){
    
    var formpartObj = $( 'span.add-form-part' ).formpart();
    
    refreshCalendar( formpartObj );
    

});

var LoadedCalendar = false;
        
var refreshCalendar = function( formpartObj ){

    var scheduleCalendar = $('#calendar').calendar({ pluginUpdateElement : formpartObj });

    if( LoadedCalendar )
    {
        scheduleCalendar.refresh();
    }

    LoadedCalendar = true;
};