var menuToggle = function ()
{
    $('a#menu_toggle').on('click', function (e)
    {
        e.preventDefault();
        if ($('body').hasClass('nav-sm'))
        {
            $('body').removeClass('nav-sm');
        }
        else
        {
            $('body').addClass('nav-sm');
        }
    });
};



var asideSubmenu = function () {

    $('aside nav>ul.nav>li>a').on('click', function (e)
    {
        e.preventDefault();
        $('div.sidebar-footer a[data-btn="lock"] span.glyphicon-eye-close').addClass('glyphicon-eye-open');
        $('div.sidebar-footer a[data-btn="lock"] span.glyphicon-eye-close').removeClass('glyphicon-eye-close');
        $('aside nav>ul.nav>li').removeClass('active');
        $('aside nav>ul.nav>li>ul').not($(this).parent().find('ul')).slideUp();
        if (!$(this).parent().hasClass('active'))
        {
            $(this).parent().addClass('active');
            $(this).parent().find('ul').slideDown();
        }
    });

    $('aside nav>ul.nav>li>ul>li.selected').parent().parent().find('a:first-child').click();
};


var actionAlertBox = function ()
{
    $('.alert.alert-display-ajax').hide();
    $('*.alert>button.close').click(function (e) {
        e.preventDefault();
        $(this).parents('*.alert').fadeOut();
    });
};


var minified = function ()
{
    $('.minified').hide();
    if ($('*.minified').hasClass('minified'))
    {
        $('i.mdi-chevron-up').on('click', function () {
            if ($(this).hasClass('mdi-chevron-up'))
            {
                $(this).addClass('mdi-chevron-down').removeClass('mdi-chevron-up');
                $(this).closest('header.tools-header').parent().find('.minified').slideUp();
            }
            else
            {
                $(this).addClass('mdi-chevron-up').removeClass('mdi-chevron-down');
                $(this).closest('header.tools-header').parent().find('.minified').slideDown();
            }
        });
        $('i.mdi-chevron-up').addClass('mdi-chevron-down').removeClass('mdi-chevron-up');
        
       
    }
    
    // To click starts 
    // <i class="mdi mdi-whatever mdi-minified"></i>
    // Set elements to open <tr class="minified">
    if ($('tr.minified').length > 0) // Slide in a table 
    {
        $('tr.minified>td').hide();
        $('tr.minified').show();
        $('i.mdi-minified').on('click', function(){
           if ($(this).hasClass('mdi-disabled'))
           {
               $(this).removeClass('mdi-disabled');
               $(this).closest('table.table').parent().find('.minified>td').slideUp();
           }
           else
           {
               $(this).addClass('mdi-disabled');
               $(this).closest('table.table').parent().find('.minified>td').slideDown();
           }
       });
   }
};



