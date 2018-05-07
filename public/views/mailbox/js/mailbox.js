$(document).ready(function(){
    
    $('.mail_list:gt(4)').hide();
    $('#mail-list-expand').attr('data-id', 5);
    
    var currentMessageFound = false;
    $('.mail_list').each(function(){
       
       if(!currentMessageFound){
           $(this).show();
           $('#mail-list-expand').attr('data-id', parseInt($('#mail-list-expand').attr('data-id'))+1);
           if($(this).hasClass("current-message")){
               currentMessageFound = true;
               $('#mail-list-column').animate({
                   scrollTop: $('#mail-list-column')[0].scrollHeight
               }, 0);
       
           }
       }
    
    });
    
    
    $('#mail-list-expand').click(function(e){
        
        e.preventDefault();
        $(this).attr('data-id', parseInt($(this).attr('data-id'))+5);
        var i = $(this).attr('data-id');
        //console.log(i);
        $('.mail_list:lt('+i+')').slideDown(800).position('bottom');
        $('#mail-list-column').animate({
            scrollTop: $('#mail-list-column')[0].scrollHeight
        }, 800);
       
        
    });
    
    
    $('textarea#messagemessagerie').focus();
    
    
    
});