$('.mdi-star-outline').click( function() {
    
    $(this).attr('class', 'mdi mdi-star');
    
    setPreviousStars( this );
    
    setNextStars( this );
    
    var contentStars = $(this).parent();
    
    var idContentStars = contentStars.attr( 'id' );
    
    var valueEval = $( '#' + idContentStars + ' i' ).index( $(this) );
    
    contentStars.next().val( ( valueEval ) );
});

var setPreviousStars = function( currentEl )
{
    $( currentEl ).prevAll( '.mdi-star-outline' ).attr( 'class', 'mdi mdi-star' );
};

var setNextStars = function( currentEl )
{
    $( currentEl ).nextAll( '.mdi-star' ).attr( 'class', 'mdi mdi-star-outline' );
};