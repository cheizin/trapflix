//this file listens to onclick events and responds to the necessary view
var static_page_title = " | PPRMIS ";

var error_response = `<div class="alert alert-warning alert-dismissible fade show" role="alert">
                      <strong>Failed!</strong> Sorry, your request could not be not completed. Please try again.
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>`;

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

/*
var blockui_spinner = `<div class="d-flex align-items-center text-primary">
  <strong>Processing...</strong>
  <div class="spinner-border ml-auto" role="status" aria-hidden="true"></div>
</div>`;
*/

var blockui_spinner = `<div id="loaders">
    <span class="loader loader-1"></span>
    <span class="loader loader-2"></span>
    <span class="loader loader-3"></span>
    <span class="loader loader-4"></span>
</div>`;


$('[data-toggle="tooltip"]').tooltip("dispose");

$(document).ajaxStart(function() { Pace.restart(); });

$(document).ajaxError(function() { Pace.stop(); });

//LOG IN LINK
$(document).on("click",".login-link", function(){
  //destrory any active tooltips

  $('[data-toggle="tooltip"]').tooltip("dispose");
  var form_url = 'views/auth/LoginForm.php';
  var form_method = 'POST';
  var form_data = {
  };

  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#response-data').html(data);
          $('.current-breadcrumb').html(link_title);
          $('#password').focus();


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});


$(document).on("click",".test-login-link", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");
  var form_url = 'views/auth/TestLoginForm.php';
  var form_method = 'POST';
  var form_data = {
  };

  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#response-data').html(data);
          $('.current-breadcrumb').html(link_title);


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});

//LOG OUT LINK
$(document).on("click",".log-out-link", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

    $.ajax({
        url:"controllers/auth/LogoutController.php",
        method:"POST",
        beforeSend: function()
        {
            $.blockUI({ message: blockui_spinner });
        },
        success: function(resposeData){
           $.unblockUI();
            if(resposeData == "success")
            {
               $('#current-title').html(static_page_title);
                location.reload();
            }
            else if (resposeData == "failed")
            {
              Toast.fire({
                  icon: 'error',
                  title: 'Please try again'
              });
            }
            else
            {
              Toast.fire({
                  icon: 'error',
                  title: 'Request Failed. Please try again'
              });
            }
        },
        error: function(xhr)
        {
          $.unblockUI();
          Toast.fire({
                    icon: 'error',
                    title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
            });
        }
    });
});

$(document).on('click','.switch-to-module-standard-risks', function(){

  $('#response-data').html('');

  var department_code = $('#department_code').val();
  SelectDepartmentRisk(department_code);

  $('.performance-management-navbar').addClass('d-none');
  $('.risk-management-navbar').removeClass('d-none');

  $('.switch-to-module-standard-risks').addClass('d-none');
  $('.switch-to-module-standard-performance').removeClass('d-none');
  $('.project-management-navbar').addClass('d-none');

});

$(document).on('click','.switch-to-module-standard-performance', function(){

  $('#response-data').html('');

  var department_code = $('#department_code').val();
  SelectDepartmentWorkplan(department_code);

  $('.performance-management-navbar').removeClass('d-none');
  $('.risk-management-navbar').addClass('d-none');
  $('.project-management-navbar').addClass('d-none');


  $('.switch-to-module-standard-performance').addClass('d-none');
  $('.switch-to-module-standard-risks').removeClass('d-none');

});

//HIDE NAVBAR UNTIL A SPECIFIC MODULE IS SELECTED
$('.performance-management-navbar').addClass('d-none');
$('.risk-management-navbar').addClass('d-none');

$(document).on("click",".risk-management-module", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");
  $('.module-panel').hide();
  $('.performance-management-navbar').addClass('d-none');
  $('.risk-management-navbar').removeClass('d-none');
  $('.project-management-navbar').addClass('d-none');
  $('.switch-to-module-standard-performance').removeClass('d-none');

  $('.switch-to-module').html('<i class="fal fa-toggle-off"></i> Switch to Performance').removeClass('risk-management-module monitor-risks-link').addClass('performance-management-module monitor-workplan-link');
  $('#response-data').html('');

      //log page request
    var page_data = {
           page_id: 'risk-management-module',
           page_name: 'Risk Management Module'

            };

    $.ajax({
        url:"controllers/data/PageController.php",
        method:"POST",
        data: page_data,
        success: function(data)
        {
            console.log(data);
        },
        error: function(xhr)
        {
            console.log(xhr);
        }
    });



});
$(document).on("click",".performance-management-module", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");
  $('.module-panel').hide();

  $('.performance-management-navbar').removeClass('d-none');
  $('.project-management-navbar').addClass('d-none');
  $('.risk-management-navbar').addClass('d-none');
  $('.switch-to-module-standard-risks').removeClass('d-none');

  $('.switch-to-module').html('<i class="fal fa-toggle-off"></i>  Switch to Risks').removeClass('performance-management-module monitor-workplan-link').addClass('risk-management-module monitor-risks-link');
  $('#response-data').html('');

      //log page request
    var page_data = {
           page_id: 'performance-management-module',
           page_name: 'Performance Management Module'

            };

    $.ajax({
        url:"controllers/data/PageController.php",
        method:"POST",
        data: page_data,
        success: function(data)
        {
            console.log(data);
        },
        error: function(xhr)
        {
            console.log(xhr);
        }
    });
});

