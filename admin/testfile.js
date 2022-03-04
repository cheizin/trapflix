$(document).on("submit","#add-stock-form", function(e){
    e.preventDefault();
    var btn = document.querySelector('.submitting');

// Add the .disabled class
btn.classList.add('disabled');

// Store the original text to a data attribute
btn.setAttribute('data-original', btn.textContent);

// Update the button text
btn.textContent = 'Submitting Stock...';
    var form_data = $(this).serializeArray();
    var form_url = 'controllers/stock-item/stock_controller.php';
    var form_method = 'POST';

    $.ajax({
        type: form_method,
        url: form_url,
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData:false,
        beforeSend: function(){
            $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
            $.unblockUI();
            if(data == 'success')
            {
              Toast.fire({
                 icon: 'success',
                 title: 'Stock Successfully Added'
                });
                $('#add-project-modal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                $('.stock-management-link').click();
                // Restore the button text
                btn.textContent = btn.getAttribute('data-original');

                // Remove the data attribute
          btn.removeAttribute('data-original');

            }

            else if(data == 'failed')
              {
                Toast.fire({
                   icon: 'error',
                   title: 'Failed. Please try again'
                  });
                  // Restore the button text
                  btn.textContent = btn.getAttribute('data-original');

                  // Remove the data attribute
            btn.removeAttribute('data-original');
              }
              else if(data == 'error-uploading')
                {
                  Toast.fire({
                     icon: 'error',
                     title: 'Failed uploading file. Please try again'
                    });
                }
                else if(data == 'invalid-file')
                  {
                    Toast.fire({
                       icon: 'error',
                       title: 'Invalid file type. Please try again'
                      });
                  }
              else
              {
                Toast.fire({
                   icon: 'error',
                   title: 'Failed. Please contact System Administrator'
                  });
              }
              console.log(data);
        },
        error: function(xhr)
        {
          $.unblockUI();
            Toast.fire({
              icon: 'error',
              title: 'Your request could not be completed. Please try again: '+xhr.status
            });
        }
    });
});
