/**
 * The calendar picker. 
 * Uses the daterangepicker plugin (js/lib/daterangepicker)
 * The date value must be empty or in a 'DD.MM.YYYY' format
 * The input field is automatically a date picker as long that
 * it contains the "datepicker" class
 * Example : 
 * <input class="datepicker" value="" />
 * 
 * @return void
 */
var datepicker  = function()
{
    
    var setDatePicker = function( elements, single )
    {
        elements.each( function( index, element  )
        {
            $( element ).daterangepicker({
                singleDatePicker: ( single ) ? true : false,
                autoUpdateInput:  ( single ) ? false : true, 
                showDropdowns:true,
                minDate:"01/01/1950",
                locale: {
                    format: 'DD.MM.YYYY', 
                    "separator": " . ",
                    "applyLabel": "Appliquer",
                    "cancelLabel": "Annuler",
                    "fromLabel": "De",
                    "toLabel": "A",
                    "customRangeLabel": "Personnaliser",
                    "daysOfWeek": [
                        "Di",
                        "Lu",
                        "Ma",
                        "Me",
                        "Je",
                        "Ve",
                        "Sa"
                    ],
                    "monthNames": [
                        "Janvier",
                        "Février",
                        "Mars",
                        "Avril",
                        "Mai",
                        "Juin",
                        "Juillet",
                        "Août",
                        "Septembre",
                        "Octobre",
                        "Novembre",
                        "Décembre"
                    ],
                    "firstDay": 1
                  }
            }, function( chosen_date ) 
            {            
              $( element ).val( chosen_date.format('DD.MM.YYYY') );
            });

            
        });
        
        /*
        $(".datepicker[value='']").val('');

        $(".datepicker[value='']").on('blur', function(){

                $(this).val('');

        });
        */
    };
    
    setDatePicker( $('.datepicker'), true );
    
};


$(document).on('ready', function()
{
    datepicker();

});