$(document).on("click",".project-management-module", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");
  $('.module-panel').hide();

  $('.project-management-navbar').removeClass('d-none');
  $('.risk-management-navbar').addClass('d-none');
  $('.performance-management-navbar').addClass('d-none');
  $('.switch-to-module-standard-risks').removeClass('d-none');

  $('.monitor-projects-link').click();

  //$('#response-data').html('');

      //log page request
    var page_data = {
           page_id: 'project-management-module',
           page_name: 'Project Management Module'

            };

    $.ajax({
        url:"controllers/data/PageController.php",
        method:"POST",
        data: page_data,
        success: function(data)
        {
            console.log(data);
        },
        error: function(xhr)
        {
            console.log(xhr);
        }
    });
});

//start project management routes
$(document).on("click",".monitor-projects-link", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");
  GetPaymentsBadge();

  var form_url = 'views/project-management/MonitorProjects.php';
  var form_method = 'POST';
  var form_data = {
  };
  $('a').removeClass('active');
  $(this).addClass('active');
  var link_title = $('.monitor-projects-link').text();
  var page_title =  link_title + static_page_title;


  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
        //  $.blockUI({ message: blockui_spinner });
        $('#response-data').html(make_monitor_projects_skeleton());
      },
      success: function(data)
      {
        $('[data-toggle="tooltip"]').tooltip("dispose");
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#response-data').html(data);
          LoadDatatables();


          //log page request
        var page_data = {
               page_id: 'monitor-projects-link',
               page_name: 'Monitor Projects'

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });

      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});

//start view project
function ViewProject(str)
{
  $('#response-data').html('');
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/project-management/ViewProject.php';
  var form_method = 'POST';
  var id = str;

  var form_data = {
    id: id
  };

  $('a').removeClass('active');
  $('.monitor-projects-link').addClass('active');
  var link_title = $('.monitor-projects-link').text();
  var page_title =  $('.monitor-projects-link').text() + id + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('textarea').autosize();
          $('#current-title').html(page_title);
          $('#response-data').html(data);
          $('.performance-management-navbar').addClass('d-none');
          $('.risk-management-navbar').addClass('d-none');
          $('.switch-to-module-standard-risks').addClass('d-none');
          $('.switch-to-module-standard-performance').removeClass('d-none');
          $('.milestones-tab').click();

          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'monitor-projects-link',
               page_name: page_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });

      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
}
//end view project

//start view milestone
function ViewMilestone(str)
{
  $('#response-data').html('');
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/project-management/ViewMilestone.php';
  var form_method = 'POST';
  var reference_no = str;

  var form_data = {
    reference_no: reference_no
  };

  $('a').removeClass('active');
  $('.monitor-projects-link').addClass('active');
  var link_title = $('.monitor-projects-link').text();
  var page_title =  $('.monitor-projects-link').text() + reference_no + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('textarea').autosize();
          $('#current-title').html(page_title);
          $('#response-data').html(data);
          $('.performance-management-navbar').addClass('d-none');
          $('.risk-management-navbar').addClass('d-none');
          $('.switch-to-module-standard-risks').addClass('d-none');
          $('.switch-to-module-standard-performance').removeClass('d-none');

          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'monitor-projects-link',
               page_name: page_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });

      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
}
//end view milestone

$(document).on("click",".project-files-tab", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/project-management/ViewProjectFiles.php';
  var form_method = 'POST';
  var form_data = {
    project_id : $('.project-id').val()
  };
  $('a').removeClass('active');
  $('.monitor-projects-link').addClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#project-files-tab').html(data);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'project-files-tab',
               page_name: link_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});

$(document).on("click",".milestones-tab", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/project-management/ViewMilestone.php';
  var form_method = 'POST';
  var form_data = {
    project_id : $('.project-id').val()
  };
  $('a').removeClass('active');
  $('.monitor-projects-link').addClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#milestones-tab').html(data);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'milestones-tab',
               page_name: link_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});

$(document).on("click",".project-resource-plan-tab", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/project-management/ViewResourcePlan.php';
  var form_method = 'POST';
  var form_data = {
    project_id : $('.project-id').val()
  };
  $('a').removeClass('active');
  $('.monitor-projects-link').addClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#project-resource-plan-tab').html(data);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'project-resource-plan-tab',
               page_name: link_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});

$(document).on("click",".project-risks-tab", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/project-management/ViewProjectRisks.php';
  var form_method = 'POST';
  var form_data = {
    project_id : $('.project-id').val()
  };
  $('a').removeClass('active');
  $('.monitor-projects-link').addClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#project-risks-tab').html(data);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'project-risks-tab',
               page_name: link_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});

