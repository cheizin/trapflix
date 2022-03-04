var Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    onOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
  });

  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: 'btn btn-success',
      cancelButton: 'btn btn-danger'
    },
    buttonsStyling: false
  })
//global variables

var spinner = ` <div class="clearfix">
                        <div class="spinner-border float-right text-info" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
              </div>`;

var dynamic_navbar_url = 'views/layouts/navbar.php';
var dynamic_badge_url = 'views/risk-management/RiskApprovalsBadge.php';

var dynamic_badge_quarterly_update_url = 'views/risk-management/RiskApprovalsBadgeQuarterlyUpdate.php';
var dynamic_badge_new_edited_url='views/risk-management/RiskApprovalsBadgeNewEdited.php';

/*
var blockui_spinner = `<div class="d-flex align-items-center text-primary">
  <strong>Processing...</strong>
  <div class="spinner-border ml-auto" role="status" aria-hidden="true"></div>
</div>`;
*/

$(document).ajaxStart(function() { Pace.restart(); });

var blockui_spinner = `<div id="loaders">
    <span class="loaders loader-1"></span>
    <span class="loaders loader-2"></span>
    <span class="loaders loader-3"></span>
    <span class="loaders loader-4"></span>
</div>`;


//fetch dynamic navbar TO fetch recent notifications
$.ajax({
  url: dynamic_navbar_url,
  method:"POST",
  success: function(returned_navbar_data){
    $('#dynamic-navbar').html(returned_navbar_data);
  },
  error: function(){
    $('#dynamic-navbar').html('please reload page');
  }
});

function LoadDynamicNavbar()
{
  $.ajax({
    url: dynamic_navbar_url,
    method:"POST",
    success: function(returned_navbar_data){
      $('#dynamic-navbar').html(returned_navbar_data);
    },
    error: function(){
      $('#dynamic-navbar').html('please reload page');
    }
  });
}

//fetch dynamic badge for the risk approvals link
$.ajax({
  url: dynamic_badge_url,
  method:"POST",
  success: function(returned_badge_data){
    $('.pending-approval-count').html(returned_badge_data);
  },
  error: function(){
    $('.pending-approval-count').html('please reload page');
  }
});

function LoadDynamicBadge()
{
  $.ajax({
    url: dynamic_badge_url,
    method:"POST",
    success: function(returned_badge_data){
      $('.pending-approval-count').html(returned_badge_data);
    },
    error: function(){
      $('.pending-approval-count').html('please reload page');
    }
  });
}

//fetch the 2 badges for new and quarter
$.ajax({
  url: dynamic_badge_quarterly_update_url,
  method:"POST",
  success: function(returned_badge_data_q){
    $('.quarterly-update-badge').html(returned_badge_data_q);
  },
  error: function(){
    $('.quarterly-update-badge').html('please reload page');
  }
});


function LoadDynamicBadgeQuarterlyUpdate()
{
  $.ajax({
    url: dynamic_badge_quarterly_update_url,
    method:"POST",
    success: function(returned_badge_data_q){
      $('.quarterly-update-badge').html(returned_badge_data_q);
    },
    error: function(){
      $('.quarterly-update-badge').html('please reload page');
    }
  });
}
$.ajax({
  url: dynamic_badge_new_edited_url,
  method:"POST",
  success: function(returned_badge_data){
    $('.new-edited-badge').html(returned_badge_data);
  },
  error: function(){
    $('.new-edited-badge').html('please reload page');
  }
});

