jQuery( function()
{
    $('.startHidden').hide();
    
    $('.toggleTrigger').each( function()
    {        
        $( this ).change( function()
        {
            var toggleTarget = $( this ).attr('data-toggle');
            if ( $( this ).is(':checked') )
            {
                $( '.' + toggleTarget ).show();
                $( '.' + toggleTarget ).find('input').attr('required',true);
            } else 
            {
                $( '.' + toggleTarget ).hide();
                $( '.' + toggleTarget ).find('input').attr('required',false);
            }
        })
    })
    
    $('.toggleTriggerReverse').each( function()
    {        
        $( this ).change( function()
        {
            var toggleTarget = $( this ).attr('data-toggle');
            if ( $( this ).is(':checked') )
            {
                $( '.' + toggleTarget ).hide();
                $( '.' + toggleTarget ).find('input').attr('required',false);
            } else 
            {
                $( '.' + toggleTarget ).show();
                $( '.' + toggleTarget ).find('input').attr('required',true);
            }
        })
    })
    
    $(function () {
        $('[data-toggle="popover"]').popover({'trigger':'hover'});
    })
});