$(document).on("click",".project-lessons-learnt-tab", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/project-management/ViewLessonsLearnt.php';
  var form_method = 'POST';
  var form_data = {
    project_id : $('.project-id').val()
  };
  $('a').removeClass('active');
  $('.monitor-projects-link').addClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#project-lessons-learnt-tab').html(data);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'project-lessons-learnt-tab',
               page_name: link_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});

$(document).on("click",".project-issue-logs-tab", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/project-management/ViewIssueLogs.php';
  var form_method = 'POST';
  var form_data = {
    project_id : $('.project-id').val()
  };
  $('a').removeClass('active');
  $('.monitor-projects-link').addClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#project-issue-logs-tab').html(data);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'project-issue-logs-tab',
               page_name: link_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});

$(document).on("click",".project-payments-tab", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/project-management/ViewPayments.php';
  var form_method = 'POST';
  var form_data = {
    project_id : $('.project-id').val()
  };
  $('a').removeClass('active');
  $('.monitor-projects-link').addClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#project-payments-tab').html(data);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'project-payments-tab',
               page_name: link_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});


//end project management routes

//USER PROFILE
$(document).on("click",".user-profile-link", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/user/UserProfile.php';
  var form_method = 'POST';
  var form_data = {
  };
  $('a').removeClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;


  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#response-data').html(data);
          LoadDatatables();

        //  $("#timeline-data").load("views/user/TimeLinePaginated.php?page=1");
        //  $(".pagination").rPage();

          //log page request
        var page_data = {
               page_id: 'user-profile-link',
               page_name: 'User Profile'

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });

      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});


//USER PROFILE activity logs
$(document).on("click",".my-activity-logs-link", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/user/ActivityLogs.php';
  var form_method = 'POST';
  var form_data = {
  };
  $('a').removeClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;


  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $('#timeline').html("loading your Activity Logs...");
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#timeline').html(data);
          LoadDatatables();

          $("#timeline-data").load("views/user/TimeLinePaginated.php?page=1");
        //  $(".pagination").rPage();

          //log page request
        var page_data = {
               page_id: 'my-activity-logs-link',
               page_name: 'User Activity Logs'

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });

      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});


//USER PROFILE recent navigations
$(document).on("click",".my-recent-navigations-link", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/user/RecentNavigations.php';
  var form_method = 'POST';
  var form_data = {
  };
  $('a').removeClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;


  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $('#navigation').html("loading your Recent Navigations...");
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#navigation').html(data);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'my-recent-navigations-link',
               page_name: 'User Recent Navigations'

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });

      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});

//USER PROFILE login history
$(document).on("click",".my-login-history-link", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/user/LoginHistory.php';
  var form_method = 'POST';
  var form_data = {
  };
  $('a').removeClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;


  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $('#access-logs').html("loading your Login History...");
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#access-logs').html(data);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'my-login-history-link',
               page_name: 'User Login History'

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });

      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});

$(document).on("click","#pagination li", function(e){
  e.preventDefault();
  $("#timeline-data").html('loading...');
  $("#pagination li").removeClass('active');
  $(this).addClass('active');
      var pageNum = this.id;
      $("#timeline-data").load("views/user/TimeLinePaginated.php?page=" + pageNum);
});
//END USER PROFILE

//RISK MANAGEMENT
//departmental objectives
$(document).on("click",".departmental-objectives-link", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/objectives/DepartmentalObjectives.php';
  var form_method = 'POST';
  var form_data = {
  };
  $('a').removeClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#response-data').html(data);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'departmental-objectives-link',
               page_name: link_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });

      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});
//END departmental objectives
//start departmental objectives edit page
function ShowDepartmentalObjectiveEditPage(str)
{
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/objectives/EditObjective.php';
  var form_method = 'POST';
  var departmental_objective_id = str;
  var form_data = {
      departmental_objective_id : departmental_objective_id
  };
  $('a').removeClass('active');
  $('.departmental-objectives-link').addClass('active');
  var link_title = $('.departmental-objectives-link').text();
  var page_title =  $('.departmental-objectives-link').text() + departmental_objective_id + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#response-data').html(data);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'departmental-objectives-link',
               page_name: page_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });
      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
}
//end departmental objectives edit pae
//MONITOR RISKS
$(document).on("click",".monitor-risks-link", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/risk-management/MonitorRisks.php';
  var form_method = 'POST';
  var form_data = {
  };
  $('a').removeClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#response-data').html(data);
          $('.current-breadcrumb').html(link_title);

          //log page request
        var page_data = {
               page_id: 'monitor-risks-link',
               page_name: link_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });

      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});
//END MONITOR RISKS
//select department risk
function SelectDepartmentRisk(str)
{
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/risk-management/DepartmentalRisks.php';
  var form_method = 'POST';
  var select_department = str;
  var form_data = {
      select_department : select_department
  };
  $('a').removeClass('active');
  $('.monitor-risks-link').addClass('active');
  var link_title = $('.monitor-risks-link').text();
  var page_title =  $('.monitor-risks-link').text() + select_department + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#response-data').html(data);
          $('.current-breadcrumb').html(link_title);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'monitor-risks-link',
               page_name: page_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });

      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
}

