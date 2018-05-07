$(document).on('ready', function(){
    
    var url      = window.location.href;     // Returns full URL
    
    var selectorHashtag = url.substring(url.indexOf("#")+1);
    
    var elementHashTag = $( '#' + selectorHashtag );
    
    
    if( elementHashTag.hasClass('minified') )
    {
        elementHashTag.prev().find('.mdi-chevron-down').click();
    }
    else
    {
        elementHashTag.closest('.minified').prev().find('.mdi-chevron-down').click();
    }
    
    var position = elementHashTag.offset().top;

    $('html, body').animate({ scrollTop:position });
    
});