function LoadDynamicBadgeNewEdited()
{
  $.ajax({
    url: dynamic_badge_new_edited_url,
    method:"POST",
    success: function(returned_badge_data){
      $('.new-edited-badge').html(returned_badge_data);
    },
    error: function(){
      $('.new-edited-badge').html('please reload page');
    }
  });
}
/* $(document).on("mouseenter","form", function(){
    $('form').sisyphus({

    });
});
*/
//START LOGIN FORM
$(document).on("submit", "#login-form", function(e){
    e.preventDefault();

    var process_url = 'controllers/auth/LoginController.php';
    var form_data = $('#login-form').serializeArray();
    var form_method = 'POST';
    var invalid_server = 'Unable to bind to server: Invalid credentials';
    var disconnected_server = "Can't contact LDAP server";
    var could_not_bind = "Could not bind to Authentication Server";


    $.ajax({
        url:process_url,
        data:form_data,
        method:form_method,
        beforeSend:function(){
            $.blockUI({ message: blockui_spinner });
        },
        success: function(data){
            $.unblockUI();
            if(data == "success")
            {
              location.reload();
                //fetch dynamic navbar
                $.ajax({
                  url: dynamic_navbar_url,
                  method:"POST",
                  success: function(returned_navbar_data){
                    $('#dynamic-navbar').html(returned_navbar_data);
                    console.log(returned_navbar_data);
                  },
                  error: function(){
                    $('#dynamic-navbar').html('please reload page');
                  }
                });
            }
             else if(data == 'invalid')
             {
               Toast.fire({
                   icon: 'error',
                   title: 'Your PPRMIS Account is not set up. Please contact System Administrator'
                 });

                 $(".lockscreen-wrapper").removeClass('shake_effect');
                  setTimeout(function()
                  {
                   $(".lockscreen-wrapper").addClass('shake_effect')
                  },1);
             }
             else if(data.indexOf(invalid_server) != -1)
             {
               console.log(data);
               Toast.fire({
                   icon: 'error',
                   title: 'Invalid Credentials'
                 });
                 $(".lockscreen-wrapper").removeClass('shake_effect');
                  setTimeout(function()
                  {
                   $(".lockscreen-wrapper").addClass('shake_effect')
                  },1);
             }
             else if(data.indexOf(could_not_bind) != -1)
             {
               console.log(data);
               Toast.fire({
                   icon: 'error',
                   title: 'Invalid Credentials'
                 });
                 $(".lockscreen-wrapper").removeClass('shake_effect');
                  setTimeout(function()
                  {
                   $(".lockscreen-wrapper").addClass('shake_effect')
                  },1);
             }
             else if(data.indexOf(disconnected_server) != -1)
             {
               console.log(data);
               Toast.fire({
                   icon: 'error',
                   title: 'Not Connected to Domain'
                 });
                 $(".lockscreen-wrapper").removeClass('shake_effect');
                  setTimeout(function()
                  {
                   $(".lockscreen-wrapper").addClass('shake_effect')
                  },1);
             }
             else
            {
                console.log(data);
                Toast.fire({
                    icon: 'error',
                    title: 'An error occured. Please try again'
                  });
                  $(".lockscreen-wrapper").removeClass('shake_effect');
                   setTimeout(function()
                   {
                    $(".lockscreen-wrapper").addClass('shake_effect')
                   },1);
            }

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


$(document).on("submit", "#test-login-form", function(e){
    e.preventDefault();

    var process_url = 'controllers/auth/TestLoginController.php';
    var form_data = $('#test-login-form').serializeArray();
    var form_method = 'POST';


    $.ajax({
        url:process_url,
        data:form_data,
        method:form_method,
        beforeSend:function(){
            $.blockUI({ message: blockui_spinner });
        },
        success: function(data){
            $.unblockUI();
            if(data == "success")
            {
              location.reload();
                //fetch dynamic navbar
                $.ajax({
                  url: dynamic_navbar_url,
                  method:"POST",
                  success: function(returned_navbar_data){
                    $('#dynamic-navbar').html(returned_navbar_data);
                    console.log(returned_navbar_data);
                  },
                  error: function(){
                    $('#dynamic-navbar').html('please reload page');
                  }
                });
            }
            else if(data == "invalid")
            {
            console.log(data);
            Toast.fire({
                icon: 'error',
                title: 'Invalid Credentials'
              });
            }
            else
            {
                console.log(data);
                Toast.fire({
                    icon: 'error',
                    title: 'An error occured. Please try again'
                  });
            }

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

//END LOGIN FORM

// add stock list

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

                $.ajax({
                  data : $('#add-stock-form').serializeArray(),
                  type: "post",
                  url: "controllers/stock-item/process-send-mail.php",
                  success: function (mail_data) {
                      console.log(mail_data);
                  }
              });

                $('#add-project-modal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                $('.stock-management-link').click();
                // Restore the button text
                btn.textContent = btn.getAttribute('data-original');

                // Remove the data attribute
          btn.removeAttribute('data-original');
          LoadDynamicNavbar();



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

// Sign up to System
$(document).on("submit","#inventory-signup-form", function(e){
    e.preventDefault();
    var btn = document.querySelector('.submitting');

// Add the .disabled class
btn.classList.add('disabled');

// Store the original text to a data attribute
btn.setAttribute('data-original', btn.textContent);

// Update the button text
btn.textContent = 'Changing Password...';
    var form_data = $(this).serializeArray();
    var form_url = 'controllers/user-management/user.php';
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
                 title: 'Password Successfully Changed'
                });
                                $('#add-about-me-modal2').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
            location.reload();

                $.ajax({
                //  location.replace("https://inventory.panoramaengineering.com/");
                  data : $('#inventory-signup-form').serializeArray(),
                  type: "post",
                  url: "controllers/user-management/process-send-mail-user.php",
                  success: function (data) {
                      console.log(data);
                  }
              });

                // Restore the button text
                btn.textContent = btn.getAttribute('data-original');

                // Remove the data attribute
          btn.removeAttribute('data-original');
          LoadDynamicNavbar();


            }

            else if(data == 'failed')
              {
                Toast.fire({
                   icon: 'error',
                   title: 'The User does Not Exist In the sytem. Kindly Confirm Your Email'
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


// Upload Videos

$(document).on("submit","#upload-vidz-form", function(e){
    e.preventDefault();
    var btn = document.querySelector('.submitting');

// Add the .disabled class
btn.classList.add('disabled');

// Store the original text to a data attribute
btn.setAttribute('data-original', btn.textContent);

// Update the button text
btn.textContent = 'Uploading Video..';
    var form_data = $(this).serializeArray();
    var form_url = 'controllers/video-management/uploadLocal.php';
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
                 title: 'Video Uploaded Successfully'
                });

                $.ajax({
                  data : $('#upload-vidz-form').serializeArray(),
                  type: "post",
                  url: "controllers/stock-item/process-send-mail.php",
                  success: function (mail_data) {
                      console.log(mail_data);
                  }
              });

              $('#add-video-modal').modal('hide');
              $('body').removeClass('modal-open');
              $('.modal-backdrop').remove();
              $('.upload-local-link').click();
                // Restore the button text
                btn.textContent = btn.getAttribute('data-original');

                // Remove the data attribute
          btn.removeAttribute('data-original');
          LoadDynamicNavbar();



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

// Add channel Details

$(document).on("submit","#add-channel-form", function(e){
    e.preventDefault();
    var btn = document.querySelector('.submitting');

// Add the .disabled class
btn.classList.add('disabled');

// Store the original text to a data attribute
btn.setAttribute('data-original', btn.textContent);

// Update the button text
btn.textContent = 'Adding Channel ...';
    var form_data = $(this).serializeArray();
    var form_url = 'controllers/channel-management/addChannel.php';
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
                 title: 'Channel Created Successfully'
                });

                $.ajax({
                  data : $('#add-channel-form').serializeArray(),
                  type: "post",
                  url: "controllers/stock-item/process-send-mail.php",
                  success: function (mail_data) {
                      console.log(mail_data);
                  }
              });

              $('#add-channel-modal').modal('hide');
              $('body').removeClass('modal-open');
              $('.modal-backdrop').remove();
              $('.add-channel-link').click();
                // Restore the button text
                btn.textContent = btn.getAttribute('data-original');

                // Remove the data attribute
          btn.removeAttribute('data-original');
          LoadDynamicNavbar();



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

$(document).on("submit","#upload-video-form", function(e){
    e.preventDefault();

    var btn = document.querySelector('.submitting8');

// Add the .disabled class
btn.classList.add('disabled');

// Store the original text to a data attribute
btn.setAttribute('data-original', btn.textContent);

// Update the button text
btn.textContent = 'Uploading Video...';

    var form_data = $(this).serializeArray();
    var form_url = 'controllers/video-management/uploadLocal.php';
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
                 title: 'Video Details Successfully Uploaded'
                });

                $.ajax({
                  data : $('#upload-video-form').serializeArray(),
                  type: "post",
                  url: "controllers/stock-item/process-send-mail-stock-attachment.php",
                  success: function (data) {
                      console.log(data);
                  }
              });
                $('#add-video-modal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                $('.upload-local-link').click();

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
                    // Restore the button text
                    btn.textContent = btn.getAttribute('data-original');

                    // Remove the data attribute
              btn.removeAttribute('data-original');
                }
                else if(data == 'invalid-file')
                  {
                    Toast.fire({
                       icon: 'error',
                       title: 'Invalid file type. Please try again'
                      });
                      // Restore the button text
                      btn.textContent = btn.getAttribute('data-original');

                      // Remove the data attribute
                btn.removeAttribute('data-original');
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


// attached stock list evidence documents

$(document).on("submit","#add-evidence-document-form", function(e){
    e.preventDefault();

    var btn = document.querySelector('.submitting8');

// Add the .disabled class
btn.classList.add('disabled');

// Store the original text to a data attribute
btn.setAttribute('data-original', btn.textContent);

// Update the button text
btn.textContent = 'Attaching Documents...';

    var form_data = $(this).serializeArray();
    var form_url = 'controllers/video-management/uploadVidz.php';
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
                 title: 'CV and Cover Letter saved Successfully'
                });

                $.ajax({
                  data : $('#add-evidence-document-form').serializeArray(),
                  type: "post",
                  url: "controllers/stock-item/process-send-mail-stock-attachment.php",
                  success: function (data) {
                      console.log(data);
                  }
              });
                $('#evidence-document-modal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                $('.academic-docs-link').click();

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
                    // Restore the button text
                    btn.textContent = btn.getAttribute('data-original');

                    // Remove the data attribute
              btn.removeAttribute('data-original');
                }
                else if(data == 'invalid-file')
                  {
                    Toast.fire({
                       icon: 'error',
                       title: 'Invalid file type. Please try again'
                      });
                      // Restore the button text
                      btn.textContent = btn.getAttribute('data-original');

                      // Remove the data attribute
                btn.removeAttribute('data-original');
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

// attached evidence documents for Deliveries
$(document).on("submit","#add-delivery-evidence-document-form", function(e){
    e.preventDefault();

    var btn = document.querySelector('.submitting6');

// Add the .disabled class
btn.classList.add('disabled');

// Store the original text to a data attribute
btn.setAttribute('data-original', btn.textContent);

// Update the button text
btn.textContent = 'Attaching Documents...';

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
                 title: 'Delivery Evidence Documents Successfully Added'
                });
                $('#delivery-evidence-document-modall').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                $('.delivery-evidence-doc-tab').click();

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




// product category list
// add stock list

$(document).on("submit","#add-product_category_form", function(e){
    e.preventDefault();

    var btn = document.querySelector('.submitting');

// Add the .disabled class
btn.classList.add('disabled');

// Store the original text to a data attribute
btn.setAttribute('data-original', btn.textContent);

// Update the button text
btn.textContent = 'Submitting Category...';

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
                 title: 'Stock Category Successfully Added'
                });
                $('#add-product-category-modal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                $('.stock-category-management-link').click();

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


$(document).on("submit","#upload-local-video2", function(e){
    e.preventDefault();

    var btn = document.querySelector('.submitting');

// Add the .disabled class
btn.classList.add('disabled');

// Store the original text to a data attribute
btn.setAttribute('data-original', btn.textContent);

// Update the button text
btn.textContent = 'Submitting Category...';

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
                 title: 'Stock Category Successfully Added'
                });
                $('#add-product-category-modal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                $('.stock-category-management-link').click();

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


// update stock item from supplier_id

$(document).on("submit","#updating-stock-form", function(e){
    e.preventDefault();

    var btn = document.querySelector('.submitting2');

// Add the .disabled class
btn.classList.add('disabled');

// Store the original text to a data attribute
btn.setAttribute('data-original', btn.textContent);
// Update the button text
btn.textContent = 'Updating Stock...';
    var form_data = $(this).serializeArray();
    var form_url = 'controllers/stock-item/update_stock_invoice.php';
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
                 title: 'Stock Successfully Updated'
                });

                $.ajax({
                  data : $('#updating-stock-form').serializeArray(),
                  type: "post",
                  url: "controllers/stock-item/process-send-mail-stock-updates.php",
                  success: function (data) {
                      console.log(data);
                    }
            });
                $('#update-stock-modal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                $('.stock-payments-tab').click();

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
//START FETCHING REPORTS
$(document).on("submit","#all_stocks_in_production_form", function (e){
e.preventDefault();

  var form_data = $(this).serializeArray();
  var target_url  = 'views/reports/fetch_all_stocks_in_production.php';
  var form_method = 'POST';

    $.ajax({
    data : form_data,
    url  : target_url,
    method : form_method,
    beforeSend: function()
    {
      $('#generated-report-data').html('');
      $.blockUI({ message: blockui_spinner });
    },
    success:function(data){
     $.unblockUI();
    if(data == 'failed')
    {
        Toast.fire({
              icon: 'error',
              title: 'Failed. Please try again'
          });
    }
    else
    {
        $('#generated-report-data').html(data);
        LoadDatatables();
    }

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


$(document).on("submit","#all_stock_items_form", function (e){
e.preventDefault();

  var form_data = $(this).serializeArray();
  var target_url  = 'views/reports/fetch_all_stock_items.php';
  var form_method = 'POST';

    $.ajax({
    data : form_data,
    url  : target_url,
    method : form_method,
    beforeSend: function()
    {
      $('#generated-report-data').html('');
      $.blockUI({ message: blockui_spinner });
    },
    success:function(data){
     $.unblockUI();
    if(data == 'failed')
    {
        Toast.fire({
              icon: 'error',
              title: 'Failed. Please try again'
          });
    }
    else
    {
        $('#generated-report-data').html(data);
        LoadDatatables();
    }

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

$(document).on("submit","#all_out_of_stock_items_form", function (e){
e.preventDefault();

  var form_data = $(this).serializeArray();
  var target_url  = 'views/reports/fetch_all_out_of_stock_items.php';
  var form_method = 'POST';

    $.ajax({
    data : form_data,
    url  : target_url,
    method : form_method,
    beforeSend: function()
    {
      $('#generated-report-data').html('');
      $.blockUI({ message: blockui_spinner });
    },
    success:function(data){
     $.unblockUI();
    if(data == 'failed')
    {
        Toast.fire({
              icon: 'error',
              title: 'Failed. Please try again'
          });
    }
    else
    {
        $('#generated-report-data').html(data);
        LoadDatatables();
    }

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

$(document).on("submit","#all_end_product_form", function (e){
e.preventDefault();

  var form_data = $(this).serializeArray();
  var target_url  = 'views/reports/fetch_all_end_product.php';
  var form_method = 'POST';

    $.ajax({
    data : form_data,
    url  : target_url,
    method : form_method,
    beforeSend: function()
    {
      $('#generated-report-data').html('');
      $.blockUI({ message: blockui_spinner });
    },
    success:function(data){
     $.unblockUI();
    if(data == 'failed')
    {
        Toast.fire({
              icon: 'error',
              title: 'Failed. Please try again'
          });
    }
    else
    {
        $('#generated-report-data').html(data);
        LoadDatatables();
    }

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

$(document).on("submit","#all_products_delivered_form", function (e){
e.preventDefault();

  var form_data = $(this).serializeArray();
  var target_url  = 'views/reports/fetch_all_products_delivered.php';
  var form_method = 'POST';

    $.ajax({
    data : form_data,
    url  : target_url,
    method : form_method,
    beforeSend: function()
    {
      $('#generated-report-data').html('');
      $.blockUI({ message: blockui_spinner });
    },
    success:function(data){
     $.unblockUI();
    if(data == 'failed')
    {
        Toast.fire({
              icon: 'error',
              title: 'Failed. Please try again'
          });
    }
    else
    {
        $('#generated-report-data').html(data);
        LoadDatatables();
    }

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

$(document).on("submit","#all_products_in_production_form", function (e){
e.preventDefault();

  var form_data = $(this).serializeArray();
  var target_url  = 'views/reports/fetch_all_products_in_production.php';
  var form_method = 'POST';

    $.ajax({
    data : form_data,
    url  : target_url,
    method : form_method,
    beforeSend: function()
    {
      $('#generated-report-data').html('');
      $.blockUI({ message: blockui_spinner });
    },
    success:function(data){
     $.unblockUI();
    if(data == 'failed')
    {
        Toast.fire({
              icon: 'error',
              title: 'Failed. Please try again'
          });
    }
    else
    {
        $('#generated-report-data').html(data);
        LoadDatatables();
    }

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

$(document).on("submit","#all_paid_invoices_form", function (e){
e.preventDefault();

  var form_data = $(this).serializeArray();
  var target_url  = 'views/reports/fetch_all_paid_invoices.php';
  var form_method = 'POST';

    $.ajax({
    data : form_data,
    url  : target_url,
    method : form_method,
    beforeSend: function()
    {
      $('#generated-report-data').html('');
      $.blockUI({ message: blockui_spinner });
    },
    success:function(data){
     $.unblockUI();
    if(data == 'failed')
    {
        Toast.fire({
              icon: 'error',
              title: 'Failed. Please try again'
          });
    }
    else
    {
        $('#generated-report-data').html(data);
        LoadDatatables();
    }

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

$(document).on("submit","#all_pending_payments_form", function (e){
e.preventDefault();

  var form_data = $(this).serializeArray();
  var target_url  = 'views/reports/fetch_all_pending_payments.php';
  var form_method = 'POST';

    $.ajax({
    data : form_data,
    url  : target_url,
    method : form_method,
    beforeSend: function()
    {
      $('#generated-report-data').html('');
      $.blockUI({ message: blockui_spinner });
    },
    success:function(data){
     $.unblockUI();
    if(data == 'failed')
    {
        Toast.fire({
              icon: 'error',
              title: 'Failed. Please try again'
          });
    }
    else
    {
        $('#generated-report-data').html(data);
        LoadDatatables();
    }

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

$(document).on("submit","#all_profit_loss_form", function (e){
e.preventDefault();

  var form_data = $(this).serializeArray();
  var target_url  = 'views/reports/fetch_all_profit_loss_form.php';
  var form_method = 'POST';

    $.ajax({
    data : form_data,
    url  : target_url,
    method : form_method,
    beforeSend: function()
    {
      $('#generated-report-data').html('');
      $.blockUI({ message: blockui_spinner });
    },
    success:function(data){
     $.unblockUI();
    if(data == 'failed')
    {
        Toast.fire({
              icon: 'error',
              title: 'Failed. Please try again'
          });
    }
    else
    {
        $('#generated-report-data').html(data);
        LoadDatatables();
    }

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






//calculation for stock quantity

                 $('#unit_price').change(function(){

              var unit_price = parseInt($('#unit_price').val());

               var qtt = parseInt($('#qtt').val());


           var total= parseInt(unit_price * qtt);



            $('#total').val(total);



    });
              $('#qtt').change(function()
              {

                var unit_price = parseInt($('#unit_price').val());

                 var qtt = parseInt($('#qtt').val());


             var tot_stock = parseInt(unit_price * qtt);



              $('#total_stock').val(tot_stock);

    }
  );



      $('#ps_total_cost').change(function(){
        var ps_games_no = parseInt($('#ps_games_no').val());

               var unit_cost = parseInt($('#unit_cost').val());


           var tot_collection = parseInt(ps_games_no * unit_cost);


            $('#ps_total_cost').val(tot_collection);


    });

            $('#record_game').change(function(){

         var ps_games_no = parseInt($('#ps_games_no').val());

          var unit_cost = parseInt($('#unit_cost').val());


      var tot_collection = parseInt(ps_games_no * unit_cost);



       $('#ps_total_cost').val(tot_collection);


    });

//end add stock list

function SubmitStockInvoice(str)
{
  var btn = document.querySelector('.submitting');

// Add the .disabled class
btn.classList.add('disabled');

// Store the original text to a data attribute
btn.setAttribute('data-original', btn.textContent);

// Update the button text
btn.textContent = 'Submitting Invoice...';
    var id = str;
    var form_id = $('#invoice-stock-payment-form-'+str);
    var supplier_id = $('#supplier_id-'+str).val();
    var payment_type = $('#payment_type-'+str).val();
    var debit = $('#debit-'+str).val();
    var transaction_id = $('#transaction_id-'+str).val();
  //  var invoice_doc = $('#invoice_doc-'+str).val();
    var add_stock_invoice_payment = 'add_stock_invoice_payment';


    $(document).on('submit', form_id, function(event){
        event.preventDefault();
    });


    var form_data = {
      id : id,
      supplier_id : supplier_id,
      payment_type:payment_type,
      debit:debit,
      transaction_id:transaction_id,
    //  invoice_doc:invoice_doc,
      add_stock_invoice_payment:add_stock_invoice_payment,

    };
    var form_url = 'controllers/stock-item/stock_invoice_payment.php';
    var form_method = 'POST';

    $.ajax({
        data : form_data,
        url  : form_url,
        method : form_method,
        beforeSend:function()
        {
          $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
            $.unblockUI();
            if(data == 'success')
            {
              $('#invoice-payment-modal-'+str).modal('hide');
              $('body').removeClass('modal-open');
              $('.modal-backdrop').remove();

               Toast.fire({
                    icon: 'success',
                    title: 'Invoice Successfully Paid'
                  });

              $('.stock-payments-tab').click();
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
}

// invoice list payment_type
function SubmitStockInvoice2(str)
{

  var btn = document.querySelector('.submitting');

// Add the .disabled class
btn.classList.add('disabled');

// Store the original text to a data attribute
btn.setAttribute('data-original', btn.textContent);

// Update the button text
btn.textContent = 'Submitting Payment...';
    var id = str;
    var form_id = $('#invoice-stock-payment-form-'+str);
    var supplier_id = $('#supplier_id-'+str).val();
    var payment_type = $('#payment_type-'+str).val();
    var debit = $('#debit-'+str).val();
    var transaction_id = $('#transaction_id-'+str).val();
  //  var invoice_doc = $('#invoice_doc-'+str).val();
    var add_stock_invoice_payment = 'add_stock_invoice_payment';


    $(document).on('submit', form_id, function(event){
        event.preventDefault();
    });


    var form_data = {
      id : id,
      supplier_id : supplier_id,
      payment_type:payment_type,
      debit:debit,
      transaction_id:transaction_id,
    //  invoice_doc:invoice_doc,
      add_stock_invoice_payment:add_stock_invoice_payment,

    };
    var form_url = 'controllers/stock-item/stock_invoice_payment.php';
    var form_method = 'POST';

    $.ajax({
        data : form_data,
        url  : form_url,
        method : form_method,
        beforeSend:function()
        {
          $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
            $.unblockUI();
            if(data == 'success')
            {
              $('#invoice-payment-modal-'+str).modal('hide');
              $('body').removeClass('modal-open');
              $('.modal-backdrop').remove();

               Toast.fire({
                    icon: 'success',
                    title: 'Invoice Successfully Paid'
                  });

              $('.invoice-received-management-link').click();
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
}

// end milestone paymnet

//start end product creation
$(document).on("submit","#add-end-product-form", function(e){
    e.preventDefault();

    var btn = document.querySelector('.submitting');

// Add the .disabled class
btn.classList.add('disabled');

// Store the original text to a data attribute
btn.setAttribute('data-original', btn.textContent);

// Update the button text
btn.textContent = 'Submitting End Product...';

    var form_data = $(this).serializeArray();
    var form_url = 'controllers/stock-item/end_product.php';
    var form_method = 'POST';

    $.ajax({
        type: form_method,
        url: form_url,
        data: form_data,
        beforeSend: function(){
            $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
            $.unblockUI();
            if(data == 'success')
            {
              Toast.fire({
                 icon: 'success',
                 title: 'End product Successfully Added'
                });

                $.ajax({
data : $('#add-end-product-form').serializeArray(),
type: "post",
url: "controllers/stock-item/process-send-mail-end-product.php",
success: function (data) {
   console.log(data);
}
});
                $('#add-end-product-modal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                $('.end-product-management-link').click();
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
//end end product creation

// Add single end product form

$(document).on("submit","#add-single-end-product-form", function(e){
    e.preventDefault();

    var btn = document.querySelector('.submitting');

// Add the .disabled class
btn.classList.add('disabled');

// Store the original text to a data attribute
btn.setAttribute('data-original', btn.textContent);

// Update the button text
btn.textContent = 'Submitting Product..';

    var form_data = $(this).serializeArray();
    var form_url = 'controllers/stock-item/single_end_product.php';
    var form_method = 'POST';

    $.ajax({
        type: form_method,
        url: form_url,
        data: form_data,
        beforeSend: function(){
            $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
            $.unblockUI();
            if(data == 'success')
            {

              Toast.fire({
                 icon: 'success',
                 title: 'Stock Requested Successfully From Store'
                });
                $.ajax({
                  data : $('#add-single-end-product-form').serializeArray(),
                  type: "post",
                  url: "controllers/stock-item/process-send-mail-stock-request.php",
                  success: function (data) {
                      console.log(data);
                  }
              });
                $('#add-end-product-modal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                $('.end-product-tab').click();
                // Restore the button text
                btn.textContent = btn.getAttribute('data-original');

                // Remove the data attribute
          btn.removeAttribute('data-original');

            }

            else if(data == 'below')
              {
                Toast.fire({
                   icon: 'error',
                   title: 'You cannot request Below The available Quantity'
                  });
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
//end end product creation

// Add Stock returns
$(document).on("submit","#return-single-end-product-form", function(e){
    e.preventDefault();

    var btn = document.querySelector('.submitting');

// Add the .disabled class
btn.classList.add('disabled');

// Store the original text to a data attribute
btn.setAttribute('data-original', btn.textContent);

// Update the button text
btn.textContent = 'Submitting Return...';

    var form_data = $(this).serializeArray();
    var form_url = 'controllers/stock-item/single_end_product.php';
    var form_method = 'POST';

    $.ajax({
        type: form_method,
        url: form_url,
        data: form_data,
        beforeSend: function(){
            $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
            $.unblockUI();
            if(data == 'success')
            {

              Toast.fire({
                 icon: 'success',
                 title: 'Stock Successfully Returned To Store'
                });
                $.ajax({
                  data : $('#return-single-end-product-form').serializeArray(),
                  type: "post",
                  url: "controllers/stock-item/process-send-mail-stock-return-store.php",
                  success: function (data) {
                      console.log(data);
                  }
              });
                $('#retun-stock-modal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                $('.stocks-returns-tab').click();
                // Restore the button text
                btn.textContent = btn.getAttribute('data-original');

                // Remove the data attribute
          btn.removeAttribute('data-original');

            }

            else if(data == 'below')
              {
                Toast.fire({
                   icon: 'error',
                   title: 'You cannot return More than the Quantity Requested To Production'
                  });
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
//add product Returns

$(document).on("submit","#returns-delivery-product-form", function(e){
    e.preventDefault();

    var btn = document.querySelector('.submitting');

// Add the .disabled class
btn.classList.add('disabled');

// Store the original text to a data attribute
btn.setAttribute('data-original', btn.textContent);

// Update the button text
btn.textContent = 'Submitting Return...';

    var form_data = $(this).serializeArray();
    var form_url = 'controllers/customer-management/customer_delivery.php';
    var form_method = 'POST';

    $.ajax({
        type: form_method,
        url: form_url,
        data: form_data,
        beforeSend: function(){
            $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
            $.unblockUI();
            if(data == 'success')
            {

              Toast.fire({
                 icon: 'success',
                 title: 'Product Successfully Returned To Production'
                });
                $.ajax({
                  data : $('#returns-delivery-product-form').serializeArray(),
                  type: "post",
                  url: "controllers/customer-management/process-send-mail-product-return.php",
                  success: function (data) {
                      console.log(data);
                  }
              });
                $('#retun-stock-modal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                $('.return-inwards-tabb').click();
                // Restore the button text
                btn.textContent = btn.getAttribute('data-original');

                // Remove the data attribute
          btn.removeAttribute('data-original');

            }

            else if(data == 'below')
              {
                Toast.fire({
                   icon: 'error',
                   title: 'You cannot return More than the Quantity Delivered To Customer'
                  });
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


// Product delivery

$(document).on("submit","#add-delivery-product-form", function(e){
    e.preventDefault();

    var btn = document.querySelector('.submitting');

// Add the .disabled class
btn.classList.add('disabled');

// Store the original text to a data attribute
btn.setAttribute('data-original', btn.textContent);

// Update the button text
btn.textContent = 'Submitting Delivery...';

    var form_data = $(this).serializeArray();
    var form_url = 'controllers/customer-management/customer_delivery.php';
    var form_method = 'POST';

    $.ajax({
        type: form_method,
        url: form_url,
        data: form_data,
        beforeSend: function(){
            $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
            $.unblockUI();
            if(data == 'success')
            {

              Toast.fire({
                 icon: 'success',
                 title: 'Product Delivery Successfully Added'
                });

                                  $.ajax({
                                    data : $('#add-delivery-product-form').serializeArray(),
                                    type: "post",
                                    url: "controllers/customer-management/process-send-mail-product-delivery.php",
                                    success: function (data) {
                                        console.log(data);
                                      }
                              });

                $('#delivery-stock-modal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                $('.product-delivery-tab').click();
                // Restore the button text
                btn.textContent = btn.getAttribute('data-original');

                // Remove the data attribute
          btn.removeAttribute('data-original');

            }
            else if(data == 'below')
            {
    Toast.fire({
       icon: 'error',
       title: 'You cannot Deliver above the Production Units'
      });
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
//end end product creation

// adding youtube link

$(document).on("submit","#add-youtube-form", function(e){
    e.preventDefault();

    var btn = document.querySelector('.submitting');

// Add the .disabled class
btn.classList.add('disabled');

// Store the original text to a data attribute
btn.setAttribute('data-original', btn.textContent);

// Update the button text
btn.textContent = 'Submitting ...';
    var form_data = $(this).serializeArray();
    var form_url = 'controllers/video-management/youtube_link.php';
    var form_method = 'POST';

    $.ajax({
        type: form_method,
        url: form_url,
        data: form_data,
        beforeSend: function(){
            $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
            $.unblockUI();
            if(data == 'success')
            {

              Toast.fire({
                 icon: 'success',
                 title: 'youtube Successfully Added'
                });


                $('#add-youtube-video-modal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                $('.upload-youtube-link').click();
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
//end youtube link

// adding client list

$(document).on("submit","#add-customer-list-form", function(e){
    e.preventDefault();

    var btn = document.querySelector('.submitting');

// Add the .disabled class
btn.classList.add('disabled');

// Store the original text to a data attribute
btn.setAttribute('data-original', btn.textContent);

// Update the button text
btn.textContent = 'Submitting ...';
    var form_data = $(this).serializeArray();
    var form_url = 'controllers/customer-management/customer_list_controller.php';
    var form_method = 'POST';

    $.ajax({
        type: form_method,
        url: form_url,
        data: form_data,
        beforeSend: function(){
            $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
            $.unblockUI();
            if(data == 'success')
            {

              Toast.fire({
                 icon: 'success',
                 title: 'Client Details Successfully Added'
                });

                $.ajax({
                  data : $('#add-customer-list-form').serializeArray(),
                  type: "post",
                  url: "controllers/customer-management/process-send-mail-customer-creation.php",
                  success: function (data) {
                      console.log(data);
                    }
            });

                $('#add-customer-modal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                $('.customer-management-link').click();
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
//end end product creation


// adding supplier list
// adding client list

$(document).on("submit","#add-supplier-list-form", function(e){
    e.preventDefault();

    var btn = document.querySelector('.submitting');

// Add the .disabled class
btn.classList.add('disabled');

// Store the original text to a data attribute
btn.setAttribute('data-original', btn.textContent);

// Update the button text
btn.textContent = 'Submitting Supplier...';

    var form_data = $(this).serializeArray();
    var form_url = 'controllers/supplier-management/supplier_list_controller.php';
    var form_method = 'POST';

    $.ajax({
        type: form_method,
        url: form_url,
        data: form_data,
        beforeSend: function(){
            $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
            $.unblockUI();
            if(data == 'success')
            {

              Toast.fire({
                 icon: 'success',
                 title: 'Supplier Details Successfully Added'
                });
                    $.ajax({
                      data : $('#add-supplier-list-form').serializeArray(),
                      type: "post",
                      url: "controllers/supplier-management/process-send-mail-supplier.php",
                      success: function (data) {
                          console.log(data);
                      }
                  });
                $('#add-supplier-modal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                $('.supplier-management-link').click();
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
//end end product creation


//start add resource
function SubmitProductResource(str)
{
    var id = str;
    var form_id = $('#add-product-resource-form-'+str);
    var resource_name = $('#resource_name-'+str).val();
    var add_product_resource = 'add_product_resource';

    $(document).on('submit', form_id, function(event){
        event.preventDefault();
    });


    var form_data = {
      id  : id,
      resource_name:resource_name,
      add_product_resource:add_product_resource
    };
    var form_url = 'controllers/stock-item/product_resource_controller.php';
    var form_method = 'POST';

    $.ajax({
        data : form_data,
        url  : form_url,
        method : form_method,
        beforeSend:function()
        {

          $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
            $.unblockUI();
            if(data == 'success')
            {
              $('body').removeClass('modal-open');
              $('.modal-backdrop').remove();

               Toast.fire({
                    icon: 'success',
                    title: 'End Product Resource Added Successfully'
                  });

              $('.end-product-resource-link').click();
              //ViewProject(project_id);
            }
            else if(data == 'failed')
            {
             Toast.fire({
                    icon: 'error',
                    title: 'Failed. Please try again'
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
}
//end add end product resource
//start add prodcut status
function SubmitProductStatus(str)
{
    var reference_no = str;
    var form_id = $('#add-product-status-form-'+str);
    var product_status = $('#product_status-'+str).val();
    var product_status_comments = $('#add_product_status_comments-'+str).val();
    var add_product_status = 'add_product_status';

    $(document).on('submit', form_id, function(event){
        event.preventDefault();
    });


    var form_data = {
      reference_no : reference_no,
      product_status:product_status,
      product_status_comments:product_status_comments,
      add_product_status:add_product_status
    };
      var form_url = 'controllers/stock-item/product_resource_controller.php';
    var form_method = 'POST';

    $.ajax({
        data : form_data,
        url  : form_url,
        method : form_method,
        beforeSend:function()
        {
          $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
            $.unblockUI();
            if(data == 'success')
            {
              $('body').removeClass('modal-open');
              $('.modal-backdrop').remove();

               Toast.fire({
                    icon: 'success',
                    title: 'Task Updated Successfully'
                  });

              $('.project-resource-plan-tab').click();
              //ViewProject(project_id);
            }
            else if(data == 'failed')
            {
             Toast.fire({
                    icon: 'error',
                    title: 'Failed. Please try again'
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
}

//end add task status

//get amount for each selected End Product
function getDsAmount() {
  $("#unit_p").html('');
        var str='';
        var val=document.getElementById('product_name');
        for (i=0;i< val.length;i++) {
            if(val[i].selected){
                str += val[i].value + ',';
            }
        }
        var str=str.slice(0,str.length -1);
        console.log(str);


	$.ajax({
        	type: "GET",
        	url: "controllers/stock-item/fetch_product_details.php",
        	data:'subscription_type='+str,
        	success: function(data){
        		$("#unit_p").html(data);
        	},
          error: function(xhr)
          {
            Toast.fire({
                      type: 'error',
                      title: 'Your request could not be completed. Please try again: '+xhr.status
              });
          }
	});
}

//start remove resource
function DeleteEndResource(resource_id) {

  Swal.fire({
      title: 'This Resource will be removed from the assigned End Product',
      text: "Are you sure to proceed?",
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'YES, REMOVE RESOURCE',
      cancelButtonText: 'NO, KEEP RESOURCE!',
      reverseButtons: true
    }).then((result) => {
      if (result.value) {

        var delete_resource = 'delete_resource';
        var form_data = {
          resource_id:resource_id,
          delete_resource:delete_resource
        };
        var form_url = 'controllers/stock-item/product_resource_controller.php';
        var form_method = 'POST';

        $.ajax({
            data : form_data,
            url  : form_url,
            method : form_method,
            beforeSend:function()
            {
              $.blockUI({ message: blockui_spinner });
            },
            success:function(data){
              $.unblockUI();
              if(data == "success")
              {
                Swal.fire(
                  'DELETED',
                  'Resource Deleted Successfully',
                  'success'
                );
                $('.end-product-resource-link').click();
                //ViewProject(project_id);

              }
              else
              {
                Swal.fire(
                  'NOT DELETED',
                  'Failed to Delete. Please try again',
                  'error'
                );
                console.log(data);
              }
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

      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
        Swal.fire(
          'Cancelled',
          'Not Deleted',
          'error'
        );
      }
  });
}

//end remove resource
//START RISK MANAGEMENT
//START ADD risk management form
$(document).on("submit","#add-risk-management-form", function(e){
    e.preventDefault();

    var dep_code = $('#department_code').val();

    var form_data = $(this).serializeArray();
    var form_url = 'controllers/risk-management/process-add-risk-management-form.php';
    var form_method = 'POST';
    var loader =`<div class="loader text-center"></div>`;
    var risk_opportunity = $('#risk_opportunity').val();
    $('#loader').html(loader);

    $.ajax({
        data : form_data,
        url  : form_url,
        method : form_method,
        beforeSend:function()
        {
          $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
            $.unblockUI();
            if(data == 'success')
            {
              $('body').removeClass('modal-open');
              $('.modal-backdrop').remove();
              SelectDepartmentRisk(dep_code);
                  Toast.fire({
                      icon: 'success',
                      title: 'Risk Successfully Added'
                    });
                    LoadDynamicNavbar();
                    LoadDynamicBadge();
                    LoadDynamicBadgeQuarterlyUpdate();
                    LoadDynamicBadgeNewEdited();

                    $.ajax({
                          data : $('#add-risk-management-form').serializeArray(),
                          type: "post",
                          url: "controllers/risk-management/process-send-mail-add-risk-management.php",
                          success: function (mail_data) {
                              console.log(mail_data);
                          }
                      });

            }
            else if(data == 'failed')
            {
              Toast.fire({
                  icon: 'error',
                  title: 'Failed. Please Try Again'
                });
            }
            else
            {
              Toast.fire({
                  icon: 'error',
                  title: 'Could not Submit. Please Contact System Administrator'
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

//END ADD risk management form


//START ADD opportunity management form
$(document).on("submit","#add-opportunity-management-form", function(e){
    e.preventDefault();

    var dep_code = $('#department_code_opp').val();

    var form_data = $(this).serializeArray();
    var form_url = 'controllers/risk-management/process-add-risk-management-form.php';
    var form_method = 'POST';
    var loader =`<div class="loader text-center"></div>`;
    var risk_opportunity = $('#risk_opportunity_opp').val();
    $('#loader').html(loader);

    $.ajax({
        data : form_data,
        url  : form_url,
        method : form_method,
        beforeSend:function()
        {
          $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
            $.unblockUI();
            if(data == 'success')
            {
              $('body').removeClass('modal-open');
              $('.modal-backdrop').remove();
              SelectDepartmentRisk(dep_code);
                  Toast.fire({
                      icon: 'success',
                      title: 'Opportunity Successfully Added'
                    });
                    LoadDynamicNavbar();
                    LoadDynamicBadge();
                    LoadDynamicBadgeQuarterlyUpdate();
                    LoadDynamicBadgeNewEdited();

                    $.ajax({
                          data : $('#add-opportunity-management-form').serializeArray(),
                          type: "post",
                          url: "controllers/risk-management/process-send-mail-add-risk-management.php",
                          success: function (mail_data) {
                              console.log(mail_data);
                          }
                      });

            }
            else if(data == 'failed')
            {
              Toast.fire({
                  icon: 'error',
                  title: 'Failed. Please Try Again'
                });
            }
            else
            {
              Toast.fire({
                  icon: 'error',
                  title: 'Could not Submit. Please Contact System Administrator'
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

//END ADD opportunity management form


//start update quarterly risk form
$(document).on("submit","#update-status-risk-management-form", function(e){
    e.preventDefault();

    var form_data =  $(this).serializeArray();
    var form_url = 'controllers/risk-management/process-update-risk-risk-management-form.php';
    var form_method = 'POST';
    var dep_code = $('#dep_code').val();

    $.ajax({
        data : form_data,
        url  : form_url,
        method : form_method,
        beforeSend: function()
        {
            $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
          $.unblockUI();
            if(data == 'success')
            {
              $('body').removeClass('modal-open');
              $('.modal-backdrop').remove();
              SelectDepartmentRisk(dep_code);
                  Toast.fire({
                      icon: 'success',
                      title: 'Updated Successfully'
                    });
                    LoadDynamicNavbar();
                    LoadDynamicBadge();
                    LoadDynamicBadgeQuarterlyUpdate();
                    LoadDynamicBadgeNewEdited();

                  $.ajax({
                     data: $('#update-status-risk-management-form').serializeArray(),
                      type: "post",
                      url: "controllers/risk-management/process-send-mail-update-risk-management.php",
                      success: function (data) {
                          console.log(data);
                      }
                  });
            }
            else if(data == 'failed')
            {
              Toast.fire({
                  icon: 'error',
                  title: 'Failed to Update. Please Try Again'
                });
            }
            else if(data == 'duplicate')
            {

              swalWithBootstrapButtons.fire({
                title: 'This Quarterly Update for this period has already been submitted',
                text: "Is this a re-submission?",
                icon: 'question',
                showCancelButton: true,
                allowOutsideClick: false,
                confirmButtonText: 'YES, CONTINUE',
                cancelButtonText: 'NO, CANCEL!',
                reverseButtons: true
              }).then((result) => {
                if (result.value) {

                  $.ajax({
                      data : form_data,
                      url  : "controllers/risk-management/process-edit-update-risk-risk-management-form.php",
                      method : form_method,
                      success:function(data)
                      {
                        if(data == "success")
                        {
                          SelectDepartmentRisk(dep_code);
                          swalWithBootstrapButtons.fire(
                            'RESUBMITTED',
                            'This Quarterly Update has successfully been resubmitted',
                            'success'
                          );
                          LoadDynamicNavbar();
                          LoadDynamicBadge();
                          LoadDynamicBadgeQuarterlyUpdate();
                          LoadDynamicBadgeNewEdited();

                            $.ajax({
                                data: $('#update-status-risk-management-form').serializeArray(),
                                type: "post",
                                url: "controllers/risk-management/process-send-mail-update-risk-management.php",
                                success: function (mail_data) {
                                           console.log(mail_data);
                                       }
                                   });
                           }
                           else
                           {
                             swalWithBootstrapButtons.fire(
                               'FAILED',
                               'Failed to resubmit. Please Try again',
                               'error'
                             );

                           }
                        }

                    });
                } else if (
                  /* Read more about handling dismissals below */
                  result.dismiss === Swal.DismissReason.cancel
                ) {
                  swalWithBootstrapButtons.fire(
                    'Cancelled',
                    'The resubmission has been cancelled :)',
                    'error'
                  );
                }
              });

            }
            else
            {
              swalWithBootstrapButtons.fire(
                'FAILED',
                'Failed to update. Please Try again',
                'error'
              );
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

//end update quarterly form


//start edit risk management form
$(document).on("submit","#edit-risk-management-form", function(e){
    e.preventDefault();

    var form_data = $(this).serializeArray();
    var form_url = 'controllers/risk-management/process-edit-risk-management-form.php';
    var form_method = 'POST';
    var dep_code = $('#department_code').val();

    $.ajax({
        data : form_data,
        url  : form_url,
        method : form_method,
        beforeSend: function()
        {
          $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
            $.unblockUI();
            if(data == 'success')
            {
              $('body').removeClass('modal-open');
              $('.modal-backdrop').remove();
              SelectDepartmentRisk(dep_code);

              Toast.fire({
                  icon: 'success',
                  title: 'Successfully Modified'
                });
                LoadDynamicNavbar();
                LoadDynamicBadge();
                LoadDynamicBadgeQuarterlyUpdate();
                LoadDynamicBadgeNewEdited();

                  $.ajax({
                      data: $('#edit-risk-management-form').serializeArray(),
                      type: "post",
                      url: "controllers/risk-management/process-send-mail-edit-risk-management.php",
                      success: function (data) {
                          console.log(data);
                      }
                  });
            }
            else if(data == 'failed')
            {
              Toast.fire({
                  icon: 'error',
                  title: 'Failed. Please Try Again'
                });
            }
            else
            {
              Toast.fire({
                  icon: 'error',
                  title: 'Failed. An Error occured.Please contact System Administrator'
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

//END EDIT risk management form

//start copy risk to other department
$(document).on("submit","#copy-risk-form", function(e){
  e.preventDefault();

  var form_data = $(this).serializeArray();
  var form_url = 'controllers/risk-management/copy-to-other-departments.php';
  var form_method = 'POST';



  $.ajax({
      data : form_data,
      url  : form_url,
      method : form_method,
      beforeSend: function()
      {
        $.blockUI({ message: blockui_spinner });
      },
      success:function(data){
        $.unblockUI();
          if(data == 'success')
          {
            Toast.fire({
                icon: 'success',
                title: 'Successfully Copied!'
              });

              $.ajax({
                  data : $('#copy-risk-form').serializeArray(),
                  type: "post",
                  url: "controllers/risk-management/process-send-mail-copy.php",
                  success: function (data) {
                      console.log(data);
                  }
              });
          }
          else if(data == 'duplicate')
          {
            Toast.fire({
                icon: 'warning',
                title: 'Not Copied, This risk already exists in the department you are copying to!'
              });
          }
          else
          {
            Toast.fire({
                icon: 'error',
                title: 'Not Copied. An error occured. Please try again!'
              });
          }
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

//end copy risk to other department

//start close risk
$(document).on("submit","#close-risk-form", function(e){
    e.preventDefault();

    Swal.fire({
        title: 'Retire Risk?',
        text: "Once you confirm, this risk will no longer appear on the risk register. Are you sure to proceed?",
        icon: 'question',
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonText: 'YES, RETIRE',
        cancelButtonText: 'NO, KEEP!',
        reverseButtons: true
      }).then((result) => {
        if (result.value) {

          var form_data = $('#close-risk-form').serializeArray();
          var form_url = 'controllers/risk-management/process-delete-risk-management.php';
          var form_method = 'POST';
          var dep_code = $('#dep').val();

          $.ajax({
              data : form_data,
              url  : form_url,
              method : form_method,
              beforeSend:function()
              {
                $.blockUI({ message: blockui_spinner });
              },
              success:function(data){
                {
                  $.unblockUI();
                }
                if(data == "success")
                {
                  Swal.fire(
                    'RETIRED',
                    'Successfully Retired',
                    'success'
                  );
                  SelectDepartmentRisk(dep_code);

                  $.ajax({
                      data : $('#close-risk-form').serializeArray(),
                      type: "post",
                      url: "controllers/risk-management/process-send-mail-close-open-risk.php",
                      success: function (data) {
                          console.log(data);
                      }
                  });
                }
                else
                {
                  Swal.fire(
                    'NOT RETIRED',
                    'Failed to Close. Please try again',
                    'error'
                  );
                }
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

        } else if (
          /* Read more about handling dismissals below */
          result.dismiss === Swal.DismissReason.cancel
        ) {
          Swal.fire(
            'Cancelled',
            'Not Retired',
            'error'
          );
        }
      });

});
//end of close risk

//start of open risk form]
$(document).on("submit","#open-risk-form", function(e){
    e.preventDefault();

    swalWithBootstrapButtons.fire({
        title: 'Open Risk?',
        text: "Once you confirm, this risk will appear on the risk register. Are you sure to proceed?",
        icon: 'question',
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonText: 'YES, OPEN',
        cancelButtonText: 'NO, KEEP!',
        reverseButtons: true
      }).then((result) => {
        if (result.value) {

          var form_data = $('#open-risk-form').serializeArray();
          var form_url = 'controllers/risk-management/process-delete-risk-management.php';
          var form_method = 'POST';
          var dep_code = $('#dep').val();

          $.ajax({
              data : form_data,
              url  : form_url,
              method : form_method,
              beforeSend:function()
              {
                $.blockUI({ message: blockui_spinner });
              },
              success:function(data){
                {
                  $.unblockUI();
                }
                if(data == "success")
                {
                  swalWithBootstrapButtons.fire(
                    'OPENED',
                    'Successfully Opened',
                    'success'
                  );
                  SelectDepartmentRisk(dep_code);

                  $.ajax({
                      data : $('#open-risk-form').serializeArray(),
                      type: "post",
                      url: "controllers/risk-management/process-send-mail-close-open-risk.php",
                      success: function (data) {
                          console.log(data);
                      }
                  });
                }
                else
                {
                  swalWithBootstrapButtons.fire(
                    'NOT OPENED',
                    'Failed to Open. Please try again',
                    'error'
                  );
                }
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

        } else if (
          /* Read more about handling dismissals below */
          result.dismiss === Swal.DismissReason.cancel
        ) {
          swalWithBootstrapButtons.fire(
            'Cancelled',
            'Not Opened',
            'error'
          );
        }
      });

});

//end of open risk

//start approvals and rejection for quarterly update
//start approve risk
function approveRisk(str){
      var id = str;
      var quarterly_person_responsible = $('#quarterly_person_responsible-'+str).val();
      var quarterly_approval_value = $('#quarterly_approval_value-'+str).val();
      var risk_description =$('#risk_description-'+str).val();
      var reference_no =$('#reference_no-'+str).val();
      var risk_or_opportunity_notification = $('#risk_or_opportunity_notification-'+str).val();
      var approve_quarterly_button = $('#approve-quarterly-button-'+str);
      var row = $('#row-'+str);

      var form_data = {
        quarterly_approval_value: quarterly_approval_value,
        id:id,
        risk_description: risk_description,
        reference_no: reference_no,
        risk_or_opportunity_notification:risk_or_opportunity_notification,
        quarterly_person_responsible: quarterly_person_responsible
      };

      var newForm = $('#approve-quarterly-update-form-'+str);

      $(newForm).submit(function(e){
         e.preventDefault();
      });
      $.ajax({
         type: "POST",
         url :"controllers/risk-management/process-approve-quarterly-update-form.php",
         data: form_data,
         beforeSend:function()
         {
           $.blockUI({ message: blockui_spinner });
         },
         success:function(data)
         {
           $.unblockUI();
           if(data == 'success')
           {
             Toast.fire({
                 icon: 'success',
                 title: 'Approved Successfully'
               });
             $('.pending_approval_quarterly_updates_tab').click();
             LoadDynamicNavbar();
             LoadDynamicBadge();
             LoadDynamicBadgeQuarterlyUpdate();
             LoadDynamicBadgeNewEdited();


             $.ajax({
                 data: form_data,
                 type: "post",
                 url: "controllers/risk-management/process-send-mail-approve-update-risk-management.php",
                 success: function (data) {
                     console.log(data);
                 }
             });
           }
           else
           {
             Toast.fire({
                 icon: 'error',
                 title: 'Failed. Please try again'
               });
           }
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
}

//end approve risk

//start reject risk
function rejectRisk(str){
      var id = str;
      var quarterly_person_responsible = $('#quarterly_person_responsible-reject-'+str).val();
      var quarterly_approval_value = $('#quarterly_approval_value_reject-'+str).val();
      var risk_description =$('#risk_description_reject-'+str).val();
      var reference_no =$('#reference_no_reject-'+str).val();
      var risk_or_opportunity_notification = $('#risk_or_opportunity_notification_reject-'+str).val();
      var reject_quarterly_button = $('#reject-quarterly-button-'+str);
      var row = $('#row-'+str);
      var newForm = $('#reject-quarterly-update-form-'+str);

      var form_data = {
        quarterly_approval_value: quarterly_approval_value,
        id:id,
        risk_description: risk_description,
        reference_no: reference_no,
        risk_or_opportunity_notification:risk_or_opportunity_notification,
        quarterly_person_responsible: quarterly_person_responsible
      };

      $(newForm).submit(function(e){
         e.preventDefault();
      });
      $.ajax({
         type: "POST",
         url :"controllers/risk-management/process-approve-quarterly-update-form.php",
         data: form_data,
         beforeSend:function()
         {
           $.blockUI({ message: blockui_spinner });
         },
         success:function(data)
         {
           $.unblockUI();
           if(data == 'success')
           {

             Toast.fire({
                 icon: 'success',
                 title: 'Rejected Successfully'
               });
               $('.pending_approval_quarterly_updates_tab').click();
               LoadDynamicNavbar();
               LoadDynamicBadge();
               LoadDynamicBadgeQuarterlyUpdate();
               LoadDynamicBadgeNewEdited();

             $.ajax({
                 data: form_data,
                 type: "post",
                 url: "controllers/risk-management/process-send-mail-approve-update-risk-management.php",
                 success: function (data) {
                     console.log(data);
                 }
             });
           }
           else
           {
             Toast.fire({
                 icon: 'error',
                 title: 'Failed. Please try again'
               });
           }
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
}

//end reject risk
//end approvals and rejection for quarterly update

//start approvals and rejection for new/edited risk
//start new / edited risk from register
function approveNewStock(str){
      var id = str;

      var new_approval_value = $('#new_approval_value-'+str).val();

      var new_reference_no =$('#new_reference_no-'+str).val();
      var new_risk_or_opportunity_notification = $('#new_risk_or_opportunity_notification-'+str).val();
    //  var approve_new_risk_button = $('#approve-new-risk-button-'+str);
      var row = $('#new_row-'+str);

      var newForm = $('#approve-new-stock-form-'+str);

      var form_data = {
        new_approval_value:new_approval_value,
        id:id,

        new_reference_no:new_reference_no,
        new_risk_or_opportunity_notification:new_risk_or_opportunity_notification,

      };

      $(newForm).submit(function(e){
         e.preventDefault();
      });
      $.ajax({
         type: "POST",
         url :"controllers/stock-item/process_approve_stock.php",
         data: form_data,
         beforeSend:function()
         {
           $.blockUI({ message: blockui_spinner });
         },
         success:function(data)
         {
           $.unblockUI();
           if(data == 'success')
           {
             Toast.fire({
                 icon: 'success',
                 title: 'Approved Stock Item Successfully'
               });
               $('.approved_stocks_tab').click();
               LoadDynamicNavbar();
               LoadDynamicBadge();
               LoadDynamicBadgeQuarterlyUpdate();
               LoadDynamicBadgeNewEdited();

           }
           else
           {
             Toast.fire({
                 icon: 'error',
                 title: 'Failed. Please try again'
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
}


function approveNewDelivery(str){
      var id = str;

      var new_approval_value = $('#new_approval_value-'+str).val();

      var new_reference_no =$('#new_reference_no-'+str).val();
      var new_risk_or_opportunity_notification = $('#new_risk_or_opportunity_notification-'+str).val();
    //  var approve_new_risk_button = $('#approve-new-risk-button-'+str);
      var row = $('#new_row-'+str);

      var newForm = $('#approve-new-delivery-form-'+str);

      var form_data = {
        new_approval_value:new_approval_value,
        id:id,

        new_reference_no:new_reference_no,
        new_risk_or_opportunity_notification:new_risk_or_opportunity_notification,

      };

      $(newForm).submit(function(e){
         e.preventDefault();
      });
      $.ajax({
         type: "POST",
         url :"controllers/stock-item/process_approve_delivery.php",
         data: form_data,
         beforeSend:function()
         {
           $.blockUI({ message: blockui_spinner });
         },
         success:function(data)
         {
           $.unblockUI();
           if(data == 'success')
           {
             Toast.fire({
                 icon: 'success',
                 title: 'Approved Delivery Successfully'
               });
               $('.approved_deliveries_tab').click();
               LoadDynamicNavbar();
               LoadDynamicBadge();
               LoadDynamicBadgeQuarterlyUpdate();
               LoadDynamicBadgeNewEdited();

           }
           else
           {
             Toast.fire({
                 icon: 'error',
                 title: 'Failed. Please try again'
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
}


function rejectNewRisk(str){
      var id = str;
      var new_person_responsible = $('#new_person_responsible-reject-'+str).val();
      var new_approval_value = $('#new_approval_value_reject-'+str).val();
      var new_risk_description =$('#new_risk_description_reject-'+str).val();
      var new_reference_no =$('#new_reference_no_reject-'+str).val();
      var new_risk_or_opportunity_notification = $('#new_risk_or_opportunity_notification_reject-'+str).val();
      var reject_new_risk_button = $('#reject-new-risk-button-'+str);
      var row = $('#new_row-'+str);

      var newForm = $('#reject-new-risk-form-'+str);

      var form_data = {
        new_approval_value:new_approval_value,
        id:id,
        new_risk_description:new_risk_description,
        new_reference_no:new_reference_no,
        new_risk_or_opportunity_notification:new_risk_or_opportunity_notification,
        new_person_responsible:new_person_responsible
      };

      $(newForm).submit(function(e){
         e.preventDefault();
      });
      $.ajax({
         type: "POST",
         url :"controllers/risk-management/process-approve-new-risk-form.php",
         data: form_data,
         beforeSend:function()
         {
           $.blockUI({ message: blockui_spinner });
         },
         success:function(data)
         {
           $.unblockUI();
           if(data == 'success')
           {
             Toast.fire({
                 icon: 'success',
                 title: 'Rejected Successfully'
               });
               $('.pending_approval_new_edited_tab').click();
               LoadDynamicNavbar();
               LoadDynamicBadge();
               LoadDynamicBadgeQuarterlyUpdate();
               LoadDynamicBadgeNewEdited();
             //send mail
             $.ajax({
                 data: form_data,
                 type: "post",
                 url: "controllers/risk-management/process-send-mail-approve-new-edited-risk.php",
                 success: function (data) {
                     console.log(data);
                 }
             });
           }
           else
           {
             Toast.fire({
                 icon: 'error',
                 title: 'Failed. Please try again'
               });
           }
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
}

//end approvals and rejection for new/edited risk

//start hod approval amendments
$(document).on("blur","td[contenteditable=true]", function(e){
  var message_status = $(".status");
  var field_userid = $(this).attr("id") ;
  var value = $(this).text() ;
  $.post('controllers/risk-management/ajax.php' , field_userid + "=" + value, function(data){
      if(data != '')
      {
          message_status.show();
          message_status.text(data);
          //hide the message
          //setTimeout(function(){message_status.hide()},3000);
          console.log(data);
      }
  });
});
// start update likelihood score
function updateLikelihoodscore(str){
      var id = str;
      var likelihood_score = $('#likelihood_score-'+str).val();
      var score_id = $('#score_id-'+str).val();
      var newForm = $('#update-scores-form-'+str);

      $(newForm).submit(function(e){
         e.preventDefault();
      });
      $.ajax({
         type: "POST",
         url :"controllers/risk-management/update-scores.php",
         data: "likelihood_score="+likelihood_score+"&id="+id+"&score_id="+score_id,
         //success: alert("hey"),
         success:function(data){
        if(data == 'success')
           {
             $('#feedback').html("updated");
           }
         if(data == 'failed')
         {
             $('#feedback').html('please try again');
         }
         else
         {
             $('#loader').html('');
             $('#feedback').html(data);
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
}
//end update likelihood score

// start update impact score
function updateImpactscore(str){
      var id = str;
      var impact_score = $('#impact_score-'+str).val();
      var newForm = $('#update-scores-form-impact-'+str);

      $(newForm).submit(function(e){
         e.preventDefault();
      });
      $.ajax({
         type: "POST",
         url :"controllers/risk-management/update-scores-impact.php",
         data: "impact_score="+impact_score+"&id="+id,
         //success: alert("hey"),
         success:function(data){
        if(data == 'success')
           {
             $('.feedback').html("updated");
           }
         if(data == 'failed')
         {
             $('.feedback').html('please try again');
         }
         else
         {
             $('#loader').html('');
             $('.feedback').html(data);
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
}
//end update impact score

// start submit new edited approval comments
function submitNewEditedApprovalComments(str){
      var id = str;
      var comments = $('#comments-'+str).val();
      var reference_no_for_risk = $('#reference_no_for_risk-'+str).val();
      var submit_new_comments_button = $('#submit-new-comments-button-'+str);
      var newForm = $('#approve-quarterly-updates-form-'+str);

      $(newForm).submit(function(e){
         e.preventDefault();
      });
      $.ajax({
         type: "POST",
         url :"controllers/risk-management/process-submit-new-edited-approvals.php",
         data: "comments="+comments+"&id="+id+"&reference_no_for_risk="+reference_no_for_risk,
         beforeSend:function()
         {
           $(submit_new_comments_button).prop("disabled",true);
           $(submit_new_comments_button).html(" <i class='fa fa-spinner fa-spin'></i> sending...");
         },
         success:function(data){
        if(data == 'success')
           {
             $(submit_new_comments_button).prop("disabled",false);
             $(submit_new_comments_button).html("Sent");
           }
         if(data == 'failed')
         {
           $(submit_new_comments_button).prop("disabled",false);
           $(submit_new_comments_button).html("Try again");
         }
         else
         {
           $(submit_new_comments_button).prop("disabled",false);
           $(submit_new_comments_button).html("Sent.");
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
}
//end submit new edited approval comments

// start submit quarterly approval comments
function submitQuarterlyApprovalComments(str){
      var id = str;
      var comments = $('#comments-quarterly-'+str).val();
      var reference_no_for_risk = $('#reference_no_for_risk-quarterly-'+str).val();
      var submit_quarterly_comments_button = $('#submit-quarterly-comments-button-'+str);
      var newForm = $('#approve-quarterly-updates-form-quarterly-'+str);

      $(newForm).submit(function(e){
         e.preventDefault();
      });
      $.ajax({
         type: "POST",
         url :"controllers/risk-management/process-submit-quarterly-approvals.php",
         data: "comments="+comments+"&id="+id+"&reference_no_for_risk="+reference_no_for_risk,
         beforeSend:function()
         {
           $(submit_quarterly_comments_button).prop("disabled",true);
           $(submit_quarterly_comments_button).html(" <i class='fa fa-spinner fa-spin'></i> sending...");
         },
         success:function(data){
        if(data == 'success')
           {
             $(submit_quarterly_comments_button).prop("disabled",false);
             $(submit_quarterly_comments_button).html("Sent");
             LoadDynamicNavbar();
             LoadDynamicBadge();
             LoadDynamicBadgeQuarterlyUpdate();
             LoadDynamicBadgeNewEdited();
           }
         if(data == 'failed')
         {
           $(submit_quarterly_comments_button).prop("disabled",false);
           $(submit_quarterly_comments_button).html("Try again");
         }
         else
         {
           $(submit_quarterly_comments_button).prop("disabled",false);
           $(submit_quarterly_comments_button).html("Sent.");
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
}
//end submit quarterly approval comments

// start update likelihood score for quarterly
function updateLikelihoodscoreQuarterly(str){
      var id = str;
      var likelihood_score = $('#likelihood_score_quarterly-'+str).val();
      var newForm = $('#update-scores-form-likelihood-quarterly-'+str);
      var feedback_id = $('#feedback-likelihood-quarterly-'+str);

      $(newForm).submit(function(e){
         e.preventDefault();
      });
      $.ajax({
         type: "POST",
         url :"controllers/risk-management/update-scores-likelihood-quarterly.php",
         data: "likelihood_score_quarterly="+likelihood_score+"&id="+id,
         //success: alert("hey"),
         success:function(data){
        if(data == 'success')
           {
             $(feedback_id).html("updated");
           }
         if(data == 'failed')
         {
             $(feedback_id).html('please try again');
         }
         else
         {
             $(feedback_id).html(data);
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
}
//end update likelihood score for quarterly

// start update impact score for quarterly
function updateImpactscoreQuarterly(str){
      var id = str;
      var likelihood_score = $('#impact_score_quarterly-'+str).val();
      var newForm = $('#update-scores-form-impact-quarterly-'+str);
      var feedback_id = $('#feedback-impact-quarterly-'+str);

      $(newForm).submit(function(e){
         e.preventDefault();
      });
      $.ajax({
         type: "POST",
         url :"controllers/risk-management/update-scores-impact-quarterly.php",
         data: "impact_score_quarterly="+likelihood_score+"&id="+id,
         //success: alert("hey"),
         success:function(data){
        if(data == 'success')
           {
             $(feedback_id).html("updated");
           }
         if(data == 'failed')
         {
             $(feedback_id).html('please try again');
         }
         else
         {
             $(feedback_id).html(data);
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
}
//end update impact score for quarterly
//end hod approval ammendsmens

//start delegation
$(document).on("submit","#add-delegation-form", function(e){
    e.preventDefault();

    var form_data = $(this).serializeArray();
    var form_url = 'controllers/risk-management/process-add-delegation.php';
    var form_method = 'POST';

    $.ajax({
        data : form_data,
        url  : form_url,
        method : form_method,
        beforeSend:function()
        {
        $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
          $.unblockUI();
            if(data == 'success')
            {
              Toast.fire({
                  icon: 'success',
                  title: 'Delegation Successfully Made'
                });
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                $('.delegate-approvals-link').click();

                $.ajax({
                    data: form_data,
                    type: "post",
                    url: "controllers/risk-management/process-send-add-delegation.php",
                    success: function (data) {
                        console.log(data);
                    }
                });
            }
            else if(data == 'failed')
            {
              Toast.fire({
                  icon: 'error',
                  title: 'Failed. Please try again'
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

//UPDATE DELEGATION STATUS
function deactivateDelegation(id) {

  var form_data = {
    sid:id
  };

  var form_url = 'controllers/risk-management/process-change-delegation-status.php';
  var form_method = 'POST';

  $.ajax({
      data : form_data,
      url  : form_url,
      method : form_method,
      beforeSend:function()
      {
      $.blockUI({ message: blockui_spinner });
      },
      success:function(data){
        $.unblockUI();
          if(data == 'success')
          {
            Toast.fire({
                icon: 'success',
                title: 'Delegation Successfully Deactivated'
              });
              $('.delegate-approvals-link').click();
          }
          else if(data == 'failed')
          {
            Toast.fire({
                icon: 'error',
                title: 'Failed. Please try again'
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
}
//end delegation

//start add emerging trends
$(document).on("submit","#add-emerging-trends-form", function(e){
    e.preventDefault();

    var form_data = $(this).serializeArray();
    var form_url = 'controllers/risk-management/process-add-emerging-trends-form.php';
    var form_method = 'POST';

    $.ajax({
        data : form_data,
        url  : form_url,
        method : form_method,
        beforeSend: function(){
          $.blockUI({ message: blockui_spinner });
        },

        success:function(data){
          $.unblockUI();
            if(data == 'success')
            {
              Toast.fire({
                  icon: 'success',
                  title: 'Emerging Trend Successfully Added'
                });
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                $('.emerging-trends-link').click();
            }
            else if(data == 'failed')
            {
              Toast.fire({
                  icon: 'error',
                  title: 'Failed. An please try again'
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
//end add emerging trends

//start edit emerging trends
function updateEmergingtrends(str){
      var id = str;
      var period = $('#edit_period-'+str).val();
      var quarter = $('#edit_quarter-'+str).val();
      var factor = $('#edit_factor-'+str).val();
      var external_internal = $('#edit_external_internal-'+str).val();
      var related_risk_event = $('#edit_related_risk_event-'+str).val();
      var changes_in_risk_profile = $('#edit_changes_in_risk_profile-'+str).val();
      var newForm = $('#edit-emerging-trends-form-'+str);

      $(newForm).submit(function(e){
         e.preventDefault();
      });
      $.ajax({
         type: "POST",
         url :"controllers/risk-management/process-edit-emerging-trends.php",
         data: "edit_period="+period+"&id="+id+"&edit_quarter="+quarter+"&edit_factor="+factor+"&edit_external_internal="+external_internal+"&edit_related_risk_event="+related_risk_event+"&edit_changes_in_risk_profile="+changes_in_risk_profile,
         beforeSend: function()
         {
           $.blockUI({ message: blockui_spinner });
         },
         success:function(data)
         {
           $.unblockUI();
           if(data == 'success')
           {
             Toast.fire({
                 icon: 'success',
                 title: 'Emerging Trend Successfully Modified'
               });
               $('body').removeClass('modal-open');
               $('.modal-backdrop').remove();
               $('.emerging-trends-link').click();
           }
           else
           {
             Toast.fire({
                 icon: 'error',
                 title: 'Failed. Please try again'
               });
           }
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
}

//end edit emerging trends

//start delete emerging strend
function deleteEmergingtrends(id) {

  Swal.fire({
      title: 'DELETE ?',
      text: "This action is irriversible. Are you sure to proceed?",
      icon: 'question',
      showCancelButton: true,
      allowOutsideClick: false,
      confirmButtonText: 'YES, DELETE',
      cancelButtonText: 'NO, KEEP!',
      reverseButtons: true
    }).then((result) => {
      if (result.value) {

        var form_data = {
          sid:id
        };
        var form_url = 'controllers/risk-management/process-delete-emerging-trends.php';
        var form_method = 'POST';

        $.ajax({
            data : form_data,
            url  : form_url,
            method : form_method,
            beforeSend:function()
            {
              $.blockUI({ message: blockui_spinner });
            },
            success:function(data){
              {
                $.unblockUI();
              }
              if(data == "success")
              {
                Swal.fire(
                  'DELETED',
                  'Emerging Trend Deleted Successfully',
                  'success'
                );
                $('.emerging-trends-link').click();

              }
              else
              {
                Swal.fire(
                  'NOT DELETED',
                  'Failed to Delete. Please try again',
                  'error'
                );
              }
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

      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
        Swal.fire(
          'Cancelled',
          'Not Deleted',
          'error'
        );
      }
  });
}

//end delete merging trend

//start lessons learnt
//start strategies that worked well
$(document).on("submit","#add-strategies-that-worked-well-form", function(e){
    e.preventDefault();

    var form_data = $(this).serializeArray();
    var form_url = 'controllers/risk-management/process-add-lessons-learnt-form.php';
    var form_method = 'POST';

    $.ajax({
        data : form_data,
        url  : form_url,
        method : form_method,
        beforeSend: function()
        {
          $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
          $.unblockUI();
            if(data == 'success')
            {
              Toast.fire({
                 icon: 'success',
                 title: 'Added Successfully'
               });
               $('body').removeClass('modal-open');
               $('.modal-backdrop').remove();
               $('.strategies_that_worked_well_tab').click();
            }
            else
            {
              Toast.fire({
                 icon: 'error',
                 title: 'Failed. Please try again'
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
//end strategies that did not work well
$(document).on("submit","#add-strategies-that-did-not-work-form", function(e){
    e.preventDefault();

    var form_data = $(this).serializeArray();
    var form_url = 'controllers/risk-management/process-add-lessons-learnt-form.php';
    var form_method = 'POST';
    $.ajax({
        data : form_data,
        url  : form_url,
        method : form_method,
        beforeSend: function()
        {
          $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
          $.unblockUI();
            if(data == 'success')
            {
              Toast.fire({
                 icon: 'success',
                 title: 'Added Successfully'
               });
               $('body').removeClass('modal-open');
               $('.modal-backdrop').remove();
               $('.strategies_that_did_not_work_tab').click();
            }
            else
            {
              Toast.fire({
                 icon: 'error',
                 title: 'Failed. Please try again'
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
$(document).on("submit","#add-near-misses-form", function(e){
    e.preventDefault();

    var form_data = $(this).serializeArray();
    var form_url = 'controllers/risk-management/process-add-lessons-learnt-form.php';
    var form_method = 'POST';
    $.ajax({
        data : form_data,
        url  : form_url,
        method : form_method,
        beforeSend: function()
        {
          $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
          $.unblockUI();
            if(data == 'success')
            {
              Toast.fire({
                 icon: 'success',
                 title: 'Added Successfully'
               });
               $('body').removeClass('modal-open');
               $('.modal-backdrop').remove();
               $('.near_misses_tab').click();
            }
            else
            {
              Toast.fire({
                 icon: 'error',
                 title: 'Failed. Please try again'
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

function updateLessonslearntStrategiesThatWorked(str){
      var id = str;
      var period = $('#edit_period_strategies_that_worked-'+str).val();
      var quarter = $('#edit_quarter_strategies_that_worked-'+str).val();
      var strategies_that_worked_well = $('#edit_strategies_that_worked_well-'+str).val();
      var newForm = $('#edit-strategies-that-worked-form-'+str);

      $(newForm).submit(function(f){
         f.preventDefault();
      });
      $.ajax({
         type: "POST",
         url :"controllers/risk-management/process-edit-lessons-learnt.php",
         data: "edit_period="+period+"&id="+id+"&edit_quarter="+quarter+"&edit_strategies_that_worked_well="+strategies_that_worked_well,
         beforeSend:function()
         {
           $.blockUI({ message: blockui_spinner });
         },
         success:function(data)
         {
           if(data == 'success')
           {
             Toast.fire({
                icon: 'success',
                title: 'Updated Successfully'
              });
              $('body').removeClass('modal-open');
              $('.modal-backdrop').remove();
              $('.strategies_that_worked_well_tab').click();

           }
           else
           {
             Toast.fire({
                icon: 'error',
                title: 'Failed. Please try again'
              });
           }
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
}



function UpdateLessonsLearntStrategiesDidNotWork(str){
      var id = str;
      var period = $('#edit_period_strategies_did_not_work-'+str).val();
      var quarter = $('#edit_quarter_strategies_did_not_work-'+str).val();
      var strategies_that_did_not_work  = $('#edit_strategies_that_did_not_work-'+str).val();
      var newForm = $('#edit-strategies-that-did-not-work-form-'+str);

      $(newForm).submit(function(f){
         f.preventDefault();
      });
      $.ajax({
         type: "POST",
         url :"controllers/risk-management/process-edit-lessons-learnt.php",
         data: "edit_period="+period+"&id="+id+"&edit_quarter="+quarter+"&edit_strategies_that_did_not_work="+strategies_that_did_not_work,
         beforeSend:function()
         {
           $.blockUI({ message: blockui_spinner });
         },
         success:function(data)
         {
           if(data == 'success')
           {
             Toast.fire({
                icon: 'success',
                title: 'Updated Successfully'
              });
              $('body').removeClass('modal-open');
              $('.modal-backdrop').remove();
              $('.strategies_that_did_not_work_tab').click();

           }
           else
           {
             Toast.fire({
                icon: 'error',
                title: 'Failed. Please try again'
              });
           }
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
}


function updateLessonslearntNearMisses(str){
      var id = str;
      var period = $('#edit_period_near_misses-'+str).val();
      var quarter = $('#edit_quarter_near_misses-'+str).val();
      var near_misses  = $('#edit_near_misses-'+str).val();
      var newForm = $('#edit-near-misses-form-'+str);

      $(newForm).submit(function(f){
         f.preventDefault();
      });
      $.ajax({
         type: "POST",
         url :"controllers/risk-management/process-edit-lessons-learnt.php",
         data: "edit_period="+period+"&id="+id+"&edit_quarter="+quarter+"&edit_near_misses="+near_misses,
         beforeSend:function()
         {
           $.blockUI({ message: blockui_spinner });
         },
         success:function(data)
         {
           if(data == 'success')
           {
             Toast.fire({
                icon: 'success',
                title: 'Updated Successfully'
              });
              $('body').removeClass('modal-open');
              $('.modal-backdrop').remove();
              $('.near_misses_tab').click();

           }
           else
           {
             Toast.fire({
                icon: 'error',
                title: 'Failed. Please try again'
              });
           }
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
}


function deleteStrategiesThatWorkedWell(id) {
  Swal.fire({
      title: 'DELETE ?',
      text: "This action is irriversible. Are you sure to proceed?",
      icon: 'question',
      showCancelButton: true,
      allowOutsideClick: false,
      confirmButtonText: 'YES, DELETE',
      cancelButtonText: 'NO, KEEP!',
      reverseButtons: true
    }).then((result) => {
      if (result.value) {

        var form_data = {
          sid:id
        };
        var form_url = 'controllers/risk-management/process-delete-strategies-that-worked-well.php';
        var form_method = 'POST';

        $.ajax({
            data : form_data,
            url  : form_url,
            method : form_method,
            beforeSend:function()
            {
              $.blockUI({ message: blockui_spinner });
            },
            success:function(data){
              {
                $.unblockUI();
              }
              if(data == "success")
              {
                Swal.fire(
                  'DELETED',
                  'Deleted Successfully',
                  'success'
                );
                $('.strategies_that_worked_well_tab').click();

              }
              else
              {
                Swal.fire(
                  'NOT DELETED',
                  'Failed to Delete. Please try again',
                  'error'
                );
              }
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

      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
        Swal.fire(
          'Cancelled',
          'Not Deleted',
          'error'
        );
      }
  });
}

function deleteStrategiesThatDidNotWork(id) {

  Swal.fire({
      title: 'DELETE ?',
      text: "This action is irriversible. Are you sure to proceed?",
      icon: 'question',
      showCancelButton: true,
      allowOutsideClick: false,
      confirmButtonText: 'YES, DELETE',
      cancelButtonText: 'NO, KEEP!',
      reverseButtons: true
    }).then((result) => {
      if (result.value) {

        var form_data = {
          sid:id
        };
        var form_url = 'controllers/risk-management/process-delete-strategies-that-did-not-work.php';
        var form_method = 'POST';

        $.ajax({
            data : form_data,
            url  : form_url,
            method : form_method,
            beforeSend:function()
            {
              $.blockUI({ message: blockui_spinner });
            },
            success:function(data){
              {
                $.unblockUI();
              }
              if(data == "success")
              {
                Swal.fire(
                  'DELETED',
                  'Deleted Successfully',
                  'success'
                );
                $('.strategies_that_did_not_work_tab').click();

              }
              else
              {
                Swal.fire(
                  'NOT DELETED',
                  'Failed to Delete. Please try again',
                  'error'
                );
              }
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

      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
        Swal.fire(
          'Cancelled',
          'Not Deleted',
          'error'
        );
      }
  });
}

function deleteNearMisses(id) {

  Swal.fire({
      title: 'DELETE ?',
      text: "This action is irriversible. Are you sure to proceed?",
      icon: 'question',
      showCancelButton: true,
      allowOutsideClick: false,
      confirmButtonText: 'YES, DELETE',
      cancelButtonText: 'NO, KEEP!',
      reverseButtons: true
    }).then((result) => {
      if (result.value) {

        var form_data = {
          sid:id
        };
        var form_url = 'controllers/risk-management/process-delete-near-misses.php';
        var form_method = 'POST';

        $.ajax({
            data : form_data,
            url  : form_url,
            method : form_method,
            beforeSend:function()
            {
              $.blockUI({ message: blockui_spinner });
            },
            success:function(data){
              {
                $.unblockUI();
              }
              if(data == "success")
              {
                Swal.fire(
                  'DELETED',
                  'Deleted Successfully',
                  'success'
                );
                $('.near_misses_tab').click();

              }
              else
              {
                Swal.fire(
                  'NOT DELETED',
                  'Failed to Delete. Please try again',
                  'error'
                );
              }
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

      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
        Swal.fire(
          'Cancelled',
          'Not Deleted',
          'error'
        );
      }
  });
}
//end lessons learnt

//start incident report
//START ADD incident report form
$(document).on("submit","#add-incident-report-form", function(e){
   e.preventDefault();

   var form_data = $(this).serializeArray();
   var form_url = 'controllers/risk-management/process-add-incident-report-form.php';
   var form_method = 'POST';

   $.ajax({
       data : form_data,
       url  : form_url,
       method : form_method,
       beforeSend: function()
       {
         $.blockUI({ message: blockui_spinner });
       },
       success:function(data){
           $.unblockUI();
           if(data == 'success')
           {
             Toast.fire({
               icon: 'success',
               title: 'Incident Added Successfully'
             });
             $('body').removeClass('modal-open');
             $('.modal-backdrop').remove();
             $('.incident-reporting-link').click();
           }
           else if(data == 'failed')
           {
             Toast.fire({
               icon: 'error',
               title: 'Failed. Please try again'
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

function updateIncident(str){
      var id = str;
      var period_from = $('#period_from-'+str).val();
      var quarter = $('#quarter-'+str).val();
      var the_event = $('#the_event-'+str).val();
      var impact = $('#impact-'+str).val();
      var root_causes = $('#root_causes-'+str).val();
      var corrective_action_plans = $('#corrective_action_plans-'+str).val();
      var lessons_learnt = $('#lessons_learnt-'+str).val();
      var newForm = $('#edit-incident-report-form-'+str);

      $(newForm).submit(function(e){
         e.preventDefault();
      });
      $.ajax({
         type: "POST",
         url :"controllers/risk-management/update-incident-reports.php",
         data: "period_from="+period_from+"&quarter="+quarter+"&the_event="+the_event+"&id="+id+"&impact="+impact+"&root_causes="+root_causes+"&corrective_action_plans="+corrective_action_plans+"&lessons_learnt="+lessons_learnt,
         beforeSend:function()
        {
          $.blockUI({ message: blockui_spinner });
        },
         success:function(data)
         {
           if(data == 'success')
           {
             Toast.fire({
                 icon: 'success',
                 title: 'Incident Updated Successfully'
               });
               $('body').removeClass('modal-open');
               $('.modal-backdrop').remove();
               $('.incident-reporting-link').click();
           }
           else
           {
             Toast.fire({
                 icon: 'error',
                 title: 'Failed. Please try again'
               });
           }
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
}

function deleteIncident(id) {

  Swal.fire({
      title: 'DELETE ?',
      text: "This action is irriversible. Are you sure to proceed?",
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'YES, DELETE',
      cancelButtonText: 'NO, KEEP!',
      reverseButtons: true
    }).then((result) => {
      if (result.value) {

        var form_data = {
          sid:id
        };
        var form_url = 'controllers/risk-management/process-delete-incident.php';
        var form_method = 'POST';

        $.ajax({
            data : form_data,
            url  : form_url,
            method : form_method,
            beforeSend:function()
            {
              $.blockUI({ message: blockui_spinner });
            },
            success:function(data){
              {
                $.unblockUI();
              }
              if(data == "success")
              {
                Swal.fire(
                  'DELETED',
                  'Deleted Successfully',
                  'success'
                );
                $('.incident-reporting-link').click();

              }
              else
              {
                Swal.fire(
                  'NOT DELETED',
                  'Failed to Delete. Please try again',
                  'error'
                );
              }
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

      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
        Swal.fire(
          'Cancelled',
          'Not Deleted',
          'error'
        );
      }
  });
}

//end incident report
//END RISK MANAGEMENT

//start objectives
//START ADD departmental objective form
$(document).on("submit","#add-departmental-objective-form", function(e){
   e.preventDefault();

   var form_data = $(this).serializeArray();
   var form_url = 'controllers/objectives/process-add-departmental-objective-form.php';
   var form_method = 'POST';

   $.ajax({
       data : form_data,
       url  : form_url,
       method : form_method,
       beforeSend: function()
       {
         $.blockUI({ message: blockui_spinner });
       },
       success:function(data){
         $.unblockUI();
           if(data == 'success')
           {
             Toast.fire({
               icon: 'success',
               title: 'Objective Added Successfully'
             });
             $('body').removeClass('modal-open');
             $('.modal-backdrop').remove();
             $('.departmental-objectives-link').click();
           }
           else if(data == 'failed')
           {
             Toast.fire({
               icon: 'error',
               title: 'Failed. Please try again'
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
//END ADD departmental objective form

//start modify objectives
$(document).on("submit","#edit-departmental-objectives-form", function(e){
    e.preventDefault();

    var form_data = $(this).serializeArray();
    var form_url = 'controllers/objectives/update-objectives.php';
    var form_method = 'POST';

    $.ajax({
        data : form_data,
        url  : form_url,
        method : form_method,
        beforeSend:function()
        {
          $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
          $.unblockUI();
            if(data == 'success')
            {
              Toast.fire({
               icon: 'success',
               title: 'Objective Modified Successfully'
             });
             $('.departmental-objectives-link').click();

            }
            else
            {
              Toast.fire({
               icon: 'error',
               title: 'Failed. Please try again'
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
//end modify objectives

//start delete objectives
function deleteObjective(id) {

  Swal.fire({
      title: 'DELETE PERMENENTLY?',
      text: "This action is irriversible. The underlying Sub Objective/s attached to this objective will be removed too. Are you sure to proceed?",
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'YES, DELETE',
      cancelButtonText: 'NO, KEEP!',
      reverseButtons: true
    }).then((result) => {
      if (result.value) {

        var form_data = {
          sid:id
        };
        var form_url = 'controllers/objectives/process-delete-objective.php';
        var form_method = 'POST';

        $.ajax({
            data : form_data,
            url  : form_url,
            method : form_method,
            beforeSend:function()
            {
              $.blockUI({ message: blockui_spinner });
            },
            success:function(data){
              {
                $.unblockUI();
              }
              if(data == "success")
              {
                Swal.fire(
                  'DELETED',
                  'Deleted Successfully',
                  'success'
                );
                $('.departmental-objectives-link').click();

              }
              else
              {
                Swal.fire(
                  'NOT DELETED',
                  'Failed to Delete. Please try again',
                  'error'
                );
              }
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

      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
        Swal.fire(
          'Cancelled',
          'Not Deleted',
          'error'
        );
      }
  });
}



function removeObjective(id) {

  Swal.fire({
      title: 'REMOVE OBJECTIVE?',
      text: "This action will remove the objective from the list. Are you sure to proceed?",
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'YES, DELETE',
      cancelButtonText: 'NO, KEEP!',
      reverseButtons: true
    }).then((result) => {
      if (result.value) {

        var form_data = {
          sid:id
        };
        var form_url = 'controllers/objectives/process-delete-keep-objective.php';
        var form_method = 'POST';

        $.ajax({
            data : form_data,
            url  : form_url,
            method : form_method,
            beforeSend:function()
            {
              $.blockUI({ message: blockui_spinner });
            },
            success:function(data){
              {
                $.unblockUI();
              }
              if(data == "success")
              {
                Swal.fire(
                  'REMOVED',
                  'Removed Successfully',
                  'success'
                );
                $('.departmental-objectives-link').click();

              }
              else
              {
                Swal.fire(
                  'NOT REMOVED',
                  'Failed to Remove. Please try again',
                  'error'
                );
              }
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

      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
        Swal.fire(
          'Cancelled',
          'Not Removed',
          'error'
        );
      }
  });
}
//end delete objectives
//end objectives



//START PERFORMANCE MANAGEMENT
$(document).on("submit","#add-departmental-workplan-form", function(e){
    e.preventDefault();

    var form_data = $(this).serializeArray();
    var form_url = 'controllers/performance-management/process-add-departmental-workplan.php';
    var form_method = 'POST';
    var fin_year = $('#fin_year').val();
    var selected_department = $('#selected_department').val();
    $.ajax({
        data : form_data,
        url  : form_url,
        method : form_method,
        beforeSend:function()
        {
          $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
            $.unblockUI();
            if(data == 'success')
            {
              Toast.fire({
                icon: 'success',
                title: 'Activity Added Successfully'
              });

            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
            fetchWorkplan(fin_year,selected_department);

                  $.ajax({
                      data : $('#add-departmental-workplan-form').serializeArray(),
                      type: "post",
                      url: "controllers/performance-management/process-send-mail-add-activity.php",
                      success: function (data) {
                          console.log(data);
                      }
                  });
            }
            else if(data == 'failed')
              {
                Toast.fire({
                  icon: 'error',
                  title: 'Failed. Please try again'
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
//end departmental workplan form

//start monitor deartmental workplan
$(document).on("submit","#update-activity-form", function(e){
    e.preventDefault();

    var form_data = $(this).serializeArray();
    var form_url = 'controllers/performance-management/process-update-activity.php';
    var form_method = 'POST';
    var fin_year = $('#fin_year_update').val();
    var selected_department = $('#selected_department_update').val();

    $.ajax({
        data : form_data,
        url  : form_url,
        method : form_method,
        beforeSend:function()
        {
            $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
            $.unblockUI();
            if(data == 'success')
            {
              Toast.fire({
                 icon: 'success',
                 title: 'Updated Successfully'
                });
                fetchWorkplan(fin_year,selected_department);
            }

            else if(data == 'failed')
              {
                Toast.fire({
                   icon: 'error',
                   title: 'Failed. Please try again'
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

//end monitor departmental workplan

//start edit workplan
$(document).on("submit","#edit-departmental-workplan-form", function(e){
    e.preventDefault();

    var form_data = $(this).serializeArray();
    var form_url = 'controllers/performance-management/process-edit-activity.php';
    var form_method = 'POST';
    var fin_year = $('#fin_year_edit').val();
    var selected_department = $('#selected_department_edit').val();

    $.ajax({
        data : form_data,
        url  : form_url,
        method : form_method,
        beforeSend:function()
        {
          $.blockUI({ message: blockui_spinner });
        },

        success:function(data){
            $.unblockUI();
            if(data == 'success')
            {
              Toast.fire({
                 icon: 'success',
                 title: 'Modified Successfully'
                });
                fetchWorkplan(fin_year,selected_department);
            }

            else if(data == 'failed')
              {
                Toast.fire({
                   icon: 'error',
                   title: 'Failed. Please try again'
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

//end edit workplan


//start of close activity form
$(document).on("submit","#close-activity-form", function(e){
    e.preventDefault();

    Swal.fire({
        title: 'CLOSE ACTIVITY ? ',
        text: "Once you confirm, this activity will no longer appear on the workplan. Are you sure to proceed?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'YES, CLOSE ACTIVITY',
        cancelButtonText: 'NO, KEEP!',
        reverseButtons: true
      }).then((result) => {
        if (result.value) {

          var form_data = $('#close-activity-form').serializeArray();
          var form_url = 'controllers/performance-management/process-close-activity.php';
          var form_method = 'POST';
          var fin_year = $('#fin_year_close_open').val();
          var selected_department = $('#selected_department_close_open').val();

          $.ajax({
              data : form_data,
              url  : form_url,
              method : form_method,
              beforeSend:function()
              {
                $.blockUI({ message: blockui_spinner });
              },
              success:function(data){
                $.unblockUI();
                if(data == "success")
                {
                  Swal.fire(
                    'CLOSED',
                    'Activity Closed Successfully',
                    'success'
                  );
                  fetchWorkplan(fin_year,selected_department);
                  $.ajax({
                      data : $('#close-activity-form').serializeArray(),
                      type: "post",
                      url: "controllers/performance-management/process-send-mail-close-open-activity.php",
                      success: function (data) {
                          console.log(data);
                      }
                  });

                }
                else
                {
                  Swal.fire(
                    'NOT CLOSED',
                    'Failed to Close. Please try again',
                    'error'
                  );
                }
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

        } else if (
          /* Read more about handling dismissals below */
          result.dismiss === Swal.DismissReason.cancel
        ) {
          Swal.fire(
            'Cancelled',
            'Not Removed',
            'error'
          );
        }
    });
});
//end of close activity form

//start of open activity form
$(document).on("submit","#open-activity-form", function(e){
    e.preventDefault();

    Swal.fire({
        title: 'OPEN ACTIVITY ? ',
        text: "Once you confirm, this activity will longer appear on the workplan. Are you sure to proceed?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'YES, OPEN ACTIVITY',
        cancelButtonText: 'NO, KEEP!',
        reverseButtons: true
      }).then((result) => {
        if (result.value) {

          var form_data = $('#open-activity-form').serializeArray();
          var form_url = 'controllers/performance-management/process-close-activity.php';
          var form_method = 'POST';
          var fin_year = $('#fin_year_close_open').val();
          var selected_department = $('#selected_department_close_open').val();

          $.ajax({
              data : form_data,
              url  : form_url,
              method : form_method,
              beforeSend:function()
              {
                $.blockUI({ message: blockui_spinner });
              },
              success:function(data){
                $.unblockUI();
                if(data == "success")
                {
                  Swal.fire(
                    'OPENED',
                    'Activity Opened Successfully',
                    'success'
                  );
                  fetchWorkplan(fin_year,selected_department);
                  $.ajax({
                      data : $('#open-activity-form').serializeArray(),
                      type: "post",
                      url: "controllers/performance-management/process-send-mail-close-open-activity.php",
                      success: function (data) {
                          console.log(data);
                      }
                  });

                }
                else
                {
                  Swal.fire(
                    'NOT OPENED',
                    'Failed to Open. Please try again',
                    'error'
                  );
                }
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

        } else if (
          /* Read more about handling dismissals below */
          result.dismiss === Swal.DismissReason.cancel
        ) {
          Swal.fire(
            'Cancelled',
            'Not Opened',
            'error'
          );
        }
    });
});
//end of open activity form


//END PERFORMANCE MANAGEMENT

//start ldap users
$(document).on("submit","#submit-password-form", function(e){
    e.preventDefault();

    var form_data = $(this).serializeArray();
    var form_url = 'views/admin-portal/fetch_ldap_users.php';
    var form_method = 'POST';

    $.ajax({
        data : form_data,
        url  : form_url,
        method : form_method,
        beforeSend:function()
        {
          $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
          $.unblockUI();
            if(data.indexOf("Couldn't connect to AD!") != -1)
            {
              Toast.fire({
                    icon: 'error',
                    title: 'Failed to connect to Active Directory'
                  });
            }
            else if(data.indexOf("Couldn't bind to AD!") != -1)
            {
              Toast.fire({
                    icon: 'error',
                    title: 'Invalid Credentials'
                  });
            }
            else
            {
              $('#ldap_users_tab').html(data);
              $('#database_users_tab').removeClass('show active');
              $('.database_users_tab').removeClass('active');
              $('#ldap_users_tab').addClass('show active');
              $('.ldap_users_tab').addClass('active');
              $('#user-password-modal').modal('hide');
              LoadDatatables();
            }
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

//end ldap users

//set current period and quarter
$(document).on("submit","#add-current-period-form", function(e){
    e.preventDefault();

    var form_data = new FormData($(this)[0]);//$(this).serializeArray();
    var form_url = 'controllers/admin-portal/process-add-current-quarter.php';
    var form_method = 'POST';

    $.ajax({
        data : form_data,
        url  : form_url,
        method : form_method,
        contentType : false,
        processData:false,
        async:false,
        beforeSend:function()
        {
          $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
          $.unblockUI();
            if(data == 'success')
            {
               Toast.fire({
                icon: 'success',
                title: 'Current Period Set Successfully'
              });
            }
            else if(data == 'failed')
            {
               Toast.fire({
                icon: 'error',
                title: 'Failed. Please try again'
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
//end set current period and quarter

//start set deadline
$(document).on("submit","#set-deadline-form", function(e){
    e.preventDefault();
    var form_data = $(this).serializeArray();
    var form_url = 'controllers/admin-portal/process-set-deadline.php';
    var form_method = 'POST';

    $.ajax({
        data : form_data,
        url  : form_url,
        method : form_method,
        beforeSend: function()
        {
           $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
          $.unblockUI();
            if(data == 'success')
            {
              Toast.fire({
                icon: 'success',
                title: 'Deadline Set Successfully'
              });
            }
            else
            {
              Toast.fire({
                icon: 'error',
                title: 'Failed. Please Try Again'
              });
            }
            console.log(data);
            $('#set-deadline-form').trigger("reset");
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

//end set deadline

  // create backup form
  $(document).on("submit","#create-backups-form", function(e){
      e.preventDefault();

      var form_data = $(this).serializeArray();
      var form_url = 'controllers/admin-portal/db-backup.php';
      var form_method = 'POST';
      $.ajax({
          data : form_data,
          url  : form_url,
          method : form_method,
          beforeSend:function()
          {
            $.blockUI({ message: blockui_spinner });
          },
          success:function(data){
             $.unblockUI();
              if(data == 'success')
              {
                  Toast.fire({
                  icon: 'success',
                  title: 'Backup Created Successfully'
                });
                $('.admin-backup-link').click();

              }
              else if (data == "Notposted") {
                Toast.fire({
                icon: 'error',
                title: 'Failed. Not Posted'
                });

              }

              else
              {
                Toast.fire({
                icon: 'error',
                title: 'Failed. Please try again'
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


  $(document).on("submit","#system-backup-form", function(e){
      e.preventDefault();
      var form_data = $(this).serializeArray();
      var form_url = 'controllers/admin-portal/system-backup.php';
      var form_method = 'POST';
      $.ajax({
          data : form_data,
          url  : form_url,
          method : form_method,
          beforeSend:function()
          {
            $.blockUI({ message: blockui_spinner });
          },

          success:function(data){
             $.unblockUI();
              if(data == 'success')
              {
                  Toast.fire({
                  icon: 'success',
                  title: 'Backup Created Successfully'
                });
                $('.admin-backup-link').click();

              }
              else if (data == "Notposted") {
                Toast.fire({
                icon: 'error',
                title: 'Failed. Not Posted'
                });

              }

              else
              {
                Toast.fire({
                icon: 'error',
                title: 'Failed. Please try again'
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
  //end create backup form

  //start add user form
   $(document).on("submit","#add-new-user-form", function(e){
        e.preventDefault();

        var form_data = new FormData($(this)[0]);//$(this).serializeArray();
        var form_url = 'controllers/admin-portal/process-add-user-form.php';
        var form_method = 'POST';

        $.ajax({
            data : form_data,
            url  : form_url,
            method : form_method,
            contentType : false,
            processData:false,
            async:false,
            beforeSend:function()
            {
              $.blockUI({ message: blockui_spinner });
            },
            success:function(data){
               $.unblockUI();
                if(data == 'success')
                {
                    Toast.fire({
                    icon: 'success',
                    title: 'User Added Successfully'
                  });
                  $('body').removeClass('modal-open');
                  $('.modal-backdrop').remove();
                  $('.admin-user-management-link').click();

                }
                else if(data == 'failed')
                {
                   Toast.fire({
                    icon: 'error',
                    title: 'Failed. Please try again'
                  });
                }
                else if(data =='duplicate')
                {
                   Toast.fire({
                    icon: 'error',
                    title: 'Failed. Duplicate Email/Employee Number'
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

  //end add user form


  // Admin add user Settings
function EditUser(str)
{

    var form_id = $('#update-user-settings2-form-'+str);
    var emp_no = $('#emp_no-'+str).val();
    var name = $('#name-'+str).val();
    var email = $('#email-'+str).val();
    var department = $('#department-'+str).val();
    var designation = $('#designation-'+str).val();
    var access_level = $('#access_level-'+str).val();
    var status = $('#status-'+str).val();

    var form_data = {
      emp_no:emp_no,
      name:name,
      email:email,
      department:department,
      designation:designation,
      access_level:access_level,
      status:status
    };
    var form_url = 'controllers/admin-portal/process-edit-user-settings2-form.php';
    var form_method = 'POST';


    $.ajax({
        data : form_data,
        url  : form_url,
        method : form_method,
        beforeSend:function()
        {
          $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
            $.unblockUI();
            if(data == 'success')
            {
              $('body').removeClass('modal-open');
              $('.modal-backdrop').remove();

               Toast.fire({
                    icon: 'success',
                    title: 'User Data Updated Successfully'
                  });

              $('.admin-user-management-link').click();
            }
            else if(data == 'failed')
            {
             Toast.fire({
                    icon: 'error',
                    title: 'Failed. Please try again'
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
}

//end edit user settings

//start directors report
function sendtoDirectorsreport(str){
      var id = str;
      var activity_id = $('#activity_id-'+str).val();
      var risk_ref = $('#risk_ref-'+str).val();
      var directorate_id = $('#directorate_id-'+str).val();
      var year_id =$('#year_id-'+str).val();
      var quarter_id =$('#quarter_id-'+str).val();
      var send_to_directorate_button = $('#send-to-directorate-button-'+str);

      var row = $('#row-'+str);

      var newForm = $('#send-to-directorate-form-'+str);

      $(newForm).submit(function(e){
         e.preventDefault();
      });
      $.ajax({
         type: "POST",
         url :"controllers/reports/process-send-to-directorate.php",
         data: "activity_id="+activity_id+"&risk_ref="+risk_ref+"&directorate_id="+directorate_id+"&year_id="+year_id+"&quarter_id="+quarter_id,
         beforeSend:function()
         {
           $(send_to_directorate_button).prop("disabled",true);
           $(send_to_directorate_button).html("<i class='fa fa-spinner fa-spin'></i>Sending...");
         },
         success:function(data)
         {
           if(data == 'success')
           {
             //alert(reference_no);
             $(send_to_directorate_button).prop("disabled",true);
             $(send_to_directorate_button).html("<i class='fa fa-check'></i>Sent!");
             //$(row).hide();
             $(row).delay(3000).fadeOut('slow');

           }
           else
           {
             $(send_to_directorate_button).prop("disabled",false);
             $(send_to_directorate_button).html("Please Try Again");
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
}

//remove from directorate

function removeFromDirectorsreport(str){
      var id = str;
      var directors_risk_id = $('#directors_risk_id-'+str).val();
      var activity_id = $('#activity_id-'+str).val();
      var risk_ref = $('#risk_ref-'+str).val();
      var directorate_id = $('#directorate_id-'+str).val();
      var year_id =$('#year_id-'+str).val();
      var quarter_id =$('#quarter_id-'+str).val();
      var remove_from_directorate_button = $('#remove-from-directorate-button-'+str);

      var row = $('#row-'+str);

      var newForm = $('#remove-from-directorate-form-'+str);

      $(newForm).submit(function(e){
         e.preventDefault();
      });
      $.ajax({
         type: "POST",
         url :"controllers/reports/process-remove-from-directorate.php",
         data: "activity_id="+activity_id+"&risk_ref="+risk_ref+"&directorate_id="+directorate_id+"&directors_risk_id="+directors_risk_id,
         beforeSend:function()
         {
           $(remove_from_directorate_button).prop("disabled",true);
           $(remove_from_directorate_button).html("<i class='fa fa-spinner fa-spin'></i>Removing...");
         },
         success:function(data)
         {
           if(data == 'success')
           {
             //alert(reference_no);
             $(remove_from_directorate_button).prop("disabled",true);
             $(remove_from_directorate_button).html("<i class='fa fa-check'></i>Removed!");
             //$(row).hide();
             $(row).delay(3000).fadeOut('slow');
             var loader =`<div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>`;
             var year_id = $('#select_period').val();
             var quarter_id = $('#select_quarter').val();
             var directorate_id = $('#directorates').val();
             $('#directorate_risks_with_activities_generated').html(loader);
             $.post("controllers/reports/fetch-directorate-risks.php",
             {
               year_id: year_id,
               quarter_id: quarter_id,
               directorate_id: directorate_id
             },
             function(data_fetch,status){
               $('#loader_directorate_risks').html('');
               $('#directorate_risks_with_activities_generated').html(data_fetch);
               console.log(data_fetch);
             });


           }
           else
           {
             $(remove_from_directorate_button).prop("disabled",false);
             $(remove_from_directorate_button).html("Please Try Again");
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
}

function removeFromDirectorscumulativereport(str){
      var id = str;
      var directors_risk_id = $('#directors_risk_id-'+str).val();
      var activity_id = $('#activity_id-'+str).val();
      var risk_ref = $('#risk_ref-'+str).val();
      var directorate_id = $('#directorate_id-'+str).val();
      var year_id =$('#year_id-'+str).val();
      var quarter_id =$('#quarter_id-'+str).val();
      var remove_from_directorate_button = $('#remove-from-directorate-button-'+str);

      var row = $('#row-'+str);

      var newForm = $('#remove-from-directorate-form-'+str);

      $(newForm).submit(function(e){
         e.preventDefault();
      });
      $.ajax({
         type: "POST",
         url :"controllers/reports/process-remove-from-directorate-cumulative-risk.php",
         data: "activity_id="+activity_id+"&risk_ref="+risk_ref+"&directorate_id="+directorate_id+"&directors_risk_id="+directors_risk_id,
         beforeSend:function()
         {
           $(remove_from_directorate_button).prop("disabled",true);
           $(remove_from_directorate_button).html("<i class='fa fa-spinner fa-spin'></i>Removing...");
         },
         success:function(data)
         {
           if(data == 'success')
           {
             //alert(reference_no);
             $(remove_from_directorate_button).prop("disabled",true);
             $(remove_from_directorate_button).html("<i class='fa fa-check'></i>Removed!");
             //$(row).hide();
             $(row).delay(3000).fadeOut('slow');
             var loader =`<div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>`;
             var year_id = $('#select_period').val();
             var quarter_id = $('#select_quarter').val();
             var directorate_id = $('#directorates').val();
             $('#directorate_risks_with_activities_generated').html(loader);
             $.post("views/reports/fetch-directorate-risks.php",
             {
               year_id: year_id,
               quarter_id: quarter_id,
               directorate_id: directorate_id
             },
             function(data_fetch,status){
               $('#loader_directorate_risks').html('');
               $('#directorate_risks_with_activities_generated').html(data_fetch);
               console.log(data_fetch);
             });


           }
           else
           {
             $(remove_from_directorate_button).prop("disabled",false);
             $(remove_from_directorate_button).html("Please Try Again");
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
}

//end directors report

$(document).on("submit","#cumulative-risks-directorate-form", function(e){
  e.preventDefault();

  var form_data = $(this).serializeArray();
  var form_url = 'controllers/reports/add-cumulative-risks-directorate.php';
  var form_method = 'POST';
  var loader =`<div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>`;
  $('#loader_submit_cumulative_risks').html(loader);

  $.ajax({
      data : form_data,
      url  : form_url,
      method : form_method,
      beforeSend:function()
      {
        $('#cumulative-risks-directorate-button').prop("disabled",true);
        $('#cumulative-risks-directorate-button').html("submitting...");
      },
      success:function(data){
          $('#cumulative-risks-directorate-button').prop("disabled",false);
          $('#cumulative-risks-directorate-button').html("SUBMIT");
          $('#loader_submit_cumulative_risks').html('');
          if(data == 'success')
          {
            Toast.fire({
                      icon: 'success',
                      title: 'Successfully Created'
              });
              var year_id = $('#select_period').val();
              var quarter_id = $('#select_quarter').val();
              var directorate_id = $('#directorates').val();
              $('#directorate_risks_with_activities_generated').html(loader);
              $.post("views/reports/fetch-directorate-risks.php",
              {
                year_id: year_id,
                quarter_id: quarter_id,
                directorate_id: directorate_id
              },
              function(data,status){
                $('#loader_directorate_risks').html('');
                $('#directorate_risks_with_activities_generated').html(data);
                console.log(data);
              });

          }
         else  if(data == 'failed')
          {
            Toast.fire({
                      icon: 'error',
                      title: 'Failed. Please try again'
              });
          }
          else
          {
              //$('#feedback_message').html("<span class='alert alert-danger text-center'>System Error. Try Again</span>");
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

  //edit cumulative risk
$(document).on("submit","#edit-cumulative-risk-form", function(e){
  e.preventDefault();

  var form_data = $(this).serializeArray();
  var form_url = 'controllers/reports/edit-cumulative-risks-directorate.php';
  var form_method = 'POST';

  $.ajax({
      data : form_data,
      url  : form_url,
      method : form_method,
      beforeSend:function()
      {
        $.blockUI({ message: blockui_spinner });
      },
      success:function(data){
          $.unblockUI();
          if(data == 'success')
          {
            Toast.fire({
                      icon: 'success',
                      title: 'Successfully Modified'
              });
              ReportType('detailed_activities_related_risks_directorate');
          }
          else
          {
            Toast.fire({
                      icon: 'error',
                      title: 'Failed. Please try again'
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


function deleteCumulativeRisk(id) {

  Swal.fire({
      title: 'This Cumulative Risk will be deleted permanently!',
      text: "Are you sure to proceed?",
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'YES, DELETE',
      cancelButtonText: 'NO, KEEP!',
      reverseButtons: true
    }).then((result) => {
      if (result.value) {

        var form_data = {
          sid:id
        };
        var form_url = 'controllers/reports/process-delete-cumulative-risk.php';
        var form_method = 'POST';

        $.ajax({
            data : form_data,
            url  : form_url,
            method : form_method,
            beforeSend:function()
            {
              $.blockUI({ message: blockui_spinner });
            },
            success:function(data){
              $.unblockUI();
              if(data == "success")
              {
                Swal.fire(
                  'DELETED',
                  'Deleted Successfully',
                  'success'
                );
                var loader =`<div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>`;
                var year_id = $('#select_period').val();
                var quarter_id = $('#select_quarter').val();
                var directorate_id = $('#directorates').val();
                $('#cumulative_risks_tab_generated').html(loader);
                $.post("views/reports/fetch-directorate-cumulative-risks.php",
                {
                  select_period: year_id,
                  select_quarter: quarter_id,
                  directorates: directorate_id
                },
                function(data,status){
                  $('#cumulative_risks_tab_generated').html(data);
                  console.log(data);
                });

              }
              else
              {
                Swal.fire(
                  'NOT DELETED',
                  'Failed to Delete. Please try again',
                  'error'
                );
              }
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

      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
        Swal.fire(
          'Cancelled',
          'Not Deleted',
          'error'
        );
      }
  });
}

//START DOWNLOADING BACKUP
function DownLoadBackup(str)
{
  var download_id = str
  var form_data = {
    download_id : download_id
  };
  var form_url = 'controllers/admin-portal/DownLoadBackup.php';
  var form_method = 'POST';

  $.ajax({
      data : form_data,
      url  : form_url,
      method : form_method,
      beforeSend:function()
      {
        $.blockUI({ message: blockui_spinner });
      },
      success:function(data){
          $.unblockUI();
          if(data == 'success')
          {
            Toast.fire({
                      icon: 'success',
                      title: 'Download Started'
              });
          }
          else
          {
            Toast.fire({
                      icon: 'error',
                      title: 'Download Failed. Please try again'
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
}



//start user Feeback form
$(document).on("submit","#user-feedback-form", function(e){
    e.preventDefault();

    var form_data = $(this).serializeArray();
    var form_url = 'controllers/admin-portal/process-user-feedback.php';
    var form_method = 'POST';

    $.ajax({
        data : form_data,
        url  : form_url,
        method : form_method,
        beforeSend:function()
        {
            $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
            $.unblockUI();
            if(data == 'success')
            {
              Toast.fire({
                 icon: 'success',
                 title: 'Thank you for your feedback'
                });
                $('#submit-feedback-modal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                $('.user-feedback-link').click();

                $.ajax({
                      data : $('#user-feedback-form').serializeArray(),
                      type: "post",
                      url: "controllers/admin-portal/process-send-mail-user-feedback.php",
                      success: function (mail_data) {
                          console.log(mail_data);
                      }
                  });
            }

            else if(data == 'failed')
              {
                Toast.fire({
                   icon: 'error',
                   title: 'Failed. Please try again'
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

//end user feedback form


//start Feeback receiver form
$(document).on("submit","#feedback-receiver-form", function(e){
    e.preventDefault();

    var form_data = $(this).serializeArray();
    var form_url = 'controllers/admin-portal/process-feedback-receiver.php';
    var form_method = 'POST';

    $.ajax({
        data : form_data,
        url  : form_url,
        method : form_method,
        beforeSend:function()
        {
            $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
            $.unblockUI();
            if(data == 'success')
            {
              Toast.fire({
                 icon: 'success',
                 title: 'Successfully Added'
                });
                $('#add-feedback-receiver-modal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();

                $('.user-feedback-link').click();
                LoadDatatables();

                $.ajax({
                      data : $('#feedback-receiver-form').serializeArray(),
                      type: "post",
                      url: "controllers/admin-portal/process-send-mail-add-feedback-receiver.php",
                      success: function (mail_data) {
                          console.log(mail_data);
                      }
                  });

            }

            else if(data == 'failed')
              {
                Toast.fire({
                   icon: 'error',
                   title: 'Failed. Please try again'
                  });
              }
              else if(data == 'duplicate')
                {
                  Toast.fire({
                     icon: 'error',
                     title: 'The User already exists as a feedback receiver'
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

//end  feedback receiver form

//add feedback receiver
function DeleteFeedbackReceiver(id) {

  Swal.fire({
      title: 'This User will be deleted as a feedback receiver!',
      text: "Are you sure to proceed?",
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'YES, DELETE',
      cancelButtonText: 'NO, KEEP!',
      reverseButtons: true
    }).then((result) => {
      if (result.value) {

        var delete_feedback_receiver = 'delete_feedback_receiver';
        var form_data = {
          sid:id,
          delete_feedback_receiver:delete_feedback_receiver
        };
        var form_url = 'controllers/admin-portal/process-feedback-receiver.php';
        var form_method = 'POST';

        $.ajax({
            data : form_data,
            url  : form_url,
            method : form_method,
            beforeSend:function()
            {
              $.blockUI({ message: blockui_spinner });
            },
            success:function(data){
              $.unblockUI();
              if(data == "success")
              {
                Swal.fire(
                  'DELETED',
                  'Deleted Successfully',
                  'success'
                );
                $('.user-feedback-link').click();

                var mail_data = {
                  sid:id
                };

                $.ajax({
                      data : mail_data,
                      type: "post",
                      url: "controllers/admin-portal/process-send-mail-remove-feedback-receiver.php",
                      success: function (mail_data) {
                          console.log(mail_data);
                      }
                  });
              }
              else
              {
                Swal.fire(
                  'NOT DELETED',
                  'Failed to Delete. Please try again',
                  'error'
                );
              }
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

      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
        Swal.fire(
          'Cancelled',
          'Not Deleted',
          'error'
        );
      }
  });
}

//delete feedback receiver


//-----------------------------------START PROJECT MANAGEMENT FORMS----------------------------------------------------------//
//start add project form

// File type validation
$(document).on("change",".purchase-order-document", function(){
  $('.purchase-order-document-error').html('');
  $('.purchase-order-document-label').html('');
    var contract_document_error = `<small class="text-danger">
          Sorry, only PDF DOC files are allowed.
        </small>`;
    var file = this.files[0];
    var fileType = file.type;
    var match = ['application/pdf', 'application/msword', 'application/vnd.ms-office','application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    if(!((fileType == match[0]) || (fileType == match[1]) || (fileType == match[2]) || (fileType == match[3]))){
        $('.purchase-order-document-error').html(purchase-order_error);
        $(".contract-document").val('');
        $('.purchase-order-document-label').html('');
        return false;
    }
});

$(document).on("change",".delivery-note-document", function(){
  $('.delivery-note-document-error').html('');
  $('.delivery-note-document-label').html('');
    var contract_document_error = `<small class="text-danger">
          Sorry, only PDF DOC files are allowed.
        </small>`;
    var file = this.files[0];
    var fileType = file.type;
    var match = ['application/pdf', 'application/msword', 'application/vnd.ms-office','application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    if(!((fileType == match[0]) || (fileType == match[1]) || (fileType == match[2]) || (fileType == match[3]))){
        $('.delivery-note-document-error').html(delivery-note_error);
        $(".delivery-note-document").val('');
        $('.delivery-note-document-label').html('');
        return false;
    }
});

$(document).on("change",".invoice-document", function(){
  $('.invoice-document-error').html('');
  $('.invoice-document-label').html('');
    var contract_document_error = `<small class="text-danger">
          Sorry, only PDF DOC files are allowed.
        </small>`;
    var file = this.files[0];
    var fileType = file.type;
    var match = ['application/pdf', 'application/msword', 'application/vnd.ms-office','application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    if(!((fileType == match[0]) || (fileType == match[1]) || (fileType == match[2]) || (fileType == match[3]))){
        $('.invoice-document-error').html(invoice-document_error);
        $(".invoice-document").val('');
        $('.invoice-document-label').html('');
        return false;
    }
});


$(document).on("submit","#add-project-form", function(e){
    e.preventDefault();

    var form_data = $(this).serializeArray();
    var form_url = 'controllers/project-management/ProjectManagementController.php';
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
                 title: 'Project Successfully Added'
                });
                $('#add-project-modal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                $('.monitor-projects-link').click();

                $.ajax({
                      data : $('#add-project-form').serializeArray(),
                      type: "post",
                      url: "controllers/project-management/SendMailProjectManagement.php",
                      success: function (mail_data) {
                          console.log(mail_data);
                      }
                  });
            }

            else if(data == 'failed')
              {
                Toast.fire({
                   icon: 'error',
                   title: 'Failed. Please try again'
                  });
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


//end add project form

//start modify project form
function ModifyProject(str)
{

    var form_id = $('#edit-project-form-'+str);

    //$(form_id).submit(false);

    $(document).on('submit', form_id, function(event){
        event.preventDefault();
    });

    var strategic_objective = $('#strategic_objective-'+str).val();
    var project_name = $('#project_name-'+str).val();
    var project_description = $('#project_description-'+str).val();
    var project_start_date = $('#project_start_date-'+str).val();
    var project_end_date = $('#project_end_date-'+str).val();
    var duration = $('#duration-project-duration-in-days-'+str).val();
    var funding_agency = $('#funding-agency-'+str).val();
    var internal_currency = $('#internal-currency-'+str).val();
    var internal_budget = $('#internal-budget-value-'+str).val();
    var external_curreedncy = $('#external-currency-'+str).val();
    var external_budget = $('#external-budget-value-'+str).val();
    var project_owner = $('#project_owner-'+str).val();
    var senior_user = $('#senior_user-'+str).val();
    var senior_contractor = $('#senior_contractor-'+str).val();

    var project_advisor = $('#project_advisor-'+str).val();
    var related_workplan_activity = $('#related_workplan_activity-'+str).val();
    var edit_project = 'edit_project';

    var file = $('#contract-document-'+str);

    var image_data = $(file).prop("files")[0];
    //var form_data = new FormData();
    var form_data = new FormData($(form_id)[0]);//$(this).serializeArray();

    form_data.append('file', $(file)[0].files[0]);
    form_data.append('id', str);
    form_data.append('strategic_objective', strategic_objective);
    form_data.append('project_name', project_name);
    form_data.append('project_description', project_description);
    form_data.append('project_start_date', project_start_date);
    form_data.append('project_end_date', project_end_date);
    form_data.append('duration', duration);
    form_data.append('funding_agency', funding_agency);
    form_data.append('internal_currency', internal_currency);
    form_data.append('internal_budget', internal_budget);
    form_data.append('external_currency', external_currency);
    form_data.append('external_budget', external_budget);

    form_data.append('project_owner', project_owner);
    form_data.append('senior_user', senior_user);
    form_data.append('senior_contractor', senior_contractor);
    form_data.append('project_advisor', project_advisor);
    form_data.append('related_workplan_activity', related_workplan_activity);
    form_data.append('edit_project',edit_project);


    var form_url = 'controllers/project-management/ProjectManagementController.php';
    var form_method = 'POST';

    $.ajax({
        data : form_data,
        url  : form_url,
        method : form_method,
        contentType: false,
        cache: false,
        processData:false,
        beforeSend:function()
        {
          $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
            $.unblockUI();
            if(data == 'success')
            {
              $('body').removeClass('modal-open');
              $('.modal-backdrop').remove();

               Toast.fire({
                    icon: 'success',
                    title: 'Project Modified Successfully'
                  });

              $('.monitor-projects-link').click();
            }
            else if(data == 'failed')
            {
             Toast.fire({
                    icon: 'error',
                    title: 'Failed. Please try again'
                  });
            }
            else
            {
                 Toast.fire({
                    icon: 'error',
                    title: 'Failed. Please contact System Administrator'
                  });
                    console.log(data);
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


}


$(document).on("change",".editable", function(e){
  var message_status = $(".status");
  var field_userid = $(this).attr("data-column-name");
  var value = $(this).val() ;
  $.post('controllers/project-management/ajax.php' , field_userid + "=" + value, function(data){
      if(data != '')
      {
          message_status.show();
          message_status.text(data);
          //hide the message
          setTimeout(function(){message_status.hide()},3000);
          console.log(data);
      }
  });
});

//end modify project
// Close channel
function closeChannel(id) {

  Swal.fire({
      title: 'This Channel will be Deleted and all Related videos Archived',
      text: "Are you sure to proceed?",
      icon: 'Channel',
      showCancelButton: true,
      confirmButtonText: 'YES, Delete',
      cancelButtonText: 'NO',
      reverseButtons: true
    }).then((result) => {
      if (result.value) {

        var close_channel = 'close_channel';
        var form_data = {
          id:id,
          close_channel:close_channel
        };
        var form_url = 'controllers/delete/deleteChannel.php';
        var form_method = 'POST';

        $.ajax({
            data : form_data,
            url  : form_url,
            method : form_method,
            beforeSend:function()
            {
              $.blockUI({ message: blockui_spinner });
            },
            success:function(data){
              $.unblockUI();
              if(data == "success")
              {
                Swal.fire(
                  'DELETED',
                  'Channel Deleted Successfully',
                  'success'
                );
                $('.add-channel-link').click();

                var mail_data = {
                  id:id
                };

                $.ajax({
                      data : mail_data,
                      type: "post",
                      url: "controllers/delete/SendMailDeleteQuestions.php",
                      success: function (mail_data) {
                          console.log(mail_data);
                      }
                  });
              }
              else
              {
                Swal.fire(
                  'NOT Deleted',
                  'Failed to DElete. Please try again',
                  'error'
                );
                console.log(data);
              }
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

      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
        Swal.fire(
          'Cancelled',
          'Not Closed',
          'error'
        );
      }
  });
}



//start close project
//start close question
function Closevideo(id) {

  Swal.fire({
      title: 'This video will be removed from the list',
      text: "Are you sure to proceed?",
      icon: 'Video',
      showCancelButton: true,
      confirmButtonText: 'YES, Delete',
      cancelButtonText: 'NO',
      reverseButtons: true
    }).then((result) => {
      if (result.value) {

        var close_video = 'close_video';
        var form_data = {
          id:id,
          close_video:close_video
        };
        var form_url = 'controllers/delete/deletevideocontroller.php';
        var form_method = 'POST';

        $.ajax({
            data : form_data,
            url  : form_url,
            method : form_method,
            beforeSend:function()
            {
              $.blockUI({ message: blockui_spinner });
            },
            success:function(data){
              $.unblockUI();
              if(data == "success")
              {
                Swal.fire(
                  'DELETED',
                  'Video Deleted Successfully',
                  'success'
                );
                $('.upload-youtube-link').click();

                var mail_data = {
                  id:id
                };

                $.ajax({
                      data : mail_data,
                      type: "post",
                      url: "controllers/delete/SendMailDeleteQuestions.php",
                      success: function (mail_data) {
                          console.log(mail_data);
                      }
                  });
              }
              else
              {
                Swal.fire(
                  'NOT CLOSED',
                  'Failed to Close. Please try again',
                  'error'
                );
                console.log(data);
              }
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

      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
        Swal.fire(
          'Cancelled',
          'Not Closed',
          'error'
        );
      }
  });
}

// Popularity video change
function makePopular(id) {

  Swal.fire({
      title: 'This video will be made Popular',
      text: "Are you sure to proceed?",
      icon: 'Video',
      showCancelButton: true,
      confirmButtonText: 'YES, Popularise',
      cancelButtonText: 'NO',
      reverseButtons: true
    }).then((result) => {
      if (result.value) {

        var make_popular = 'make_popular';
        var form_data = {
          id:id,
          make_popular:make_popular
        };
        var form_url = 'controllers/popular/popularVideoController.php';
        var form_method = 'POST';

        $.ajax({
            data : form_data,
            url  : form_url,
            method : form_method,
            beforeSend:function()
            {
              $.blockUI({ message: blockui_spinner });
            },
            success:function(data){
              $.unblockUI();
              if(data == "success")
              {
                Swal.fire(
                  'Popular videos',
                  'Video Popularised Successfully',
                  'success'
                );
                $('.change-popular-link').click();

                var mail_data = {
                  id:id
                };

                $.ajax({
                      data : mail_data,
                      type: "post",
                      url: "controllers/delete/SendMailDeleteQuestions.php",
                      success: function (mail_data) {
                          console.log(mail_data);
                      }
                  });
              }
              else
              {
                Swal.fire(
                  'NOT Popularised',
                  'Failed to Close. Please try again',
                  'error'
                );
                console.log(data);
              }
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

      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
        Swal.fire(
          'Cancelled',
          'Not Closed',
          'error'
        );
      }
  });
}
//end populkar video Change

//REmove from popular list
function removePopular(id) {

  Swal.fire({
      title: 'This video will be made REmoved from Popular Channel',
      text: "Are you sure to proceed?",
      icon: 'Video',
      showCancelButton: true,
      confirmButtonText: 'YES, REmove',
      cancelButtonText: 'NO',
      reverseButtons: true
    }).then((result) => {
      if (result.value) {

        var remove_popular = 'remove_popular';
        var form_data = {
          id:id,
          remove_popular:remove_popular
        };
        var form_url = 'controllers/popular/popularVideoController.php';
        var form_method = 'POST';

        $.ajax({
            data : form_data,
            url  : form_url,
            method : form_method,
            beforeSend:function()
            {
              $.blockUI({ message: blockui_spinner });
            },
            success:function(data){
              $.unblockUI();
              if(data == "success")
              {
                Swal.fire(
                  'Popular videos',
                  'Video Popular remeoved Successfully',
                  'success'
                );
                $('.change-popular-link').click();

                var mail_data = {
                  id:id
                };

                $.ajax({
                      data : mail_data,
                      type: "post",
                      url: "controllers/delete/SendMailDeleteQuestions.php",
                      success: function (mail_data) {
                          console.log(mail_data);
                      }
                  });
              }
              else
              {
                Swal.fire(
                  'NOT Popularised',
                  'Failed to Close. Please try again',
                  'error'
                );
                console.log(data);
              }
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

      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
        Swal.fire(
          'Cancelled',
          'Not Closed',
          'error'
        );
      }
  });
}
//end remove popular video Change

//start makeTop channel

function makeTop(id) {

  Swal.fire({
      title: 'This Channel will be made Top',
      text: "Are you sure to proceed?",
      icon: 'Channel',
      showCancelButton: true,
      confirmButtonText: 'YES, Make Top',
      cancelButtonText: 'NO',
      reverseButtons: true
    }).then((result) => {
      if (result.value) {

        var make_top = 'make_top';
        var form_data = {
          id:id,
          make_top:make_top
        };
        var form_url = 'controllers/popular/topChannelController.php';
        var form_method = 'POST';

        $.ajax({
            data : form_data,
            url  : form_url,
            method : form_method,
            beforeSend:function()
            {
              $.blockUI({ message: blockui_spinner });
            },
            success:function(data){
              $.unblockUI();
              if(data == "success")
              {
                Swal.fire(
                  'Top Channel',
                  'Channel Made Top Successfully',
                  'success'
                );
                $('.top-channel-link').click();

                var mail_data = {
                  id:id
                };

                $.ajax({
                      data : mail_data,
                      type: "post",
                      url: "controllers/delete/SendMailDeleteQuestions.php",
                      success: function (mail_data) {
                          console.log(mail_data);
                      }
                  });
              }
              else
              {
                Swal.fire(
                  'NOT Top,
                  'Failed to Close. Please try again',
                  'error'
                );
                console.log(data);
              }
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

      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
        Swal.fire(
          'Cancelled',
          'Not Closed',
          'error'
        );
      }
  });
}

// end of top channel_type
// remove from top channel list
function removePopular(id) {

  Swal.fire({
      title: 'This Channel will be made Removed from Top Channel',
      text: "Are you sure to proceed?",
      icon: 'Channel',
      showCancelButton: true,
      confirmButtonText: 'YES, Remove Top',
      cancelButtonText: 'NO',
      reverseButtons: true
    }).then((result) => {
      if (result.value) {

        var remove_top = 'remove_top';
        var form_data = {
          id:id,
          remove_top:remove_top
        };
        var form_url = 'controllers/popular/topChannelController.php';
        var form_method = 'POST';

        $.ajax({
            data : form_data,
            url  : form_url,
            method : form_method,
            beforeSend:function()
            {
              $.blockUI({ message: blockui_spinner });
            },
            success:function(data){
              $.unblockUI();
              if(data == "success")
              {
                Swal.fire(
                  'Top Channel',
                  'Channel REmoved from Top Successfully',
                  'success'
                );
                $('.top-channel-link').click();

                var mail_data = {
                  id:id
                };

                $.ajax({
                      data : mail_data,
                      type: "post",
                      url: "controllers/delete/SendMailDeleteQuestions.php",
                      success: function (mail_data) {
                          console.log(mail_data);
                      }
                  });
              }
              else
              {
                Swal.fire(
                  'NOT Top,
                  'Failed to Close. Please try again',
                  'error'
                );
                console.log(data);
              }
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

      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
        Swal.fire(
          'Cancelled',
          'Not Closed',
          'error'
        );
      }
  });
}
// end remove top channel




//start add milestone form
$(document).on("submit","#add-project-milestone-form", function(e){
    e.preventDefault();

    var form_data = $(this).serializeArray();
    var form_url = 'controllers/project-management/MilestoneController.php';
    var form_method = 'POST';
    var id = $('#id').val();

    $.ajax({
        type: form_method,
        url: form_url,
        data: form_data,
        beforeSend: function(){
            $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
            $.unblockUI();
            if(data == 'success')
            {
              Toast.fire({
                 icon: 'success',
                 title: 'Milestone Successfully Added'
                });
                $('#add-project-milestone-modal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                $('.milestones-tab').click();
                //ViewProject(id);

                $.ajax({
                      data : $('#add-project-milestone-form').serializeArray(),
                      type: "post",
                      url: "controllers/project-management/SendMailProjectManagement.php",
                      success: function (mail_data) {
                          console.log(mail_data);
                      }
                  });
            }

            else if(data == 'failed')
              {
                Toast.fire({
                   icon: 'error',
                   title: 'Failed. Please try again'
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
//end add milestone form

//start modify milestone
function ModifyMilestone(str)
{
    var id = str;
    var form_id = $('#edit-project-milestone-form-'+str);
    var project_id = $('.id').val();
    var milestone_name = $('#milestone_name-'+str).val();
    var start_date = $('#milestone-start-date-'+str).val();
    var end_date = $('#milestone-end-date-'+str).val();
    var duration = $('#milestone-duration-in-days'+str).val();
    var edit_milestone = 'edit_milestone';

    $(document).on('submit', form_id, function(event){
        event.preventDefault();
    });


    var form_data = {
      id : id,
      milestone_name:milestone_name,
      start_date:start_date,
      end_date:end_date,
      duration:duration,
      edit_milestone:edit_milestone
    };
    var form_url = 'controllers/project-management/MilestoneController.php';
    var form_method = 'POST';


    $.ajax({
        data : form_data,
        url  : form_url,
        method : form_method,
        beforeSend:function()
        {
          $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
            $.unblockUI();
            if(data == 'success')
            {
              $('body').removeClass('modal-open');
              $('.modal-backdrop').remove();

               Toast.fire({
                    icon: 'success',
                    title: 'Milestone Modified Successfully'
                  });

              $('.milestones-tab').click();
              //ViewProject(project_id);
            }
            else if(data == 'failed')
            {
             Toast.fire({
                    icon: 'error',
                    title: 'Failed. Please try again'
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
}


$(document).on("change",".editable-task", function(e){
  var message_status = $(".status-task");
  var field_userid = $(this).attr("data-column-name-task");
  var value = $(this).val() ;
  $.post('controllers/project-management/ModifyTasks.php' , field_userid + "=" + value, function(data){
      if(data != '')
      {
          message_status.show();
          message_status.text(data);
          //hide the message
          setTimeout(function(){message_status.hide()},3000);
          console.log(data);
      }
  });
});

function ChangeActivityDuration(str)
{

    var message_status = $("#status-task-"+str);
    var field_userid = $("#activity-duration-in-days-"+str).attr("data-column-name-task");
    var value = $("#activity-duration-in-days-"+str).val() ;
    console.log('changed');
    $.post('controllers/project-management/ModifyTasks.php' , field_userid + "=" + value, function(data){
        if(data != '')
        {
            message_status.show();
            message_status.text(data);
            //hide the message
            setTimeout(function(){message_status.hide()},3000);
            console.log(data);
        }
    });
}
//end modify milestone

//start delete milestone
function  DeleteMilestone(id,project_id) {

  Swal.fire({
      title: 'This Milestone and its underlying tasks will be deleted permanently',
      text: "This action is irriversible. Are you sure to proceed?",
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'YES, DELETE MILESTONE',
      cancelButtonText: 'NO, KEEP MILESTONE!',
      reverseButtons: true
    }).then((result) => {
      if (result.value) {

        var delete_milestone = 'delete_milestone';
        var form_data = {
          project_id : project_id,
          sid:id,
          delete_milestone:delete_milestone
        };
        var form_url = 'controllers/project-management/MilestoneController.php';
        var form_method = 'POST';

        $.ajax({
            data : form_data,
            url  : form_url,
            method : form_method,
            beforeSend:function()
            {
              $.blockUI({ message: blockui_spinner });
            },
            success:function(data){
              $.unblockUI();
              if(data == "success")
              {
                Swal.fire(
                  'DELETED',
                  'Milestone Deleted Successfully',
                  'success'
                );
                $('.milestones-tab').click();
                //ViewProject(project_id);

                var mail_data = {
                  sid:id
                };

                $.ajax({
                      data : mail_data,
                      type: "post",
                      url: "controllers/project-management/SendMailProjectManagement.php",
                      success: function (mail_data) {
                          console.log(mail_data);
                      }
                  });
              }
              else
              {
                Swal.fire(
                  'NOT DELETED',
                  'Failed to Delete. Please try again',
                  'error'
                );
                console.log(data);
              }
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

      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
        Swal.fire(
          'Cancelled',
          'Not Deleted',
          'error'
        );
      }
  });
}

//end delete milestone
//start add milestone invoice payment
function SubmitInvoice(str)
{
    var id = str;
    var form_id = $('#invoice-payment-form-'+str);
    var invoice_ksh = $('#invoice_ksh-'+str).val();
    var invoice_usd = $('#invoice_usd-'+str).val();
    var invoice_number = $('#invoice_number-'+str).val();
    var invoice_doc = $('#invoice_doc-'+str).val();
    var add_invoice_payment = 'add_invoice_payment';


    $(document).on('submit', form_id, function(event){
        event.preventDefault();
    });


    var form_data = {
      milestone_payment_id : id,
      invoice_ksh:invoice_ksh,
      invoice_usd:invoice_usd,
      invoice_number:invoice_number,
      invoice_doc:invoice_doc,
      add_invoice_payment:add_invoice_payment,

    };
    var form_url = 'controllers/project-management/ProjectMilestoneInvoicePayment.php';
    var form_method = 'POST';


    $.ajax({
        data : form_data,
        url  : form_url,
        method : form_method,
        beforeSend:function()
        {
          $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
            $.unblockUI();
            if(data == 'success')
            {
              $('body').removeClass('modal-open');
              $('.modal-backdrop').remove();

               Toast.fire({
                    icon: 'success',
                    title: 'Invoice Added Successfully'
                  });

              $('.project-payments-tab').click();
              //ViewProject(project_id);
            }
            else if(data == 'failed')
              {
                Toast.fire({
                   icon: 'error',
                   title: 'Failed. Please try again'
                  });
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
}

// end milestone paymnet

//start add resource
function SubmitResource(str)
{
    var id = str;
    var form_id = $('#add-resource-form-'+str);
    var resource_name = $('#resource_name-'+str).val();
    var add_resource = 'add_resource';

    $(document).on('submit', form_id, function(event){
        event.preventDefault();
    });


    var form_data = {
      task_id : id,
      resource_name:resource_name,
      add_resource:add_resource
    };
    var form_url = 'controllers/project-management/ResourceController.php';
    var form_method = 'POST';

    $.ajax({
        data : form_data,
        url  : form_url,
        method : form_method,
        beforeSend:function()
        {
          $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
            $.unblockUI();
            if(data == 'success')
            {
              $('body').removeClass('modal-open');
              $('.modal-backdrop').remove();

               Toast.fire({
                    icon: 'success',
                    title: 'Resource Added Successfully'
                  });

              $('.end-product-resource-link').click();
              //ViewProject(project_id);
            }
            else if(data == 'failed')
            {
             Toast.fire({
                    icon: 'error',
                    title: 'Failed. Please try again'
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
}
//end add resource


//start remove resource
function  DeleteResource(id) {

  Swal.fire({
      title: 'This Resource will be removed from the assigned End Product',
      text: "Are you sure to proceed?",
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'YES, REMOVE RESOURCE',
      cancelButtonText: 'NO, KEEP RESOURCE!',
      reverseButtons: true
    }).then((result) => {
      if (result.value) {

        var delete_resource = 'delete_resource';
        var form_data = {
          id:id,
          delete_resource:delete_resource
        };
        var form_url = 'controllers/project-management/ResourceController.php';
        var form_method = 'POST';

        $.ajax({
            data : form_data,
            url  : form_url,
            method : form_method,
            beforeSend:function()
            {
              $.blockUI({ message: blockui_spinner });
            },
            success:function(data){
              $.unblockUI();
              if(data == "success")
              {
                Swal.fire(
                  'DELETED',
                  'Resource Deleted Successfully',
                  'success'
                );
                $('.end-product-resource-link').click();
                //ViewProject(project_id);

              }
              else
              {
                Swal.fire(
                  'NOT DELETED',
                  'Failed to Delete. Please try again',
                  'error'
                );
                console.log(data);
              }
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

      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
        Swal.fire(
          'Cancelled',
          'Not Deleted',
          'error'
        );
      }
  });
}

//end remove resource

//start add task status
function SubmitTaskStatus(str)
{
    var id = str;
    var form_id = $('#add-task-status-form-'+str);
    var task_status = $('#task_status-'+str).val();
    var task_status_comments = $('#add_task_status_comments-'+str).val();
    var add_task_status = 'add_task_status';

    $(document).on('submit', form_id, function(event){
        event.preventDefault();
    });


    var form_data = {
      task_id : id,
      task_status:task_status,
      task_status_comments:task_status_comments,
      add_task_status:add_task_status
    };
    var form_url = 'controllers/project-management/ResourceController.php';
    var form_method = 'POST';

    $.ajax({
        data : form_data,
        url  : form_url,
        method : form_method,
        beforeSend:function()
        {
          $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
            $.unblockUI();
            if(data == 'success')
            {
              $('body').removeClass('modal-open');
              $('.modal-backdrop').remove();

               Toast.fire({
                    icon: 'success',
                    title: 'Task Updated Successfully'
                  });

              $('.project-resource-plan-tab').click();
              //ViewProject(project_id);
            }
            else if(data == 'failed')
            {
             Toast.fire({
                    icon: 'error',
                    title: 'Failed. Please try again'
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
}

//end add task status

//start delete task
function  DeleteTask(id) {

  Swal.fire({
      title: 'This task will be deleted from the task list',
      text: "All Resources tied to this task will be removed. This action is irriversible. Are you sure to proceed?",
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'YES, DELETE TASK',
      cancelButtonText: 'NO, KEEP TASK!',
      reverseButtons: true
    }).then((result) => {
      if (result.value) {

        var delete_task = 'delete_task';
        var form_data = {
          sid:id,
          delete_task:delete_task
        };
        var form_url = 'controllers/project-management/ResourceController.php';
        var form_method = 'POST';

        $.ajax({
            data : form_data,
            url  : form_url,
            method : form_method,
            beforeSend:function()
            {
              $.blockUI({ message: blockui_spinner });
            },
            success:function(data){
              $.unblockUI();
              if(data == "success")
              {
                Swal.fire(
                  'DELETED',
                  'Task Deleted Successfully',
                  'success'
                );
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                $('.project-resource-plan-tab').click();
                //ViewProject(project_id);

                var mail_data = {
                  sid:id
                };

                $.ajax({
                      data : mail_data,
                      type: "post",
                      url: "controllers/project-management/SendMailProjectManagement.php",
                      success: function (mail_data) {
                          console.log(mail_data);
                      }
                  });
              }
              else
              {
                Swal.fire(
                  'NOT DELETED',
                  'Failed to Delete. Please try again',
                  'error'
                );
                console.log(data);
              }
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

      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
        Swal.fire(
          'Cancelled',
          'Not Deleted',
          'error'
        );
      }
  });
}
//end delete task


//start add project risk
$(document).on("submit","#add-project-risk-form", function(e){
    e.preventDefault();

    var form_data = $(this).serializeArray();
    var form_url = 'controllers/project-management/ProjectRiskController.php';
    var form_method = 'POST';

    $.ajax({
        type: form_method,
        url: form_url,
        data: form_data,
        beforeSend: function(){
            $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
            $.unblockUI();
            if(data == 'success')
            {
              Toast.fire({
                 icon: 'success',
                 title: 'Project Risk Successfully Added'
                });
                $('#add-project-risk-modal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                $('.project-risks-tab').click();
                //ViewProject(id);

                $.ajax({
                      data : $('#add-project-risk-form').serializeArray(),
                      type: "post",
                      url: "controllers/project-management/SendMailProjectManagement.php",
                      success: function (mail_data) {
                          console.log(mail_data);
                      }
                  });
            }

            else if(data == 'failed')
              {
                Toast.fire({
                   icon: 'error',
                   title: 'Failed. Please try again'
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

//end add project risk

//start monitor project risk
function MonitorProjectRisk(str)
{
    var id = str;
    var form_id = $('#edit-project-risk-form-'+str);

    $(document).on('submit', form_id, function(event){
        event.preventDefault();
    });

    var form_data = {
      project_risk_id : $('#project_risk_id-'+str).val(),
      project_id : $('.project_id-'+str).val(),
      edit_project_risk : $('#edit_project_risk-'+str).val(),
      project_risk_description : $('#project_risk_description-'+str).val(),
      project_impact : $('#project_impact-'+str).val(),
      project_phase : $('#project_phase-'+str).val(),
      person_responsible : $('#person_responsible-'+str).val(),
      risk_mitigations_strategy : $('#risk_mitigations_strategy-'+str).val(),
      actions_applied : $('#actions_applied-'+str).val(),
      likelihood_score : $('#project-risk-edit-likelihood-score-'+str).val(),
      impact_score : $('#project-risk-edit-impact-score-'+str).val(),
      overall_score : $('#project-risk-edit-overall-score-'+str).val(),
    };
    var form_url = 'controllers/project-management/ProjectRiskController.php';
    var form_method = 'POST';

    $.ajax({
        data : form_data,
        url  : form_url,
        method : form_method,
        beforeSend:function()
        {
          $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
            $.unblockUI();
            if(data == 'success')
            {
              $('body').removeClass('modal-open');
              $('.modal-backdrop').remove();

               Toast.fire({
                    icon: 'success',
                    title: 'Risk Updated Successfully'
                  });

              $('.project-risks-tab').click();
              //ViewProject(project_id);
            }
            else if(data == 'failed')
            {
             Toast.fire({
                    icon: 'error',
                    title: 'Failed. Please try again'
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
}
//end monitor project risk


//start add project lesson leaarnt
$(document).on("submit","#add-project-lesson-learnt-form", function(e){
    e.preventDefault();

    var form_data = $(this).serializeArray();
    var form_url = 'controllers/project-management/ProjectLessonController.php';
    var form_method = 'POST';

    $.ajax({
        type: form_method,
        url: form_url,
        data: form_data,
        beforeSend: function(){
            $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
            $.unblockUI();
            if(data == 'success')
            {
              Toast.fire({
                 icon: 'success',
                 title: 'Lesson Successfully Added'
                });
                $('#add-project-lessons-learnt-modal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                $('.project-lessons-learnt-tab').click();
                //ViewProject(id);

                $.ajax({
                      data : $('#add-project-lesson-learnt-form').serializeArray(),
                      type: "post",
                      url: "controllers/project-management/SendMailProjectManagement.php",
                      success: function (mail_data) {
                          console.log(mail_data);
                      }
                  });
            }

            else if(data == 'failed')
              {
                Toast.fire({
                   icon: 'error',
                   title: 'Failed. Please try again'
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
//end add project lesson learnt


//start modifying supplier




//start delete project lesson
function  DeleteProjectLesson(id) {
  Swal.fire({
      title: 'This Lesson will be deleted',
      text: "This action is irriversible. Are you sure to proceed?",
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'YES, DELETE',
      cancelButtonText: 'NO, KEEP!',
      reverseButtons: true
    }).then((result) => {
      if (result.value) {

        var delete_project_lesson = 'delete_project_lesson';
        var form_data = {
          sid:id,
          delete_project_lesson:delete_project_lesson
        };
        var form_url = 'controllers/project-management/ProjectLessonController.php';
        var form_method = 'POST';

        $.ajax({
            data : form_data,
            url  : form_url,
            method : form_method,
            beforeSend:function()
            {
              $.blockUI({ message: blockui_spinner });
            },
            success:function(data){
              $.unblockUI();
              if(data == "success")
              {
                Swal.fire(
                  'Deleted',
                  'Lesson Deleted Successfully',
                  'success'
                );
                $('.project-lessons-learnt-tab').click();
                //ViewProject(project_id);

                var mail_data = {
                  sid:id
                };

                $.ajax({
                      data : mail_data,
                      type: "post",
                      url: "controllers/project-management/SendMailProjectManagement.php",
                      success: function (mail_data) {
                          console.log(mail_data);
                      }
                  });
              }
              else
              {
                Swal.fire(
                  'Not Deleted',
                  'Failed to delete , please try again',
                  'error'
                );
                console.log(data);
              }
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

      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
        Swal.fire(
          'Cancelled',
          'Not Deleted',
          'error'
        );
      }
  });
}
//end delete project lesson

//start issue log creation
$(document).on("submit","#add-project-issue-log-form", function(e){
    e.preventDefault();

    var form_data = $(this).serializeArray();
    var form_url = 'controllers/project-management/IssueLogsController.php';
    var form_method = 'POST';

    $.ajax({
        type: form_method,
        url: form_url,
        data: form_data,
        beforeSend: function(){
            $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
            $.unblockUI();
            if(data == 'success')
            {
              Toast.fire({
                 icon: 'success',
                 title: 'Issue Log Successfully Added'
                });
                $('#add-project-issue-logs-modal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                $('.project-issue-logs-tab').click();
                //ViewProject(id);

                $.ajax({
                      data : $('#add-project-issue-log-form').serializeArray(),
                      type: "post",
                      url: "controllers/project-management/SendMailProjectManagement.php",
                      success: function (mail_data) {
                          console.log(mail_data);
                      }
                  });
            }

            else if(data == 'failed')
              {
                Toast.fire({
                   icon: 'error',
                   title: 'Failed. Please try again'
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
//end issue log creation
// start project phase updates

function SubmitProjectPhase(str)
{


  var id = str;
  var form_id = $('#add-project-phase-form-'+str);
  var project_phase = $('#project_phase-'+str).val();

  var add_project_phase = 'add_project_phase';

  $(document).on('submit', form_id, function(event){
      event.preventDefault();
  });


      var form_data = {
        project_id : id,
        project_phase:project_phase,
        add_project_phase:add_project_phase
      };
      var form_url = 'controllers/project-management/ProjectManagementController.php';
      var form_method = 'POST';

      $.ajax({
          data : form_data,
          url  : form_url,
          method : form_method,
          beforeSend:function()
          {
            $.blockUI({ message: blockui_spinner });
          },
          success:function(data){
              $.unblockUI();
              if(data == 'success')
              {
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();

                 Toast.fire({
                      icon: 'success',
                      title: 'Project Phase Updated Successfully'
                    });

              $('.monitor-projects-link').click();
                //ViewProject(project_id);
              }
              else if(data == 'failed')
              {
               Toast.fire({
                      icon: 'error',
                      title: 'Failed. Please try again'
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

  }
//end project phase update

// start project status updates

function SubmitProjectStatus(str)
{

  var id = str;
  var form_id = $('#add-project-status-form-'+str);
  var project_status = $('#project_status-'+str).val();

  var add_project_status = 'add_project_status';

  $(document).on('submit', form_id, function(event){
      event.preventDefault();
  });


      var form_data = {
        project_id : id,
        project_status:project_status,
        add_project_status:add_project_status
      };
      var form_url = 'controllers/project-management/ProjectManagementController.php';
      var form_method = 'POST';

      $.ajax({
          data : form_data,
          url  : form_url,
          method : form_method,
          beforeSend:function()
          {
            $.blockUI({ message: blockui_spinner });
          },
          success:function(data){
              $.unblockUI();
              if(data == 'success')
              {
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();

                 Toast.fire({
                      icon: 'success',
                      title: 'Project Status Updated Successfully'
                    });

              $('.monitor-projects-link').click();

                //ViewProject(project_id);
              }
              else if(data == 'failed')
              {
               Toast.fire({
                      icon: 'error',
                      title: 'Failed. Please try again'
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

  }
//end project status update


//start issue log monitoring
function ModifyCustomer(str)
{
    var id = str;
    var form_id = $('#edit-customer-form-'+str);

    $(document).on('submit', form_id, function(event){
        event.preventDefault();
    });

    var form_data = {
      id : id,
      edit_customer : 'edit_customer',
      customer_name : '#customer_name',
      contact : $('#contact-'+str).val(),
      email : $('.#email-'+str).val(),
      sector : $('#sector-'+str).val(),

    };
    var form_url = 'controllers/customer-management/customer_list_controller.php';
    var form_method = 'POST';


    $.ajax({
        data : form_data,
        url  : form_url,
        method : form_method,
        beforeSend:function()
        {
          $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
            $.unblockUI();
            if(data == 'success')
            {
              $('body').removeClass('modal-open');
              $('.modal-backdrop').remove();

               Toast.fire({
                    icon: 'success',
                    title: 'Customer Modified Successfully'
                  });

              $('.customer-management-link').click();
              //ViewProject(project_id);
            }
            else if(data == 'failed')
            {
             Toast.fire({
                    icon: 'error',
                    title: 'Failed. Please try again'
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
}
//end issue log monitoring

//start change status issue long
function  ChangeIssueLogStatus(id,status) {
if(status == "closed")
{
  status_label = "Closed";
  status_action = "CLOSE";
}
else
{
  status_label = "Opened";
  status_action = "OPEN";
}
  Swal.fire({
      title: 'This Issue Log will be ' + status,
      text: "Are you sure to proceed?",
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'YES, '+ status_action,
      cancelButtonText: 'NO, KEEP!',
      reverseButtons: true
    }).then((result) => {
      if (result.value) {

        var form_data = {
          sid:id,
          status:status,
          close_issue_log:'close_issue_log'
        };
        var form_url = 'controllers/project-management/IssueLogsController.php';
        var form_method = 'POST';

        $.ajax({
            data : form_data,
            url  : form_url,
            method : form_method,
            beforeSend:function()
            {
              $.blockUI({ message: blockui_spinner });
            },
            success:function(data){
              $.unblockUI();
              if(data == "success")
              {
                Swal.fire(
                  status_label,
                  'Issue Log ' +status_label+ ' Successfully',
                  'success'
                );
                $('.project-issue-logs-tab').click();
                //ViewProject(project_id);

                var mail_data = {
                  sid:id
                };

                $.ajax({
                      data : mail_data,
                      type: "post",
                      url: "controllers/project-management/SendMailProjectManagement.php",
                      success: function (mail_data) {
                          console.log(mail_data);
                      }
                  });
              }
              else
              {
                Swal.fire(
                  'Not '+status_label,
                  'Failed to ' + status_action+ ' , please try again',
                  'error'
                );
                console.log(data);
              }
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

      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
        Swal.fire(
          'Cancelled',
          'Not ' + status_label,
          'error'
        );
      }
  });
}
// end change status issue log

//start contract price addition
$(document).on("submit","#add-contract-price-form", function(e){
    e.preventDefault();

    var form_data = $(this).serializeArray();
    var form_url = 'controllers/project-management/ProjectContractPrice.php';
    var form_method = 'POST';

    $.ajax({
        type: form_method,
        url: form_url,
        data: form_data,
        beforeSend: function(){
            $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
            $.unblockUI();
            if(data == 'success')
            {
              Toast.fire({
                 icon: 'success',
                 title: 'Contract Price Successfully Added'
                });
                $('#add-contract-price-modal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                $('.project-payments-tab').click();
                //ViewPayment(id);

            }

              else
              {
                Toast.fire({
                   icon: 'error',
                   title: 'Failed to Add contract price'
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
//end contract price creation


//edit contract price addition
$(document).on("submit","#edit-contract-price-form", function(e){
    e.preventDefault();

    var form_data = $(this).serializeArray();
    var form_url = 'controllers/project-management/ProjectContractPrice.php';
    var form_method = 'POST';

    $.ajax({
        type: form_method,
        url: form_url,
        data: form_data,
        beforeSend: function(){
            $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
            $.unblockUI();
            if(data == 'success')
            {
              Toast.fire({
                 icon: 'success',
                 title: 'Contract Price Successfully Edited'
                });
                $('#edit-contract-price-modal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                $('.project-payments-tab').click();
                //ViewPayment(id);

            }

              else
              {
                Toast.fire({
                   icon: 'error',
                   title: 'Failed to edit contract price'
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
//end contract price creation

//start milestone payment addition
$(document).on("submit","#add-project-milestone-payments-form", function(e){
    e.preventDefault();

    var form_data = $(this).serializeArray();
    var form_url = 'controllers/project-management/ProjectMilestonePayment.php';
    var form_method = 'POST';

    $.ajax({
        type: form_method,
        url: form_url,
        data: form_data,
        beforeSend: function(){
            $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
            $.unblockUI();
            if(data == 'success')
            {
              Toast.fire({
                 icon: 'success',
                 title: 'Milestone Payment Successfully Added'
                });
                $('#add-payment-modal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                $('.project-payments-tab').click();
                //ViewPayment(id);
            }



              else
              {
                Toast.fire({
                   icon: 'error',
                   title: 'Failed to Add Milestone Payment price'
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
//end issue log creation


//-----------------------------------END PROJECT MANAGEMENT FORMS----------------------------------------------------------//