//end select department risk

$(document).on("click",".monitor-risks-link-standard", function(){
  SelectDepartmentRisk(str);
});
//END MONITOR RISKS for standard
//select risk
function ViewRisk(reference_no,department_code)
{
  $('#response-data').html('');
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/risk-management/ViewRisk.php';
  var form_method = 'POST';
  var reference_no = reference_no;
  var department_code =department_code;
  var form_data = {
      department_code : department_code,
      reference_no: reference_no
  };
  $('a').removeClass('active');
  $('.monitor-risks-link').addClass('active');
  var link_title = $('.monitor-risks-link').text();
  var page_title =  $('.monitor-risks-link').text() + reference_no + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('textarea').autosize();
          $('#current-title').html(page_title);
          $('#response-data').html(data);
          $('.current-breadcrumb').html(link_title);
          $('.performance-management-navbar').addClass('d-none');
          $('.switch-to-module').html('<i class="fal fa-toggle-off"></i> Switch to Performance').removeClass('risk-management-module').addClass('performance-management-module');
          $('.risk-management-navbar').removeClass('d-none');

          $('.switch-to-module-standard-risks').addClass('d-none');
          $('.switch-to-module-standard-performance').removeClass('d-none');
          $('.project-management-navbar').addClass('d-none');

          $('.update-comments').hide();
          $('.edit-comments').hide();

          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'monitor-risks-link',
               page_name: page_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });

      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
}

//end view risk

//risk approvals
$(document).on("click",".risk-approvals-link", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/risk-management/RiskApprovals.php';
  var form_method = 'POST';
  var form_data = {
  };
  $('a').removeClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;
  var dynamic_badge_quarterly_update_url = 'views/risk-management/RiskApprovalsBadgeQuarterlyUpdate.php';
  var dynamic_badge_new_edited_url='views/risk-management/RiskApprovalsBadgeNewEdited.php';

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
        //$('.pending_approval_quarterly_updates_tab').click();
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#response-data').html(data);
          $('.current-breadcrumb').html(link_title);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'risk-approvals-link',
               page_name: link_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });

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


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});

$(document).on("click",".new-edit-notification",function(){
  $('.pending_approval_new_edited_tab').click();
});
$(document).on("click",".quarterly-update-notification",function(){
  $('.pending_approval_quarterly_updates_tab').click();
});

$(document).on("click",".pending_approval_quarterly_updates_tab", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/risk-management/RiskApprovalsQuarterlyUpdatesPendingApproval.php';
  var form_method = 'POST';
  var form_data = {
  };
  $('a').removeClass('active');
  $('.pending_approval_quarterly_updates_tab').addClass('active');
  $('.risk-approvals-link').addClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#risk-approvals-data').html('');
          $('#risk-approvals-data').html(data);
          $('.current-breadcrumb').html(link_title);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'pending_approval_quarterly_updates_tab',
               page_name: link_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});

$(document).on("click",".pending_approval_new_edited_tab", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/risk-management/RiskApprovalsNewEditedPendingApproval.php';
  var form_method = 'POST';
  var form_data = {
  };
  $('a').removeClass('active');
  $('.risk-approvals-link').addClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#risk-approvals-data').html('');
          $('#risk-approvals-data').html(data);
          $('.current-breadcrumb').html(link_title);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'pending_approval_new_edited_tab',
               page_name: link_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});

$(document).on("click",".approved_risks_tab", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/risk-management/RiskApprovalsApproved.php';
  var form_method = 'POST';
  var form_data = {
  };
  $('a').removeClass('active');
  $('.risk-approvals-link').addClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#risk-approvals-data').html('');
          $('#risk-approvals-data').html(data);
          $('.current-breadcrumb').html(link_title);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'approved_risks_tab',
               page_name: link_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});

$(document).on("click",".rejected_risks_tab", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/risk-management/RiskApprovalsRejected.php';
  var form_method = 'POST';
  var form_data = {
  };
  $('a').removeClass('active');
  $('.risk-approvals-link').addClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#risk-approvals-data').html('');
          $('#risk-approvals-data').html(data);
          $('.current-breadcrumb').html(link_title);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'rejected_risks_tab',
               page_name: link_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });

      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});

//END risk approvals

//delegate approvals
$(document).on("click",".delegate-approvals-link", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/risk-management/DelegateApprovals.php';
  var form_method = 'POST';
  var form_data = {
  };
  $('a').removeClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#response-data').html(data);
          $('.current-breadcrumb').html(link_title);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'delegate-approvals-link',
               page_name: link_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});
//END delegate approvals

//emerging trends
$(document).on("click",".emerging-trends-link", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/risk-management/EmergingTrends.php';
  var form_method = 'POST';
  var form_data = {
  };
  $('a').removeClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#response-data').html(data);
          $('.current-breadcrumb').html(link_title);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'emerging-trends-link',
               page_name: link_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});