var displayCalendar = function ()
{
    var eventsTypes = $('#calendar').data('eventstype');
    var getEvents = $('#calendar').data('getcalendarevents');
    //var curCalStart;
    //var curCalEnd;
    //var curCalallDay;
    //var curCalAction;
    //var curCalEvent;
    var calendar = $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        editable: false,
        //eventLimit: true, // allow "more" link when too many events
        lang: 'fr-ch',
        eventSources: [
            {
                url: getEvents + 'Ajax/ajax',
                type: 'POST',
                data: function () {
                    return {
                        token: $('input[name="token"]').val(),
                        eventstypes: eventsTypes
                    };
                }
            }
        ],
        eventRender: function (datas, element) // Display Calendar and datas into it (see : SITE_URL.'schedule/calendarevents').
        {
            $('input[name="token"]').val(datas.token); // !!!!!! Doesn't show up when clicking on next month more than two times...

            var content = (typeof datas.description != 'undefined') ? datas.description : '';

            $(element).append('<div class="description">' + content + '</div>');
        },
        dayClick: function (date)  // Sets up the form for adding (insert) a new element (in occurrence : new Task)
        {
            $('#TaskModalForm').modal('show');

            $('#TaskModalForm').find('*[name]').val('');
        },
        eventClick: function (calEvent) // For modify : Gets datas to insert in form fields.
        {
            var modalForm = $(calEvent.target);

            $(modalForm).modal('show');

            // Clean form from previous datas
            modalForm.find('*[name]').val('');

            if (modalForm.find('div.form-add-zone').length > 0)
            {
                var rowClass = $('button.add-form-part').data('addform');

                $('.' + rowClass + ':not(:last)').remove();
            }

            // Insert datas in form 
            if (calEvent.datas)
            {
                var nbObj = calEvent.datas.length;

                $.each(calEvent.datas, function (key, value)
                {
                    if (typeof calEvent.datas[ key ] === 'object')
                    {
                        $.each(calEvent.datas[ key ], function (k, v)
                        {
                            modalForm.find('*[name="' + k + '[]"]:last').val(v);
                        });
                        if (key < (nbObj - 1))
                        {
                            $('button.add-form-part').click();
                        }
                    }
                    else
                    {
                        modalForm.find('*[name="' + key + '"]').val(value);
                    }
                });
            }

            /*
             curCalAction    = 'update';
             curCalEvent     = calEvent;
             */
        }
    });


    /*
     $(".antosubmit").on("click", function (){
     ajaxCalUpdate();
     if( curCalAction === 'insert' )
     {
     var title = $("#title").val();
     if( title ) {
     calendar.fullCalendar('renderEvent', {
     title: title,
     start: curCalStart,
     end: curCalEnd,
     allDay: curCalallDay
     },
     true // make the event "stick"
     );
     } 
     }
     else if( curCalAction === 'update' )
     {
     curCalEvent.title = $("#title").val();
     calendar.fullCalendar('updateEvent', curCalEvent);
     }
     clearCalendarFormValues();
     $('.antoclose').click();
     calendar.fullCalendar('unselect');
     return false;
     });
     
     
     var ajaxCalUpdate = function(){
     
     /*
     * $.each($("#whoRequired").val(), function (idx2, val2) {
     blkstr = val2 + "," + blkstr;
     });
     var Reminder = "";
     if ($('#chkReminder').prop('checked')) {
     Reminder = true;
     } else {
     Reminder = false;
     }
     * var dataRow = {
     'MeetingId': $('#MeetingId').val(),
     'Title': $('#eventTitle').val(),
     'NewEventTime': $('#eventTime').val(),
     'WhoRequired': blkstr,
     'Descripttion': $('#txtdescription').val(),
     'Status': $('#Status').val(),
     'NewEventDate': $('#eventDate').val(),
     'Remider': Reminder,
     'NewEventDuration': $('#eventDuration').val(),
     'Notes': $('#txtnotes').val(),
     }
     * $.ajax({
     type: 'POST',
     url: "/Calender/SaveEvent",
     data: dataRow,
     success: function (response) {
     if (response == 'True') {
     $('#calendar').fullCalendar('refetchEvents');
     alert('New event saved!');
     }
     else {
     alert('Error, could not save event!');
     }
     }
     });
     };
     
     var clearCalendarFormValues = function(){
     $('#title').val('');
     $('#descr').val('');
     };
     */
};


