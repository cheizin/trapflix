function SelectPackage(id)
{
    if(id == '4') // means it is free
    {
        location.replace("https://trapflix.com/home.php?trial_activated='true'");
    }
    else
    {
        form_url = 'PaymentsIframe.php';
        form_method = 'POST';
        form_data = {id:id};
    
        $.ajax({
            method:form_method,
            url: form_url,
            data: form_data,
            
            beforeSend: function()
            {
                
            },
            success: function(data)
            {
                $('#payments_section').html(data);
            },
            error: function(xhr)
            {
                
            }
        })
        
        
        
    }

}