//END emerging trends

//lessons learnt
$(document).on("click",".lessons-learnt-link", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/risk-management/LessonsLearnt.php';
  var form_method = 'POST';
  var form_data = {
  };
  $('a').removeClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#response-data').html(data);
          $('.current-breadcrumb').html(link_title);
          $('.strategies_that_worked_well_tab').click();
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'lessons-learnt-link',
               page_name: link_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});
//END lessons learnt


//strategies that worked well
$(document).on("click",".strategies_that_worked_well_tab", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/risk-management/LessonsLearntStrategiesThatWorkedWell.php';
  var form_method = 'POST';
  var form_data = {
  };
  $('a').removeClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#strategies_that_worked_well_tab').html(data);
          $('.current-breadcrumb').html(link_title);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'strategies_that_worked_well_tab',
               page_name: link_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});
//END strategies that worked well

//strategies that did not work
$(document).on("click",".strategies_that_did_not_work_tab", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/risk-management/LessonsLearntStrategiesThatDidNotWork.php';
  var form_method = 'POST';
  var form_data = {
  };
  $('a').removeClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#strategies_that_did_not_work_tab').html(data);
          $('.current-breadcrumb').html(link_title);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'strategies_that_did_not_work_tab',
               page_name: link_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });

      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});
//END strategies that did not work

//near misses
$(document).on("click",".near_misses_tab", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/risk-management/LessonsLearntNearMisses.php';
  var form_method = 'POST';
  var form_data = {
  };
  $('a').removeClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#near_misses_tab').html(data);
          $('.current-breadcrumb').html(link_title);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'near_misses_tab',
               page_name: link_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});
//END near misses

//incident reporting
$(document).on("click",".incident-reporting-link", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/risk-management/IncidentReporting.php';
  var form_method = 'POST';
  var form_data = {
  };
  $('a').removeClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#response-data').html(data);
          $('.current-breadcrumb').html(link_title);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'incident-reporting-link',
               page_name: link_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});
//END incident reporting

//END RISK MANAGEMENT


//PERFORMANCE MANAGEMENT

//monitor workplan
$(document).on("click",".monitor-workplan-link", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/performance-management/MonitorWorkplans.php';
  var form_method = 'POST';
  var form_data = {
  };
  $('a').removeClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#response-data').html(data);
          $('.current-breadcrumb').html(link_title);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'monitor-workplan-link',
               page_name: link_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});
//END monitor workplan

//select department workplan
function SelectDepartmentWorkplan(str)
{
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/performance-management/WorkplanYears.php';
  var form_method = 'POST';
  var select_department = str;
  var form_data = {
      select_department : select_department
  };
  $('a').removeClass('active');
  $('.monitor-workplan-link').addClass('active');
  var link_title = $('.monitor-workplan-link').text();
  var page_title =  $('.monitor-workplan-link').text() + select_department + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#response-data').html(data);
          $('.current-breadcrumb').html(link_title);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'monitor-workplan-link',
               page_name: page_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });

      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
}

//end select department workplan
//start select financial year for workplan
function fetchWorkplan(id,dep) {
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");


    var form_data = {
      sid:id,
      dep:dep
    };
    var form_url = 'views/performance-management/DepartmentalWorkplan.php';
    var form_method = 'POST';

    var page_title = "Monitor Workplan " + dep + " " +id + static_page_title;

    $.ajax({
        data: form_data,
        url: form_url,
        method: form_method,

        beforeSend: function()
        {
            $.blockUI({ message: blockui_spinner });
        },
        success: function(data)
        {
            //place the response data in the response data id
            $.unblockUI();
            $('#current-title').html(page_title);
            $('#response-data').html(data);
            LoadDatatables();

            //log page request
          var page_data = {
                 page_id: 'monitor-workplan-link',
                 page_name: page_title

                  };

          $.ajax({
              url:"controllers/data/PageController.php",
              method:"POST",
              data: page_data,
              success: function(data)
              {
                  console.log(data);
              },
              error: function(xhr)
              {
                  console.log(xhr);
              }
          });

        },
        error: function(xhr)
        {
          $.unblockUI();
          Toast.fire({
                    icon: 'error',
                    title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
            });
        }
    });
}
//end select financial year for workplan

//select activity
function ViewActivity(activity_id,selected_department,year_id)
{
  $('#response-data').html('')
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/performance-management/ViewActivity.php';
  var form_method = 'POST';
  var activity_id = activity_id;
  var selected_department =selected_department;
  var year_id =year_id;

  var form_data = {
    activity_id: activity_id,
    selected_department: selected_department,
    year_id: year_id
  };
  $('a').removeClass('active');
  $('.monitor-workplan-link').addClass('active');
  var link_title = $('.monitor-workplan-link').text();
  var page_title =  $('.monitor-workplan-link').text() + activity_id + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#response-data').html(data);
          $('.current-breadcrumb').html(link_title);
          $('.risk-management-navbar').addClass('d-none');
          $('.switch-to-module').html('<i class="fal fa-toggle-off"></i> Switch to Risks').removeClass('performance-management-module').addClass('risk-management-module');
          $('.performance-management-navbar').removeClass('d-none');

          $('.switch-to-module-standard-risks').removeClass('d-none');
          $('.switch-to-module-standard-performance').addClass('d-none');
          $('.project-management-navbar').addClass('d-none');

          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'monitor-workplan-link',
               page_name: page_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });

      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
}