var sidebarFooterInit = function ()
{
    $('div.sidebar-footer a[data-btn="fullscreen"]').click(function (e) {
        e.preventDefault();
        if ($('div.sidebar-footer a[data-btn="fullscreen"] span.glyphicon').hasClass('glyphicon-resize-small'))
        {
            $('div.sidebar-footer a[data-btn="fullscreen"] span.glyphicon-resize-small').addClass('glyphicon-fullscreen');
            $('div.sidebar-footer a[data-btn="fullscreen"] span.glyphicon-resize-small').removeClass('glyphicon-resize-small');
            $(document).fullScreen(false);
        }
        else
        {
            $('div.sidebar-footer a[data-btn="fullscreen"] span.glyphicon-fullscreen').addClass('glyphicon-resize-small');
            $('div.sidebar-footer a[data-btn="fullscreen"] span.glyphicon-fullscreen').removeClass('glyphicon-fullscreen');
            $(document).fullScreen(true);
        }
    });

    $('div.sidebar-footer a[data-btn="lock"]').click(function (e) {
        e.preventDefault();
        if ($('div.sidebar-footer a[data-btn="lock"] span.glyphicon').hasClass('glyphicon-eye-open'))
        {
            $('aside nav>ul.nav>li').addClass('active');
            $('aside nav>ul.nav>li>ul').slideDown();
            $('div.sidebar-footer a[data-btn="lock"] span.glyphicon-eye-open').addClass('glyphicon-eye-close');
            $('div.sidebar-footer a[data-btn="lock"] span.glyphicon-eye-open').removeClass('glyphicon-eye-open');
        }
        else
        {
            $('aside nav>ul.nav>li').removeClass('active');
            $('aside nav>ul.nav>li>ul').slideUp();
            $('div.sidebar-footer a[data-btn="lock"] span.glyphicon-eye-close').addClass('glyphicon-eye-open');
            $('div.sidebar-footer a[data-btn="lock"] span.glyphicon-eye-close').removeClass('glyphicon-eye-close');
        }
    });

};

/* A form-to date Calendar. Seems useless for this project
 var daterangePicker = function(){
 
 var cb = function (start, end, label) {
 console.log(start.toISOString(), end.toISOString(), label);
 $('#reportrange span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
 //alert("Callback has fired: [" + start.format('MMMM D, YYYY') + " to " + end.format('MMMM D, YYYY') + ", label = " + label + "]");
 }
 
 var optionSet1 = {
 startDate: moment().subtract(29, 'days'),
 endDate: moment(),
 minDate: '01/01/2012',
 maxDate: '12/31/2015',
 dateLimit: {
 days: 60
 },
 showDropdowns: true,
 showWeekNumbers: true,
 timePicker: false,
 timePickerIncrement: 1,
 timePicker12Hour: true,
 ranges: {
 'Aujourd\'hui': [moment(), moment()],
 'Hier': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
 'Derniers 7 jours': [moment().subtract(6, 'days'), moment()],
 'Derniers 30 jours': [moment().subtract(29, 'days'), moment()],
 'Ce mois': [moment().startOf('month'), moment().endOf('month')],
 'Dernier Mois': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
 },
 opens: 'left',
 buttonClasses: ['btn btn-default'],
 applyClass: 'btn-small btn-primary',
 cancelClass: 'btn-small',
 format: 'MM/DD/YYYY',
 separator: ' to ',
 locale: {
 applyLabel: 'Envoyer',
 cancelLabel: 'Annuler',
 fromLabel: 'De',
 toLabel: 'à',
 customRangeLabel: 'Personnaliser',
 daysOfWeek: ['Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa'],
 monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
 firstDay: 1
 }
 };
 $('#reportrange span').html(moment().subtract(29, 'days').format('D MMMM YYYY') + ' - ' + moment().format('D MMMM YYYY'));
 $('#reportrange').daterangepicker(optionSet1, cb);
 $('#reportrange').on('show.daterangepicker', function () {
 console.log("show event fired");
 });
 $('#reportrange').on('hide.daterangepicker', function () {
 console.log("hide event fired");
 });
 $('#reportrange').on('apply.daterangepicker', function (ev, picker) {
 console.log("apply event fired, start/end dates are " + picker.startDate.format('D MMMM YYYY') + " to " + picker.endDate.format('D MMMM YYYY'));
 });
 $('#reportrange').on('cancel.daterangepicker', function (ev, picker) {
 console.log("cancel event fired");
 });
 $('#options1').click(function () {
 $('#reportrange').data('daterangepicker').setOptions(optionSet1, cb);
 });
 $('#destroy').click(function () {
 $('#reportrange').data('daterangepicker').remove();
 });     
 };
 */

/**
 * The calendar picker. 
 * The date value must be empty or in a 'DD.MM.YYYY' format
 * The input field is automatically a date picker as long that
 * it contains the "datepicker" class
 * Example : 
 * <input class="datepicker" value="" />
 * 
 * @return void
 */
