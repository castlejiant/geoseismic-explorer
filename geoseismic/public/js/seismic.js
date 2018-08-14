
function event_call()
{
    $.ajax({
            url:  'home/update',
            type: 'GET',
            cache: false,
            success: function(res) 
            {  
              if(res==1){
              toastr.error('Alert! Earthquake.');
              location.reload();
              }
            },
            error: function() 
            {                
              //  toastr.warning('Error! No Response.'); 
            },
        });
        toastr.options.showMethod = 'slideDown';
        toastr.options.timeOut = 10000;
}

setInterval(event_call, 8000);