//end view activity

//END PERFORMANCE MANAGEMENT

//START UPDATE MONITORING
$(document).on("click",".update-monitoring-admin-link", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/admin-portal/update-monitoring.php';
  var form_method = 'POST';
  var form_data = {
  };
  $('a').removeClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#response-data').html(data);
          $('.current-breadcrumb').html(link_title);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'update-monitoring-admin-link',
               page_name: link_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});

$(document).on("click",".update-monitoring-user-link", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/user/update-monitoring.php';
  var form_method = 'POST';
  var form_data = {
  };
  $('a').removeClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#response-data').html(data);
          $('.current-breadcrumb').html(link_title);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'update-monitoring-user-link',
               page_name: link_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});

//END UPDATE MONITORING

//start dashboard
$(document).on("click",".superuser-dashboard-link", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/dashboard/superuser-dashboard.php';
  var form_method = 'POST';
  var form_data = {
  };
  $('a').removeClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html('Dashboard -  Risk Management');
          $('#response-data').html(data);
          $('.current-breadcrumb').html(link_title);

          //log page request
        var page_data = {
               page_id: 'superuser-dashboard-link',
               page_name: link_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });

        LoadDatatables();


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});

$(document).on("click",".power_bi_tab", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/dashboard/PowerBi.php';
  var form_method = 'POST';
  var form_data = {
  };
  $('a').removeClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#powerBi_tab').html(data);
          $('.current-breadcrumb').html(link_title);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'power_bi_tab',
               page_name: link_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});

$(document).on("click",".standard-dashboard-link", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/dashboard/standard-dashboard.php';
  var form_method = 'POST';
  var form_data = {
  };
  $('a').removeClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html('Dashboard -  Risk Management');
          $('#response-data').html(data);
          $('.current-breadcrumb').html(link_title);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'standard-dashboard-link',
               page_name: link_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });

      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});

$(document).on("click",".standard_power_bi_tab", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/dashboard/PowerBiStandard.php';
  var form_method = 'POST';
  var form_data = {
  };
  $('a').removeClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#powerBi_tab').html(data);
          $('.current-breadcrumb').html(link_title);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'standard_power_bi_tab',
               page_name: link_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});


//start projects dashboard
$(document).on("click",".projects-dashboard-link", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/dashboard/projects-dashboard.php';
  var form_method = 'POST';
  var form_data = {
  };
  $('a').removeClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html('Dashboard - Project Management');
          $('#response-data').html(data);

          //log page request
        var page_data = {
               page_id: 'projects-dashboard-link',
               page_name: link_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });

        LoadDatatables();


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});


//end projects dashboard



//end dashboard

//START ADMIN LINKS
$(document).on("click",".admin-user-management-link", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/admin-portal/UserManagement.php';
  var form_method = 'POST';
  var form_data = {
  };
  $('a').removeClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#response-data').html(data);
          $('.current-breadcrumb').html(link_title);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'admin-user-management-link',
               page_name: link_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});

//start admin logs
$(document).on("click",".admin-logs-link", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/admin-portal/Logs.php';
  var form_method = 'POST';
  var form_data = {
  };
  $('a').removeClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#response-data').html(data);
          $('.current-breadcrumb').html(link_title);
          $('.activity_logs_tab').click();
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'admin-logs-link',
               page_name: link_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});

$(document).on("click",".activity_logs_tab", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/admin-portal/LogsActivityLogs.php';
  var form_method = 'POST';
  var form_data = {
  };
  $('a').removeClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#logs-data').html(data);
          $('.current-breadcrumb').html(link_title);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'activity_logs_tab',
               page_name: link_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});

$(document).on("click",".page_logs_tab", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/admin-portal/LogsPageLogs.php';
  var form_method = 'POST';
  var form_data = {
  };
  $('a').removeClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#logs-data').html(data);
          $('.current-breadcrumb').html(link_title);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'page_logs_tab',
               page_name: link_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});

$(document).on("click",".sign_in_logs_tab", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/admin-portal/LogsSignInLogs.php';
  var form_method = 'POST';
  var form_data = {
  };
  $('a').removeClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#logs-data').html(data);
          $('.current-breadcrumb').html(link_title);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'sign_in_logs_tab',
               page_name: link_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});

$(document).on("click",".mail_logs_tab", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/admin-portal/LogsMailLogs.php';
  var form_method = 'POST';
  var form_data = {
  };
  $('a').removeClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#logs-data').html(data);
          $('.current-breadcrumb').html(link_title);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'mail_logs_tab',
               page_name: link_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});