var singleDatePicker = function ()
{
    $('.datepicker').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        minDate: "01/01/1950",
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
    }, function (start, end, label) {
        console.log(start.toISOString(), end.toISOString(), label);
    });

    $(".datepicker[value='']").val('');
    $(".datepicker[value='']").on('blur', function () {
        $(this).val('');
    });
};

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
 * @return void
 */
var addFormPart = function ()
{
    $('button.add-form-part').on('click', function (e) {

        e.preventDefault();

        var addform = $(this).data('addform');

        $('div.form-add-zone').append($('.' + addform + ':last-child').clone());

    });
};


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
var nextFormPart = function ()
{
    var nextform = $('button.next-form-part').data('nextform');
    $('.' + nextform).hide();

    $('button.next-form-part').on('click', function (e) {

        e.preventDefault();

        var nextform = $(this).data('nextform');
        var form = $(this).parents('form');
        var datas = form.serialize();
        var fields = datas.split('&');
        var errors = false;
        var href = '';

        form.find('*[name="*"]').removeClass('error-form-field');

        $.each(fields, function (key, value) {
            console.log(value);
            var fieldsAndValue = value.split('=');
            if (fieldsAndValue[1].length === 0)
            {
                form.find('*[name="' + fieldsAndValue[0] + '"]').addClass('error-form-field');
                errors = true;
            }
            href += fieldsAndValue[1] + '-';
        });

        if (!errors)
        {
            $('.' + nextform).fadeIn();
            $.each($('*[data-url]'), function () {
                var element = $(this);
                var dataUrl = $(this).data('url');
                var datasUrl = dataUrl.split('/');
                var nbDatasUrl = datasUrl.length;
                var dataUrlNew = '';

                element.data('url', 'test');

                $.each(datasUrl, function (key, value) {
                    dataUrlNew += (key < (nbDatasUrl - 1)) ? value + '/' : href + value;
                });

                element.data('url', dataUrlNew);
            });
        }

    });
};


/**
 * Generate a filter tool from the Bootstrap dropdown menu that
 * shows up and hide HTML tag in the curent page (contain in 
 * <div class="body-section">) that has the correspondant
 * class.
 * 
 * It use the data-type attribute that indicates the class to select
 * Example :
 * <li class=""><a href="..." data-type="archive">Archive</a></li>
 * 
 * It gets the elements in the page that has the 
 * class attribute value : archive
 * 
 * In case data-type="all" means every content shows up (kind of reset).
 * Example :
 * <li class=""><a href="..." data-type="all">Tous</a></li>
 * 
 * @returns {void}
 */
var dropdownFilter = function ()
{
    var htmlTag = $('li.dropdown ul.dropdown-menu li.active a');

    if (htmlTag.data('type'))
    {
        filterSet(htmlTag);
    }

    $('li.dropdown ul.dropdown-menu li a').click(function (e) {

        if ($(this).data('type'))
        {
            e.preventDefault();

            filterSet($(this));
        }
    });
};
/**
 * This method is specificaly called by the "dropdownFilter" method
 * (see comments of "dropdownFilter" method for more information).
 * It checks data-type value of an HTML Tag and use it as a class name
 * to define wich content must show up and wich will be hidden.
 * 
 * @param {obj} htmlTag
 * @returns {void}
 */
var filterSet = function (htmlTag)
{
    var type = htmlTag.data('type');
    var name = htmlTag.text();

    $('header.tools-header li').removeClass('active');
    $('header.tools-header li.dropdown>a>span').text(name);
    htmlTag.parent().addClass('active');

    if (type !== 'all')
    {
        $('div.body-section>div:not(.' + type + ')').hide();
        $('.' + type).fadeIn();
    }
    else
    {
        $('div.body-section>div:not(.modal)').fadeIn();
    }
};




$(document).on('ready', function ()
{
    menuToggle();
    asideSubmenu();
    actionAlertBox();
    minified();
    dropdownFilter();
    //wizardAssistant()

});


$(window).on('load', function ()
{
    sidebarFooterInit();
    displayCalendar();
    //daterangePicker();
    singleDatePicker();
    nextFormPart();
    addFormPart();
});