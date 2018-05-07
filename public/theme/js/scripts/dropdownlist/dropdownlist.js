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
var dropdownlist = function ()
{
    var htmlTag = $('li.dropdown ul.dropdown-menu li.active a');

    if (htmlTag.data('type'))
    {
        filterset(htmlTag);
    }

    $('li.dropdown ul.dropdown-menu li a').click(function (e) {

        if ($(this).data('type'))
        {
            e.preventDefault();

            filterset($(this));
        }
    });
};
/**
 * This method is specificaly called by the "dropdownlist" method
 * (see comments of "dropdownFilter" method for more information).
 * It checks data-type value of an HTML Tag and use it as a class name
 * to define wich content must show up and wich will be hidden.
 * 
 * @param {obj} htmlTag
 * @returns {void}
 */
var filterset = function (htmlTag)
{
    var type = htmlTag.data('type');

    var name = htmlTag.text();

    $('header.tools-header li').removeClass('active');

    $('header.tools-header li.dropdown>a>span').text(name);

    htmlTag.parent().addClass('active');

    if (type !== 'all')
    {   


            $('header.tools-header:not(.' + type + ')').closest('section.profile').hide();
            $('.' + type).closest('section.profile').fadeIn();
  
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
    dropdownlist();
});