//end admin logs
$(document).on("click",".admin-period-management-link", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/admin-portal/PeriodManagement.php';
  var form_method = 'POST';
  var form_data = {
  };
  $('a').removeClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#response-data').html(data);
          $('.current-breadcrumb').html(link_title);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'admin-period-management-link',
               page_name: link_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});


$(document).on("click",".admin-backup-link", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/admin-portal/Backup.php';
  var form_method = 'POST';
  var form_data = {
  };
  $('a').removeClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#response-data').html(data);
          $('.current-breadcrumb').html(link_title);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'admin-backup-link',
               page_name: link_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});

$(document).on("click",".module-settings-link", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/admin-portal/ModuleSettings.php';
  var form_method = 'POST';
  var form_data = {
  };
  $('a').removeClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#response-data').html(data);
          $('.current-breadcrumb').html(link_title);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'module-settings-link',
               page_name: link_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});


$(document).on("click",".system-info-link", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'assets/libs/phpsysinfo/index.php';
  var form_method = 'POST';
  var form_data = {
  };
  $('a').removeClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
        console.log(data);
          //place the response data in the response data id
          var iframe = `<iframe width="100%" height="1500"" frameborder="0"
                                src="assets/libs/phpsysinfo/index.php"
                                allowFullScreen="true"></iframe>`;


          $.unblockUI();
          $('#current-title').html(page_title);
          $('#response-data').html(iframe);
          $('.current-breadcrumb').html(link_title);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'system-info-link',
               page_name: link_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});
//END ADMIN LINKS


//START REPORTS LINK
$(document).on("click",".reports-link", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/reports/SelectReportType.php';
  var form_method = 'POST';
  var form_data = {
  };
  $('a').removeClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html('Reports - Risk Management');
          $('#response-data').html(data);
          $('.current-breadcrumb').html(link_title);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'reports-link',
               page_name: link_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});
//END REPORTS LINK

//START REPORTS
function ReportType(str){
    //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/reports/ReportTypes.php';
  var form_method = 'POST';
  var report_type = str;
  var form_data = {
    report_type : report_type
  };
  $('a').removeClass('active');
  $(this).addClass('active');
  $('.reports-link').addClass('active');
  var link_title = "Reports | "+report_type;
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#response-data').html(data);
          $('.current-breadcrumb').html(link_title);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'reports-link',
               page_name: "Reports "+ report_type

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
}


function EditCumulativeRisk(directors_cumulative_id){
    //destrory any active tooltips
  var id = directors_cumulative_id
  $('[data-toggle="tooltip"]').tooltip("dispose");
  var form_url = 'views/reports/fetch-tied-risks-per-directorate-risks.php';
  var form_method = 'POST';

  var form_id = $('#edit-cumulative-risk-'+directors_cumulative_id);


  $('form').submit(function(e){
      e.preventDefault();;
  });

  //var form_data = form_id.serializeArray();

  console.log(form_data);

  var form_data =
  {
    'directors_cumulative_id':directors_cumulative_id,
    'directorate_id':$('#directorate_id-'+id).val(),
    'year_id':$('#year_id-'+id).val(),
    'quarter_id':$('#quarter_id-'+id).val()
  };

  $('a').removeClass('active');
  $(this).addClass('active');
  $('.reports-link').addClass('active');
  var link_title = $('.reports-link').text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#response-data').html(data);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'reports-link',
               page_name: link_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
}

//END REPORTS

//start feedback link
$(document).on("click",".user-feedback-link", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/admin-portal/UserFeedback.php';
  var form_method = 'POST';
  var form_data = {
  };
  $('a').removeClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#response-data').html(data);
          $('.current-breadcrumb').html(link_title);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'user-feedback-link',
               page_name: link_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});
//end feedback link

//START DASHBOARD ROUTES - PROJECTS
//total projects link
$(document).on("click",".open-total-projects-modal", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/dashboard/projects-dashboard-total-projects.php';
  var form_method = 'POST';
  var form_data = {
  };

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $('#total-projects-modal-body').html('loading..');
      },
      success: function(data)
      {
          //place the response data in the response data id
          $('#total-projects-modal-body').html('');
          $('#total-projects-modal-body').html(data);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'open-total-projects-modal',
               page_name: 'Dashboard Total Projects'

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});
//end total projects link

//total projects resources link
$(document).on("click",".open-dashboard-project-resources-modal", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/dashboard/projects-dashboard-project-resources.php';
  var form_method = 'POST';
  var form_data = {
  };

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $('#dashboard-project-resources-modal-body').html('loading..');
      },
      success: function(data)
      {
          //place the response data in the response data id
          $('#dashboard-project-resources-modal-body').html('');
          $('#dashboard-project-resources-modal-body').html(data);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'open-dashboard-project-resources-modal',
               page_name: 'Dashboard Project Resources'

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});
//end total projects resources link

