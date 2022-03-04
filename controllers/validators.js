function CallSmartWizard()
{
  // Toolbar extra buttons
 var btnFinish = $('<button></button>').text('SUBMIT')
                                  .addClass('btn btn-info sw-btn-group-extra d-none')
                                  .on('click', function(){  });

  // SmartWizard initialize
  var wizard = $('#smartwizard-add-project').smartWizard({
    selected: 0, // Initial selected step, 0 = first step
    theme: 'arrows', // theme for the wizard, related css need to include for other than default theme
    justified: true, // Nav menu justification. true/false
    darkMode:false, // Enable/disable Dark Mode if the theme supports. true/false
    autoAdjustHeight: true, // Automatically adjust content height
    cycleSteps: false, // Allows to cycle the navigation of steps
    backButtonSupport: true, // Enable the back button support
    enableURLhash: false, // Enable selection of the step based on url hash
    transition: {
        animation: 'none', // Effect on navigation, none/fade/slide-horizontal/slide-vertical/slide-swing
        speed: '400', // Transion animation speed
        easing:'' // Transition animation easing. Not supported without a jQuery easing plugin
    },
    toolbarSettings: {
        toolbarPosition: 'bottom', // none, top, bottom, both
        toolbarButtonPosition: 'right', // left, right, center
        showNextButton: true, // show/hide a Next button
        showPreviousButton: true, // show/hide a Previous button
        toolbarExtraButtons: [btnFinish] // Extra buttons to show on toolbar, array of jQuery input/buttons elements

    },
    anchorSettings: {
        anchorClickable: true, // Enable/Disable anchor navigation
        enableAllAnchors: false, // Activates all anchors clickable all times
        markDoneStep: true, // Add done state on navigation
        markAllPreviousStepsAsDone: true, // When a step selected by url hash, all previous steps are marked done
        removeDoneStepOnNavigateBack: false, // While navigate back done step after active step will be cleared
        enableAnchorOnDoneStep: true // Enable/Disable the done steps navigation
    },
    keyboardSettings: {
        keyNavigation: true, // Enable/Disable keyboard navigation(left and right keys are used if enabled)
        keyLeft: [37], // Left key code
        keyRight: [39] // Right key code
    },
    lang: { // Language variables for button
        next: 'Next',
        previous: 'Previous'
    },
    disabledSteps: [], // Array Steps disabled
    errorSteps: [], // Highlight step with errors
    hiddenSteps: [], // Hidden steps

  });


  $(wizard).on("leaveStep", function(e, anchorObject, stepNumber, stepDirection) {
      if(stepNumber == "0")
      {
        var validator = $("#add-project-form").validate({
          rules: {
            strategic_objective: {
              required: true
            },
            project_name: {
              required: true,
              maxlength: 250
            },
            project_description: {
              required: true,
              maxlength: 500
            }
          }
        });
      }

      else if (stepNumber == "1")
      {
        var validator = $("#add-project-form").validate({
          rules: {
            project_start_date: {
              required: true,
              date:false
            },
            project_end_date: {
              required: true,
              date:false
            },
            duration: {
              required: true
            },
            project_phase: {
              required: true
            }
          }
        });
      }
      else if (stepNumber == "2")
      {
        var validator = $("#add-project-form").validate({
          rules: {
            funding_agency: {
              required: true
            }
          }
        });
      }

      else
      {
        var validator = $("#add-project-form").validate({
          rules: {
            project_owner: {
              required: true
            },
            senior_user: {
              required: true
            },
            senior_contractor: {
              required: true
            },
            project_advisor: {
              required: true
            }
          }
        });
      }

      if ($("#add-project-form").valid())
              {
                 return true;
              }
              else
              {
                 return false;
              }
  });

}

$(document.body).on("change",".select2",function(){
      $(this).valid();
});
$(document.body).on("change",".project_start_date",function(){
      $(this).valid();
});
$(document.body).on("change",".project_end_date",function(){
      $(this).valid();
});
$(document.body).on("change",".project_duration_in_days",function(){
      $(this).valid();
});
$(document.body).on("change",".project_duration",function(){
      $(this).valid();
});

$(document.body).on("change",".date-field",function(){
      $(this).valid();
});

//bootstrap styling for errors
jQuery.validator.setDefaults({
    onfocusout: function (e) {
        this.element(e);
    },
    onkeyup: false,

    highlight: function (element) {
        jQuery(element).closest('.form-control').addClass('is-invalid');
    },
    unhighlight: function (element) {
        jQuery(element).closest('.form-control').removeClass('is-invalid');
        jQuery(element).closest('.form-control').addClass('is-valid');
    },

    errorElement: 'div',
    errorClass: 'invalid-feedback',
    errorPlacement: function (error, element) {
        if (element.parent('.input-group-prepend').length) {
            $(element).siblings(".invalid-feedback").append(error);
            //error.insertAfter(element.parent());
        } else {
            error.insertAfter(element);
        }
    },
});