//active projects risks link
$(document).on("click",".open-total-project-risks-modal", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/dashboard/projects-dashboard-risks.php';
  var form_method = 'POST';
  var form_data = {
  };

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $('#total-project-risks-modal-body').html('loading..');
      },
      success: function(data)
      {
          //place the response data in the response data id
          $('#total-project-risks-modal-body').html('');
          $('#total-project-risks-modal-body').html(data);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'open-total-project-risks-modal',
               page_name: 'Dashboard Total Projects'

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});
//end active project risks link


//active projects payments link
$(document).on("click",".open-dashboard-project-payments-modal", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/dashboard/projects-dashboard-payments.php';
  var form_method = 'POST';
  var form_data = {
  };

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $('#dashboard-project-payments-modal-body').html('loading..');
      },
      success: function(data)
      {
          //place the response data in the response data id
          $('#dashboard-project-payments-modal-body').html('');
          $('#dashboard-project-payments-modal-body').html(data);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'open-dashboard-project-payments-modal',
               page_name: 'Dashboard Projects Payments'

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});
//end active project paymentss link

//start load resource tasks
function LoadResourceTasks(str)
{

  var form_url = 'views/dashboard/projects-dashboard-tasks-per-resource.php';
  var form_method = 'POST';
  var form_data = {
    resource_name: str
  };

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $('.tasks-per-resource-modal-body').html('loading..');
      },
      success: function(data)
      {
          //place the response data in the response data id;
          $('.tasks-per-resource-modal-body').html('');
          $('.tasks-per-resource-modal-body').html(data);
          //log page request
        var page_data = {
               page_id: 'projects-dashboard-link',
               page_name: 'Dashboard Tasks Per Resource'

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
}
//end load resource tasks


//END DASHBOARD ROUTES -- PROJECTS

//START REPORTS LINK -- PROJECTS
$(document).on("click",".projects-reports-link", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/reports/pm-select-report-type.php';
  var form_method = 'POST';
  var form_data = {
  };
  $('a').removeClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html('Reports - Project Management');
          $('#response-data').html(data);
          $('.current-breadcrumb').html(link_title);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'reports-link',
               page_name: link_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});

//END REPORTS LINK --PROJECTS


// START PROJECT MANAGEMENT LINK
$(document).on("click",".project", function(){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var form_url = 'views/project-management/SelectProject.php';
  var form_method = 'POST';
  var form_data = {
     "project_module": $.trim( $(this).text() )
  };
  $('a').removeClass('active');
  $(this).addClass('active');
  var link_title = $(this).text();
  var page_title =  link_title + static_page_title;

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
          //place the response data in the response data id
          $.unblockUI();
          $('#current-title').html(page_title);
          $('#response-data').html(data);
          $('.current-breadcrumb').html(link_title);
          LoadDatatables();

          //log page request
        var page_data = {
               page_id: 'reports-link',
               page_name: link_title

                };

        $.ajax({
            url:"controllers/data/PageController.php",
            method:"POST",
            data: page_data,
            success: function(data)
            {
                console.log(data);
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });


      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });
});

function SelectProjectModule(project_module)
{
  var project_module = project_module;
  var project_id = $('.select-project').val();

  if(project_module == 'Project Files')
  {
      var form_url = 'views/project-management/ViewProjectFiles.php';
  }
  else if (project_module == 'Milestones')
  {
    var form_url = 'views/project-management/ViewMilestone.php';
  }
  else if (project_module == 'Resource Plan')
  {
    var form_url = 'views/project-management/ViewResourcePlan.php';
  }
  else if (project_module == 'Project Risks')
  {
    var form_url = 'views/project-management/ViewProjectRisks.php';
  }
  else if (project_module == 'Lessons Learnt')
  {
    var form_url = 'views/project-management/ViewLessonsLearnt.php';
  }

  else if (project_module == 'Issue Logs')
  {
    var form_url = 'views/project-management/ViewIssueLogs.php';
  }
  else
  {
    var form_url = 'views/project-management/ViewPayments.php';
  }
  var form_method = 'POST';
  var form_data = {
     "project_id": project_id
  };

  $.ajax({
      data: form_data,
      url: form_url,
      method: form_method,

      beforeSend: function()
      {
          $.blockUI({ message: blockui_spinner });
      },
      success: function(data)
      {
        $('.project-module-data').removeClass('d-none')
          //place the response data in the response data id
          $.unblockUI();
          $('.project-module-data').html(data);
          LoadDatatables();



      },
      error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
                  icon: 'error',
                  title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
          });
      }
  });

}

function GetPaymentsBadge()
{
    var form_url = 'views/project-management/PaymentDues.php';
    var form_method = 'POST';
    var form_data = {
    };

    $.ajax({
        data: form_data,
        url: form_url,
        method: form_method,

        beforeSend: function()
        {
            $('.payments-badge').html('...')
        },
        success: function(data)
        {
            $('.payments-badge').html(data);
        },
        error: function(xhr)
        {
          $.unblockUI();
          Toast.fire({
                    icon: 'error',
                    title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
            });
        }
    });
}


//END PROJECT MANAGEMENT LINK
